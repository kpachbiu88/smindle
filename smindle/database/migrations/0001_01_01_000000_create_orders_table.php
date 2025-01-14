<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->timestamps();
        });

        Schema::create('basket_items', function (Blueprint $table) {
            $table->id()->primary();
            // $table->foreignId('order_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('name');
            $table->string('type');
            $table->integer('price');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('basket_items');
    }
};
