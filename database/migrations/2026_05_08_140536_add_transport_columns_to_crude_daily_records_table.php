<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crude_daily_records', function (Blueprint $table) {
            if (! Schema::hasColumn('crude_daily_records', 'vacuum_truck')) {
                $table->decimal('vacuum_truck', 18, 4)->default(0)->after('record_date');
            }

            if (! Schema::hasColumn('crude_daily_records', 'road_tank')) {
                $table->decimal('road_tank', 18, 4)->default(0)->after('vacuum_truck');
            }
        });

        if (Schema::hasColumn('crude_daily_records', 'production')) {
            DB::table('crude_daily_records')
                ->where(function ($query) {
                    $query->whereNull('vacuum_truck')
                        ->orWhere('vacuum_truck', 0);
                })
                ->where(function ($query) {
                    $query->whereNull('road_tank')
                        ->orWhere('road_tank', 0);
                })
                ->update([
                    'vacuum_truck' => DB::raw('COALESCE(production, 0)'),
                    'road_tank' => 0,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('crude_daily_records', function (Blueprint $table) {
            if (Schema::hasColumn('crude_daily_records', 'road_tank')) {
                $table->dropColumn('road_tank');
            }

            if (Schema::hasColumn('crude_daily_records', 'vacuum_truck')) {
                $table->dropColumn('vacuum_truck');
            }
        });
    }
};