<?php

namespace Tests\Unit;

use App\Models\News;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthorTest extends TestCase
{

	use RefreshDatabase, WithFaker;

    /**
     * @return void
     * @test
     */
    public function a_users_table_has_expected_columns()
    {

	    $this->assertTrue(
		    Schema::hasColumns('users', [
			    'id','name', 'email', 'email_verified_at', 'password'
		    ]), 1);
    }

	/**
	 * @return void
	 * @test
	 */
	public function a_author_belongs_to_many_news()
	{
		$author = factory(User::class)->create();

		$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $author->news);
	}

	/**
	 * @return void
	 * @test
	 */
	function a_author_has_a_name()
	{
		$author = factory(User::class)->create(['name' => $name = $this->faker->name]);
		$this->assertEquals($name, $author->getAttribute('name'));
	}

	/**
	 * @return void
	 * @test
	 */
	function a_author_has_a_email()
	{
		$author = factory(User::class)->create(['email' => $email = $this->faker->email]);
		$this->assertEquals($email, $author->getAttribute('email'));
	}
}
