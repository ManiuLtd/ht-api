<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSettingsTable.
 */
class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('settings', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->text ('enough_reduce')->nullable ();
            $table->text ('enough_free')->nullable ();
            $table->text ('deduction')->nullable ();
            $table->text ('payment')->nullable ();
            $table->text ('recharge')->nullable ();
            $table->text ('credit')->nullable ();
            $table->text ('shop')->nullable ();

            $table->timestamps ();

            $table->index ('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('settings');
    }
}
