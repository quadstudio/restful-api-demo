<?php

namespace Tests\Feature\App\Http\Controllers\Auth;


use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{

	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function login_display_login_form()
	{
		$response = $this->get(route('login'));

		$response->assertStatus(200);
		$response->assertViewIs('auth.login');
	}

	/**
	 * @return void
	 * @test
	 */
	public function login_without_data_display_validation_form()
	{
		$response = $this->post('/login', []);

		$response->assertStatus(302);
		$response->assertSessionHasErrors('email');
	}

	/**
	 * @return void
	 * @test
	 */
	public function login_authenticates_and_redirects_user()
	{
		$user = factory(User::class)->create();

		$response = $this->post(route('login'), [
			'email' => $user->email,
			'password' => 'password',
		]);

		$response->assertRedirect(RouteServiceProvider::HOME);
		$this->assertAuthenticatedAs($user);
	}

}
