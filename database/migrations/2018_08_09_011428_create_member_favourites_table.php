<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberFavouritesTable.
 */
class CreateMemberFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_favourites', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->integer ('goods_id')->nullable ();
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
        Schema::drop ('member_favourites');
    }
}
