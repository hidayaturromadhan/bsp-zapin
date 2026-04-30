<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use Illuminate\Http\Request;

class BroadcastMessageController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ];

        $query = BroadcastMessage::query();

        if (!empty($filters['search'])) {
            $search = trim((string) $filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        if ($filters['status'] !== null && $filters['status'] !== '') {
            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            } elseif ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $records = $query
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'count' => BroadcastMessage::count(),
            'active_count' => BroadcastMessage::where('is_active', true)->count(),
            'inactive_count' => BroadcastMessage::where('is_active', false)->count(),
        ];

        return view('operational.broadcast.index', [
            'records' => $records,
            'filters' => $filters,
            'summary' => $summary,
        ]);
    }

    public function create()
    {
        return view('operational.broadcast.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        BroadcastMessage::create([
            'label' => $validated['label'] ?? null,
            'message' => $validated['message'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()
            ->route('operational.broadcast.index')
            ->with('success', 'Broadcast berhasil ditambahkan.');
    }

    public function edit(BroadcastMessage $broadcast)
    {
        return view('operational.broadcast.edit', [
            'record' => $broadcast,
        ]);
    }

    public function update(Request $request, BroadcastMessage $broadcast)
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $broadcast->update([
            'label' => $validated['label'] ?? null,
            'message' => $validated['message'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()
            ->route('operational.broadcast.index')
            ->with('success', 'Broadcast berhasil diperbarui.');
    }

    public function destroy(BroadcastMessage $broadcast)
    {
        $broadcast->delete();

        return redirect()
            ->route('operational.broadcast.index')
            ->with('success', 'Broadcast berhasil dihapus.');
    }
}