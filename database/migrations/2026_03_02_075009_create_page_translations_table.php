<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();

            $table->string('locale', 5); // id/en
            $table->string('title', 190);
            $table->string('slug', 190);
            $table->longText('content')->nullable();

            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->unique(['page_id', 'locale']);
            $table->index(['page_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_translations');
    }
};