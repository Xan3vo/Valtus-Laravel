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
        Schema::create('robux_discount_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('purchase_method', ['gamepass', 'group']);
            $table->integer('min_amount')->nullable(); // Null untuk exact amount, atau batas bawah range
            $table->integer('max_amount')->nullable(); // Null untuk unlimited, atau batas atas range
            $table->enum('discount_method', ['percentage', 'fixed_amount']);
            $table->decimal('discount_value', 10, 2);
            $table->integer('sort_order')->default(0); // Untuk prioritas, lebih kecil = prioritas lebih tinggi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index untuk query yang efisien
            $table->index(['purchase_method', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robux_discount_rules');
    }
};
