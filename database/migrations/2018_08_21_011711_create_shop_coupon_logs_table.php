<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopCouponLogsTable.
 */
class CreateShopCouponLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_coupon_logs', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->integer ('member_id')->nullable ();  //会员ID
            $table->integer ('coupon_id')->nullable (); //优惠券ID
            $table->string ('ordersn')->nullable (); //订单编号

            $table->string ('name')->nullable (); // 优惠券名字
            $table->string ('thumb')->nullable (); // 缩略图
            $table->tinyInteger ('coupon_type')->nullable (); //0 优惠券 1 充值券
            $table->tinyInteger ('discount_type')->nullable (); // 返利方式 1 立减 2打折
            $table->decimal ('discount')->nullable (); // 折扣
            $table->decimal ('deduct')->nullable (); // 立减金额

            $table->tinyInteger ('get_type')->nullable (); // 	获得方式 0 后台发放 1 领券中心 2 积分商城 3 超级海报 4 活动海报 5 口令优惠券
            $table->tinyInteger ('pay_type')->nullable (); // 	支付类型
            $table->tinyInteger ('status')->nullable ()->default (0); //状态 1已使用 0 未使用


            $table->timestamp ('get_time')->nullable ();  //获取时间
            $table->timestamp ('use_time')->nullable (); //使用时间
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('merch_id');
            $table->index ('member_id');
            $table->index ('coupon_id');
            $table->index ('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_coupon_logs');
    }
}
