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
        Schema::table('orders', function (Blueprint $table) {
            // Add purchase_method field - nullable, only used for Robux orders
            // NULL = not Robux order or old order (backward compatibility)
            // 'gamepass' = Robux via gamepass (default)
            // 'group' = Robux via group
            $table->string('purchase_method')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('purchase_method');
        });
    }
};
