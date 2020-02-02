<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
	return [
		'path' => $faker->imageUrl(),
		'width' => $faker->numberBetween(100, 1000),
		'height' => $faker->numberBetween(100, 1000),
		'storage' => $faker->word,
	];
});
