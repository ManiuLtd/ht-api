<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopGoodsCommentsTable.
 */
class CreateShopGoodsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_goods_comments', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('merch_id')->nullable (); // 商户
            $table->integer ('order_id')->nullable ();
            $table->integer ('goods_id')->nullable ();
            $table->string ('nickname')->nullable ();
            $table->string ('headimgurl')->nullable ();
            $table->tinyInteger ('level')->nullable (); //星级
            $table->text ('content')->nullable (); //评价
            $table->text ('reply_content')->nullable (); //回复
            $table->text ('append_content')->nullable (); //追评
            $table->text ('append_reply_content')->nullable (); // 回复追评
            $table->text ('images')->nullable (); // 图片
            $table->text ('appendimages')->nullable (); //追评图片
            $table->text ('appendreplyimages')->nullable (); //回复追评图片
            $table->tinyInteger ('istop')->nullable ()->default (0);
            $table->tinyInteger ('checked')->nullable ()->default (0);
            $table->tinyInteger ('reply_checked')->nullable ()->default (0);

            $table->timestamps ();
            $table->softDeletes ();


            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
            $table->index ('order_id');
            $table->index ('goods_id');
            $table->index ('nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_goods_comments');
    }
}
