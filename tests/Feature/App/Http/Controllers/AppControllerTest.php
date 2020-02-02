<?php

namespace Tests\Feature\App\Http\Controllers;


use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppControllerTest extends TestCase
{

	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function authenticated_user_can_see_a_index_page()
	{
		$user = factory(User::class)->create();

		$response = $this->actingAs($user)->get(RouteServiceProvider::HOME);

		$response->assertStatus(200);
	}

	/**
	 * @return void
	 * @test
	 */
	public function guest_can_see_a_index_page()
	{

		$response = $this->get(RouteServiceProvider::HOME);

		$response->assertStatus(200);
	}
}
