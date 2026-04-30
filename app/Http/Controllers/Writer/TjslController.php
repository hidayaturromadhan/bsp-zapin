<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\TjslProgram;
use App\Models\TjslProgramImage;
use App\Models\TjslProgramTranslation;
use App\Services\NewsAutoTranslator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TjslController extends Controller
{
    public function index(Request $request)
    {
        $status = trim((string) $request->query('status'));
        $search = trim((string) $request->query('search'));

        $programs = TjslProgram::query()
            ->with(['translations', 'images'])
            ->forWriter((int) Auth::id())
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('year', 'like', "%{$search}%")
                        ->orWhereHas('translations', function ($tr) use ($search) {
                            $tr->where('title', 'like', "%{$search}%")
                                ->orWhere('summary', 'like', "%{$search}%")
                                ->orWhere('content', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('writer.tjsl.index', [
            'programs' => $programs,
            'statuses' => TjslProgram::statuses(),
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('writer.tjsl.create', [
            'program' => new TjslProgram([
                'status' => TjslProgram::STATUS_DRAFT,
                'is_active' => false,
                'sort_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request, NewsAutoTranslator $translator)
    {
        $data = $this->validateData($request);

        try {
            DB::beginTransaction();

            $program = TjslProgram::create([
                'year' => $data['year'],
                'featured_image' => $this->uploadImage($request, 'featured_image'),
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => false,
                'status' => TjslProgram::STATUS_DRAFT,
                'created_by' => Auth::id(),
                'reviewed_by' => null,
                'submitted_at' => null,
                'reviewed_at' => null,
                'published_at' => null,
                'rejected_at' => null,
                'review_note' => null,
            ]);

            $this->syncTranslations($program, $data, $translator);
            $this->storeGalleryImages($request, $program);

            DB::commit();

            return redirect()
                ->route('writer.tjsl.edit', $program)
                ->with('success', 'Program TJSL berhasil disimpan sebagai draft.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer TJSL store failed', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan TJSL. Silakan cek kembali data yang diinput.');
        }
    }

    public function show(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        $tjsl->load(['translations', 'images', 'author']);

        return view('writer.tjsl.show', [
            'program' => $tjsl,
            'statuses' => TjslProgram::statuses(),
        ]);
    }

    public function edit(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        $tjsl->load(['translations', 'images']);

        return view('writer.tjsl.edit', [
            'program' => $tjsl,
        ]);
    }

    public function update(Request $request, TjslProgram $tjsl, NewsAutoTranslator $translator)
    {
        $this->authorizeWriter($tjsl);

        $data = $this->validateData($request);

        try {
            DB::beginTransaction();

            $featuredImage = $tjsl->featured_image;

            if ($request->hasFile('featured_image')) {
                $this->deletePublicFile($featuredImage);
                $featuredImage = $this->uploadImage($request, 'featured_image');
            }

            $tjsl->update([
                'year' => $data['year'],
                'featured_image' => $featuredImage,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $tjsl->status === TjslProgram::STATUS_PUBLISHED,
                'status' => $tjsl->status ?: TjslProgram::STATUS_DRAFT,
                'review_note' => null,
                'rejected_at' => null,
            ]);

            $this->syncTranslations($tjsl, $data, $translator);
            $this->storeGalleryImages($request, $tjsl);

            DB::commit();

            return redirect()
                ->route('writer.tjsl.edit', $tjsl)
                ->with('success', 'Program TJSL berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer TJSL update failed', [
                'message' => $e->getMessage(),
                'program_id' => $tjsl->id,
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui TJSL. Silakan cek kembali data yang diinput.');
        }
    }

    public function preview(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        $tjsl->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
        ]);

        return view('web.tjsl.index', [
            'locale' => 'id',
            'programs' => collect([$tjsl]),
            'activeProgram' => $tjsl,
            'activeTranslation' => $tjsl->getTranslation('id'),
            'metaTitle' => 'Preview TJSL - BSP Zapin',
            'metaDescription' => $tjsl->getTranslation('id')?->summary ?? 'Preview TJSL',
            'metaImage' => $tjsl->featured_image ? asset($tjsl->featured_image) : asset('images/logo.png'),
        ]);
    }

    public function sendPreviewWhatsapp(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        $tjsl->loadMissing('translations');

        $translation = $tjsl->getTranslation('id');

        if (! $translation || trim((string) $translation->title) === '') {
            return redirect()
                ->route('writer.tjsl.edit', $tjsl)
                ->with('error', 'Judul TJSL wajib diisi sebelum mengirim preview ke reviewer.');
        }

        $phone = config('services.tjsl_whatsapp.reviewer');

        $waUrl = $this->makeWhatsappLink(
            $phone,
            $this->buildPreviewWhatsappMessage($tjsl, $translation->title)
        );

        if (! $waUrl) {
            return redirect()
                ->route('writer.tjsl.show', $tjsl)
                ->with('error', 'Nomor WhatsApp reviewer belum dikonfigurasi di file .env.');
        }

        return redirect()->away($waUrl);
    }

    public function publish(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        $tjsl->loadMissing('translations');

        $translation = $tjsl->getTranslation('id');

        if (! $translation || trim((string) $translation->title) === '') {
            return redirect()
                ->route('writer.tjsl.edit', $tjsl)
                ->with('error', 'Judul TJSL wajib diisi sebelum publish.');
        }

        try {
            $tjsl->update([
                'status' => TjslProgram::STATUS_PUBLISHED,
                'is_active' => true,
                'published_at' => now(),
                'review_note' => null,
                'rejected_at' => null,
            ]);

            return redirect()
                ->route('writer.tjsl.show', $tjsl)
                ->with('success', 'Program TJSL berhasil dipublish ke website publik.');
        } catch (\Throwable $e) {
            Log::error('Writer TJSL publish failed', [
                'message' => $e->getMessage(),
                'program_id' => $tjsl->id,
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Gagal publish program TJSL.');
        }
    }

    public function unpublish(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        if ($tjsl->status !== TjslProgram::STATUS_PUBLISHED) {
            return back()->with('error', 'Program TJSL ini belum dalam status published.');
        }

        try {
            $tjsl->update([
                'status' => TjslProgram::STATUS_DRAFT,
                'is_active' => false,
                'published_at' => null,
            ]);

            return redirect()
                ->route('writer.tjsl.show', $tjsl)
                ->with('success', 'Program TJSL berhasil ditarik dari website publik dan kembali menjadi draft.');
        } catch (\Throwable $e) {
            Log::error('Writer TJSL unpublish failed', [
                'message' => $e->getMessage(),
                'program_id' => $tjsl->id,
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Gagal unpublish program TJSL.');
        }
    }

    public function destroy(TjslProgram $tjsl)
    {
        $this->authorizeWriter($tjsl);

        try {
            DB::beginTransaction();

            $tjsl->load(['images', 'translations']);

            $this->deletePublicFile($tjsl->featured_image);

            foreach ($tjsl->images as $image) {
                $this->deletePublicFile($image->image_path);
                $image->delete();
            }

            foreach ($tjsl->translations as $translation) {
                $translation->delete();
            }

            $tjsl->delete();

            DB::commit();

            return redirect()
                ->route('writer.tjsl.index')
                ->with('success', 'Program TJSL berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer TJSL destroy failed', [
                'message' => $e->getMessage(),
                'program_id' => $tjsl->id,
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Gagal menghapus program TJSL.');
        }
    }

    public function deleteImage(TjslProgram $tjsl, TjslProgramImage $image)
    {
        $this->authorizeWriter($tjsl);

        if ((int) $image->tjsl_program_id !== (int) $tjsl->id) {
            abort(404);
        }

        $this->deletePublicFile($image->image_path);
        $image->delete();

        return back()->with('success', 'Gambar galeri berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:' . ((int) date('Y') + 5)],
            'sort_order' => ['nullable', 'integer', 'min:0'],

            'featured_image' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
            ],

            'title_id' => ['required', 'string', 'max:190'],
            'summary_id' => ['nullable', 'string'],
            'content_id' => ['nullable', 'string'],

            'gallery_images' => ['nullable', 'array', 'max:5'],
            'gallery_images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
            ],

            'gallery_captions' => ['nullable', 'array', 'max:5'],
            'gallery_captions.*' => ['nullable', 'string', 'max:190'],
        ]);
    }

    private function syncTranslations(TjslProgram $program, array $data, NewsAutoTranslator $translator): void
    {
        $titleId = trim((string) ($data['title_id'] ?? ''));
        $summaryId = trim((string) ($data['summary_id'] ?? ''));
        $contentId = trim((string) ($data['content_id'] ?? ''));

        TjslProgramTranslation::updateOrCreate(
            [
                'tjsl_program_id' => $program->id,
                'locale' => 'id',
            ],
            [
                'title' => $titleId,
                'summary' => $summaryId,
                'content' => $contentId,
            ]
        );

        TjslProgramTranslation::updateOrCreate(
            [
                'tjsl_program_id' => $program->id,
                'locale' => 'en',
            ],
            [
                'title' => $translator->translateText($titleId, 'id', 'en'),
                'summary' => $translator->translateText($summaryId, 'id', 'en'),
                'content' => $translator->translateHtml($contentId, 'id', 'en'),
            ]
        );
    }

    private function storeGalleryImages(Request $request, TjslProgram $program): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        File::ensureDirectoryExists(public_path('uploads/tjsl/gallery'));

        $files = $request->file('gallery_images', []);
        $captions = $request->input('gallery_captions', []);

        if (! is_array($files)) {
            $files = [$files];
        }

        $existingCount = $program->images()->count();
        $allowedSlots = max(0, 5 - $existingCount);

        if ($allowedSlots <= 0) {
            return;
        }

        $files = array_slice($files, 0, $allowedSlots);
        $lastSortOrder = (int) $program->images()->max('sort_order');

        foreach ($files as $index => $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $mime = $file->getMimeType();

            if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
                continue;
            }

            $extension = strtolower((string) $file->getClientOriginalExtension());

            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $realPath = $file->getRealPath();

            if (! $realPath || @getimagesize($realPath) === false) {
                continue;
            }

            $lastSortOrder++;

            $safeExtension = $extension === 'jpeg' ? 'jpg' : $extension;
            $filename = 'tjsl-gallery-' . now()->format('YmdHis') . '-' . Str::random(24) . '.' . $safeExtension;
            $path = 'uploads/tjsl/gallery/' . $filename;

            $file->move(public_path('uploads/tjsl/gallery'), $filename);

            TjslProgramImage::create([
                'tjsl_program_id' => $program->id,
                'image_path' => $path,
                'caption' => $captions[$index] ?? null,
                'sort_order' => $lastSortOrder,
            ]);
        }
    }

    private function uploadImage(Request $request, string $field): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);

        if (! $file || ! $file->isValid()) {
            return null;
        }

        $mime = $file->getMimeType();

        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return null;
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return null;
        }

        $realPath = $file->getRealPath();

        if (! $realPath || @getimagesize($realPath) === false) {
            return null;
        }

        File::ensureDirectoryExists(public_path('uploads/tjsl'));

        $safeExtension = $extension === 'jpeg' ? 'jpg' : $extension;
        $filename = 'tjsl-' . now()->format('YmdHis') . '-' . Str::random(24) . '.' . $safeExtension;
        $path = 'uploads/tjsl/' . $filename;

        $file->move(public_path('uploads/tjsl'), $filename);

        return $path;
    }

    private function deletePublicFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath) && File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }

    private function buildPreviewWhatsappMessage(TjslProgram $program, string $title): string
    {
        $previewUrl = route('login', [
            'redirect' => route('reviewer.tjsl.preview', $program),
        ]);

        return "Assalamu’alaikum Warahmatullahi Wabarakatuh, Pak MTQ.\n\n"
            . "Mohon izin, saya ingin meminta waktu Bapak untuk melakukan peninjauan terhadap draft konten TJSL sebelum dipublikasikan pada website resmi.\n\n"
            . "Berikut detail konten:\n"
            . "• Judul Program : {$title}\n"
            . "• Tahun         : {$program->year}\n"
            . "• Status        : {$program->status_label}\n\n"
            . "Silakan mengakses preview melalui tautan berikut:\n"
            . "{$previewUrl}\n\n"
            . "Catatan:\n"
            . "Link di atas akan mengarahkan Bapak ke halaman login terlebih dahulu. Setelah login sebagai reviewer, Bapak akan langsung diarahkan ke halaman preview konten TJSL tersebut.\n\n"
            . "Apabila terdapat masukan atau koreksi, mohon informasikan kepada saya melalui WhatsApp ini.\n\n"
            . "Atas perhatian dan waktu Bapak, saya ucapkan terima kasih.\n\n"
            . "Wassalamu’alaikum Warahmatullahi Wabarakatuh.";
    }

    private function makeWhatsappLink(?string $phone, string $message): ?string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);

        if ($phone === '') {
            return null;
        }

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    private function authorizeWriter(TjslProgram $program): void
    {
        if ((int) $program->created_by !== (int) Auth::id()) {
            abort(403);
        }
    }
}