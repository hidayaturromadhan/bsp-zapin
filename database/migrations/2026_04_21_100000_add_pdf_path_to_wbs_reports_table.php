<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wbs_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('wbs_reports', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('follow_up_result');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wbs_reports', function (Blueprint $table) {
            if (Schema::hasColumn('wbs_reports', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
        });
    }
};