<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_code_id')->constrained('referral_codes')->cascadeOnDelete();
            $table->string('order_id', 50)->index();

            $table->string('buyer_username', 255)->nullable();
            $table->string('buyer_email', 255)->nullable();

            $table->decimal('order_amount', 12, 2)->default(0);

            $table->enum('buyer_discount_method', ['percentage', 'fixed_amount']);
            $table->decimal('buyer_discount_value', 12, 2)->default(0);
            $table->decimal('buyer_discount_amount', 12, 2)->default(0);

            $table->enum('reward_method', ['percentage', 'fixed_amount']);
            $table->decimal('reward_value', 12, 2)->default(0);
            $table->decimal('reward_amount', 12, 2)->default(0);

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();

            $table->unique(['referral_code_id', 'order_id']);
            $table->index(['referral_code_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_conversions');
    }
};
