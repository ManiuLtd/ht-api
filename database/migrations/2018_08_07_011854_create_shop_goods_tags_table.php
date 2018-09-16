<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopGoodsTagsTable.
 */
class CreateShopGoodsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_goods_tags', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->string ('name')->nullable ();
            $table->integer ('sort')->nullable ()->default (100);
            $table->string ('color')->nullable ();
            $table->tinyInteger ('status')->nullable ()->default (1);

            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('name');
            $table->index ('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_goods_tags');
    }
}
