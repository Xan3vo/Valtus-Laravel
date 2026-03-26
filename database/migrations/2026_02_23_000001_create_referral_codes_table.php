<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name')->nullable();
            $table->string('secret_token', 100)->unique();

            $table->enum('buyer_discount_method', ['percentage', 'fixed_amount']);
            $table->decimal('buyer_discount_value', 12, 2);

            $table->enum('reward_method', ['percentage', 'fixed_amount']);
            $table->decimal('reward_value', 12, 2);

            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->decimal('max_order_amount', 12, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_codes');
    }
};
