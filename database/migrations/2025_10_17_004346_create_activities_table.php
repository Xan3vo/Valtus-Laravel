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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('username');
            $table->string('game_type')->default('Robux');
            $table->integer('amount');
            $table->decimal('total_amount', 10, 2);
            $table->string('activity_type')->default('purchase'); // purchase, completion, etc
            $table->string('status')->default('completed'); // completed, pending, etc
            $table->timestamp('processed_at');
            $table->timestamps();
            
            $table->index(['processed_at', 'status']);
            $table->index('activity_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};