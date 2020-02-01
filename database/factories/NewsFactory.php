<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
	return [
		'author_id' => factory(\App\User::class),
		'title' => $faker->sentence,
		'annotation' => $faker->sentence,
		'body' => $faker->paragraph,
		'published_at' => $faker->date('Y-m-d'),
	];
});
