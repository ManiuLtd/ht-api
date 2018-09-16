<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopGoodsCategoriesTable.
 */
class CreateShopGoodsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_goods_categories', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('parentid')->nullable ()->default (0);
            $table->string ('name')->nullable ();
            $table->string ('thumb')->nullable ();
            $table->string ('description')->nullable ();
            $table->string ('advimg')->nullable ();
            $table->string ('advurl')->nullable ();
            $table->integer ('sort')->default (100);
            $table->tinyInteger ('status')->nullable ()->default (1);
            $table->tinyInteger ('ishome')->nullable ()->default (0);
            $table->tinyInteger ('isrecommand')->nullable ()->default (0);

            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('parentid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_goods_categories');
    }
}
