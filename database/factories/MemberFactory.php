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

$factory->define (App\Models\Member\Member::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'inviter_id' => null,
        'unionid' => $faker->uuid,
        'openid' => $faker->uuid,
        'nickname' => $faker->name,
        'phone' => $faker->phoneNumber,
        'relaname' => $faker->name,
        'isagent' => $faker->randomKey ([0, 1]),
        'total_commission' => rand (10, 9999),
        'credit1' => $faker->randomFloat (2, 0, 9999),
        'credit2' => rand (10, 9999),
        'level1' => rand (1, 4),
        'level2' => rand (1, 4),
        'password' => bcrypt ('123456'),
        'headimgurl' => $faker->imageUrl (100, 100),
        'status' => $faker->randomKey ([0, 1]),
        'agent_time' => now (),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});


$factory->define (App\Models\Member\MemberCreditLog::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand (1, 50),
        'credit' => rand (100, 1000),
        'type' => $faker->randomKey ([1, 2]),
        'remark' => $faker->name,
        'created_at' => now (),
        'updated_at' => now (),
    ];
});


$factory->define (App\Models\Member\MemberLevel::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'name' => $faker->name,
        'logo' => $faker->imageUrl (100, 100),
        'level' => $faker->numberBetween (1, 100),
        'ordermoney' => $faker->randomFloat (2, 10, 999),
        'ordernum' => $faker->numberBetween (1, 9999),
        'discount' => $faker->randomFloat (2, 0.01, 0.99),
        'sort' => $faker->numberBetween (1, 9999),
        'status' => $faker->randomKey ([0, 1]),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Member\MemberHistory::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'member_id' => rand (1, 50),//会员id
        'merch_id' => rand (1, 50),//商户id
        'goods_id' => rand (1, 50),//商品id
        'views' => $faker->numberBetween (1, 9999),//浏览次数
    ];
});


$factory->define (App\Models\Member\MemberAddress::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'member_id' => 1,
        'realname' => $faker->name,
        'phone' => $faker->numberBetween (1, 100),
        'province' => $faker->name,
        'city' => $faker->name,
        'area' => $faker->name,
        'address' => $faker->name,
        'zipcode' => $faker->name,
        'isdefault' => $faker->numberBetween (1, 4),
        'type' => $faker->numberBetween (1, 4),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Member\MemberFavourite::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'member_id' => 1,
        'merch_id' => 1,
        'goods_id' => 1,
        'created_at' => now (),
        'updated_at' => now (),
    ];
});


$factory->define (App\Models\Member\MemberRecharge::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'member_id' => rand (1, 50),
        'title' => $faker->name,
        'out_trade_no' => $faker->bankAccountNumber,
        'money' => rand (10, 999),
        'real_money' => rand (10, 999),
        'giving_money' => rand (10, 999),
        'status' => rand (0, 2),
        'type' => rand (1, 3),
        'pay_time' => now (),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Member\MemberWithdraw::class, function (Faker $faker) {

    return [
        'user_id' => 1,
        'member_id' => rand (1, 50),
        'money' => rand (1, 999),
        'real_money' => rand (1, 999),
        'deduct_money' => rand (1, 999),
        'realname' => $faker->name,
        'alipay' => $faker->email,
        'bankname' => $faker->name,
        'bankcard' => $faker->bankAccountNumber,
        'reason' => $faker->text,
        'status' => rand (1, 3),
        'pay_type' => rand (1, 3),
        'pay_time' => now (),
        'refused_time' => now (),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});
