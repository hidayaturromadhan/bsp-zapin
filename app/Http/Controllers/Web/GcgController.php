<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GcgCategory;
use App\Models\GcgCategoryTranslation;
use App\Models\GcgDocument;
use App\Models\GcgHighlightItem;

class GcgController extends Controller
{
    // ── INDEX ──────────────────────────────────────────────────────────────
    public function index(string $locale)
    {
        $highlightItems = GcgHighlightItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $documents = GcgDocument::with([
                'translations',
                'category.translations',
            ])
            ->where('is_active', true)
            ->latest('id')
            ->get();

        return view('web.gcg.index', compact(
            'locale',
            'highlightItems',
            'documents'
        ));
    }

    // ── SHOW ───────────────────────────────────────────────────────────────
    public function show(string $locale, string $slug)
    {
        $translation = GcgCategoryTranslation::where('slug', $slug)
            ->where('locale', $locale)
            ->first();

        if (! $translation) {
            $translation = GcgCategoryTranslation::where('slug', $slug)->first();
        }

        abort_if(! $translation, 404);

        $category = GcgCategory::with([
            'translations',
            'activeDocuments.translations',
        ])
            ->where('is_active', true)
            ->findOrFail($translation->gcg_category_id);

        $allCategories = GcgCategory::with(['translations', 'activeDocuments'])
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        $highlightItems = GcgHighlightItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('web.gcg.show', compact(
            'category',
            'allCategories',
            'locale',
            'translation',
            'highlightItems'
        ));
    }

    // ── DOWNLOAD ───────────────────────────────────────────────────────────
    public function download(GcgDocument $document)
    {
        abort_if(! $document->is_active, 404);

        $fullPath = public_path('documents/gcg/' . $document->file_path);

        abort_if(! file_exists($fullPath), 404);

        return response()->download($fullPath, $document->file_name);
    }
}