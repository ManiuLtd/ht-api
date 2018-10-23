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

$factory->define(App\Models\Member\Member::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'inviter_id' => null,
        'group_id' => rand(1,50),
        'oldgroup_id' => rand(1,50),
        'wx_unionid' => $faker->uuid,
        'wx_openid1' => $faker->uuid,
        'wx_openid2' => $faker->uuid,
        'ali_unionid' => $faker->uuid,
        'ali_openid1' => $faker->uuid,
        'ali_openid2' => $faker->uuid,
        'nickname' => $faker->name,
        'phone' => $faker->phoneNumber,
        'credit1' => $faker->randomFloat(2, 0, 9999),
        'credit2' => $faker->randomFloat(2, 0, 9999),
        'credit3' => $faker->randomFloat(2, 0, 9999),
        'level_id' => rand(1, 4),
        'password' => bcrypt('123456'),
        'headimgurl' => $faker->imageUrl(100, 100),
        'status' => $faker->randomKey([0, 1]),
        'agent_time' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\CreditLog::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'credit' => rand(100, 1000),
        'column' => 'credit1',
        'remark' => $faker->name,
        'type'   => array_rand([11,12,13,14,15,21,22,23]),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\History::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50), //会员id
        'merch_id' => rand(1, 50), //商户id
        'goods_id' => rand(1, 50), //商品id
        'views' => $faker->numberBetween(1, 9999), //浏览次数
    ];
});

$factory->define(App\Models\Member\Address::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => 1,
        'realname' => $faker->name,
        'phone' => $faker->numberBetween(1, 100),
        'province' => $faker->name,
        'city' => $faker->name,
        'area' => $faker->name,
        'address' => $faker->name,
        'zipcode' => $faker->name,
        'isdefault' => $faker->numberBetween(1, 4),
        'type' => $faker->numberBetween(1, 4),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\Favourite::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => 1,
        'merch_id' => 1,
        'goods_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\Recharge::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'title' => $faker->name,
        'out_trade_no' => $faker->bankAccountNumber,
        'money' => rand(10, 999),
        'real_money' => rand(10, 999),
        'giving_money' => rand(10, 999),
        'status' => rand(0, 1),
        'type' => rand(1, 4),
        'pay_time' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\Withdraw::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'money' => rand(1, 999),
        'real_money' => rand(1, 999),
        'deduct_money' => rand(1, 999),
        'realname' => $faker->name,
        'alipay' => $faker->email,
        'reason' => $faker->text,
        'status' => rand(0, 2),
        'pay_type' => rand(1, 4),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\Address::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'realname' => $faker->name,
        'phone' => rand(1, 999),
        'province' => $faker->name,
        'city' => $faker->name,
        'area' => $faker->name,
        'address' => $faker->name,
        'zipcode' => rand(100000, 999999),
        'isdefault' => rand(1, 3),
        'type' => rand(1, 3),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->define(App\Models\Member\Level::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'level' => $faker->numberBetween(1, 100),
        'name' => $faker->name,
        'logo' => $faker->imageUrl(100, 100),
        'group_rate1' => rand(10, 99),
        'group_rate2' => rand(10, 99),
        'commission_rate1' => rand(10, 99),
        'commission_rate2' => rand(10, 99),
        'credit' => rand(10, 99),
        'price' => rand(10, 99),
        'duration' => rand(10, 999),
        'description' => $faker->text(80),
        'default' => rand(0, 1),
        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Member\Group::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand(1, 50),
        'pid' => rand(1, 50),
        'qrcode' => $faker->imageUrl(100, 100),
        'qq' => rand(1111111, 9999999),
        'wechat' => rand(1111111, 9999999),
        'name' => $faker->name,
        'logo' => $faker->imageUrl(100, 100),
        'description' => $faker->title,
        'status' => rand(0, 1),
        'default' => rand(0, 1),
        'type' => rand(1, 2),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
