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
        Schema::create('carts', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('profile_id');
        $table->bigInteger('product_id');
        $table->integer('quantity');
        $table->integer('price');
        $table->integer('total_price');
        $table->boolean('is_checked_out')->default(false);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
