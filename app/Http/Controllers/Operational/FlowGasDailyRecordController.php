<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\FlowGasCategory;
use App\Models\FlowGasDailyRecord;
use Illuminate\Http\Request;

class FlowGasDailyRecordController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'date' => $request->input('date'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'search' => $request->input('search'),
        ];

        $categories = FlowGasCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $query = FlowGasDailyRecord::query()
            ->with('category')
            ->where('type', 'gas');

        if (! empty($filters['category_id'])) {
            $query->where('flow_gas_category_id', $filters['category_id']);
        }

        if (! empty($filters['date'])) {
            $query->whereDate('record_date', $filters['date']);
        }

        if (! empty($filters['month'])) {
            $query->whereMonth('record_date', (int) $filters['month']);
        }

        if (! empty($filters['year'])) {
            $query->whereYear('record_date', (int) $filters['year']);
        }

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('code', 'like', '%' . $search . '%');
                    });
            });
        }

        $records = $query
            ->orderByDesc('record_date')
            ->orderBy('flow_gas_category_id')
            ->paginate(20)
            ->withQueryString();

        $summaryQuery = FlowGasDailyRecord::query()->where('type', 'gas');

        if (! empty($filters['category_id'])) {
            $summaryQuery->where('flow_gas_category_id', $filters['category_id']);
        }

        if (! empty($filters['date'])) {
            $summaryQuery->whereDate('record_date', $filters['date']);
        }

        if (! empty($filters['month'])) {
            $summaryQuery->whereMonth('record_date', (int) $filters['month']);
        }

        if (! empty($filters['year'])) {
            $summaryQuery->whereYear('record_date', (int) $filters['year']);
        }

        $summary = [
            'count' => (clone $summaryQuery)->count(),
            'total_mscf' => (float) (clone $summaryQuery)->sum('mscf'),
            'total_mmbtu' => (float) (clone $summaryQuery)->sum('mmbtu'),
            'total_fix' => (float) (clone $summaryQuery)->sum('fix'),
        ];

        $yearOptions = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values();

        if ($yearOptions->isEmpty()) {
            $yearOptions = collect([now()->year]);
        }

        return view('operational.flow-gas.index', [
            'records' => $records,
            'categories' => $categories,
            'filters' => $filters,
            'summary' => $summary,
            'yearOptions' => $yearOptions,
            'monthOptions' => $this->monthOptions(),
        ]);
    }

    public function create(Request $request)
    {
        $categories = FlowGasCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('operational.flow-gas.create', [
            'categories' => $categories,
            'defaultDate' => $request->input('date', now()->toDateString()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flow_gas_category_id' => ['required', 'exists:flow_gas_categories,id'],
            'record_date' => ['required', 'date'],
            'mscf' => ['nullable', 'numeric'],
            'mmbtu' => ['nullable', 'numeric'],
            'fix' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->where('flow_gas_category_id', $validated['flow_gas_category_id'])
            ->whereDate('record_date', $validated['record_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'record_date' => 'Data untuk kategori dan tanggal tersebut sudah ada.',
                ])
                ->withInput();
        }

        $validated['type'] = 'gas';

        FlowGasDailyRecord::create($validated);

        return redirect()
            ->route('operational.flow-gas.index', [
                'month' => date('m', strtotime($validated['record_date'])),
                'year' => date('Y', strtotime($validated['record_date'])),
            ])
            ->with('success', 'Data flow gas harian berhasil ditambahkan.');
    }

    public function edit(FlowGasDailyRecord $flowGas)
    {
        abort_if($flowGas->type !== 'gas', 404);

        $categories = FlowGasCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('operational.flow-gas.edit', [
            'record' => $flowGas->load('category'),
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, FlowGasDailyRecord $flowGas)
    {
        abort_if($flowGas->type !== 'gas', 404);

        $validated = $request->validate([
            'flow_gas_category_id' => ['required', 'exists:flow_gas_categories,id'],
            'record_date' => ['required', 'date'],
            'mscf' => ['nullable', 'numeric'],
            'mmbtu' => ['nullable', 'numeric'],
            'fix' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = FlowGasDailyRecord::query()
            ->where('id', '!=', $flowGas->id)
            ->where('type', 'gas')
            ->where('flow_gas_category_id', $validated['flow_gas_category_id'])
            ->whereDate('record_date', $validated['record_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'record_date' => 'Data untuk kategori dan tanggal tersebut sudah ada.',
                ])
                ->withInput();
        }

        $validated['type'] = 'gas';

        $flowGas->update($validated);

        return redirect()
            ->route('operational.flow-gas.index', [
                'month' => date('m', strtotime($validated['record_date'])),
                'year' => date('Y', strtotime($validated['record_date'])),
            ])
            ->with('success', 'Data flow gas harian berhasil diperbarui.');
    }

    public function destroy(FlowGasDailyRecord $flowGas)
    {
        abort_if($flowGas->type !== 'gas', 404);

        $month = optional($flowGas->record_date)->format('m');
        $year = optional($flowGas->record_date)->format('Y');

        $flowGas->delete();

        return redirect()
            ->route('operational.flow-gas.index', [
                'month' => $month,
                'year' => $year,
            ])
            ->with('success', 'Data flow gas harian berhasil dihapus.');
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
