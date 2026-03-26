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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('discount_active')->default(false)->after('is_active');
            $table->enum('discount_method', ['percentage', 'fixed_amount'])->nullable()->after('discount_active');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount_active', 'discount_method', 'discount_value']);
        });
    }
};
