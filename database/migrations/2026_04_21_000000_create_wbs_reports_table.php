<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wbs_reports', function (Blueprint $table) {
            $table->id();

            $table->string('report_number', 50)->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('category', 100);
            $table->string('title', 255);

            $table->longText('description');
            $table->text('involved_parties')->nullable();
            $table->string('location')->nullable();
            $table->date('incident_date')->nullable();
            $table->text('chronology')->nullable();

            $table->decimal('estimated_loss', 18, 2)->nullable();

            $table->boolean('has_evidence')->default(false);
            $table->boolean('reported_before')->default(false);
            $table->boolean('reported_to_other_party')->default(false);

            $table->string('status', 50)->default('laporan_masuk');

            $table->text('admin_notes')->nullable();
            $table->text('follow_up_result')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['category']);
            $table->index(['status']);
            $table->index(['incident_date']);
            $table->index(['submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wbs_reports');
    }
};