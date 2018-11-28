<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->comment('用户id');
            $table->decimal('price', 9, 2)->nullable()->comment('下单金额');
            $table->string('transaction_id')->nullable()->comment('微信支付订单号');
            $table->string('out_trade_no')->nullable()->comment('商户订单号');
            $table->timestamp('time_end')->nullable()->comment('支付完成时间');
            $table->timestamp('time_start')->nullable()->comment('下单时间');
            $table->integer('type')->nullable()->comment('1 是等级 2 是订单');
            $table->integer('status')->nullable()->comment('1 支付完成 2 支付未完成');
            $table->string('other')->nullable()->comment('其余参数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_payment');
    }
}
