<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->cascadeOnDelete();

            $table->string('locale', 5); // id/en

            $table->string('title', 190);
            $table->string('slug', 190);

            $table->string('excerpt', 350)->nullable();
            $table->longText('content')->nullable();

            $table->string('seo_title', 190)->nullable();
            $table->string('seo_description', 255)->nullable();

            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->unique(['news_id', 'locale']);
            $table->index(['news_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_translations');
    }
};