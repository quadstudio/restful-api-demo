<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Models\News;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NewsTest extends TestCase
{

	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function a_users_table_has_expected_columns()
	{

		$this->assertTrue(
			Schema::hasColumns('news', [
				'id', 'title', 'annotation', 'body', 'published_at',
			]), 1);
	}

	/**
	 * @return void
	 * @test
	 */
	public function a_news_has_a_author()
	{
		$user = factory(User::class)->create();
		$news = factory(News::class)->create(['author_id' => $user->id]);

		$this->assertInstanceOf(User::class, $news->author);

		$this->assertEquals(1, $news->author->count());
	}

	/**
	 * @return void
	 * @test
	 */
	public function a_news_has_a_image()
	{
		$news = factory(News::class)->create();

		factory(Image::class)->create(['imageable_id' => $news->id, 'imageable_type' => 'news']);

		$this->assertInstanceOf(Image::class, $news->image);

		$this->assertEquals(1, $news->image->count());
	}

	/**
	 * @return void
	 * @test
	 */
	function a_news_has_a_title()
	{
		$news = factory(News::class)->create(['title' => $title = $this->faker->sentence]);
		$this->assertEquals($title, $news->getAttribute('title'));
	}

	/**
	 * @return void
	 * @test
	 */
	function a_news_has_a_annotation()
	{
		$news = factory(News::class)->create(['annotation' => $annotation = $this->faker->sentence]);
		$this->assertEquals($annotation, $news->getAttribute('annotation'));
	}

	/**
	 * @return void
	 * @test
	 */
	function a_news_has_a_body()
	{
		$news = factory(News::class)->create(['body' => $body = $this->faker->paragraph]);
		$this->assertEquals($body, $news->getAttribute('body'));
	}

	/**
	 * @return void
	 * @test
	 */
	function a_news_has_a_published_at()
	{
		$news = factory(News::class)->create(['published_at' => $published_at = $this->faker->date('Y-m-d')]);
		$this->assertInstanceOf(Carbon::class, $news->getAttribute('published_at'));
		$this->assertEquals($published_at, $news->getAttribute('published_at')->format('Y-m-d'));
	}

}
