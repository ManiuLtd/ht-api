<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define (App\Models\Image\Banner::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'image' => $faker->imageUrl (300, 100),
        'sort' => rand (0, 1000),
        'tag' => 'category',
        'status' => array_rand ([1, 0]),
    ];
});
