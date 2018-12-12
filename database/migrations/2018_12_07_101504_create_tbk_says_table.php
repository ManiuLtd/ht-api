<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbkSaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbk_says', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->nullable();  //文章ID
            $table->string('name', 190)->nullable();  //文章标题
            $table->string('short_title', 190)->nullable();  //文章短标题
            $table->string('thumb', 190)->nullable();  //置顶图片
            $table->string('label', 190)->nullable();  //文章标签
            $table->string('item_id', 255)->nullable();  //文章包含商品的id
            $table->string('banner', 190)->nullable();  //文章banner
            $table->integer('readtimes')->nullable(); //	浏览量
            $table->integer('followtimes')->nullable(); //	点赞量
            $table->string('nickname', 190)->nullable(); //	达人名号
            $table->string('headimgurl', 190)->nullable(); //	达人头像
            $table->integer('user_id')->default(1); //	达人id
            $table->string('compose_image', 190)->nullable(); // APP信息流主图
            $table->longText('content')->nullable(); // 内容
            $table->tinyInteger('is_top')->default(0)->comment("置顶文章 1是 0否"); //文章类别（topdata.置顶文章,newdata.最新文章,clickdata.所有）
            $table->tinyInteger('is_new')->default(0)->comment("最新文章 1是 0否");
            $table->tinyInteger('is_all')->default(0)->comment("所有文章 1是 0否");
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbk_says');
    }
}
