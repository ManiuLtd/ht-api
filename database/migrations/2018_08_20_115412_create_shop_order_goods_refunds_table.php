<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopOrderGoodsRefundsTable.
 */
class CreateShopOrderGoodsRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_order_goods_refunds', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->integer ('order_id')->nullable ();
            $table->integer ('order_goods_id')->nullable ();
            $table->string ('refundno')->nullable ();
            $table->decimal ('price')->nullable (); //订单价格
            $table->decimal ('apply_price')->nullable (); //商议价格
            $table->string ('reason')->nullable (); //理由
            $table->text ('image')->nullable (); //图片
            $table->text ('content')->nullable (); //内容
            $table->string ('reply')->nullable (); //回复

            $table->text ('refund_address')->nullable (); //卖家
            $table->string ('express')->nullable (); //卖家
            $table->string ('expresscom')->nullable ();
            $table->string ('expresssn')->nullable ();
            $table->string ('rexpress')->nullable (); //买家
            $table->string ('rexpresscom')->nullable ();
            $table->string ('rexpresssn')->nullable ();

            $table->tinyInteger ('type')->nullable ()->default (1); //维权类型 1退款退货 2换货  3仅退款
            $table->tinyInteger ('refund_type')->nullable ()->default (1); //1整单退款 2部分退款
            $table->tinyInteger ('status')->nullable ()->default (0);
            $table->timestamp ('send_time')->nullable (); //发货时间
            $table->timestamp ('operate_time')->nullable (); //操作时间
            $table->timestamp ('return_ime')->nullable (); //到货时间
            $table->timestamp ('finish_time')->nullable (); //完成时间
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
            $table->index ('order_id');
            $table->index ('order_goods_id');
            $table->index ('refundno');
            $table->index ('type');
            $table->index ('refund_type');
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
        Schema::drop ('shop_order_goods_refunds');
    }
}
