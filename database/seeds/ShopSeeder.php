<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('shop_carts')->truncate();
        DB::table('shop_categories')->truncate();
        DB::table('shop_category_shop_goods')->truncate();
        DB::table('shop_coupon_categories')->truncate();
        DB::table('shop_coupon_categories')->truncate();
        DB::table('shop_coupon_logs')->truncate();
        DB::table('shop_coupons')->truncate();
        DB::table('shop_goods')->truncate();
        DB::table('shop_goods_comments')->truncate();
        DB::table('shop_goods_options')->truncate();
        DB::table('shop_goods_params')->truncate();
        DB::table('shop_goods_spec_items')->truncate();
        DB::table('shop_goods_specs')->truncate();
        DB::table('shop_goods_tags')->truncate();
        DB::table('shop_order_goods')->truncate();
        DB::table('shop_orders')->truncate();
        DB::table('shop_refund_orders')->truncate();

        Schema::enableForeignKeyConstraints();

        factory(App\Models\Shop\Order::class, 100)->create();
        factory(App\Models\Shop\OrderGoods::class, 200)->create();
        factory(App\Models\Shop\GoodsComment::class, 40)->create();
        factory(App\Models\Shop\RefundOrder::class, 40)->create();
        factory(App\Models\Shop\GoodsTag::class, 40)->create();
        factory(App\Models\Shop\Category::class, 40)->create();
        factory(App\Models\Shop\Goods::class, 100)->create();
        factory(App\Models\Shop\CouponCategory::class, 20)->create();
        factory(App\Models\Shop\Coupon::class, 100)->create();
        factory(App\Models\Shop\CouponLog::class, 40)->create();
    }
}
