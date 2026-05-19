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
        $items = InvestorHighlightItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.investor-highlight-items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.investor-highlight-items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label_id'   => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $labelId = $request->input('label_id');

        InvestorHighlightItem::create([
            'label_id'   => $labelId,
            'label_en'   => $this->translator->translateText($labelId),
            'sort_order' => (int) $request->input('sort_order', 0),

            // FIX:
            // Jika checkbox tidak dicentang, field is_active tidak dikirim browser.
            // boolean('is_active') akan menghasilkan false.
            'is_active'  => $request->boolean('is_active'),
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
            'label_id'   => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $labelId = $request->input('label_id');

        $investorHighlightItem->update([
            'label_id'   => $labelId,
            'label_en'   => $this->translator->translateText($labelId),
            'sort_order' => (int) $request->input('sort_order', 0),

            // FIX:
            // Jika checkbox tidak dicentang, simpan sebagai false.
            'is_active'  => $request->boolean('is_active'),
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