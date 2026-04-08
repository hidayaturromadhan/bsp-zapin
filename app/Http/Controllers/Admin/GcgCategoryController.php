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
use Spatie\PdfToImage\Pdf;

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

    // ── STORE CATEGORY ─────────────────────────────────────────────────────
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

        return redirect()
            ->route('admin.gcg.index')
            ->with('success', 'Kategori GCG berhasil ditambahkan.');
    }

    // ── EDIT ───────────────────────────────────────────────────────────────
    public function edit(GcgCategory $gcg)
    {
        $gcg->load([
            'translations',
            'documents.translations',
        ]);

        $translationId = $gcg->translations->firstWhere('locale', 'id');

        return view('admin.gcg.edit', compact('gcg', 'translationId'));
    }

    // ── UPDATE CATEGORY ────────────────────────────────────────────────────
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

            $nameId = $request->input('name');
            $descId = $request->input('description', '');

            $transId = $gcg->translations()->where('locale', 'id')->first();
            $slugId  = $this->uniqueSlug(Str::slug($nameId), $transId?->id);

            $gcg->translations()->updateOrCreate(
                ['locale' => 'id'],
                [
                    'name'        => $nameId,
                    'slug'        => $slugId,
                    'description' => $descId,
                ]
            );

            $nameEn = $this->translator->translateText($nameId);
            $descEn = $this->translator->translateText($descId);

            $transEn = $gcg->translations()->where('locale', 'en')->first();
            $slugEn  = $this->uniqueSlug(Str::slug($nameEn), $transEn?->id);

            $gcg->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'name'        => $nameEn,
                    'slug'        => $slugEn,
                    'description' => $descEn,
                ]
            );
        });

        return redirect()
            ->route('admin.gcg.edit', $gcg)
            ->with('success', 'Kategori GCG berhasil diperbarui.');
    }

    // ── DESTROY CATEGORY ───────────────────────────────────────────────────
    public function destroy(GcgCategory $gcg)
    {
        $gcg->load('documents');

        foreach ($gcg->documents as $doc) {
            $pdfPath = public_path('documents/gcg/' . $doc->file_path);
            if ($doc->cover) {
                $coverPath = public_path('images/gcg/' . $doc->cover);
                if (file_exists($coverPath)) {
                    unlink($coverPath);
                }
            }

            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }

        $gcg->delete();

        return redirect()
            ->route('admin.gcg.index')
            ->with('success', 'Kategori GCG berhasil dihapus.');
    }

    // ── STORE DOCUMENT ─────────────────────────────────────────────────────
    public function storeDocument(Request $request, GcgCategory $gcg)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'file'      => 'required|file|max:20480',
            'cover'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $gcg) {
            $file         = $request->file('file');
            $extension    = strtolower($file->getClientOriginalExtension());
            $fileName     = Str::uuid() . '.' . $extension;
            $originalName = $file->getClientOriginalName();
            $fileSize     = $file->getSize();

            $destDir = public_path('documents/gcg');
            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $fileName);

            $pdfPath = public_path('documents/gcg/' . $fileName);

            $coverName = null;

            // Cover manual
            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                $coverExt  = strtolower($coverFile->getClientOriginalExtension());
                $coverName = Str::uuid() . '.' . $coverExt;

                $coverDir = public_path('images/gcg');
                if (! is_dir($coverDir)) {
                    mkdir($coverDir, 0755, true);
                }

                $coverFile->move($coverDir, $coverName);
            } else {
                // Auto generate dari PDF page 1
                try {
                    $coverDir = public_path('images/gcg');
                    if (! is_dir($coverDir)) {
                        mkdir($coverDir, 0755, true);
                    }

                    $coverName = Str::uuid() . '.jpg';
                    $coverPath = public_path('images/gcg/' . $coverName);

                    $pdf = new Pdf($pdfPath);
                    $pdf->setPage(1)
                        ->setOutputFormat('jpg')
                        ->saveImage($coverPath);
                } catch (\Throwable $e) {
                    $coverName = null;
                }
            }

            $document = GcgDocument::create([
                'gcg_category_id' => $gcg->id,
                'file_path'       => $fileName,
                'cover'           => $coverName,
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

        return redirect()
            ->route('admin.gcg.edit', $gcg)
            ->with('success', 'Dokumen berhasil diupload.');
    }

    // ── UPDATE DOCUMENT ────────────────────────────────────────────────────
    public function updateDocument(Request $request, GcgCategory $gcg, GcgDocument $document)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'cover'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $document) {
            $document->update([
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->hasFile('cover')) {
                $oldCoverPath = $document->cover
                    ? public_path('images/gcg/' . $document->cover)
                    : null;

                if ($oldCoverPath && file_exists($oldCoverPath)) {
                    unlink($oldCoverPath);
                }

                $coverFile = $request->file('cover');
                $coverExt  = strtolower($coverFile->getClientOriginalExtension());
                $coverName = Str::uuid() . '.' . $coverExt;

                $coverDir = public_path('images/gcg');
                if (! is_dir($coverDir)) {
                    mkdir($coverDir, 0755, true);
                }

                $coverFile->move($coverDir, $coverName);

                $document->update([
                    'cover' => $coverName,
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

        return redirect()
            ->route('admin.gcg.edit', $gcg)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    // ── DESTROY DOCUMENT ───────────────────────────────────────────────────
    public function destroyDocument(GcgCategory $gcg, GcgDocument $document)
    {
        $pdfPath = public_path('documents/gcg/' . $document->file_path);
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        if ($document->cover) {
            $coverPath = public_path('images/gcg/' . $document->cover);
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        $document->delete();

        return redirect()
            ->route('admin.gcg.edit', $gcg)
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    // ── HELPER UNIQUE SLUG ─────────────────────────────────────────────────
    private function uniqueSlug(string $slug, ?int $ignoreTranslationId): string
    {
        $original = $slug;
        $i = 1;

        while (true) {
            $query = GcgCategoryTranslation::where('slug', $slug);

            if ($ignoreTranslationId) {
                $query->where('id', '!=', $ignoreTranslationId);
            }

            if (! $query->exists()) {
                break;
            }

            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}