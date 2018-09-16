<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateShopCartsTable.
 */
class CreateShopCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('shop_carts', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->integer ('goods_id')->nullable ();
            $table->integer ('option_id')->nullable ();
            $table->integer ('total')->nullable ();
            $table->decimal ('price')->nullable ();
            $table->decimal ('old_price')->nullable ();

            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('shop_carts');
    }
}
