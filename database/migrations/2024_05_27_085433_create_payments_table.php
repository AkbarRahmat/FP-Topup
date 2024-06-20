<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('vendor');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->integer('product_price');
            $table->integer('seller_cost');
            $table->integer('service_cost');
            $table->integer('total_cost');
            $table->integer('paid_price');
            $table->integer('refund_cost');
            $table->integer('debt_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments_tableable');
    }
}
