<?php

namespace Tests\Feature\App\Http\Controllers\Auth;


use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{

	use RefreshDatabase, WithFaker;

	/**
	 * @return void
	 * @test
	 */
	public function register_creates_and_authenticates_a_user()
	{
		$response = $this->post('/register', [
			'name' => $name = $this->faker->name,
			'email' => $email = $this->faker->email,
			'password' => $password = $this->faker->password(8),
			'password_confirmation' => $password,
		]);

		$response->assertRedirect(RouteServiceProvider::HOME);

		$this->assertDatabaseHas('users', [
			'name' => $name,
			'email' => $email,
		]);

		$user = User::query()
			->where('email', $email)
			->where('name', $name)
			->first();
		$this->assertNotNull($user);

		$this->assertAuthenticatedAs($user);
	}
}
