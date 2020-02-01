<?php

namespace Tests\Feature\App\Models;

use App\Models\Image;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{


	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function news_images_can_be_uploaded()
	{
		$this->withoutExceptionHandling();
		$this->actingAs($user = factory(User::class)->create(), 'api');

		$file = UploadedFile::fake()->image('user-image.jpg');

		$response = $this
			->postJson(
				'/api/v1/images/',
				[
					'data' => [
						'attributes' => [
							'image' => $file,
							'width' => "720",
							'height' => "405",
							'storage' => 'news',
						],
					],
				],
				[
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				]
			);
		$response->assertCreated();
		Storage::disk('public')->assertExists('images/' . $file->hashName());
		$image = Image::query()->first();
		$this->assertEquals('images/' . $file->hashName(), $image->getAttribute('path'));
		$this->assertEquals(720, $image->getAttribute('width'));
		$this->assertEquals(405, $image->getAttribute('height'));
		$this->assertEquals('news', $image->getAttribute('storage'));
		$response
			->assertJson([
				'data' => [
					'type' => 'images',
					'id' => $image->getKey(),
					'attributes' => [
						'width' => $image->getAttribute('width'),
						'height' => $image->getAttribute('height'),
						'storage' => $image->getAttribute('storage'),
					],
					"links" => [
						'self' => url(Storage::url($image->getAttribute('path'))),
					]
				],
			]);
	}
}
