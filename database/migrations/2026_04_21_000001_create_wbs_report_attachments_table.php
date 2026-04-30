<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wbs_report_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wbs_report_id')
                ->constrained('wbs_reports')
                ->cascadeOnDelete();

            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_disk')->default('public');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);

            $table->timestamps();

            $table->index(['wbs_report_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wbs_report_attachments');
    }
};