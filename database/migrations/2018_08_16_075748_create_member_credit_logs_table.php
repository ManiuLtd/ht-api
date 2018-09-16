<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberCreditLogsTable.
 */
class CreateMemberCreditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_credit_logs', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('operater_id')->nullable (); //操作人ID
            $table->decimal ('credit')->default (0); //余额 积分
            $table->tinyInteger ('type'); //1余额 2积分
            $table->string ('remark')->nullable ();

            $table->timestamps ();

            $table->index ('member_id');
            $table->index ('type');
            $table->index ('user_id');
            $table->index ('operater_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('member_credit_logs');
    }
}
