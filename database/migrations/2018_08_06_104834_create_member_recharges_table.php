<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberRechargesTable.
 */
class CreateMemberRechargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_recharges', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->string ('title')->nullable ();
            $table->string ('out_trade_no')->nullable (); //订单号
            $table->decimal ('money')->nullble ()->default (0); //充值金额
            $table->decimal ('real_money')->nullble ()->default (0); //实际到账金额
            $table->decimal ('giving_money')->nullble ()->default (0); //赠送金额
            $table->tinyInteger ('type')->nullable (); //支付类型
            $table->tinyInteger ('status')->nullable (); //状态
            $table->timestamp ('pay_time')->nullable ();
            $table->timestamps ();

            $table->index ('user_id');
            $table->index ('member_id');
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
        Schema::drop ('member_recharges');
    }
}
