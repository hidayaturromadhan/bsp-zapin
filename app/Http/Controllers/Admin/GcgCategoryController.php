<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GcgCategory;
use App\Models\GcgCategoryTranslation;
use App\Models\GcgDocument;
use App\Models\GcgDocumentTranslation;
use App\Services\NewsAutoTranslator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GcgCategoryController extends Controller
{
    public function __construct(protected NewsAutoTranslator $translator) {}

    // ── INDEX ──────────────────────────────────────────────────────────────
    public function index()
    {
        $categories = GcgCategory::with(['translations', 'documents'])
            ->orderBy('id')
            ->paginate(15);

        return view('admin.gcg.index', compact('categories'));
    }

    // ── CREATE ─────────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.gcg.create');
    }

    // ── STORE ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $category = GcgCategory::create([
                'is_active' => $request->boolean('is_active', true),
            ]);

            $nameId = $request->input('name');
            $descId = $request->input('description', '');
            $slugId = $this->uniqueSlug(Str::slug($nameId), null);

            GcgCategoryTranslation::create([
                'gcg_category_id' => $category->id,
                'locale'          => 'id',
                'name'            => $nameId,
                'slug'            => $slugId,
                'description'     => $descId,
            ]);

            // Auto-translate ke EN via DeepL
            $nameEn = $this->translator->translateText($nameId);
            $descEn = $this->translator->translateText($descId);
            $slugEn = $this->uniqueSlug(Str::slug($nameEn), null);

            GcgCategoryTranslation::create([
                'gcg_category_id' => $category->id,
                'locale'          => 'en',
                'name'            => $nameEn,
                'slug'            => $slugEn,
                'description'     => $descEn,
            ]);
        });

        return redirect()->route('admin.gcg.index')
            ->with('success', 'Kategori GCG berhasil ditambahkan.');
    }

    // ── EDIT ───────────────────────────────────────────────────────────────
    public function edit(GcgCategory $gcg)
    {
        $gcg->load(['translations', 'documents.translations']);
        $translationId = $gcg->translations->firstWhere('locale', 'id');

        return view('admin.gcg.edit', compact('gcg', 'translationId'));
    }

    // ── UPDATE ─────────────────────────────────────────────────────────────
    public function update(Request $request, GcgCategory $gcg)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $gcg) {
            $gcg->update([
                'is_active' => $request->boolean('is_active', true),
            ]);

            $nameId  = $request->input('name');
            $descId  = $request->input('description', '');

            // Ambil ID row translation yang ada agar row itu sendiri dikecualikan
            // dari pengecekan slug (supaya tidak dianggap duplikat dengan dirinya sendiri)
            $transId = $gcg->translations()->where('locale', 'id')->first();
            $slugId  = $this->uniqueSlug(Str::slug($nameId), $transId?->id);

            $gcg->translations()->updateOrCreate(
                ['locale' => 'id'],
                ['name' => $nameId, 'slug' => $slugId, 'description' => $descId]
            );

            // Re-translate EN otomatis
            $nameEn  = $this->translator->translateText($nameId);
            $descEn  = $this->translator->translateText($descId);

            $transEn = $gcg->translations()->where('locale', 'en')->first();
            $slugEn  = $this->uniqueSlug(Str::slug($nameEn), $transEn?->id);

            $gcg->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['name' => $nameEn, 'slug' => $slugEn, 'description' => $descEn]
            );
        });

        return redirect()->route('admin.gcg.edit', $gcg)
            ->with('success', 'Kategori GCG berhasil diperbarui.');
    }

    // ── DESTROY ────────────────────────────────────────────────────────────
    public function destroy(GcgCategory $gcg)
    {
        foreach ($gcg->documents as $doc) {
            $fullPath = public_path('documents/gcg/' . $doc->file_path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $gcg->delete();

        return redirect()->route('admin.gcg.index')
            ->with('success', 'Kategori GCG berhasil dihapus.');
    }

    // ── STORE DOKUMEN ──────────────────────────────────────────────────────
    public function storeDocument(Request $request, GcgCategory $gcg)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'file'      => 'required|file|max:20480',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $gcg) {
            $file         = $request->file('file');
            $extension    = strtolower($file->getClientOriginalExtension());
            $fileName     = Str::uuid() . '.' . $extension;
            $originalName = $file->getClientOriginalName(); // ambil sebelum move
            $fileSize     = $file->getSize();               // ambil sebelum move

            $destDir = public_path('documents/gcg');
            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $fileName);

            $document = GcgDocument::create([
                'gcg_category_id' => $gcg->id,
                'file_path'       => $fileName,
                'file_name'       => $originalName,
                'file_type'       => $extension,
                'file_size'       => $fileSize,
                'is_active'       => $request->boolean('is_active', true),
            ]);

            $titleId = $request->input('title');
            $titleEn = $this->translator->translateText($titleId);

            GcgDocumentTranslation::create([
                'gcg_document_id' => $document->id,
                'locale'          => 'id',
                'title'           => $titleId,
            ]);

            GcgDocumentTranslation::create([
                'gcg_document_id' => $document->id,
                'locale'          => 'en',
                'title'           => $titleEn,
            ]);
        });

        return redirect()->route('admin.gcg.edit', $gcg)
            ->with('success', 'Dokumen berhasil diupload.');
    }

    // ── UPDATE DOKUMEN ─────────────────────────────────────────────────────
    public function updateDocument(Request $request, GcgCategory $gcg, GcgDocument $document)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $document) {
            $document->update([
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->hasFile('file')) {
                $request->validate(['file' => 'file|max:20480']);

                $oldPath = public_path('documents/gcg/' . $document->file_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }

                $file         = $request->file('file');
                $extension    = strtolower($file->getClientOriginalExtension());
                $fileName     = Str::uuid() . '.' . $extension;
                $originalName = $file->getClientOriginalName(); // ambil sebelum move
                $fileSize     = $file->getSize();               // ambil sebelum move

                $destDir = public_path('documents/gcg');
                if (! is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                $file->move($destDir, $fileName);

                $document->update([
                    'file_path' => $fileName,
                    'file_name' => $originalName,
                    'file_type' => $extension,
                    'file_size' => $fileSize,
                ]);
            }

            $titleId = $request->input('title');
            $titleEn = $this->translator->translateText($titleId);

            $document->translations()->updateOrCreate(
                ['locale' => 'id'],
                ['title' => $titleId]
            );
            $document->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['title' => $titleEn]
            );
        });

        return redirect()->route('admin.gcg.edit', $gcg)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    // ── DESTROY DOKUMEN ────────────────────────────────────────────────────
    public function destroyDocument(GcgCategory $gcg, GcgDocument $document)
    {
        $fullPath = public_path('documents/gcg/' . $document->file_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $document->delete();

        return redirect()->route('admin.gcg.edit', $gcg)
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    // ── HELPER: UNIQUE SLUG ────────────────────────────────────────────────
    // $ignoreTranslationId = primary key (id) dari row gcg_category_translations
    // yang sedang diupdate — row ini dikecualikan dari pengecekan agar tidak
    // dianggap duplikat dengan dirinya sendiri.
    private function uniqueSlug(string $slug, ?int $ignoreTranslationId): string
    {
        $original = $slug;
        $i = 1;

        while (true) {
            $query = GcgCategoryTranslation::where('slug', $slug);

            if ($ignoreTranslationId) {
                $query->where('id', '!=', $ignoreTranslationId);
            }

            if (! $query->exists()) break;

            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}