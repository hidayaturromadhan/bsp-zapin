<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestorHighlightItem;
use App\Services\NewsAutoTranslator;
use Illuminate\Http\Request;

class InvestorHighlightItemController extends Controller
{
    public function __construct(protected NewsAutoTranslator $translator) {}

    public function index()
    {
        $items = InvestorHighlightItem::orderBy('sort_order')->orderBy('id')->paginate(20);

        return view('admin.investor-highlight-items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.investor-highlight-items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label_id'    => 'required|string|max:255',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        InvestorHighlightItem::create([
            'label_id'    => $request->label_id,
            'label_en'    => $this->translator->translateText($request->label_id),
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.investor-highlight-items.index')
            ->with('success', 'Highlight investor berhasil ditambahkan.');
    }

    public function edit(InvestorHighlightItem $investorHighlightItem)
    {
        return view('admin.investor-highlight-items.edit', [
            'item' => $investorHighlightItem,
        ]);
    }

    public function update(Request $request, InvestorHighlightItem $investorHighlightItem)
    {
        $request->validate([
            'label_id'    => 'required|string|max:255',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $investorHighlightItem->update([
            'label_id'    => $request->label_id,
            'label_en'    => $this->translator->translateText($request->label_id),
            'sort_order'  => (int) $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.investor-highlight-items.index')
            ->with('success', 'Highlight investor berhasil diperbarui.');
    }

    public function destroy(InvestorHighlightItem $investorHighlightItem)
    {
        $investorHighlightItem->delete();

        return redirect()
            ->route('admin.investor-highlight-items.index')
            ->with('success', 'Highlight investor berhasil dihapus.');
    }
}