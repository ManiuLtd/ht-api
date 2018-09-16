<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberHistoriesTable.
 */
class CreateMemberHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_histories', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->integer ('merch_id')->nullable ();
            $table->integer ('goods_id')->nullable ();
            $table->integer ('views')->nullable (); //浏览次数
            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('member_id');
            $table->index ('merch_id');
            $table->index ('goods_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('member_histories');
    }
}
