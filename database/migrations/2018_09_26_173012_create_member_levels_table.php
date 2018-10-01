<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.4.1).
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
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
        Schema::create('member_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('name', 191)->nullable()->default('');
            $table->string('logo', 191)->nullable()->default('');
            $table->integer('level')->nullable();
            $table->decimal('discount', 8, 2)->nullable()->default(0.00);
            $table->integer('credit')->nullable()->default(0);
            $table->decimal('money', 8, 2)->nullable();
            $table->integer('sort')->nullable()->default(100);
            $table->tinyInteger('status')->nullable();
            $table->nullableTimestamps();

            $table->index('user_id', 'member_levels_user_id_index');
            $table->index('name', 'member_levels_name_index');
            $table->index('status', 'member_levels_status_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_levels');
    }
}
