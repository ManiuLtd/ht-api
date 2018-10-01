<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.4.1).
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->integer('merch_id')->nullable();
            $table->string('orderno', 191)->nullable();
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('discount_price', 8, 2)->nullable()->default(0.00);
            $table->decimal('dispatch_price', 8, 2)->nullable()->default(0.00);
            $table->decimal('deduct_credit1', 8, 2)->nullable()->default(0.00);
            $table->integer('deduct_credit2')->nullable()->default(0);
            $table->decimal('deduct_enough', 8, 2)->nullable()->default(0.00);
            $table->decimal('change_price', 8, 2)->nullable()->default(0.00);
            $table->decimal('change_dispatch_price', 8, 2)->nullable()->default(0.00);
            $table->decimal('old_price', 8, 2)->nullable()->default(0.00);
            $table->decimal('old_dispatch_price', 8, 2)->nullable();
            $table->integer('coupon_id')->nullable();
            $table->decimal('coupon_price', 8, 2)->nullable()->default(0.00);
            $table->integer('address_id')->nullable();
            $table->text('address')->nullable();
            $table->tinyInteger('paytype')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->text('remark')->nullable();
            $table->string('close_reason', 191)->nullable();
            $table->timestamp('pay_time')->nullable();
            $table->timestamp('cancel_time')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('user_id', 'shop_orders_user_id_index');
            $table->index('member_id', 'shop_orders_member_id_index');
            $table->index('merch_id', 'shop_orders_merch_id_index');
            $table->index('type', 'shop_orders_type_index');
            $table->index('paytype', 'shop_orders_paytype_index');
            $table->index('status', 'shop_orders_status_index');
            $table->index('created_at', 'shop_orders_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_orders');
    }
}
