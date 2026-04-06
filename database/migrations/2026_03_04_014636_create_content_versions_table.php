<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('content_versions', function (Blueprint $table) {
            $table->id();

            // 'page' | 'news'
            $table->string('entity_type', 20);

            // pages.id or news.id
            $table->unsignedBigInteger('entity_id');

            // null untuk snapshot global; atau 'id'/'en' untuk snapshot translation
            $table->string('locale', 5)->nullable();

            // snapshot data
            $table->json('payload');

            // session user_id (manual auth kamu)
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            $table->index(['entity_type', 'entity_id', 'created_at']);
            $table->index(['entity_type', 'entity_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_versions');
    }
};