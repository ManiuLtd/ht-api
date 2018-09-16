<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMemberAddressesTable.
 */
class CreateMemberAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ('member_addresses', function (Blueprint $table) {
            $table->increments ('id');
            $table->integer ('user_id')->nullable ();
            $table->integer ('member_id')->nullable ();
            $table->string ('realname')->nullable ();
            $table->string ('phone')->nullable ();
            $table->string ('province')->nullable ();
            $table->string ('city')->nullable ();
            $table->string ('area')->nullable ();
            $table->string ('address')->nullable ();
            $table->string ('zipcode')->nullable ();
            $table->tinyInteger ('isdefault')->nullable ()->default (0);
            $table->tinyInteger ('type')->nullable ()->default (1); //1 收货地址 2退货地址
            $table->timestamps ();
            $table->softDeletes ();

            $table->index ('user_id');
            $table->index ('member_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ('member_addresses');
    }
}
