<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (!Schema::hasColumn('partners', 'category')) {
                $table->string('category', 30)
                    ->default('business_partner')
                    ->after('name');
            }
        });

        DB::table('partners')
            ->whereNull('category')
            ->update([
                'category' => 'business_partner',
            ]);
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            if (Schema::hasColumn('partners', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};