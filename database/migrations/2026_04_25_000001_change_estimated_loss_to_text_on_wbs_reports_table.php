<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE wbs_reports MODIFY estimated_loss TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE wbs_reports MODIFY estimated_loss DECIMAL(15,2) NULL');
    }
};