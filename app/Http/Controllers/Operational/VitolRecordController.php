<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\VitolRecord;
use Illuminate\Http\Request;

class VitolRecordController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'year' => $request->input('year'),
            'month' => $request->input('month'),
            'search' => $request->input('search'),
        ];

        $query = VitolRecord::query();

        if (!empty($filters['year'])) {
            $query->where('year', (int) $filters['year']);
        }

        if (!empty($filters['month'])) {
            $query->where('month', (int) $filters['month']);
        }

        if (!empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('unit', 'like', '%' . $search . '%')
                    ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        $records = $query
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(20)
            ->withQueryString();

        $summaryQuery = VitolRecord::query();

        if (!empty($filters['year'])) {
            $summaryQuery->where('year', (int) $filters['year']);
        }

        if (!empty($filters['month'])) {
            $summaryQuery->where('month', (int) $filters['month']);
        }

        $summary = [
            'count' => (clone $summaryQuery)->count(),
            'total_quantity' => (float) (clone $summaryQuery)->sum('quantity'),
            'total_fee_rate' => (float) (clone $summaryQuery)->sum('fee_rate'),
            'total_commission' => (float) (clone $summaryQuery)->sum('commission'),
        ];

        $yearOptions = VitolRecord::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values();

        if ($yearOptions->isEmpty()) {
            $yearOptions = collect([now()->year]);
        }

        return view('operational.vitol.index', [
            'records' => $records,
            'filters' => $filters,
            'summary' => $summary,
            'yearOptions' => $yearOptions,
            'monthOptions' => $this->monthOptions(),
            'unitOptions' => $this->unitOptions(),
        ]);
    }

    public function create()
    {
        return view('operational.vitol.create', [
            'monthOptions' => $this->monthOptions(),
            'unitOptions' => $this->unitOptions(),
            'defaultYear' => now()->year,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'quantity' => ['required', 'numeric'],
            'unit' => ['required', 'string', 'max:50'],
            'fee_rate' => ['nullable', 'numeric'],
            'commission' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = VitolRecord::query()
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['month' => 'Data VITOL untuk bulan dan tahun tersebut sudah ada.'])
                ->withInput();
        }

        VitolRecord::create($validated);

        return redirect()
            ->route('operational.vitol.index', [
                'year' => $validated['year'],
                'month' => $validated['month'],
            ])
            ->with('success', 'Data VITOL berhasil ditambahkan.');
    }

    public function edit(VitolRecord $vitol)
    {
        return view('operational.vitol.edit', [
            'record' => $vitol,
            'monthOptions' => $this->monthOptions(),
            'unitOptions' => $this->unitOptions(),
        ]);
    }

    public function update(Request $request, VitolRecord $vitol)
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'quantity' => ['required', 'numeric'],
            'unit' => ['required', 'string', 'max:50'],
            'fee_rate' => ['nullable', 'numeric'],
            'commission' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = VitolRecord::query()
            ->where('id', '!=', $vitol->id)
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['month' => 'Data VITOL untuk bulan dan tahun tersebut sudah ada.'])
                ->withInput();
        }

        $vitol->update($validated);

        return redirect()
            ->route('operational.vitol.index', [
                'year' => $validated['year'],
                'month' => $validated['month'],
            ])
            ->with('success', 'Data VITOL berhasil diperbarui.');
    }

    public function destroy(VitolRecord $vitol)
    {
        $year = $vitol->year;
        $month = $vitol->month;

        $vitol->delete();

        return redirect()
            ->route('operational.vitol.index', [
                'year' => $year,
                'month' => $month,
            ])
            ->with('success', 'Data VITOL berhasil dihapus.');
    }

    private function monthOptions(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    private function unitOptions(): array
    {
        return [
            'BBL' => 'BBL',
            'MT' => 'MT',
        ];
    }
}
