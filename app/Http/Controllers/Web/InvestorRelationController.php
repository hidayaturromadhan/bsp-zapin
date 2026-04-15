<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InvestorDocument;
use App\Models\InvestorHighlightItem;

class InvestorRelationController extends Controller
{
    public function index(string $locale)
    {
        $highlightItems = InvestorHighlightItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $documents = InvestorDocument::with('translations')
            ->where('is_active', true)
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $metaTitle = $locale === 'id'
            ? 'Hubungan Investor - PT Bumi Siak Pusako Zapin'
            : 'Investor Relations - PT Bumi Siak Pusako Zapin';

        $metaDescription = $locale === 'id'
            ? 'Laporan tahunan dan dokumen hubungan investor PT Bumi Siak Pusako Zapin.'
            : 'Annual reports and investor relations documents of PT Bumi Siak Pusako Zapin.';

        return view('web.investor-relations.index', compact(
            'locale',
            'highlightItems',
            'documents',
            'metaTitle',
            'metaDescription'
        ));
    }

    public function download(InvestorDocument $document)
    {
        abort_if(! $document->is_active, 404);

        $fullPath = public_path('documents/investor-relations/' . $document->file_path);

        abort_if(! file_exists($fullPath), 404);

        return response()->download($fullPath, $document->file_name);
    }
}