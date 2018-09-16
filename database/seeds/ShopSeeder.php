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
        DB::table ('shop_coupon_categories')->truncate ();
        DB::table ('shop_coupon_logs')->truncate ();
        DB::table ('shop_coupons')->truncate ();
        DB::table ('shop_goods_categories')->truncate ();
        DB::table ('shop_goods_tags')->truncate ();
        DB::table ('shop_orders')->truncate ();
        DB::table ('shop_order_goods')->truncate ();
        DB::table ('shop_order_goods_refunds')->truncate ();
        DB::table ('shop_goods_comments')->truncate ();

        factory (App\Models\Shop\ShopOrder::class, 100)->create ();
        factory (App\Models\Shop\ShopOrderGoods::class, 200)->create ();
        factory (App\Models\Shop\ShopGoodsComment::class, 40)->create ();
        factory (App\Models\Shop\ShopOrderGoodsRefund::class, 40)->create ();
        factory (App\Models\Shop\ShopGoodsTag::class, 40)->create ();
        factory (App\Models\Shop\ShopGoodsCategory::class, 40)->create ();
        factory (App\Models\Shop\ShopCouponCategory::class, 20)->create ();
        factory (App\Models\Shop\ShopCoupon::class, 100)->create ();
        factory (App\Models\Shop\ShopCouponLog::class, 40)->create ();
    }
}
