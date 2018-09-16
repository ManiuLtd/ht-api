<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberWithdrawsTable.
 */
class CreateMemberWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_withdraws', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->decimal ('money')->nullable ()->default (0);//提现金额
            $table->decimal ('real_money')->nullable ()->default (0);//实际到账
            $table->decimal ('deduct_money')->nullable ()->default (0);//扣除金额
            $table->string ('realname')->nullable ();  //真实姓名
            $table->string ('alipay')->nullable (); //支付宝
            $table->string ('bankname')->nullable (); //银行
            $table->string ('bankcard')->nullable (); //银行卡
            $table->text ('reason')->nullable (); //原因
            $table->tinyInteger ('status')->nullable ();
            $table->tinyInteger ('pay_type')->nullable (); //支付类型
            $table->timestamp ('pay_time')->nullable (); //支付时间
            $table->timestamp ('refused_time')->nullable (); //拒绝提现时间
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
        Schema::drop ('member_withdraws');
    }
}
