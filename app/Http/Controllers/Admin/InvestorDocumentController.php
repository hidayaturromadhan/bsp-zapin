<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestorDocument;
use App\Models\InvestorDocumentTranslation;
use App\Services\NewsAutoTranslator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;

class InvestorDocumentController extends Controller
{
    public function __construct(protected NewsAutoTranslator $translator) {}

    public function index()
    {
        $documents = InvestorDocument::with('translations')
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.investor-relations.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.investor-relations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'summary'    => 'nullable|string',
            'year'       => 'nullable|string|max:10',
            'file'       => 'required|file|mimes:pdf|max:20480',
            'cover'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $file         = $request->file('file');
            $extension    = strtolower($file->getClientOriginalExtension());
            $fileName     = Str::uuid() . '.' . $extension;
            $originalName = $file->getClientOriginalName();
            $fileSize     = $file->getSize();

            $destDir = public_path('documents/investor-relations');

            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            $file->move($destDir, $fileName);

            $pdfPath = public_path('documents/investor-relations/' . $fileName);

            $coverName = null;

            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                $coverExt  = strtolower($coverFile->getClientOriginalExtension());
                $coverName = Str::uuid() . '.' . $coverExt;

                $coverDir = public_path('images/investor-relations');

                if (! is_dir($coverDir)) {
                    mkdir($coverDir, 0755, true);
                }

                $coverFile->move($coverDir, $coverName);
            } else {
                try {
                    $coverDir = public_path('images/investor-relations');

                    if (! is_dir($coverDir)) {
                        mkdir($coverDir, 0755, true);
                    }

                    $coverName = Str::uuid() . '.jpg';
                    $coverPath = public_path('images/investor-relations/' . $coverName);

                    $pdf = new Pdf($pdfPath);
                    $pdf->setPage(1)
                        ->setOutputFormat('jpg')
                        ->saveImage($coverPath);
                } catch (\Throwable $e) {
                    $coverName = null;
                }
            }

            $document = InvestorDocument::create([
                'year'       => $request->input('year'),
                'file_path'  => $fileName,
                'cover'      => $coverName,
                'file_name'  => $originalName,
                'file_type'  => $extension,
                'file_size'  => $fileSize,
                'sort_order' => (int) $request->input('sort_order', 0),

                // FIX:
                // Jika checkbox tidak dicentang, browser tidak mengirim is_active.
                // Maka boolean('is_active') akan menjadi false.
                'is_active'  => $request->boolean('is_active'),
            ]);

            $titleId   = $request->input('title');
            $summaryId = $request->input('summary', '');

            $titleEn   = $this->translator->translateText($titleId);
            $summaryEn = $this->translator->translateText($summaryId);

            InvestorDocumentTranslation::create([
                'investor_document_id' => $document->id,
                'locale'               => 'id',
                'title'                => $titleId,
                'summary'              => $summaryId,
            ]);

            InvestorDocumentTranslation::create([
                'investor_document_id' => $document->id,
                'locale'               => 'en',
                'title'                => $titleEn,
                'summary'              => $summaryEn,
            ]);
        });

        return redirect()
            ->route('admin.investor-relations.index')
            ->with('success', 'Dokumen hubungan investor berhasil ditambahkan.');
    }

    public function edit(InvestorDocument $investorRelation)
    {
        $investorRelation->load('translations');

        return view('admin.investor-relations.edit', [
            'document' => $investorRelation,
        ]);
    }

    public function update(Request $request, InvestorDocument $investorRelation)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'summary'    => 'nullable|string',
            'year'       => 'nullable|string|max:10',
            'cover'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $investorRelation) {
            $investorRelation->update([
                'year'       => $request->input('year'),
                'sort_order' => (int) $request->input('sort_order', 0),

                // FIX:
                // Jika checkbox tidak dicentang, simpan sebagai false.
                'is_active'  => $request->boolean('is_active'),
            ]);

            if ($request->hasFile('cover')) {
                $oldCoverPath = $investorRelation->cover
                    ? public_path('images/investor-relations/' . $investorRelation->cover)
                    : null;

                if ($oldCoverPath && file_exists($oldCoverPath)) {
                    unlink($oldCoverPath);
                }

                $coverFile = $request->file('cover');
                $coverExt  = strtolower($coverFile->getClientOriginalExtension());
                $coverName = Str::uuid() . '.' . $coverExt;

                $coverDir = public_path('images/investor-relations');

                if (! is_dir($coverDir)) {
                    mkdir($coverDir, 0755, true);
                }

                $coverFile->move($coverDir, $coverName);

                $investorRelation->update([
                    'cover' => $coverName,
                ]);
            }

            $titleId   = $request->input('title');
            $summaryId = $request->input('summary', '');

            $titleEn   = $this->translator->translateText($titleId);
            $summaryEn = $this->translator->translateText($summaryId);

            $investorRelation->translations()->updateOrCreate(
                ['locale' => 'id'],
                [
                    'title'   => $titleId,
                    'summary' => $summaryId,
                ]
            );

            $investorRelation->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'title'   => $titleEn,
                    'summary' => $summaryEn,
                ]
            );
        });

        return redirect()
            ->route('admin.investor-relations.edit', $investorRelation)
            ->with('success', 'Dokumen hubungan investor berhasil diperbarui.');
    }

    public function destroy(InvestorDocument $investorRelation)
    {
        $pdfPath = public_path('documents/investor-relations/' . $investorRelation->file_path);

        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        if ($investorRelation->cover) {
            $coverPath = public_path('images/investor-relations/' . $investorRelation->cover);

            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        $investorRelation->delete();

        return redirect()
            ->route('admin.investor-relations.index')
            ->with('success', 'Dokumen hubungan investor berhasil dihapus.');
    }
}