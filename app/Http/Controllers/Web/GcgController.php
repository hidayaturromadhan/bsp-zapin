<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GcgCategory;
use App\Models\GcgCategoryTranslation;
use App\Models\GcgDocument;

class GcgController extends Controller
{
    // ── INDEX ──────────────────────────────────────────────────────────────
    public function index(string $locale)
    {
        $categories = GcgCategory::with(['translations', 'activeDocuments'])
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        // ❌ SUDAH DIHAPUS: redirect ke kategori pertama

        return view('web.gcg.index', compact('categories', 'locale'));
    }

    // ── SHOW ───────────────────────────────────────────────────────────────
    public function show(string $locale, string $slug)
    {
        $translation = GcgCategoryTranslation::where('slug', $slug)
            ->where('locale', $locale)
            ->first();

        // fallback jika slug tidak ditemukan di locale
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

        return view('web.gcg.show', compact(
            'category',
            'allCategories',
            'locale',
            'translation'
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