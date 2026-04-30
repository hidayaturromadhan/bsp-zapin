<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tjsl_programs', function (Blueprint $table) {
            if (! Schema::hasColumn('tjsl_programs', 'status')) {
                $table->string('status', 30)->default('draft')->after('is_active');
            }

            if (! Schema::hasColumn('tjsl_programs', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('tjsl_programs', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('tjsl_programs', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('reviewed_by');
            }

            if (! Schema::hasColumn('tjsl_programs', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            }

            if (! Schema::hasColumn('tjsl_programs', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('reviewed_at');
            }

            if (! Schema::hasColumn('tjsl_programs', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('published_at');
            }

            if (! Schema::hasColumn('tjsl_programs', 'review_note')) {
                $table->text('review_note')->nullable()->after('rejected_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tjsl_programs', function (Blueprint $table) {
            if (Schema::hasColumn('tjsl_programs', 'review_note')) {
                $table->dropColumn('review_note');
            }

            if (Schema::hasColumn('tjsl_programs', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }

            if (Schema::hasColumn('tjsl_programs', 'published_at')) {
                $table->dropColumn('published_at');
            }

            if (Schema::hasColumn('tjsl_programs', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }

            if (Schema::hasColumn('tjsl_programs', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }

            if (Schema::hasColumn('tjsl_programs', 'reviewed_by')) {
                $table->dropConstrainedForeignId('reviewed_by');
            }

            if (Schema::hasColumn('tjsl_programs', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }

            if (Schema::hasColumn('tjsl_programs', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};