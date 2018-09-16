<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopCouponCategoriesTable.
 */
class CreateShopCouponCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_coupon_categories', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->string ('name')->nullable (); //优惠券分类名
            $table->integer ('sort')->nullable ()->default (100); //排序
            $table->tinyInteger ('status')->nullable ()->default (1); //状态
            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('merch_id');
            $table->index ('status');
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
        Schema::drop ('shop_coupon_categories');
    }
}
