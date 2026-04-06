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
        Schema::create('gcg_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gcg_category_id')->constrained('gcg_categories')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['gcg_category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcg_category_translations');
    }
};
