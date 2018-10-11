<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 分销商等级表
 * Migration auto-generated by Sequel Pro Laravel Export (1.4.1).
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateCommissionLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('level')->nullable(); //等级大小
            $table->string('name', 100)->nullable(); //等级名
            $table->string('logo', 191)->nullable();  //等级图标
            $table->decimal('group_rate1', 4, 2)->nullable(); //组长拿团队每笔订单的佣金比例
            $table->decimal('group_rate2', 4, 2)->nullable(); //团队某个人升级为组长后，老组长拿的新组长团队的佣金比例
            $table->decimal('commission_rate1', 4, 2)->nullable();  //自购佣金比例
            $table->decimal('commission_rate2', 4, 2)->nullable();  //拿下级的佣金比例
            $table->decimal('credit', 8, 2)->nullable();  //升级所需积分
//            $table->decimal('min_commission', 9, 2)->nullable();  //升级最低佣金
//            $table->integer('friends1')->nullable(); //升级最低下级粉丝数
//            $table->integer('friends2')->nullable(); // 升级最低下下级粉丝数
//            $table->integer('ordernum')->nullable(); //升级最低订单数
            $table->decimal('price', 9, 2)->nullable(); //升级所需支付金额
            $table->integer('duration')->nullable(); // 等级有效时长 按天计算
            $table->string('description', 191)->nullable(); //等级描述
            $table->tinyInteger('is_commission')->default(1)->comment('1有返佣 0没有返佣'); //1有返佣 0没有返佣

            $table->tinyInteger('type')->nullable(); //1分销等级 2组等级
            $table->nullableTimestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_levels');
    }
}
