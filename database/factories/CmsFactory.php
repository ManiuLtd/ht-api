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

$factory->define(App\Models\Cms\Categories::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'keywords' => $faker->title,
        'description' => $faker->title,
        'logo' => $faker->imageUrl(300, 100),
        'sort' => rand(0, 1000),
        'type' => array_rand([1, 2]),
    ];
});
