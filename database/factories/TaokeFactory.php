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

$factory->define(App\Models\Taoke\Pid::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'taobao' => $faker->uuid,
        'jingdong' => $faker->uuid,
        'pinduoduo' => $faker->uuid,
        'status' => rand(0,1),
    ];
});

$factory->define(App\Models\Taoke\Order::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'group_id' => rand(1, 50),
        'ordernum' => $faker->randomNumber(),
        'title' => $faker->title,
        'itemid' => rand(1, 100),
        'count' => rand(1, 10),
        'price' => $faker->numberBetween(2, 500),
        'final_price' => $faker->numberBetween(2, 500),
        'commission_rate' => rand(10, 999),
        'commission_amount' => $faker->numberBetween(2, 100),
        'pid' => $faker->uuid,
        'status' => rand(1,3),
        'type' => rand(1,3),
        'complete_at' => now(),
    ];
});

$factory->define(App\Models\Taoke\Quan::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'item_id' => rand(1, 50),
        'nickname' => $faker->title,
        'headimg' => $faker->imageUrl(100, 100),
        'introduce' => $faker->text,
        'images' => json_encode([
            $faker->imageUrl(400, 400),
            $faker->imageUrl(400, 400),
            $faker->imageUrl(400, 400),
            $faker->imageUrl(400, 400),
        ]),
        'comments' => json_encode([
            $faker->title,
            $faker->title,
            $faker->title,
            $faker->title,
        ]),
        'taokouling' => array_rand([1, 0]),
        'shares' => rand(10, 999),
        'type' => array_rand([1, 2, 3, 4]),
    ];
});

$factory->define(App\Models\Taoke\Favourite::class, function (Faker $faker) {
    return [
        'member_id' => rand(1, 50),
        'title' => $faker->title,
        'pic_url' => $faker->imageUrl(100, 100),
        'item_id' => rand(1, 20),
        'volume' => rand(1, 1000),
        'price' => $faker->numberBetween(2, 500),
        'coupon_price' => $faker->numberBetween(2, 500),
        'final_price' => $faker->numberBetween(2, 500),
        'type' => array_rand([1, 2, 3]),
    ];
});

$factory->define(App\Models\Taoke\History::class, function (Faker $faker) {
    return [
        'member_id' => rand(1, 50),
        'title' => $faker->title,
        'pic_url' => $faker->imageUrl(100, 100),
        'item_id' => rand(1, 20),
        'volume' => rand(1, 1000),
        'price' => $faker->numberBetween(2, 500),
        'coupon_price' => $faker->numberBetween(2, 500),
        'final_price' => $faker->numberBetween(2, 500),
        'type' => array_rand([1, 2, 3]),
    ];
});

$factory->define(App\Models\Taoke\Category::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->title,
        'logo' => $faker->imageUrl(100, 100),
        'taobao' => rand (1,10),
        'jingdong' => rand (1,10),
        'pinduoduo' => rand (1,10),
        'sort' => rand(1, 20),
        'status' => rand(0,1),
    ];
});
