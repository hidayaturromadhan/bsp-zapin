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
        Schema::create('gcg_document_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gcg_document_id')->constrained('gcg_documents')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->timestamps();

            $table->unique(['gcg_document_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gcg_document_translations');
    }
};
