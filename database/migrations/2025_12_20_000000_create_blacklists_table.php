<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('username_lower', 100)->unique();
            $table->text('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('banned_until')->nullable();
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'banned_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};
