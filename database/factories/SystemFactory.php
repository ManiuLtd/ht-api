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

$factory->define(App\Models\System\Notification::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 50),
        'title' => $faker->title,
        'message' => $faker->text,
        'type' => array_rand([1, 0]),
    ];
});

$factory->define(App\Models\System\Feedback::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 50),
        'content' => $faker->text,
        'title' => $faker->text,
        'images' => $faker->imageUrl(300, 100),
    ];
});

$factory->define(App\Models\System\Sms::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 50),
        'phone' => $faker->phoneNumber,
        'code' => rand(1000, 9999),
    ];
});
