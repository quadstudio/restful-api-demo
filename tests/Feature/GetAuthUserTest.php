<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetAuthUserTest extends TestCase
{

	use RefreshDatabase;

	/**
	 * @return void
	 * @test
	 */
	public function authenticated_user_can_be_fetched()
	{
		$this->withoutExceptionHandling();
		$this->actingAs($user = factory(User::class)->create(), 'api');

		$response = $this->getJson(
			'/api/v1/profile',
			['Accept' => 'application/vnd.api+json']
		);

		$response
			->assertStatus(Response::HTTP_OK)
			->assertJson([
				'data' => [
					'type' => 'users',
					'id' => $user->getKey(),
					'attributes' => [
						'name' => $user->getAttribute('name'),
					],
				],
			]);
	}
}
