<?php

namespace Tests\Feature\App\Models;

use App\Models\News;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthorTest extends TestCase
{


	use RefreshDatabase, WithFaker;

	/**
	 * Аутентифицированный пользовтаеь может создать автора
	 *
	 * @return void
	 * @test
	 */
	public function the_authenticated_user_can_create_an_author()
	{
		$this->actingAs($user = factory(User::class)->create(), 'api');
		$response = $this
			->postJson(
				route('authors.store'),
				[
					'data' => [
						'attributes' => [
							'name' => $name = $this->faker->name,
							'email' => $mail = $this->faker->email,
							'password' => $password = $this->faker->password(8),
							'password_confirmation' => $password,
						]
					],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response->assertCreated();

		$this->assertCount(2, User::query()->get());
	}

	/**
	 * Аутентифицированный пользователь может изменить свое имя
	 *
	 * @return void
	 * @test
	 */
	public function the_authenticated_user_can_edit_an_self_name()
	{
		$this->actingAs($user = factory(User::class)->create(), 'api');

		$response = $this
			->patchJson(
				route('authors.update', $user),
				[
					'data' => [
						'attributes' => [
							'name' => $name = $this->faker->name,
						]
					],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response->assertOk();

		$user->refresh();
		$this->assertEquals($user->getAttribute('name'), $name);
	}

	/**
	 * Аутентифицированный пользователь может изменить свое имя
	 *
	 * @return void
	 * @test
	 */
	public function the_authenticated_user_cannot_edit_an_foreign_name()
	{

		$response = $this
			->patchJson(
				route('authors.update', $author = factory(User::class)->create()),
				[
					'data' => [
						'name' => $name = $this->faker->name,
					],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response->assertUnauthorized();

	}

	/**
	 * Гость не может создать автора
	 *
	 * @return void
	 * @test
	 */
	public function the_guest_cannot_create_an_author()
	{
		$response = $this
			->postJson(
				route('authors.store'),
				[
					'data' => [],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response->assertUnauthorized();
		$this->assertCount(0, User::query()->get());
	}

	/**
	 * Гость может видеть все новсти автора
	 *
	 * @return void
	 * @test
	 */
	public function the_guest_can_see_a_news_created_by_author()
	{

		$author =  factory(User::class)->create();
		$firstNews = factory(News::class)->create([
			'author_id' => $author->getKey(),
			'published_at' => Carbon::now()->subDay()->format('Y-m-d')
		]);
		$secondNews = factory(News::class)->create([
			'author_id' => $author->getKey(),
			'published_at' => Carbon::now()->format('Y-m-d')
		]);
		$anotherAuthor =  factory(User::class)->create();

		factory(News::class)->create([
			'author_id' => $anotherAuthor->getKey(),
		]);

		$response = $this
			->getJson(
				route('authors.news', $author->getKey()),
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response
			->assertOk()
			->assertJson([
			"links" => [
				"related" => url("/authors/{$author->getkey()}"),
			],
			'data' => [
				[
					'type' => 'news',
					'id' => $secondNews->getKey(),
					'attributes' => [
						'title' => $secondNews->getAttribute('title'),
						'annotation' => $secondNews->getAttribute('annotation'),
						'published_at' => $secondNews->getAttribute('published_at')->format('d.m.Y'),
					],
					"links" => [
						"self" => url("/news/{$secondNews->getkey()}"),
					],
				],
				[
					'type' => 'news',
					'id' => $firstNews->getKey(),
					'attributes' => [
						'title' => $firstNews->getAttribute('title'),
						'annotation' => $firstNews->getAttribute('annotation'),
						'published_at' => $firstNews->getAttribute('published_at')->format('d.m.Y'),
					],
					"links" => [
						"self" => url("/news/{$firstNews->getkey()}"),
					],
				]
			],
		]);

	}
}
