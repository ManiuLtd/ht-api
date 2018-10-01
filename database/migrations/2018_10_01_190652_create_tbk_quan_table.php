<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 淘客圈子
 * Migration auto-generated by Sequel Pro Laravel Export (1.4.1).
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTbkQuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbk_quan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('item_id', 191)->nullable(); //产品ID
            $table->string('nickname', 200)->nullable(); //发送人昵称
            $table->string('headimg', 190)->nullable(); // 发送人头像
            $table->text('introduce')->nullable(); //圈子文字内容
            $table->text('images')->nullable(); //圈子配图
            $table->text('comments')->nullable();  //评论列表(多条评论，使用json存放一个数组，后端添加，前端不可添加)
            $table->tinyInteger('taokouling')->nullable(); //评论列表是否显示淘口令
            $table->integer('shares')->nullable()->comment('分享次数'); //分享次数
            $table->timestamp('share_at')->nullable(); //建议分享时间
            $table->tinyInteger('type')->nullable(); //1淘宝 2京东 3拼多多 4 发圈素材
            $table->nullableTimestamps();

            $table->index('item_id');
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
        Schema::dropIfExists('tbk_quan');
    }
}
