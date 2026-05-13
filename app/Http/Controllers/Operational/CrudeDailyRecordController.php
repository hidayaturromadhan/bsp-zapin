<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\CrudeDailyRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrudeDailyRecordController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'date' => $request->input('date'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'search' => $request->input('search'),
        ];

        $query = CrudeDailyRecord::query();

        $this->applyFilters($query, $filters, true);

        $records = $query
            ->orderByDesc('record_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $summaryQuery = CrudeDailyRecord::query();
        $this->applyFilters($summaryQuery, $filters, false);

        $summary = [
            'count' => (clone $summaryQuery)->count(),
            'total_vacuum_truck' => (float) (clone $summaryQuery)->sum('vacuum_truck'),
            'total_road_tank' => (float) (clone $summaryQuery)->sum('road_tank'),
        ];

        $chartQuery = CrudeDailyRecord::query();
        $this->applyFilters($chartQuery, $filters, false);

        $chartRaw = $chartQuery
            ->selectRaw('DATE(record_date) as chart_date')
            ->selectRaw('SUM(COALESCE(vacuum_truck, 0)) as total_vacuum_truck')
            ->selectRaw('SUM(COALESCE(road_tank, 0)) as total_road_tank')
            ->groupBy(DB::raw('DATE(record_date)'))
            ->orderBy(DB::raw('DATE(record_date)'))
            ->get();

        $chartLabels = $chartRaw
            ->map(fn ($item) => date('d M', strtotime($item->chart_date)))
            ->values();

        $chartVacuumTruckValues = $chartRaw
            ->map(fn ($item) => round((float) $item->total_vacuum_truck, 4))
            ->values();

        $chartRoadTankValues = $chartRaw
            ->map(fn ($item) => round((float) $item->total_road_tank, 4))
            ->values();

        $yearOptions = CrudeDailyRecord::query()
            ->selectRaw('YEAR(record_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values();

        if ($yearOptions->isEmpty()) {
            $yearOptions = collect([now()->year]);
        }

        return view('operational.crude.index', [
            'records' => $records,
            'filters' => $filters,
            'summary' => $summary,
            'yearOptions' => $yearOptions,
            'monthOptions' => $this->monthOptions(),
            'chartLabels' => $chartLabels,
            'chartVacuumTruckValues' => $chartVacuumTruckValues,
            'chartRoadTankValues' => $chartRoadTankValues,
        ]);
    }

    public function create(Request $request)
    {
        return view('operational.crude.create', [
            'defaultDate' => $request->input('date', now()->toDateString()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'record_date' => ['required', 'date', 'unique:crude_daily_records,record_date'],
            'vacuum_truck' => ['required', 'numeric', 'min:0'],
            'road_tank' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Field production tetap diisi untuk kompatibilitas data lama/dashboard lain.
        | Namun di tampilan grafik crude yang baru, yang ditampilkan hanya:
        | - Vacuum Truck
        | - Road Tank
        |--------------------------------------------------------------------------
        */
        $validated['production'] = round(
            (float) $validated['vacuum_truck'] + (float) $validated['road_tank'],
            4
        );

        CrudeDailyRecord::create($validated);

        return redirect()
            ->route('operational.crude.index', [
                'month' => date('m', strtotime($validated['record_date'])),
                'year' => date('Y', strtotime($validated['record_date'])),
            ])
            ->with('success', 'Data produksi crude berhasil ditambahkan.');
    }

    public function edit(CrudeDailyRecord $crude)
    {
        return view('operational.crude.edit', [
            'record' => $crude,
        ]);
    }

    public function update(Request $request, CrudeDailyRecord $crude)
    {
        $validated = $request->validate([
            'record_date' => ['required', 'date', 'unique:crude_daily_records,record_date,' . $crude->id],
            'vacuum_truck' => ['required', 'numeric', 'min:0'],
            'road_tank' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['production'] = round(
            (float) $validated['vacuum_truck'] + (float) $validated['road_tank'],
            4
        );

        $crude->update($validated);

        return redirect()
            ->route('operational.crude.index', [
                'month' => date('m', strtotime($validated['record_date'])),
                'year' => date('Y', strtotime($validated['record_date'])),
            ])
            ->with('success', 'Data produksi crude berhasil diperbarui.');
    }

    public function destroy(CrudeDailyRecord $crude)
    {
        $month = optional($crude->record_date)->format('m');
        $year = optional($crude->record_date)->format('Y');

        $crude->delete();

        return redirect()
            ->route('operational.crude.index', [
                'month' => $month,
                'year' => $year,
            ])
            ->with('success', 'Data produksi crude berhasil dihapus.');
    }

    private function applyFilters($query, array $filters, bool $includeSearch = true): void
    {
        if (! empty($filters['date'])) {
            $query->whereDate('record_date', $filters['date']);
        }

        if (! empty($filters['month'])) {
            $query->whereMonth('record_date', (int) $filters['month']);
        }

        if (! empty($filters['year'])) {
            $query->whereYear('record_date', (int) $filters['year']);
        }

        if ($includeSearch && ! empty($filters['search'])) {
            $query->where('notes', 'like', '%' . trim((string) $filters['search']) . '%');
        }
    }

    private function monthOptions(): array
    {
        return [
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
        ];
    }
}