<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_display_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Display TV Operational');
            $table->string('token', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            $table->index(['token', 'is_active']);
            $table->index('expired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_display_tokens');
    }
};