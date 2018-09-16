<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopOrdersTable.
 */
class CreateShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_orders', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable (); //用户
            $table->integer ('merch_id')->nullable (); // 商户
            $table->integer ('agent_id')->nullable (); //代理商
            $table->string ('orderno')->nullable (); //订单号

            $table->decimal ('total_price')->nullable (); //总价
            $table->decimal ('discount_price')->nullable ()->default (0); //优惠价格
            $table->decimal ('dispatch_price')->nullable ()->default (0); //快递价格
            $table->decimal ('deduct_credit1')->nullable ()->default (0); //扣除金余额
            $table->integer ('deduct_credit2')->nullable ()->default (0); //扣除积分
            $table->decimal ('deduct_enough')->nullable ()->default (0); //扣除满额立减金额
            $table->decimal ('change_price')->nullable ()->default (0); //修改价格
            $table->decimal ('change_dispatch_price')->nullable ()->default (0); //修改快递价格
            $table->decimal ('old_price')->nullable ()->default (0); //修改之前的价格
            $table->decimal ('old_dispatch_price')->nullable (); //修改之前的快递价格
            $table->integer ('coupon_id')->nullable (); //优惠券id
            $table->decimal ('coupon_price')->nullable ()->default (0); //优惠券金额

            $table->integer ('address_id')->nullable (); //地址ID
            $table->text ('address')->nullable (); //地址

            $table->string ('express')->nullable (); //快递地址
            $table->string ('expresscom')->nullable (); //快递公司
            $table->string ('expresssn')->nullable (); //快递单号


            $table->tinyInteger ('paytype')->nullable (); //支付类型 //微信  支付宝 余额 后台付款
            $table->tinyInteger ('type')->nullable (); //订单来源
            $table->tinyInteger ('status')->nullable ()->default (1); //状态
            $table->text ('remark')->nullable (); //备注
            $table->string ('close_reason')->nullable (); //订单关闭原因

            $table->timestamp ('pay_time')->nullable (); //支付时间
            $table->timestamp ('send_time')->nullable (); //发货时间
            $table->timestamp ('cancel_time')->nullable (); //取消时间
            $table->timestamp ('finish_time')->nullable (); //完成时间
            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
            $table->index ('agent_id');
            $table->index ('type');
            $table->index ('paytype');
            $table->index ('status');
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
        Schema::drop ('shop_orders');
    }
}
