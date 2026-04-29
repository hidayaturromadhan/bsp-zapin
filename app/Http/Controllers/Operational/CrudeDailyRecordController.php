<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\CrudeDailyRecord;
use Illuminate\Http\Request;

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

        if (!empty($filters['date'])) {
            $query->whereDate('record_date', $filters['date']);
        }

        if (!empty($filters['month'])) {
            $query->whereMonth('record_date', (int) $filters['month']);
        }

        if (!empty($filters['year'])) {
            $query->whereYear('record_date', (int) $filters['year']);
        }

        if (!empty($filters['search'])) {
            $query->where('notes', 'like', '%' . trim((string) $filters['search']) . '%');
        }

        $records = $query
            ->orderByDesc('record_date')
            ->paginate(20)
            ->withQueryString();

        $summaryQuery = CrudeDailyRecord::query();

        if (!empty($filters['date'])) {
            $summaryQuery->whereDate('record_date', $filters['date']);
        }

        if (!empty($filters['month'])) {
            $summaryQuery->whereMonth('record_date', (int) $filters['month']);
        }

        if (!empty($filters['year'])) {
            $summaryQuery->whereYear('record_date', (int) $filters['year']);
        }

        $summary = [
            'count' => (clone $summaryQuery)->count(),
            'total_production' => (float) (clone $summaryQuery)->sum('production'),
        ];

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
            'production' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

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
            'production' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

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

    private function monthOptions(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }
}
