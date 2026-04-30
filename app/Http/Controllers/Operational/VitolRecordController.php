<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\VitolRecord;
use Illuminate\Http\Request;

class VitolRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = VitolRecord::query();

        if ($request->filled('year')) {
            $query->where('year', (int) $request->year);
        }

        if ($request->filled('month')) {
            $query->where('month', (int) $request->month);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('unit', 'like', '%' . $search . '%')
                    ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        $records = $query
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(10)
            ->through(function ($record) {
                $record->month_label = $this->monthOptions()[(int) $record->month] ?? '-';
                return $record;
            })
            ->withQueryString();

        $summaryQuery = clone $query;

        $summary = [
            'count' => (clone $summaryQuery)->count(),
            'total_quantity' => (clone $summaryQuery)->sum('quantity'),
        ];

        $yearOptions = VitolRecord::query()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $monthOptions = $this->monthOptions();
        $filters = $request->only(['year', 'month', 'search']);

        return view('operational.vitol.index', [
            'records' => $records,
            'summary' => $summary,
            'yearOptions' => $yearOptions,
            'monthOptions' => $monthOptions,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return view('operational.vitol.create', [
            'defaultYear' => now()->year,
            'monthOptions' => $this->monthOptions(),
            'unitOptions' => $this->unitOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'in:' . implode(',', array_keys($this->unitOptions()))],
            'notes' => ['nullable', 'string'],
        ]);

        VitolRecord::create([
            'year' => (int) $data['year'],
            'month' => (int) $data['month'],
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'fee_rate' => 0,
            'commission' => 0,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('operational.vitol.index')
            ->with('success', 'Data VITOL berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $record = VitolRecord::findOrFail($id);

        return view('operational.vitol.edit', [
            'record' => $record,
            'monthOptions' => $this->monthOptions(),
            'unitOptions' => $this->unitOptions(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $record = VitolRecord::findOrFail($id);

        $data = $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'in:' . implode(',', array_keys($this->unitOptions()))],
            'notes' => ['nullable', 'string'],
        ]);

        $record->update([
            'year' => (int) $data['year'],
            'month' => (int) $data['month'],
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'fee_rate' => 0,
            'commission' => 0,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('operational.vitol.index')
            ->with('success', 'Data VITOL berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $record = VitolRecord::findOrFail($id);
        $record->delete();

        return redirect()
            ->route('operational.vitol.index')
            ->with('success', 'Data VITOL berhasil dihapus.');
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

    private function unitOptions(): array
    {
        return [
            'BBL' => 'BBL',
            'MT' => 'MT',
            'KL' => 'KL',
        ];
    }
}