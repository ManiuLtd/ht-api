<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopOrderGoodsTable.
 */
class CreateShopOrderGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_order_goods', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('order_id')->nullable ();
            $table->integer ('goods_id')->nullable ();
            $table->integer ('merch_id')->nullable (); //店铺id
            $table->integer ('agent_id')->nullable (); //代理id
            $table->decimal ('price')->nullable ();
            $table->decimal ('change_price')->nullable ();
            $table->decimal ('old_price')->nullable ();
            $table->decimal ('bugagain_price')->nullable (); //重复购买金额

            $table->integer ('total')->nullable ();
            $table->integer ('refund_total')->nullable (); //退货数量
            $table->string ('title')->nullable ();
            $table->string ('thumb')->nullable ();
            $table->integer ('option_id')->nullable ();
            $table->string ('option_name')->nullable ();
            $table->decimal ('commissions')->nullable ();  //总佣金
            $table->decimal ('commission0')->nullable (); //代理商返佣
            $table->decimal ('commission1')->nullable (); //一级返佣
            $table->decimal ('commission2')->nullable (); //二级返佣
            $table->decimal ('commission3')->nullable (); //三级返佣
            $table->tinyInteger ('nocommission')->nullable (); //不参与分销
            $table->string ('goodssn')->nullable ();
            $table->string ('productsn')->nullable ();

            $table->string ('express')->nullable (); //快递地址
            $table->string ('expresscom')->nullable (); //快递公司
            $table->string ('expresssn')->nullable (); //快递单号
            $table->integer ('refundid')->nullable (); //维权订单id
            $table->string ('refund_state')->nullable (); //退货状态
            $table->tinyInteger ('can_buyagain')->nullable ()->default (1); //是否可以重复购买
            $table->tinyInteger ('iscomment')->nullable ()->default (0); //开启评价

            $table->timestamp ('send_time')->nullable (); //发货时间
            $table->timestamp ('refund_time')->nullable (); //维权时间
            $table->timestamp ('fetch_time')->nullable (); //收货时间
            $table->timestamp ('finish_time')->nullable (); //完成时间
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
            $table->index ('order_id');
            $table->index ('goods_id');
            $table->index ('agent_id');
            $table->index ('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_order_goods');
    }
}
