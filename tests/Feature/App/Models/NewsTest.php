<?php

namespace Tests\Feature\App\Models;

use App\Models\News;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class NewsTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 */
	public function a_news_index_page_should_be_retrieve_by_exact_data_structure()
	{


		$this->actingAs($user = factory(User::class)->create(), 'api');
		factory(News::class, 5)->create();

		$response = $this
			->getJson(
				route('news.index'),
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);

		$response
			->assertJsonStructure([
				"links" => [
					'self',
				],
				'data' => [
					'*' => [
						'type',
						'id',
						'attributes' => [
							"title",
							"annotation",
							'published_at',
						],
						'links' => [
							'self',
						],
						'relationships' => [
							'author' => [
								'data' => [
									'type',
									'id',
									'attributes' => [
										'name',
									],
									'links' => [
										'self',
									],
								],
							],
							'image' => [
								'data' => [
									'type',
									'id',
									'attributes' => [
										'storage',
										'width',
										'height',
									],
									'links' => [
										'self',
									],
								],
							],
						],
					],
				],
			]);
	}

	/**
	 * @return void
	 * @test
	 */
	public function the_user_can_retrieve_a_news()
	{
		$this->withoutExceptionHandling();
		$this->actingAs($user = factory(User::class)->create(), 'api');
		$news1 = factory(News::class)->create([
			'published_at' => now()->subDay()->format('Y-m-d'),
		]);
		$news2 = factory(News::class)->create([
			'published_at' => now()->format('Y-m-d'),
		]);

		$this
			->getJson(
				route('news.index'),
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			)
			->assertStatus(Response::HTTP_OK)
			->assertJsonStructure()
			->assertJson([
				"data" => [
					[
						"type" => "news",
						"id" => $news2->getKey(),
						"attributes" => [
							"title" => $news2->getAttribute('title'),
							"annotation" => $news2->getAttribute('annotation'),
							'published_at' => $news2->published_at->format('d.m.Y'),
						],
					],
					[
						"type" => "news",
						"id" => $news1->getKey(),
						"attributes" => [
							"title" => $news1->getAttribute('title'),
							"annotation" => $news1->getAttribute('annotation'),
							'published_at' => $news1->published_at->format('d.m.Y'),
						],
					],
				],
			]);
	}

	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function the_guest_cannot_create_an_news()
	{
		$response = $this
			->postJson(
				route('news.store'),
				[
					'data' => [],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response->assertUnauthorized();
		$this->assertCount(0, News::query()->get());
	}

	/**
	 * @return void
	 * @test
	 */
	public function the_authenticated_user_can_store_a_news()
	{
		$this->withoutExceptionHandling();
		$this->actingAs($user = factory(User::class)->create(), 'api');

		$response = $this
			->postJson(
				route('news.store'),
				[
					'data' => [
						'attributes' => [
							'title' => $title = $this->faker->sentence,
							'annotation' => $annotation = $this->faker->sentence,
							'body' => $body = $this->faker->paragraph,
							'published_at' => $published_at = $this->faker->date('Y-m-d'),
						],
					],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);


		$this->assertCount(1, News::query()->get());
		$news = News::query()->first();
		$this->assertEquals($user->getKey(), $news->getAttribute('author_id'));
		$this->assertEquals($title, $news->getAttribute('title'));
		$this->assertEquals($body, $news->getAttribute('body'));
		$response
			->assertStatus(Response::HTTP_CREATED)
			->assertJson([
				'data' => [
					'type' => 'news',
					'id' => $news->getKey(),
					'attributes' => [
						'title' => $title,
						'annotation' => $annotation,
						'body' => $body,
						'published_at' => $published_at,
					],
					'relationships' => [
						'author' => [
							'data' => [
								'type' => 'authors',
								'id' => $user->getKey(),
								'attributes' => [
									'name' => $user->getAttribute('name'),
								],
							],
						],
					],
				],
			]);
	}

	/**
	 * @return void
	 * @test
	 */
	public function the_authenticated_user_can_update_a_news()
	{

		$this->actingAs($user = factory(User::class)->create(), 'api');
		$news = factory(News::class)->create([
			'author_id' => $user->getKey()
		]);

		$response = $this
			->patchJson(
				route('news.update', $news->getKey()),
				[
					'data' => [
						'attributes' => [
							'title' => $title = $this->faker->sentence,
							'annotation' => $annotation = $this->faker->sentence,
							'body' => $body = $this->faker->paragraph,
							'published_at' => $published_at = $this->faker->date('Y-m-d'),
						],
					],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);


		$this->assertCount(1, News::query()->get());
		$news = News::query()->first();
		$this->assertEquals($user->getKey(), $news->getAttribute('author_id'));
		$this->assertEquals($title, $news->getAttribute('title'));
		$this->assertEquals($body, $news->getAttribute('body'));
		$response
			->assertOk()
			->assertJson([
				'type' => 'news',
				'id' => $news->getKey(),
				'attributes' => [
					'title' => $title,
					'annotation' => $annotation,
					'body' => $body,
					'published_at' => $published_at,
				],
				'relationships' => [
					'author' => [
						'data' => [
							'type' => 'authors',
							'id' => $user->getKey(),
							'attributes' => [
								'name' => $user->getAttribute('name'),
							],
						],
					],
				],
			]);
	}
}
