<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsImage;
use App\Models\NewsTranslation;
use App\Models\User;
use App\Services\NewsAutoTranslator;
use App\Services\PublicImageUploader;
use App\Jobs\TranslateNewsToEnglishJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $userId = (int) $request->session()->get('user_id');
        abort_if($userId <= 0, 403);

        $q = trim((string) $request->query('q'));
        $status = trim((string) $request->query('status'));

        $news = News::query()
            ->with([
                'category',
                'reviewer',
                'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
                'images',
                'logs.user',
            ])
            ->withoutTjsl()
            ->where('created_by', $userId)
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($sub) use ($q) {
                    $sub->whereHas('translations', function ($t) use ($q) {
                        $t->where('title', 'like', "%{$q}%")
                            ->orWhere('excerpt', 'like', "%{$q}%")
                            ->orWhere('content', 'like', "%{$q}%");
                    })->orWhereHas('category', function ($category) use ($q) {
                        $category->where('name', 'like', "%{$q}%");
                    });
                });
            })
            ->when($status !== '', fn ($qr) => $qr->where('status', $status))
            ->orderByRaw("
                CASE status
                    WHEN 'draft' THEN 1
                    WHEN 'published' THEN 2
                    WHEN 'archived' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('writer.news.index', compact('news', 'q', 'status'));
    }

    public function create()
    {
        $categories = NewsCategory::query()
            ->where('is_active', true)
            ->where('slug', '!=', 'tjsl')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $blocks = old('blocks', [
            ['type' => 'heading', 'title' => ''],
            ['type' => 'text', 'body' => ''],
        ]);

        return view('writer.news.create', compact('categories', 'blocks'));
    }

    public function store(
        Request $request,
        NewsAutoTranslator $translator,
        PublicImageUploader $uploader
    ) {
        $user = $this->sessionUser($request);
        abort_unless($user, 403);

        $data = $this->validateData($request);

        $category = NewsCategory::query()->findOrFail($data['news_category_id']);
        abort_if($category->slug === 'tjsl', 422, 'Kategori TJSL tidak dikelola dari modul news.');

        $idSlugBase = $this->makeSlug(
            $data['id_slug'] ?? null,
            $data['id_title'],
            now()->timestamp
        );

        $idSlug = $this->makeUniqueSlug('id', $idSlugBase);

        $blocksId = $this->normalizeBlocks($request, $uploader);
        $contentId = $this->blocksToHtml($blocksId);

        try {
            DB::beginTransaction();

            $payload = [
                'news_category_id' => $data['news_category_id'],
                'status' => News::STATUS_DRAFT,
                'published_at' => !empty($data['published_at'])
                    ? Carbon::parse($data['published_at'], config('app.timezone', 'Asia/Jakarta'))
                    : null,
                'is_featured' => false,
                'is_visible' => false,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
                'created_by' => $user->id,
            ];

            if ($request->hasFile('featured_image')) {
                $payload['featured_image'] = $uploader->upload(
                    $request->file('featured_image'),
                    'images/news/featured',
                    2
                );
            }

            $news = News::create($payload);

            NewsTranslation::create([
                'news_id' => $news->id,
                'locale' => 'id',
                'title' => $data['id_title'],
                'slug' => $idSlug,
                'excerpt' => $data['id_excerpt'] ?? null,
                'content' => $contentId,
                'content_blocks' => $blocksId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Placeholder EN
            |--------------------------------------------------------------------------
            | Dibuat cepat agar data EN tetap ada.
            | Isi sebenarnya akan di-update oleh TranslateNewsToEnglishJob.
            |--------------------------------------------------------------------------
            */
            $enSlug = $this->makeUniqueSlug(
                'en',
                $this->makeSlug(null, $data['id_title'], 'en-' . $news->id),
                $news->id
            );

            NewsTranslation::create([
                'news_id' => $news->id,
                'locale' => 'en',
                'title' => $data['id_title'],
                'slug' => $enSlug,
                'excerpt' => $data['id_excerpt'] ?? null,
                'content' => $contentId,
                'content_blocks' => $blocksId,
            ]);

            $this->storeGalleryImages($request, $uploader, $news);

            if (function_exists('news_log')) {
                news_log($news->id, 'created', 'News dibuat oleh writer sebagai draft. Terjemahan EN masuk antrean queue.');
            }

            DB::commit();

            TranslateNewsToEnglishJob::dispatch($news->id);

            return redirect()
                ->route('writer.news.edit', $news)
                ->with('success', 'News berhasil disimpan. Terjemahan English sedang diproses otomatis di background.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer news store failed', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan news. Silakan cek kembali data yang diinput.');
        }
    }

    public function show(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'reviewer',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'logs.user',
        ]);

        return view('writer.news.show', compact('news'));
    }

    public function edit(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'category',
            'reviewer',
            'logs.user',
        ]);

        $categories = NewsCategory::query()
            ->where('is_active', true)
            ->where('slug', '!=', 'tjsl')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $tId = $news->translations->firstWhere('locale', 'id')
            ?? new NewsTranslation(['locale' => 'id']);

        $tEn = $news->translations->firstWhere('locale', 'en')
            ?? new NewsTranslation(['locale' => 'en']);

        $blocks = old('blocks', $tId->content_blocks ?: [
            ['type' => 'heading', 'title' => ''],
            ['type' => 'text', 'body' => ''],
        ]);

        return view('writer.news.edit', compact('news', 'categories', 'tId', 'tEn', 'blocks'));
    }

    public function update(
        Request $request,
        News $news,
        NewsAutoTranslator $translator,
        PublicImageUploader $uploader
    ) {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $data = $this->validateData($request);

        $category = NewsCategory::query()->findOrFail($data['news_category_id']);
        abort_if($category->slug === 'tjsl', 422, 'Kategori TJSL tidak dikelola dari modul news.');

        $news->loadMissing([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'category',
        ]);

        $translationId = $news->translations->firstWhere('locale', 'id');
        $translationEn = $news->translations->firstWhere('locale', 'en');

        $idSlugBase = $this->makeSlug(
            $data['id_slug'] ?? null,
            $data['id_title'],
            $news->id
        );

        $idSlug = $this->makeUniqueSlug('id', $idSlugBase, $news->id);

        $oldBlockImages = $this->collectBlockImages($news);

        $blocksId = $this->normalizeBlocks($request, $uploader);
        $contentId = $this->blocksToHtml($blocksId);

        if (! $this->hasNewsEditChanges($request, $news, $data, $idSlug, $blocksId)) {
            return redirect()
                ->route('writer.news.edit', $news)
                ->with('info', 'Tidak ada perubahan data yang perlu disimpan.');
        }

        $needsTranslation = $this->hasTranslatableChanges($translationId, $data, $blocksId);

        try {
            DB::beginTransaction();

            $payload = [
                'news_category_id' => $data['news_category_id'],
                'published_at' => !empty($data['published_at'])
                    ? Carbon::parse($data['published_at'], config('app.timezone', 'Asia/Jakarta'))
                    : $news->published_at,
                'is_visible' => $news->status === News::STATUS_PUBLISHED,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
            ];

            if ($request->hasFile('featured_image')) {
                $uploader->delete($news->featured_image);

                $payload['featured_image'] = $uploader->upload(
                    $request->file('featured_image'),
                    'images/news/featured',
                    2
                );
            }

            $news->update($payload);

            NewsTranslation::updateOrCreate(
                ['news_id' => $news->id, 'locale' => 'id'],
                [
                    'title' => $data['id_title'],
                    'slug' => $idSlug,
                    'excerpt' => $data['id_excerpt'] ?? null,
                    'content' => $contentId,
                    'content_blocks' => $blocksId,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | EN tidak ditranslate langsung
            |--------------------------------------------------------------------------
            | Kalau konten ID berubah, EN sementara disamakan dulu dengan ID,
            | lalu job akan mengisi hasil translate di background.
            |--------------------------------------------------------------------------
            */
            if ($needsTranslation) {
                $enSlug = $translationEn?->slug;

                if (! $enSlug) {
                    $enSlug = $this->makeUniqueSlug(
                        'en',
                        $this->makeSlug(null, $data['id_title'], 'en-' . $news->id),
                        $news->id
                    );
                }

                NewsTranslation::updateOrCreate(
                    ['news_id' => $news->id, 'locale' => 'en'],
                    [
                        'title' => $data['id_title'],
                        'slug' => $enSlug,
                        'excerpt' => $data['id_excerpt'] ?? null,
                        'content' => $contentId,
                        'content_blocks' => $blocksId,
                    ]
                );
            }

            $this->removeGalleryImages($request, $uploader, $news);
            $this->cleanupUnusedBlockImages($oldBlockImages, $blocksId, $uploader);
            $this->storeGalleryImages($request, $uploader, $news);

            if (function_exists('news_log')) {
                $logMessage = $needsTranslation
                    ? 'News diperbarui oleh writer. Terjemahan EN masuk antrean queue.'
                    : 'News diperbarui oleh writer tanpa translate ulang.';

                news_log($news->id, 'updated', $logMessage);
            }

            DB::commit();

            if ($needsTranslation) {
                TranslateNewsToEnglishJob::dispatch($news->id);
            }

            return redirect()
                ->route('writer.news.edit', $news)
                ->with('success', $needsTranslation
                    ? 'Perubahan news berhasil disimpan. Terjemahan English sedang diproses otomatis di background.'
                    : 'Perubahan news berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer news update failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui news. Silakan cek kembali data yang diinput.');
        }
    }

    public function preview(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
        ]);

        $locale = 'id';
        $translation = $news->getTranslationByLocale($locale);

        return view('web.news.preview', [
            'news' => $news,
            'locale' => $locale,
            'translation' => $translation,
            'metaTitle' => ($translation?->title ?: 'Preview News') . ' - BSP Zapin',
            'metaDescription' => $translation?->excerpt ?: 'Preview News BSP Zapin',
            'metaImage' => $news->featured_image ? asset($news->featured_image) : asset('images/logo.png'),
        ]);
    }

    public function sendPreviewWhatsapp(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
        ]);

        $translation = $news->getTranslationByLocale('id');

        if (! $translation || trim((string) $translation->title) === '') {
            return back()->with('error', 'Judul news wajib tersedia sebelum mengirim preview ke reviewer.');
        }

        $phone = config('services.news_whatsapp.reviewer')
            ?: config('services.tjsl_whatsapp.reviewer');

        if (! $phone) {
            return back()->with('error', 'Nomor WhatsApp reviewer belum dikonfigurasi di config/services.php.');
        }

        $previewUrl = route('reviewer.news.preview', $news);

        $message = "Assalamu’alaikum Warahmatullahi Wabarakatuh, Pak MTQ.\n\n"
            . "Mohon izin, saya ingin meminta waktu Bapak untuk meninjau draft konten berita sebelum dipublikasikan pada website resmi PT Bumi Siak Pusako Zapin.\n\n"
            . "Berikut informasi kontennya:\n"
            . "• Jenis Konten : News / Berita\n"
            . "• Judul        : {$translation->title}\n"
            . "• Kategori     : " . ($news->category?->name ?? '-') . "\n"
            . "• Status       : " . ($news->status_label ?? ucfirst((string) $news->status)) . "\n"
            . "• Writer       : " . ($news->author?->name ?? '-') . "\n\n"
            . "Bapak dapat melihat tampilan preview melalui tautan berikut:\n"
            . "{$previewUrl}\n\n"
            . "Catatan:\n"
            . "Tautan preview hanya dapat diakses setelah login sebagai reviewer.\n\n"
            . "Apabila terdapat masukan atau koreksi, mohon dapat disampaikan agar konten dapat segera saya sesuaikan sebelum dipublish.\n\n"
            . "Atas perhatian dan waktu Bapak, saya ucapkan terima kasih.\n\n"
            . "Wassalamu’alaikum Warahmatullahi Wabarakatuh.";

        $waUrl = $this->makeWhatsappLink($phone, $message);

        if (! $waUrl) {
            return back()->with('error', 'Nomor WhatsApp reviewer tidak valid.');
        }

        return redirect()->away($waUrl);
    }

    public function publish(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->loadMissing([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
        ]);

        $translation = $news->getTranslationByLocale('id');

        if (! $translation || trim((string) $translation->title) === '') {
            return redirect()
                ->route('writer.news.edit', $news)
                ->with('error', 'Judul news wajib diisi sebelum publish.');
        }

        if (! $translation->content || trim(strip_tags((string) $translation->content)) === '') {
            return redirect()
                ->route('writer.news.edit', $news)
                ->with('error', 'Konten news wajib diisi sebelum publish.');
        }

        try {
            $publishAt = $news->published_at ?: now();

            $news->update([
                'status' => News::STATUS_PUBLISHED,
                'is_visible' => true,
                'published_at' => $publishAt,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
            ]);

            if (function_exists('news_log')) {
                news_log($news->id, 'published', 'News dipublish langsung oleh writer.');
            }

            return redirect()
                ->route('writer.news.show', $news)
                ->with('success', 'News berhasil dipublish ke website publik.');
        } catch (\Throwable $e) {
            Log::error('Writer news publish failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()->with('error', 'Gagal publish news.');
        }
    }

    public function unpublish(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        if ($news->status !== News::STATUS_PUBLISHED) {
            return back()->with('error', 'News ini belum dalam status published.');
        }

        try {
            $news->update([
                'status' => News::STATUS_DRAFT,
                'is_visible' => false,
                'published_at' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
            ]);

            if (function_exists('news_log')) {
                news_log($news->id, 'unpublished', 'News ditarik dari website publik oleh writer.');
            }

            return redirect()
                ->route('writer.news.show', $news)
                ->with('success', 'News berhasil ditarik dari website publik dan kembali menjadi draft.');
        } catch (\Throwable $e) {
            Log::error('Writer news unpublish failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()->with('error', 'Gagal unpublish news.');
        }
    }

    public function destroy(Request $request, News $news, PublicImageUploader $uploader)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        try {
            DB::beginTransaction();

            $news->load(['images', 'translations']);

            if (function_exists('news_log')) {
                news_log($news->id, 'deleted', 'News dihapus oleh writer.');
            }

            $uploader->delete($news->featured_image);

            foreach ($news->images as $image) {
                $uploader->delete($image->image_path);
                $image->delete();
            }

            foreach ($news->translations as $translation) {
                $translation->delete();
            }

            $news->delete();

            DB::commit();

            return redirect()
                ->route('writer.news.index')
                ->with('success', 'News berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer news destroy failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()->with('error', 'Gagal menghapus news.');
        }
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'published_at' => ['nullable', 'date'],

            'featured_image' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:8192',
            ],

            'gallery_images' => [
                'nullable',
                'array',
                'max:5',
            ],

            'gallery_images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:8192',
            ],

            'remove_gallery_image_ids' => [
                'nullable',
                'array',
            ],

            'remove_gallery_image_ids.*' => [
                'nullable',
                'integer',
            ],

            'id_title' => ['required', 'string', 'max:190'],
            'id_slug' => ['nullable', 'string', 'max:190'],
            'id_excerpt' => ['nullable', 'string', 'max:350'],

            'blocks' => [
                'nullable',
                'array',
                'max:30',
            ],

            'blocks.*.type' => ['nullable', 'in:heading,text,image'],
            'blocks.*.title' => ['nullable', 'string', 'max:255'],
            'blocks.*.body' => ['nullable', 'string', 'max:10000'],
            'blocks.*.caption' => ['nullable', 'string', 'max:255'],
            'blocks.*.existing_image' => ['nullable', 'string', 'max:255'],

            'block_images' => [
                'nullable',
                'array',
                'max:10',
            ],

            'block_images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:8192',
            ],
        ], [
            'news_category_id.required' => 'Kategori berita wajib dipilih.',
            'news_category_id.exists' => 'Kategori berita tidak valid.',

            'featured_image.image' => 'Gambar utama harus berupa file gambar.',
            'featured_image.mimes' => 'Gambar utama harus berformat JPG, JPEG, PNG, atau WEBP.',
            'featured_image.mimetypes' => 'Gambar utama harus berformat JPG, JPEG, PNG, atau WEBP.',
            'featured_image.max' => 'Gambar utama maksimal 8MB. Sistem akan mengompres otomatis ke WebP.',

            'gallery_images.array' => 'Format gallery tidak valid.',
            'gallery_images.max' => 'Gallery maksimal 5 gambar.',
            'gallery_images.*.image' => 'Setiap gambar gallery harus berupa file gambar.',
            'gallery_images.*.mimes' => 'Gambar gallery harus berformat JPG, JPEG, PNG, atau WEBP.',
            'gallery_images.*.mimetypes' => 'Gambar gallery harus berformat JPG, JPEG, PNG, atau WEBP.',
            'gallery_images.*.max' => 'Setiap gambar gallery maksimal 8MB. Sistem akan mengompres otomatis ke WebP.',
            'remove_gallery_image_ids.array' => 'Format data hapus gallery tidak valid.',
            'remove_gallery_image_ids.*.integer' => 'Data gambar gallery yang akan dihapus tidak valid.',

            'id_title.required' => 'Judul berita wajib diisi.',
            'id_title.max' => 'Judul berita maksimal 190 karakter.',
            'id_slug.max' => 'Slug maksimal 190 karakter.',
            'id_excerpt.max' => 'Ringkasan maksimal 350 karakter.',

            'blocks.array' => 'Format konten tidak valid.',
            'blocks.max' => 'Jumlah blok konten maksimal 30 blok.',
            'blocks.*.type.in' => 'Tipe blok konten tidak valid.',
            'blocks.*.title.max' => 'Judul/subjudul konten maksimal 255 karakter.',
            'blocks.*.body.max' => 'Isi setiap blok teks maksimal 10.000 karakter.',
            'blocks.*.caption.max' => 'Caption gambar maksimal 255 karakter.',

            'block_images.array' => 'Format gambar konten tidak valid.',
            'block_images.max' => 'Gambar dalam konten maksimal 10 gambar.',
            'block_images.*.image' => 'Setiap gambar konten harus berupa file gambar.',
            'block_images.*.mimes' => 'Gambar konten harus berformat JPG, JPEG, PNG, atau WEBP.',
            'block_images.*.mimetypes' => 'Gambar konten harus berformat JPG, JPEG, PNG, atau WEBP.',
            'block_images.*.max' => 'Setiap gambar konten maksimal 8MB. Sistem akan mengompres otomatis ke WebP.',
        ]);
    }

    private function normalizeBlocks(Request $request, PublicImageUploader $uploader): array
    {
        $blocks = $request->input('blocks', []);
        $result = [];

        if (! is_array($blocks)) {
            return [];
        }

        foreach ($blocks as $index => $block) {
            if (! is_array($block)) {
                continue;
            }

            $type = $block['type'] ?? 'text';

            if ($type === 'heading') {
                $title = trim((string) ($block['title'] ?? ''));

                if ($title !== '') {
                    $result[] = [
                        'type' => 'heading',
                        'title' => $title,
                    ];
                }

                continue;
            }

            if ($type === 'text') {
                $body = trim((string) ($block['body'] ?? ''));

                if ($body !== '') {
                    $result[] = [
                        'type' => 'text',
                        'body' => $body,
                    ];
                }

                continue;
            }

            if ($type === 'image') {
                $imagePath = trim((string) ($block['existing_image'] ?? ''));

                if ($request->hasFile("block_images.$index")) {
                    if ($imagePath !== '') {
                        $uploader->delete($imagePath);
                    }

                    $imagePath = $uploader->upload(
                        $request->file("block_images.$index"),
                        'images/news/blocks',
                        2
                    );
                }

                if ($imagePath !== '') {
                    $result[] = [
                        'type' => 'image',
                        'image' => $imagePath,
                        'caption' => trim((string) ($block['caption'] ?? '')),
                    ];
                }
            }
        }

        return array_values($result);
    }

    private function blocksToHtml(array $blocks): string
    {
        $html = '';

        foreach ($blocks as $block) {
            $type = $block['type'] ?? null;

            if ($type === 'heading') {
                $title = trim((string) ($block['title'] ?? ''));

                if ($title !== '') {
                    $html .= '<h2>' . e($title) . '</h2>';
                }

                continue;
            }

            if ($type === 'text') {
                $body = trim((string) ($block['body'] ?? ''));

                if ($body !== '') {
                    $paragraphs = preg_split("/\n{2,}/", $body);

                    foreach ($paragraphs as $paragraph) {
                        $paragraph = trim((string) $paragraph);

                        if ($paragraph !== '') {
                            $html .= '<p>' . nl2br(e($paragraph)) . '</p>';
                        }
                    }
                }

                continue;
            }

            if ($type === 'image' && ! empty($block['image'])) {
                $caption = trim((string) ($block['caption'] ?? ''));

                $html .= '<figure>';
                $html .= '<img src="' . asset($block['image']) . '" alt="' . e($caption ?: 'News image') . '">';

                if ($caption !== '') {
                    $html .= '<figcaption>' . e($caption) . '</figcaption>';
                }

                $html .= '</figure>';
            }
        }

        return $html;
    }

    private function removeGalleryImages(Request $request, PublicImageUploader $uploader, News $news): void
    {
        $imageIds = collect($request->input('remove_gallery_image_ids', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($imageIds->isEmpty()) {
            return;
        }

        $images = $news->images()
            ->whereIn('id', $imageIds->all())
            ->get();

        foreach ($images as $image) {
            $uploader->delete($image->image_path);
            $image->delete();
        }
    }

    private function collectBlockImages(News $news): array
    {
        $news->loadMissing([
            'translations' => fn ($q) => $q->where('locale', 'id'),
        ]);

        $translation = $news->translations->firstWhere('locale', 'id');
        $blocks = $translation?->content_blocks ?: [];

        return $this->extractBlockImages($blocks);
    }

    private function extractBlockImages(array $blocks): array
    {
        $paths = [];

        foreach ($blocks as $block) {
            if (($block['type'] ?? null) === 'image' && ! empty($block['image'])) {
                $paths[] = trim((string) $block['image']);
            }
        }

        return array_values(array_unique(array_filter($paths)));
    }

    private function cleanupUnusedBlockImages(array $oldBlockImages, array $newBlocks, PublicImageUploader $uploader): void
    {
        $newBlockImages = $this->extractBlockImages($newBlocks);
        $unusedImages = array_diff($oldBlockImages, $newBlockImages);

        foreach ($unusedImages as $imagePath) {
            $uploader->delete($imagePath);
        }
    }

    private function storeGalleryImages(Request $request, PublicImageUploader $uploader, News $news): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $currentMax = (int) $news->images()->max('sort_order');

        foreach ($request->file('gallery_images') as $index => $image) {
            if (! $image || ! $image->isValid()) {
                continue;
            }

            NewsImage::create([
                'news_id' => $news->id,
                'image_path' => $uploader->upload(
                    $image,
                    'images/news/gallery',
                    2
                ),
                'caption' => null,
                'sort_order' => $currentMax + $index + 1,
            ]);
        }
    }

    private function hasNewsEditChanges(
        Request $request,
        News $news,
        array $data,
        string $idSlug,
        array $blocksId
    ): bool {
        $translationId = $news->translations->firstWhere('locale', 'id');

        if ((int) $news->news_category_id !== (int) $data['news_category_id']) {
            return true;
        }

        $incomingPublishedAt = !empty($data['published_at'])
            ? Carbon::parse($data['published_at'], config('app.timezone', 'Asia/Jakarta'))->format('Y-m-d H:i:s')
            : optional($news->published_at)->format('Y-m-d H:i:s');

        $currentPublishedAt = optional($news->published_at)->format('Y-m-d H:i:s');

        if ($incomingPublishedAt !== $currentPublishedAt) {
            return true;
        }

        if (trim((string) ($translationId?->title ?? '')) !== trim((string) ($data['id_title'] ?? ''))) {
            return true;
        }

        if (trim((string) ($translationId?->slug ?? '')) !== trim((string) $idSlug)) {
            return true;
        }

        if (trim((string) ($translationId?->excerpt ?? '')) !== trim((string) ($data['id_excerpt'] ?? ''))) {
            return true;
        }

        $currentBlocks = $translationId?->content_blocks ?: [];

        if ($this->normalizeArrayForCompare($currentBlocks) !== $this->normalizeArrayForCompare($blocksId)) {
            return true;
        }

        if ($request->hasFile('featured_image')) {
            return true;
        }

        if ($this->hasValidUploadedFiles($request, 'gallery_images')) {
            return true;
        }

        if ($this->hasValidUploadedFiles($request, 'block_images')) {
            return true;
        }

        $removeGalleryImageIds = collect($request->input('remove_gallery_image_ids', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($removeGalleryImageIds->isNotEmpty()) {
            return true;
        }

        return false;
    }

    private function hasTranslatableChanges(?NewsTranslation $translationId, array $data, array $blocksId): bool
    {
        if (! $translationId) {
            return true;
        }

        if (trim((string) ($translationId->title ?? '')) !== trim((string) ($data['id_title'] ?? ''))) {
            return true;
        }

        if (trim((string) ($translationId->excerpt ?? '')) !== trim((string) ($data['id_excerpt'] ?? ''))) {
            return true;
        }

        $currentBlocks = $translationId->content_blocks ?: [];

        if ($this->normalizeArrayForCompare($currentBlocks) !== $this->normalizeArrayForCompare($blocksId)) {
            return true;
        }

        return false;
    }

    private function hasValidUploadedFiles(Request $request, string $key): bool
    {
        if (! $request->hasFile($key)) {
            return false;
        }

        $files = $request->file($key);

        if (! is_array($files)) {
            return $files && $files->isValid();
        }

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                return true;
            }
        }

        return false;
    }

    private function normalizeArrayForCompare(array $value): array
    {
        return json_decode(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), true) ?: [];
    }

    private function makeSlug(?string $inputSlug, string $title, string|int $fallback): string
    {
        $slugSource = trim((string) $inputSlug) !== ''
            ? trim((string) $inputSlug)
            : $title;

        $slug = Str::slug($slugSource);

        if ($slug === '') {
            $slug = Str::slug($title . '-' . $fallback);
        }

        if ($slug === '') {
            $slug = 'news-' . $fallback;
        }

        return $slug;
    }

    private function makeUniqueSlug(string $locale, string $slug, ?int $excludeNewsId = null): string
    {
        $baseSlug = trim((string) $slug) !== '' ? trim((string) $slug) : 'news';
        $finalSlug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($locale, $finalSlug, $excludeNewsId)) {
            $finalSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $finalSlug;
    }

    private function slugExists(string $locale, string $slug, ?int $excludeNewsId = null): bool
    {
        $query = NewsTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug);

        if ($excludeNewsId) {
            $query->where('news_id', '!=', $excludeNewsId);
        }

        return $query->exists();
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

    private function authorizeWriter(Request $request, News $news): void
    {
        $userId = (int) $request->session()->get('user_id');

        if ($userId <= 0) {
            abort(403);
        }

        if ((int) $news->created_by !== $userId) {
            abort(403);
        }
    }

    private function sessionUser(Request $request): ?User
    {
        $userId = (int) $request->session()->get('user_id');

        if ($userId <= 0) {
            return null;
        }

        return User::find($userId);
    }
}