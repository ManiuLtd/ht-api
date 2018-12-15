<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbkDiyAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbk_diy_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 190)->nullable();  //标题
            $table->string('thumb', 190)->nullable();  //图片
            $table->string('url', 190)->nullable();  //地址
            $table->integer('sort')->nullable();  //	排序方式
            $table->tinyInteger('status')->nullable();  //	是否显示  0.显示1.不显示
            $table->tinyInteger('type')->nullable();  //1.url 2.内部模块
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
        Schema::dropIfExists('tbk_diy_ads');
    }
}
