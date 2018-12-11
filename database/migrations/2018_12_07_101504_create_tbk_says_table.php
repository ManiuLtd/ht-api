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
            $table->integer('articleid')->nullable();  //文章ID
            $table->string('name', 190)->nullable();  //文章标题
            $table->string('shorttitle', 190)->nullable();  //文章短标题
            $table->string('image', 190)->nullable();  //文章商品主图
            $table->string('app_image', 190)->nullable();  //置顶图片
            $table->string('label', 190)->nullable();  //文章标签
            $table->string('tk_item_id', 255)->nullable();  //文章包含商品的id
            $table->string('article_banner', 190)->nullable();  //文章banner
            $table->tinyInteger('highquality')->nullable(); //文章置顶1是，0否
            $table->integer('readtimes')->nullable(); //	浏览量
            $table->string('talent_name', 190)->nullable(); //	达人名号
            $table->string('compose_image', 190)->nullable(); // APP信息流主图
            $table->tinyInteger('talentcat')->nullable(); //	文章类别（1.好物,2.潮流,3.美食,4.生活）
            $table->string('topdata', 190)->nullable(); //	文章类别（topdata.置顶文章,newdata.最新文章,clickdata.阅读量降序排列的文章）
            $table->string('newdata', 190)->nullable(); //	文章类别（topdata.置顶文章,newdata.最新文章,clickdata.阅读量降序排列的文章）
            $table->string('clickdata', 190)->nullable(); //	文章类别（topdata.置顶文章,newdata.最新文章,clickdata.阅读量降序排列的文章）
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
