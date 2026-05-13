<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use App\Models\CrudeDailyRecord;
use App\Models\FlowGasDailyRecord;
use App\Models\OperationalDisplayToken;
use App\Models\VitolRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) ($request->input('month') ?: now()->month);
        $selectedYear = (int) ($request->input('year') ?: now()->year);

        /*
        |--------------------------------------------------------------------------
        | Cache Key V2
        |--------------------------------------------------------------------------
        | Dibuat versi baru supaya cache lama yang masih menyimpan struktur crude lama
        | tidak ikut terbaca.
        |--------------------------------------------------------------------------
        */
        $cacheKey = 'operational_dashboard_v2_' . $selectedYear . '_' . $selectedMonth;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($selectedMonth, $selectedYear) {
            /*
            |--------------------------------------------------------------------------
            | FLOW GAS
            |--------------------------------------------------------------------------
            */
            $gasBaseQuery = FlowGasDailyRecord::query()->where('type', 'gas');

            $gasMonthlyRecords = (clone $gasBaseQuery)
                ->with('category')
                ->whereYear('record_date', $selectedYear)
                ->whereMonth('record_date', $selectedMonth)
                ->orderBy('record_date')
                ->orderBy('flow_gas_category_id')
                ->get();

            $gasTotalRecords = $gasMonthlyRecords->count();
            $gasTotalMscf = (float) $gasMonthlyRecords->sum('mscf');
            $gasTotalMmbtu = (float) $gasMonthlyRecords->sum('mmbtu');
            $gasTotalFix = (float) $gasMonthlyRecords->sum('fix');

            $gasCategorySummary = (clone $gasBaseQuery)
                ->select([
                    'flow_gas_category_id',
                    DB::raw('SUM(COALESCE(mscf, 0)) as total_mscf'),
                    DB::raw('SUM(COALESCE(mmbtu, 0)) as total_mmbtu'),
                    DB::raw('SUM(COALESCE(fix, 0)) as total_fix'),
                ])
                ->with('category')
                ->whereYear('record_date', $selectedYear)
                ->whereMonth('record_date', $selectedMonth)
                ->groupBy('flow_gas_category_id')
                ->orderBy('flow_gas_category_id')
                ->get();

            $gasDailyChartRaw = (clone $gasBaseQuery)
                ->select([
                    'record_date',
                    DB::raw('SUM(COALESCE(mscf, 0)) as total_mscf'),
                ])
                ->whereYear('record_date', $selectedYear)
                ->whereMonth('record_date', $selectedMonth)
                ->groupBy('record_date')
                ->orderBy('record_date')
                ->get();

            $gasDailyChartLabels = $gasDailyChartRaw->map(function ($item) {
                return Carbon::parse($item->record_date)->format('d M');
            })->values();

            $gasDailyChartValues = $gasDailyChartRaw->map(function ($item) {
                return round((float) $item->total_mscf, 4);
            })->values();

            $gasMonthlyDailySubquery = FlowGasDailyRecord::query()
                ->where('type', 'gas')
                ->selectRaw('DATE(record_date) as daily_date')
                ->selectRaw('MONTH(record_date) as month_number')
                ->selectRaw('YEAR(record_date) as year_number')
                ->selectRaw('SUM(COALESCE(mscf, 0)) as daily_total_mscf')
                ->whereYear('record_date', $selectedYear)
                ->groupBy(
                    DB::raw('DATE(record_date)'),
                    DB::raw('MONTH(record_date)'),
                    DB::raw('YEAR(record_date)')
                );

            $gasMonthlyChartRaw = DB::query()
                ->fromSub($gasMonthlyDailySubquery, 'gas_daily_totals')
                ->selectRaw('month_number, AVG(daily_total_mscf) as avg_daily_mscf')
                ->groupBy('month_number')
                ->orderBy('month_number')
                ->get()
                ->keyBy('month_number');

            $gasMonthlyChartLabels = collect(range(1, 12))->map(function ($month) {
                return Carbon::create()->month($month)->translatedFormat('M');
            })->values();

            $gasMonthlyChartValues = collect(range(1, 12))->map(function ($month) use ($gasMonthlyChartRaw) {
                return round((float) optional($gasMonthlyChartRaw->get($month))->avg_daily_mscf, 4);
            })->values();

            $gasYearlyChartRaw = (clone $gasBaseQuery)
                ->selectRaw('YEAR(record_date) as year_number, SUM(COALESCE(mscf, 0)) as total_mscf')
                ->groupBy(DB::raw('YEAR(record_date)'))
                ->orderBy(DB::raw('YEAR(record_date)'))
                ->get();

            $gasYearlyChartLabels = $gasYearlyChartRaw
                ->pluck('year_number')
                ->map(fn ($year) => (string) $year)
                ->values();

            $gasYearlyChartValues = $gasYearlyChartRaw
                ->pluck('total_mscf')
                ->map(fn ($value) => round((float) $value, 4))
                ->values();

            /*
            |--------------------------------------------------------------------------
            | CRUDE OIL
            |--------------------------------------------------------------------------
            | Struktur terbaru:
            | - vacuum_truck
            | - road_tank
            |
            | Total produksi = vacuum_truck + road_tank
            |--------------------------------------------------------------------------
            */
            $crudeBaseQuery = CrudeDailyRecord::query();

            $crudeMonthlyRecords = (clone $crudeBaseQuery)
                ->whereYear('record_date', $selectedYear)
                ->whereMonth('record_date', $selectedMonth)
                ->orderBy('record_date')
                ->get();

            $crudeTotalRecords = $crudeMonthlyRecords->count();

            $crudeTotalVacuumTruck = (float) $crudeMonthlyRecords->sum(function ($record) {
                return (float) ($record->vacuum_truck ?? 0);
            });

            $crudeTotalRoadTank = (float) $crudeMonthlyRecords->sum(function ($record) {
                return (float) ($record->road_tank ?? 0);
            });

            $crudeTotalProduction = $crudeTotalVacuumTruck + $crudeTotalRoadTank;

            $crudeLast14DaysRecords = CrudeDailyRecord::query()
                ->orderByDesc('record_date')
                ->orderByDesc('id')
                ->limit(14)
                ->get()
                ->sortBy('record_date')
                ->values();

            $crudeDailyChartLabels = $crudeLast14DaysRecords->map(function ($item) {
                return optional($item->record_date)->format('d M');
            })->values();

            $crudeDailyVacuumTruckValues = $crudeLast14DaysRecords->map(function ($item) {
                return round((float) ($item->vacuum_truck ?? 0), 4);
            })->values();

            $crudeDailyRoadTankValues = $crudeLast14DaysRecords->map(function ($item) {
                return round((float) ($item->road_tank ?? 0), 4);
            })->values();

            $crudeDailyChartValues = $crudeLast14DaysRecords->map(function ($item) {
                return round(
                    (float) ($item->vacuum_truck ?? 0) + (float) ($item->road_tank ?? 0),
                    4
                );
            })->values();

            $recentCrudeRecords = CrudeDailyRecord::query()
                ->latest('record_date')
                ->latest('id')
                ->limit(8)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | VITOL
            |--------------------------------------------------------------------------
            */
            $vitolBaseQuery = VitolRecord::query();

            $vitolYearlyRecords = (clone $vitolBaseQuery)
                ->where('year', $selectedYear)
                ->orderBy('month')
                ->orderBy('id')
                ->get()
                ->map(function ($record) {
                    $record->month_label = $this->monthLabel((int) $record->month);
                    return $record;
                });

            $vitolTotalRecords = $vitolYearlyRecords->count();
            $vitolTotalQuantity = (float) $vitolYearlyRecords->sum('quantity');

            $vitolMonthlyChartLabels = $vitolYearlyRecords->pluck('month_label')->values();

            $vitolMonthlyChartValues = $vitolYearlyRecords->map(function ($item) {
                return round((float) $item->quantity, 4);
            })->values();

            $recentVitolRecords = VitolRecord::query()
                ->orderByDesc('year')
                ->orderByDesc('month')
                ->limit(8)
                ->get()
                ->map(function ($record) {
                    $record->month_label = $this->monthLabel((int) $record->month);
                    return $record;
                });

            /*
            |--------------------------------------------------------------------------
            | OPTIONS
            |--------------------------------------------------------------------------
            */
            $totalOperationalItems = $gasTotalRecords + $crudeTotalRecords + $vitolTotalRecords;

            $monthOptions = collect(range(1, 12))->mapWithKeys(function ($month) {
                return [$month => Carbon::create()->month($month)->translatedFormat('F')];
            });

            $flowGasYears = FlowGasDailyRecord::query()
                ->where('type', 'gas')
                ->selectRaw('YEAR(record_date) as year')
                ->distinct()
                ->pluck('year');

            $crudeYears = CrudeDailyRecord::query()
                ->selectRaw('YEAR(record_date) as year')
                ->distinct()
                ->pluck('year');

            $vitolYears = VitolRecord::query()
                ->distinct()
                ->pluck('year');

            $yearOptions = $flowGasYears
                ->merge($crudeYears)
                ->merge($vitolYears)
                ->filter()
                ->unique()
                ->sortDesc()
                ->values();

            if ($yearOptions->isEmpty()) {
                $yearOptions = collect([now()->year]);
            }

            return [
                'monthOptions' => $monthOptions,
                'yearOptions' => $yearOptions,

                'gasTotalRecords' => $gasTotalRecords,
                'gasTotalMscf' => $gasTotalMscf,
                'gasTotalMmbtu' => $gasTotalMmbtu,
                'gasTotalFix' => $gasTotalFix,
                'gasCategorySummary' => $gasCategorySummary,
                'gasDailyChartLabels' => $gasDailyChartLabels,
                'gasDailyChartValues' => $gasDailyChartValues,
                'gasMonthlyChartLabels' => $gasMonthlyChartLabels,
                'gasMonthlyChartValues' => $gasMonthlyChartValues,
                'gasYearlyChartLabels' => $gasYearlyChartLabels,
                'gasYearlyChartValues' => $gasYearlyChartValues,

                'crudeTotalRecords' => $crudeTotalRecords,
                'crudeTotalProduction' => $crudeTotalProduction,
                'crudeTotalVacuumTruck' => $crudeTotalVacuumTruck,
                'crudeTotalRoadTank' => $crudeTotalRoadTank,
                'crudeDailyChartLabels' => $crudeDailyChartLabels,
                'crudeDailyChartValues' => $crudeDailyChartValues,
                'crudeDailyVacuumTruckValues' => $crudeDailyVacuumTruckValues,
                'crudeDailyRoadTankValues' => $crudeDailyRoadTankValues,
                'recentCrudeRecords' => $recentCrudeRecords,

                'vitolTotalRecords' => $vitolTotalRecords,
                'vitolTotalQuantity' => $vitolTotalQuantity,
                'vitolMonthlyChartLabels' => $vitolMonthlyChartLabels,
                'vitolMonthlyChartValues' => $vitolMonthlyChartValues,
                'recentVitolRecords' => $recentVitolRecords,

                'totalOperationalItems' => $totalOperationalItems,
            ];
        });

        return view('operational.dashboard', array_merge($data, [
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]));
    }

    public function tv(Request $request)
    {
        $selectedMonth = (int) ($request->input('month') ?: now()->month);
        $selectedYear = (int) ($request->input('year') ?: now()->year);

        /*
        |--------------------------------------------------------------------------
        | Cache Key V2
        |--------------------------------------------------------------------------
        | Penting agar TV tidak mengambil cache lama yang belum punya:
        | crudeDailyVacuumTruckValues dan crudeDailyRoadTankValues.
        |--------------------------------------------------------------------------
        */
        $cacheKey = 'operational_tv_v2_' . $selectedYear . '_' . $selectedMonth;

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($selectedMonth, $selectedYear) {
            /*
            |--------------------------------------------------------------------------
            | GAS DAILY
            |--------------------------------------------------------------------------
            */
            $gasDailyChartRaw = FlowGasDailyRecord::query()
                ->where('type', 'gas')
                ->select([
                    'record_date',
                    DB::raw('SUM(COALESCE(mscf, 0)) as total_mscf'),
                ])
                ->whereYear('record_date', $selectedYear)
                ->whereMonth('record_date', $selectedMonth)
                ->groupBy('record_date')
                ->orderBy('record_date')
                ->get();

            $gasDailyChartLabels = $gasDailyChartRaw->map(function ($item) {
                return Carbon::parse($item->record_date)->format('d M');
            })->values();

            $gasDailyChartValues = $gasDailyChartRaw->map(function ($item) {
                return round((float) $item->total_mscf, 4);
            })->values();

            $gasTotalMscf = (float) FlowGasDailyRecord::query()
                ->where('type', 'gas')
                ->whereYear('record_date', $selectedYear)
                ->whereMonth('record_date', $selectedMonth)
                ->sum('mscf');

            /*
            |--------------------------------------------------------------------------
            | GAS MONTHLY
            |--------------------------------------------------------------------------
            */
            $gasMonthlyDailySubquery = FlowGasDailyRecord::query()
                ->where('type', 'gas')
                ->selectRaw('DATE(record_date) as daily_date')
                ->selectRaw('MONTH(record_date) as month_number')
                ->selectRaw('YEAR(record_date) as year_number')
                ->selectRaw('SUM(COALESCE(mscf, 0)) as daily_total_mscf')
                ->whereYear('record_date', $selectedYear)
                ->groupBy(
                    DB::raw('DATE(record_date)'),
                    DB::raw('MONTH(record_date)'),
                    DB::raw('YEAR(record_date)')
                );

            $gasMonthlyChartRaw = DB::query()
                ->fromSub($gasMonthlyDailySubquery, 'gas_daily_totals')
                ->selectRaw('month_number, AVG(daily_total_mscf) as avg_daily_mscf')
                ->groupBy('month_number')
                ->orderBy('month_number')
                ->get()
                ->keyBy('month_number');

            $gasMonthlyChartLabels = collect(range(1, 12))->map(function ($month) {
                return Carbon::create()->month($month)->translatedFormat('M');
            })->values();

            $gasMonthlyChartValues = collect(range(1, 12))->map(function ($month) use ($gasMonthlyChartRaw) {
                return round((float) optional($gasMonthlyChartRaw->get($month))->avg_daily_mscf, 4);
            })->values();

            /*
            |--------------------------------------------------------------------------
            | CRUDE DAILY - STACKED VACUUM TRUCK & ROAD TANK
            |--------------------------------------------------------------------------
            */
            $crudeLast14DaysRecords = CrudeDailyRecord::query()
                ->orderByDesc('record_date')
                ->orderByDesc('id')
                ->limit(14)
                ->get()
                ->sortBy('record_date')
                ->values();

            $crudeDailyChartLabels = $crudeLast14DaysRecords->map(function ($item) {
                return optional($item->record_date)->format('d M');
            })->values();

            $crudeDailyVacuumTruckValues = $crudeLast14DaysRecords->map(function ($item) {
                return round((float) ($item->vacuum_truck ?? 0), 4);
            })->values();

            $crudeDailyRoadTankValues = $crudeLast14DaysRecords->map(function ($item) {
                return round((float) ($item->road_tank ?? 0), 4);
            })->values();

            $crudeTotalProduction = (float) $crudeLast14DaysRecords->sum(function ($record) {
                return (float) ($record->vacuum_truck ?? 0) + (float) ($record->road_tank ?? 0);
            });

            /*
            |--------------------------------------------------------------------------
            | VITOL MONTHLY
            |--------------------------------------------------------------------------
            */
            $vitolYearlyRecords = VitolRecord::query()
                ->where('year', $selectedYear)
                ->orderBy('month')
                ->orderBy('id')
                ->get()
                ->map(function ($record) {
                    $record->month_label = $this->monthLabel((int) $record->month);
                    return $record;
                });

            $vitolMonthlyChartLabels = $vitolYearlyRecords->pluck('month_label')->values();

            $vitolMonthlyChartValues = $vitolYearlyRecords->map(function ($item) {
                return round((float) $item->quantity, 4);
            })->values();

            $vitolTotalQuantity = (float) $vitolYearlyRecords->sum('quantity');

            /*
            |--------------------------------------------------------------------------
            | GAS YEARLY
            |--------------------------------------------------------------------------
            */
            $gasYearlyChartRaw = FlowGasDailyRecord::query()
                ->where('type', 'gas')
                ->selectRaw('YEAR(record_date) as year_number, SUM(COALESCE(mscf, 0)) as total_mscf')
                ->groupBy(DB::raw('YEAR(record_date)'))
                ->orderBy(DB::raw('YEAR(record_date)'))
                ->get();

            $gasYearlyChartLabels = $gasYearlyChartRaw
                ->pluck('year_number')
                ->map(fn ($year) => (string) $year)
                ->values();

            $gasYearlyChartValues = $gasYearlyChartRaw
                ->pluck('total_mscf')
                ->map(fn ($value) => round((float) $value, 4))
                ->values();

            $monthLabel = Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y');

            /*
            |--------------------------------------------------------------------------
            | BROADCAST
            |--------------------------------------------------------------------------
            */
            $broadcastItems = BroadcastMessage::query()
                ->visible()
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get(['label', 'message', 'is_active'])
                ->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'message' => $item->message,
                        'enabled' => (bool) $item->is_active,
                    ];
                })
                ->values();

            return [
                'monthLabel' => $monthLabel,

                'gasTotalMscf' => $gasTotalMscf,
                'gasDailyChartLabels' => $gasDailyChartLabels,
                'gasDailyChartValues' => $gasDailyChartValues,
                'gasMonthlyChartLabels' => $gasMonthlyChartLabels,
                'gasMonthlyChartValues' => $gasMonthlyChartValues,
                'gasYearlyChartLabels' => $gasYearlyChartLabels,
                'gasYearlyChartValues' => $gasYearlyChartValues,

                'crudeTotalProduction' => $crudeTotalProduction,
                'crudeDailyChartLabels' => $crudeDailyChartLabels,
                'crudeDailyVacuumTruckValues' => $crudeDailyVacuumTruckValues,
                'crudeDailyRoadTankValues' => $crudeDailyRoadTankValues,

                'vitolTotalQuantity' => $vitolTotalQuantity,
                'vitolMonthlyChartLabels' => $vitolMonthlyChartLabels,
                'vitolMonthlyChartValues' => $vitolMonthlyChartValues,

                'broadcastItems' => $broadcastItems,
            ];
        });

        return view('operational.tv', array_merge($data, [
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]));
    }

    public function publicTv(Request $request, string $token)
    {
        $displayToken = OperationalDisplayToken::query()
            ->where('token', $token)
            ->first();

        if (! $displayToken || ! $displayToken->isValid()) {
            abort(403, 'Token display tidak valid, tidak aktif, atau sudah kedaluwarsa.');
        }

        $displayToken->forceFill([
            'last_accessed_at' => now(),
        ])->save();

        return $this->tv($request);
    }

    private function monthLabel(int $month): string
    {
        return match ($month) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => '-',
        };
    }
}