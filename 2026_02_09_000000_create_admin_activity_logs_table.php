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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->string('action');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('admin_name')->nullable();
            $table->string('admin_email')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['admin_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
