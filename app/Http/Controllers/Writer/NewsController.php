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

        $idSlug = trim((string) ($data['id_slug'] ?? '')) !== ''
            ? trim((string) $data['id_slug'])
            : Str::slug($data['id_title']);

        if ($idSlug === '') {
            $idSlug = Str::slug($data['id_title'] . '-' . now()->timestamp);
        }

        $blocksId = $this->normalizeBlocks($request, $uploader);
        $contentId = $this->blocksToHtml($blocksId);

        $translatedTitle = $translator->translateText($data['id_title'], 'id', 'en');
        $translatedExcerpt = $translator->translateText($data['id_excerpt'] ?? '', 'id', 'en');
        $translatedBlocks = $translator->translateBlocks($blocksId, 'id', 'en');
        $translatedContent = $this->blocksToHtml($translatedBlocks);

        $enTitle = trim((string) $translatedTitle) !== ''
            ? trim((string) $translatedTitle)
            : $data['id_title'];

        $enExcerpt = trim((string) $translatedExcerpt) !== ''
            ? trim((string) $translatedExcerpt)
            : ($data['id_excerpt'] ?? '');

        $enSlug = Str::slug($enTitle);

        if ($enSlug === '') {
            $enSlug = Str::slug($data['id_title'] . '-en-' . now()->timestamp);
        }

        $this->validateUniqueSlug('id', $idSlug);
        $this->validateUniqueSlug('en', $enSlug);

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
                    'images/news',
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

            NewsTranslation::create([
                'news_id' => $news->id,
                'locale' => 'en',
                'title' => $enTitle,
                'slug' => $enSlug,
                'excerpt' => $enExcerpt ?: null,
                'content' => $translatedContent,
                'content_blocks' => $translatedBlocks,
            ]);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    if (! $image) {
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
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            if (function_exists('news_log')) {
                news_log($news->id, 'created', 'News dibuat oleh writer sebagai draft.');
            }

            DB::commit();

            return redirect()
                ->route('writer.news.edit', $news)
                ->with('success', 'News berhasil disimpan sebagai draft.');
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

        $idSlug = trim((string) ($data['id_slug'] ?? '')) !== ''
            ? trim((string) $data['id_slug'])
            : Str::slug($data['id_title']);

        if ($idSlug === '') {
            $idSlug = Str::slug($data['id_title'] . '-' . $news->id);
        }

        $blocksId = $this->normalizeBlocks($request, $uploader);
        $contentId = $this->blocksToHtml($blocksId);

        $translatedTitle = $translator->translateText($data['id_title'], 'id', 'en');
        $translatedExcerpt = $translator->translateText($data['id_excerpt'] ?? '', 'id', 'en');
        $translatedBlocks = $translator->translateBlocks($blocksId, 'id', 'en');
        $translatedContent = $this->blocksToHtml($translatedBlocks);

        $enTitle = trim((string) $translatedTitle) !== ''
            ? trim((string) $translatedTitle)
            : $data['id_title'];

        $enExcerpt = trim((string) $translatedExcerpt) !== ''
            ? trim((string) $translatedExcerpt)
            : ($data['id_excerpt'] ?? '');

        $enSlug = Str::slug($enTitle);

        if ($enSlug === '') {
            $enSlug = Str::slug($data['id_title'] . '-en-' . $news->id);
        }

        $this->validateUniqueSlug('id', $idSlug, $news->id);
        $this->validateUniqueSlug('en', $enSlug, $news->id);

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
                    'images/news',
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

            NewsTranslation::updateOrCreate(
                ['news_id' => $news->id, 'locale' => 'en'],
                [
                    'title' => $enTitle,
                    'slug' => $enSlug,
                    'excerpt' => $enExcerpt ?: null,
                    'content' => $translatedContent,
                    'content_blocks' => $translatedBlocks,
                ]
            );

            if ($request->hasFile('gallery_images')) {
                $currentMax = (int) $news->images()->max('sort_order');

                foreach ($request->file('gallery_images') as $index => $image) {
                    if (! $image) {
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

            if (function_exists('news_log')) {
                news_log($news->id, 'updated', 'News diperbarui oleh writer.');
            }

            DB::commit();

            return redirect()
                ->route('writer.news.edit', $news)
                ->with('success', 'Perubahan news berhasil disimpan.');
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
                'max:4096',
            ],

            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:4096',
            ],

            'id_title' => ['required', 'string', 'max:190'],
            'id_slug' => ['nullable', 'string', 'max:190'],
            'id_excerpt' => ['nullable', 'string', 'max:350'],

            'blocks' => ['nullable', 'array'],
            'blocks.*.type' => ['nullable', 'in:heading,text,image'],
            'blocks.*.title' => ['nullable', 'string'],
            'blocks.*.body' => ['nullable', 'string'],
            'blocks.*.caption' => ['nullable', 'string'],
            'blocks.*.existing_image' => ['nullable', 'string'],

            'block_images' => ['nullable', 'array'],
            'block_images.*' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:4096',
            ],
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
                $imagePath = $block['existing_image'] ?? null;

                if ($request->hasFile("block_images.$index")) {
                    $imagePath = $uploader->upload(
                        $request->file("block_images.$index"),
                        'images/news/blocks',
                        2
                    );
                }

                if ($imagePath) {
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

    private function validateUniqueSlug(string $locale, string $slug, ?int $excludeNewsId = null): void
    {
        $query = NewsTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug);

        if ($excludeNewsId) {
            $query->where('news_id', '!=', $excludeNewsId);
        }

        if ($query->exists()) {
            abort(422, "Slug ({$slug}) sudah dipakai untuk locale {$locale}.");
        }
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