<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GcgHighlightItem;
use App\Services\NewsAutoTranslator;
use Illuminate\Http\Request;

class GcgHighlightItemController extends Controller
{
    public function __construct(protected NewsAutoTranslator $translator) {}

    public function index()
    {
        $items = GcgHighlightItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.gcg_highlight_items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gcg_highlight_items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label_id'   => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $labelId = $request->input('label_id');
        $labelEn = $this->translator->translateText($labelId);

        GcgHighlightItem::create([
            'label_id'   => $labelId,
            'label_en'   => $labelEn,
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.gcg-highlight-items.index')
            ->with('success', 'Highlight GCG berhasil ditambahkan.');
    }

    public function edit(GcgHighlightItem $gcgHighlightItem)
    {
        return view('admin.gcg_highlight_items.edit', compact('gcgHighlightItem'));
    }

    public function update(Request $request, GcgHighlightItem $gcgHighlightItem)
    {
        $request->validate([
            'label_id'   => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $labelId = $request->input('label_id');
        $labelEn = $this->translator->translateText($labelId);

        $gcgHighlightItem->update([
            'label_id'   => $labelId,
            'label_en'   => $labelEn,
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.gcg-highlight-items.index')
            ->with('success', 'Highlight GCG berhasil diperbarui.');
    }

    public function destroy(GcgHighlightItem $gcgHighlightItem)
    {
        $gcgHighlightItem->delete();

        return redirect()
            ->route('admin.gcg-highlight-items.index')
            ->with('success', 'Highlight GCG berhasil dihapus.');
    }
}