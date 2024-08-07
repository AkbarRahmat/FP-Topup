<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('status', ['pending', 'processed', 'success', 'rejected'])->default('pending');
            $table->string('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('usergame_id')->nullable();
            $table->foreign('usergame_id')->references('id')->on('usergames');
            $table->string('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->string('processed_by')->nullable();
            $table->string('processed_proof')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
