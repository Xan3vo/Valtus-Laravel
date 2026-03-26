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
        Schema::create('promo_code_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->onDelete('cascade');
            $table->string('order_id')->nullable(); // Order ID yang menggunakan promo code
            $table->string('username')->nullable(); // Username yang pakai promo code
            $table->string('email')->nullable(); // Email yang pakai promo code
            $table->decimal('original_price', 10, 2); // Harga sebelum diskon
            $table->decimal('discount_amount', 10, 2); // Jumlah diskon yang diterapkan
            $table->decimal('final_price', 10, 2); // Harga setelah diskon
            $table->string('payment_status')->default('Pending'); // Status pembayaran: Pending, Completed, Expired
            $table->boolean('is_paid')->default(false); // Apakah order sudah dibayar
            $table->timestamps();
            
            // Indexes
            $table->index('promo_code_id');
            $table->index('order_id');
            $table->index('payment_status');
            $table->index('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_code_usages');
    }
};
