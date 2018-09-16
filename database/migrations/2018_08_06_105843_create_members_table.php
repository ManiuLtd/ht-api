<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMembersTable.
 */
class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('members', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();//用户id
            $table->integer ('inviter_id')->nullable ();//推荐人id
            $table->string ('unionid')->unique ()->nullable ();//公会id
            $table->string ('openid')->unique ()->nullable ();
            $table->string ('nickname')->nullable ();
            $table->string ('phone', 100)->unique ()->nullable ();
            $table->string ('password')->nullable ();
            $table->string ('headimgurl')->nullable ();
            $table->string ('relaname')->nullable (); //真实姓名
            $table->tinyInteger ('isagent')->nullable ()->default (0); //是否为代理
            $table->decimal ('total_commission')->nullable (); //总佣金
            $table->decimal ('credit1')->default (0); //余额
            $table->integer ('credit2')->default (0); //积分
            $table->integer ('level1')->nullable (); //会员等级
            $table->integer ('level2')->nullable (); //代理商等级
            $table->tinyInteger ('status')->default (1);
            $table->string ('agent_time')->nullable (); //成为代理时间

            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('inviter_id');
            $table->index ('unionid');
            $table->index ('openid');
            $table->index ('nickname');
            $table->index ('level1');
            $table->index ('level2');
            $table->index ('phone');
            $table->index ('status');
            $table->index ('created_at');
            $table->unique ('openid');
            $table->unique ('unionid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('members');
    }
}
