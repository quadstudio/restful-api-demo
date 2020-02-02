<?php

namespace Tests\Feature\App\Http\Requests;

use App\Http\Requests\NewsRequest;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveNewsRequestTest extends TestCase
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

		$this->rules = (new NewsRequest())->getRules();
		factory(User::class)->create();
	}

	public function validationProvider()
	{

		$faker = Factory::create('ru_RU');

		return [

			'request_should_fail_when_no_title_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							"body" => $faker->paragraph,
							'annotation' => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_fail_when_no_body_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							"title" => $faker->sentence,
							'annotation' => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_fail_when_no_annotation_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							"title" => $faker->sentence,
							"body" => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_fail_when_no_published_at_posted' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							"title" => $faker->sentence,
							"body" => $faker->paragraph,
							'annotation' => $faker->paragraph,
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
							'title' => $faker->sentence,
							'annotation' => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
							'body' => $faker->paragraph,
						],
					],
				],
				'method' => 'POST',
			],
			'request_should_fail_when_no_title_patched' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							"body" => $faker->paragraph,
							'annotation' => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
						]
					]
				],
				'method' => 'PATCH'
			],
			'request_should_fail_when_no_body_patched' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'title' => $faker->sentence,
							'annotation' => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
						]
					]
				],
				'method' => 'PATCH'
			],
			'request_should_fail_when_no_annotation_patched' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'title' => $faker->sentence,
							"body" => $faker->paragraph,
							'published_at' => $faker->date('Y-m-d'),
						]
					]
				],
				'method' => 'PATCH'
			],
			'request_should_fail_when_no_published_at_patched' => [
				'passed' => false,
				'data' => [
					'data' => [
						'attributes' => [
							'title' => $faker->sentence,
							"body" => $faker->paragraph,
							'annotation' => $faker->paragraph,
						]
					]
				],
				'method' => 'PATCH'
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
