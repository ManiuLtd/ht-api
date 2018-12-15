<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbkDiyZhuantiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbk_diy_zhuanti', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pid', 190)->nullable();  //父id
            $table->string('name', 190)->nullable();  //标题
            $table->string('params', 190)->nullable();  //参数
            $table->string('thumb', 190)->nullable();  //主图
            $table->string('list_thumb', 190)->nullable();  //列表图
            $table->string('bgimg', 190)->nullable();  //专题背景图
            $table->tinyInteger('show_category')->nullable();  //	显示分类  0.显示1.不显示
            $table->integer('limit')->nullable();  //	显示条数
            $table->integer('sort')->nullable();  //	排序方式
            $table->tinyInteger('status')->nullable();  //	是否显示  0.显示1.不显示
            $table->tinyInteger('layout1')->nullable();  //主页布局1 2 3
            $table->tinyInteger('layout2')->nullable();  //列表页布局1 2
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
        Schema::dropIfExists('tbk_diy_zhuanti');
    }
}
