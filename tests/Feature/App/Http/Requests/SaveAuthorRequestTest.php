<?php

namespace Tests\Feature\App\Http\Requests;

use App\Http\Requests\AuthorRequest;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveAuthorRequestTest extends TestCase
{

	use RefreshDatabase;

	/** @var \App\Http\Requests\NewsRequest */
	private $rules;

	/** @var \Illuminate\Validation\Validator */
	private $validator;

	public function setUp(): void
	{
		parent::setUp();

		$this->validator = app()->get('validator');

		$this->rules = (new AuthorRequest())->getRules();
	}

	public function validationProvider()
	{

		$faker = Factory::create('ru_RU');

		return [

			'request_should_fail_when_no_name_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'email' => $faker->email,
							'password' => $password = $faker->password(8),
							'password_confirmation' => $password,
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_fail_when_no_email_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'name' => $faker->name,
							'password' => $password = $faker->password(8),
							'password_confirmation' => $password,
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_fail_when_no_password_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'name' => $faker->name,
							'email' => $faker->email,
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_pass_when_news_data_is_posted' => [
				'passed' => true,
				'data' => [
					'data' => [
						'attributes' => [
							'email' => $faker->email,
							'name' => $faker->name,
							'password' => $password = $faker->password(8),
							'password_confirmation' => $password,
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_pass_when_news_data_is_patched' => [
				'passed' => true,
				'data' => [
					'data' => [
						'attributes' => [
							'name' => $faker->name,
						],
					],
				],
				'method' => 'PATCH',
			],
			'request_should_fail_when_no_name_patched' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'name' => '',
						],
					],
				],
				'method' => 'PATCH',
			],
		];
	}

	/**
	 * @test
	 * @dataProvider validationProvider
	 *
	 * @param bool $shouldPass
	 * @param array $mockedRequestData
	 * @param string $validatorMethod
	 */
	public function validation_results_as_expected($shouldPass, $mockedRequestData, $validatorMethod)
	{

		$this->assertEquals(
			$shouldPass,
			$this->validate($mockedRequestData, $validatorMethod)
		);
	}

	protected function validate($mockedRequestData, $validatorMethod)
	{
		return $this->validator
			->make($mockedRequestData, $this->rules[$validatorMethod])
			->passes();
	}
}
