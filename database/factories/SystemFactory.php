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
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'msg_id' => rand(1, 20),
        'logo' => $faker->imageUrl(300, 100),
        'sendno' => 'HT'.$faker->randomNumber(),
        'title' => $faker->title,
        'message' => $faker->text,
        'type' => array_rand([1, 0]),
    ];
});
