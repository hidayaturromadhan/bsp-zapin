<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_document_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_document_id')
                ->constrained('investor_documents')
                ->cascadeOnDelete();

            $table->string('locale', 5);
            $table->string('title');
            $table->text('summary')->nullable();
            $table->timestamps();

            $table->unique(
                ['investor_document_id', 'locale'],
                'inv_doc_tr_doc_locale_unq'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_document_translations');
    }
};