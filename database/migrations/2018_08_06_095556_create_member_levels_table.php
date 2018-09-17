<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberLevelsTable.
 */
class CreateMemberLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_levels', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->string ('name');
            $table->string ('logo');
            $table->integer ('level');
            $table->decimal ('discount')->default (0);
            $table->integer ('credit')->default (0); //升级所需积分
            $table->integer ('sort')->default (100);
            $table->tinyInteger ('status');
            $table->timestamps ();

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
        Schema::drop ('member_levels');
    }
}
