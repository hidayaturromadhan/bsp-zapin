<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\CrudeDailyRecord;
use App\Models\FlowGasDailyRecord;
use App\Models\VitolRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = (int) ($request->input('month') ?: now()->month);
        $selectedYear = (int) ($request->input('year') ?: now()->year);

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

        $gasTotalMmbtu = (float) FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->whereYear('record_date', $selectedYear)
            ->whereMonth('record_date', $selectedMonth)
            ->sum('mmbtu');

        $gasTotalFix = (float) FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->whereYear('record_date', $selectedYear)
            ->whereMonth('record_date', $selectedMonth)
            ->sum('fix');

        $gasTotalRecords = (int) FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->whereYear('record_date', $selectedYear)
            ->whereMonth('record_date', $selectedMonth)
            ->count();

        $gasCategorySummary = FlowGasDailyRecord::query()
            ->where('type', 'gas')
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

        $gasMonthlyChartRaw = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('MONTH(record_date) as month_number, SUM(COALESCE(mscf, 0)) as total_mscf')
            ->whereYear('record_date', $selectedYear)
            ->groupBy(DB::raw('MONTH(record_date)'))
            ->orderBy(DB::raw('MONTH(record_date)'))
            ->get()
            ->keyBy('month_number');

        $gasMonthlyChartLabels = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->translatedFormat('M');
        })->values();

        $gasMonthlyChartValues = collect(range(1, 12))->map(function ($month) use ($gasMonthlyChartRaw) {
            return round((float) optional($gasMonthlyChartRaw->get($month))->total_mscf, 4);
        })->values();

        $gasYearlyChartRaw = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year_number, SUM(COALESCE(mscf, 0)) as total_mscf')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderBy(DB::raw('YEAR(record_date)'))
            ->get();

        $gasYearlyChartLabels = $gasYearlyChartRaw->pluck('year_number')->map(fn ($year) => (string) $year)->values();
        $gasYearlyChartValues = $gasYearlyChartRaw->pluck('total_mscf')->map(fn ($value) => round((float) $value, 4))->values();

        $crudeMonthlyRecords = CrudeDailyRecord::query()
            ->whereYear('record_date', $selectedYear)
            ->whereMonth('record_date', $selectedMonth)
            ->orderBy('record_date')
            ->get();

        $crudeTotalRecords = $crudeMonthlyRecords->count();
        $crudeTotalProduction = (float) $crudeMonthlyRecords->sum('production');

        $crudeDailyChartLabels = $crudeMonthlyRecords->map(function ($item) {
            return optional($item->record_date)->format('d M');
        })->values();

        $crudeDailyChartValues = $crudeMonthlyRecords->map(function ($item) {
            return round((float) $item->production, 4);
        })->values();

        $recentCrudeRecords = CrudeDailyRecord::query()
            ->latest('record_date')
            ->latest('id')
            ->limit(8)
            ->get();

        $vitolYearlyRecords = VitolRecord::query()
            ->where('year', $selectedYear)
            ->orderByRaw("FIELD(month, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
            ->orderBy('id')
            ->get();

        $vitolTotalRecords = $vitolYearlyRecords->count();
        $vitolTotalQuantity = (float) $vitolYearlyRecords->sum('quantity');
        $vitolTotalCommission = (float) $vitolYearlyRecords->sum('commission');

        $vitolMonthlyChartLabels = $vitolYearlyRecords->pluck('month')->values();
        $vitolMonthlyChartValues = $vitolYearlyRecords->map(function ($item) {
            return round((float) $item->quantity, 4);
        })->values();

        $recentVitolRecords = VitolRecord::query()
            ->orderByDesc('year')
            ->orderByRaw("FIELD(month, 'Desember','November','Oktober','September','Agustus','Juli','Juni','Mei','April','Maret','Februari','Januari')")
            ->limit(8)
            ->get();

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

        return view('operational.dashboard', [
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
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
            'crudeDailyChartLabels' => $crudeDailyChartLabels,
            'crudeDailyChartValues' => $crudeDailyChartValues,
            'recentCrudeRecords' => $recentCrudeRecords,

            'vitolTotalRecords' => $vitolTotalRecords,
            'vitolTotalQuantity' => $vitolTotalQuantity,
            'vitolTotalCommission' => $vitolTotalCommission,
            'vitolMonthlyChartLabels' => $vitolMonthlyChartLabels,
            'vitolMonthlyChartValues' => $vitolMonthlyChartValues,
            'recentVitolRecords' => $recentVitolRecords,

            'totalOperationalItems' => $totalOperationalItems,
        ]);
    }

    public function tv(Request $request)
    {
        $selectedMonth = (int) ($request->input('month') ?: now()->month);
        $selectedYear = (int) ($request->input('year') ?: now()->year);

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

        $crudeMonthlyRecords = CrudeDailyRecord::query()
            ->whereYear('record_date', $selectedYear)
            ->whereMonth('record_date', $selectedMonth)
            ->orderBy('record_date')
            ->get();

        $crudeDailyChartLabels = $crudeMonthlyRecords->map(function ($item) {
            return optional($item->record_date)->format('d M');
        })->values();

        $crudeDailyChartValues = $crudeMonthlyRecords->map(function ($item) {
            return round((float) $item->production, 4);
        })->values();

        $crudeTotalProduction = (float) $crudeMonthlyRecords->sum('production');

        $vitolYearlyRecords = VitolRecord::query()
            ->where('year', $selectedYear)
            ->orderByRaw("FIELD(month, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
            ->orderBy('id')
            ->get();

        $vitolMonthlyChartLabels = $vitolYearlyRecords->pluck('month')->values();
        $vitolMonthlyChartValues = $vitolYearlyRecords->map(function ($item) {
            return round((float) $item->quantity, 4);
        })->values();

        $vitolTotalQuantity = (float) $vitolYearlyRecords->sum('quantity');
        $vitolTotalCommission = (float) $vitolYearlyRecords->sum('commission');

        $gasYearlyChartRaw = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year_number, SUM(COALESCE(mscf, 0)) as total_mscf')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderBy(DB::raw('YEAR(record_date)'))
            ->get();

        $gasYearlyChartLabels = $gasYearlyChartRaw->pluck('year_number')->map(fn ($year) => (string) $year)->values();
        $gasYearlyChartValues = $gasYearlyChartRaw->pluck('total_mscf')->map(fn ($value) => round((float) $value, 4))->values();

        $monthLabel = Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y');

        return view('operational.tv', [
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'monthLabel' => $monthLabel,
            'gasTotalMscf' => $gasTotalMscf,
            'crudeTotalProduction' => $crudeTotalProduction,
            'vitolTotalQuantity' => $vitolTotalQuantity,
            'vitolTotalCommission' => $vitolTotalCommission,
            'gasDailyChartLabels' => $gasDailyChartLabels,
            'gasDailyChartValues' => $gasDailyChartValues,
            'crudeDailyChartLabels' => $crudeDailyChartLabels,
            'crudeDailyChartValues' => $crudeDailyChartValues,
            'vitolMonthlyChartLabels' => $vitolMonthlyChartLabels,
            'vitolMonthlyChartValues' => $vitolMonthlyChartValues,
            'gasYearlyChartLabels' => $gasYearlyChartLabels,
            'gasYearlyChartValues' => $gasYearlyChartValues,
        ]);
    }
}