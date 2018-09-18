<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopCouponsTable.
 */
class CreateShopCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_coupons', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->integer ('category_id')->nullable ();
            $table->string ('name')->nullable (); // 优惠券名字
            $table->string ('thumb')->nullable (); // 缩略图
            $table->integer ('total')->nullable (); // 总数

            $table->integer ('max_receive')->nullable (); //最大领取数量
            $table->decimal ('enough')->nullable (); // 消费满多少可用 0 不限制
            $table->string ('color')->nullable (); // 背景色

            $table->tinyInteger ('coupon_type')->nullable (); //1 优惠券 2 充值券
            $table->tinyInteger ('is_show')->nullable (); //是否领券中心显示

            $table->tinyInteger ('discount_type')->nullable (); // 返利方式 1 立减 2打折
            $table->decimal ('discount')->nullable (); // 折扣
            $table->decimal ('deduct')->nullable (); // 立减金额

            $table->integer ('limit_type')->nullable (); // 	1 领取后几天有效 2 时间范围
            $table->integer ('limit_days')->nullable (); // 获得后可使用 0 不限时间 >0 天
            $table->timestamp ('time_start')->nullable (); // 开始时间
            $table->timestamp ('time_end')->nullable (); // 结束时间


            $table->tinyInteger ('is_limit_goods')->nullable (); // v2 是否限制商品 1 限制 0 不限制
            $table->integer ('limit_goods_ids')->nullable (); // 限制商品ID
            $table->tinyInteger ('is_limit_category')->nullable (); // v2 是否限制分类 1 限制 0 不限制
            $table->integer ('limit_category_ids')->nullable (); // 限制商品分类ID
            $table->tinyInteger ('is_limit_level')->nullable (); // v2 是否限制会员等级 1 限制 0 不限制
            $table->integer ('limit_level_ids')->nullable (); // 限制会员等级ID
            $table->tinyInteger ('is_limit_agent')->nullable (); // v2 是否限制分销商等级 1 限制 0 不限制
            $table->integer ('limit_agent_ids')->nullable (); // 限制商品分销商等级ID

            $table->string ('description')->nullable (); // 介绍
            $table->integer ('sort')->nullable ()->default (0); // 排序
            $table->tinyInteger ('status')->nullable (); // 	状态 0 不可用 1 可用

            $table->softDeletes ();
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('merch_id');
            $table->index ('category_id');
            $table->index ('name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_coupons');
    }
}
