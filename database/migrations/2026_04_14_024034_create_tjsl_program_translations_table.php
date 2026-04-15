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
        Schema::create('tjsl_program_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tjsl_program_id')->constrained()->cascadeOnDelete();

            $table->string('locale', 5);
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();

            $table->unique(['tjsl_program_id', 'locale'], 'tjsl_prog_locale_unq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tjsl_program_translations');
    }
};
