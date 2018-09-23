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

$factory->define (App\Models\Shop\CouponCategory::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->name,
        'sort' => rand (100, 99),
        'status' => $faker->randomKey ([0, 1]),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Shop\Coupon::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'category_id' => rand (1, 10),
        'name' => $faker->name,
        'total' => rand (100, 10000),
        'max_receive' => rand (0, 100),
        'enough' => rand (0, 100),
        'coupon_type' => rand (1, 2),
        'is_show' => rand (0, 1),
        'discount_type' => rand (1, 2),
        'discount' => $faker->randomFloat (2, 0, 1),
        'deduct' => $faker->randomFloat (2, 99, 99999),
        'limit_type' => rand (1, 2),
        'limit_days' => rand (7, 60),
        'time_start' => now (),
        'time_end' => now (),
        'is_limit_goods' => 0,
        'is_limit_category' => 0,
        'is_limit_level' => 0,
        'is_limit_agent' => 0,
        'description' => $faker->title,
        'sort' => rand (100, 999),
        'status' => rand (0, 1),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Shop\CouponLog::class, function (Faker $faker) {
    return [
        'member_id' => rand (1, 30),
        'coupon_id' => rand (1, 30),
        'ordersn' => $faker->bankAccountNumber,
        'name' => $faker->name,
        'thumb' => $faker->imageUrl (100, 100),
        'coupon_type' => rand (1, 2),
        'discount_type' => rand (1, 2),
        'discount' => $faker->randomFloat (2, 0, 1),
        'deduct' => $faker->randomFloat (2, 99, 99999),
        'get_type' => rand (0, 5),
        'status' => rand (0, 1),
        'get_time' => now (),
        'use_time' => now (),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Shop\Category::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->name,
        'thumb' => $faker->imageUrl (100, 100),
        'description' => $faker->text (100),
        'advimg' => $faker->imageUrl (50, 150),
        'advurl' => $faker->url,
        'sort' => rand (100, 99),
        'status' => $faker->randomKey ([0, 1]),
        'ishome' => $faker->randomKey ([0, 1]),
        'isrecommand' => $faker->randomKey ([0, 1]),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Shop\GoodsTag::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->name,
        'sort' => $faker->numberBetween (10, 999),
        'color' => '#444',
        'status' => $faker->randomKey ([0, 1]),
        'created_at' => now (),
        'updated_at' => now (),
    ];
});

$factory->define (App\Models\Shop\Order::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand (1, 50),
        'orderno' => 'HT' . $faker->randomNumber (),
        'total_price' => $faker->numberBetween (2, 500),
        'discount_price' => $faker->numberBetween (2, 20),
        'dispatch_price' => $faker->numberBetween (0, 10),
        'deduct_credit1' => $faker->numberBetween (0, 100),
        'deduct_enough' => $faker->numberBetween (0, 15),
        'change_price' => $faker->numberBetween (0, 15),
        'change_dispatch_price' => $faker->numberBetween (0, 15),
        'old_price' => $faker->numberBetween (0, 15),
        'old_dispatch_price' => $faker->numberBetween (2, 500),
        'coupon_id' => rand (1, 50),
        'coupon_price' => $faker->numberBetween (0, 15),
        'address_id' => rand (1, 10),
        'address' => $faker->text,
        'paytype' => rand (1, 4),
        'type' => rand (1, 4),
        'status' => rand (1, 5),
        'remark' => $faker->text,
        'close_reason' => $faker->name,
        'pay_time' => now (),
        'cancel_time' => now (),
    ];
});

$factory->define (App\Models\Shop\RefundOrder::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'member_id' => rand (1, 50),
        'order_id' => rand (1, 100),
        'order_goods_id' => rand (1, 50),
        'refundno' => 'HT' . $faker->randomNumber (),
        'price' => $faker->numberBetween (2, 500),
        'apply_price' => $faker->numberBetween (2, 500),
        'reason' => $faker->name,
        'refund_address' => $faker->text,
        'expresscom' => $faker->name,
        'expresssn' => $faker->name,
        'rexpresscom' => $faker->name,
        'rexpresssn' => $faker->name,
        'type' => rand (1, 2),
        'refund_type' => rand (1, 3),
        'status' => rand (1, 3),
        'send_time' => now (),
        'operate_time' => now (),
        'return_ime' => now (),
        'finish_time' => now (),
    ];
});

$factory->define (App\Models\Shop\OrderGoods::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'order_id' => rand (1, 100),
        'agent_id' => rand (1, 50),
        'price' => $faker->numberBetween (2, 500),
        'old_price' => $faker->numberBetween (2, 20),
        'total' => rand (1, 10),
        'refund_total' => rand (1, 10),
        'nocommission' => 1,
        'commissions' => 0,
        'commission0' => 0,
        'commission1' => 0,
        'commission2' => 0,
        'commission3' => 0,
        'title' => $faker->text (80),
        'thumb' => $faker->imageUrl (100, 100),
        'goodssn' => $faker->randomLetter,
        'productsn' => $faker->randomLetter,
        'expresscom' => $faker->name,
        'expresssn' => $faker->name,
        'refund_status' => rand (1, 3),
        'send_time' => now (),
        'refund_time' => now (),
        'fetch_time' => now (),
    ];
});

$factory->define (App\Models\Shop\GoodsComment::class, function (Faker $faker) {
    return [
        'member_id' => rand (1, 50),
        'order_id' => rand (1, 50),
        'goods_id' => rand (1, 50),
        'nickname' => $faker->name,
        'headimgurl' => $faker->imageUrl (100, 100),
        'level' => rand (1, 5),
        'content' => $faker->text,
        'append_content' => $faker->text,
        'reply_content' => $faker->text,
        'istop' => 0,
        'status' => rand (0, 1),
    ];
});

$factory->define (App\Models\Shop\Goods::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => $faker->text (50),
        'keywords' => $faker->title,
        'short_title' => $faker->text (30),
        'thumb' => json_encode ([
            $faker->imageUrl (400, 400),
            $faker->imageUrl (400, 400),
            $faker->imageUrl (400, 400),
            $faker->imageUrl (400, 400),
        ]),
        'description' => $faker->title,
        'content' => $faker->text,
        'goodssn' => $faker->creditCardNumber,
        'productsn' => $faker->creditCardNumber,
        'price' => rand (100, 999),
        'old_price' => rand (100, 999),
        'cost_price' => rand (100, 999),
        'min_price' => rand (100, 999),
        'max_price' => rand (100, 999),
        'total' => rand (100, 999),
        'totalcnf' => rand (0, 1),
        'sales' => rand (100, 999),
        'real_sales' => rand (100, 999),
        'show_sales' => rand (0, 1),
        'show_spec' => rand (0, 1),
        'weight' => rand (10, 999),
        'credit' => rand (10, 999),
        'minbuy' => 1,
        'maxbuy' => rand (10, 9999),
        'total_maxbuy' => rand (10, 9999),
        'hasoption' => rand (0, 1),
        'isnew' => rand (0, 1),
        'ishot' => rand (0, 1),
        'isrecommand' => rand (0, 1),
        'isdiscount' => rand (0, 1),
        'discount_title' => $faker->title,
        'discount_end' => $faker->date ('Y-m-d H:i:s'),
        'discount_price' => rand (100, 999),
        'issendfree' => rand (0, 1),
        'iscomment' => rand (0, 1),
        'views' => rand (100, 9999),
        'hascommission' => rand (0, 1),
        'commission0_rate' => rand (10, 999),
        'commission0_pay' => rand (10, 999),
        'commission1_rate' => rand (10, 999),
        'commission1_pay' => rand (10, 999),
        'commission2_rate' => rand (10, 999),
        'commission2_pay' => rand (10, 999),
        'commission3_rate' => rand (10, 999),
        'commission3_pay' => rand (10, 999),
        'is_not_discount' => rand (0, 1),
        'deduct_credit1' => rand (0, 1),
        'deduct_credit2' => rand (0, 1),
        'dispatch_type' => rand (1, 2),
        'dispatch_price' => rand (100, 999),
        'show_total' => rand (0, 1),
        'auto_receive' => rand (0, 1),
        'can_not_refund' => rand (0, 1),
        'type' => rand (1, 3),
        'status' => rand (0, 1),
        'sort' => rand (10, 999),
    ];
});

