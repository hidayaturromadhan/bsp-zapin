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
                'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
                'images',
                'logs.user',
            ])
            ->withoutTjsl()
            ->forWriter($userId)
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('translations', function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($qr) => $qr->where('status', $status))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('writer.news.index', [
            'news' => $news,
            'q' => $q,
            'status' => $status,
            'statuses' => News::statuses(),
        ]);
    }

    public function create()
    {
        $categories = NewsCategory::query()
            ->where('is_active', true)
            ->where('slug', '!=', 'tjsl')
            ->orderBy('sort_order')
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
            $idSlug = Str::slug($data['id_title'] . '-' . time());
        }

        $blocksId = $this->normalizeBlocks($request, $uploader);
        $contentId = $this->blocksToHtml($blocksId);

        $translatedTitle = $translator->translateText($data['id_title'], 'id', 'en');
        $translatedExcerpt = $translator->translateText($data['id_excerpt'] ?? '', 'id', 'en');
        $translatedBlocks = $translator->translateBlocks($blocksId, 'id', 'en');
        $translatedContent = $this->blocksToHtml($translatedBlocks);

        $enTitle = trim($translatedTitle) !== '' ? trim($translatedTitle) : $data['id_title'];
        $enExcerpt = trim($translatedExcerpt) !== '' ? trim($translatedExcerpt) : ($data['id_excerpt'] ?? '');
        $enSlug = Str::slug($enTitle);

        if ($enSlug === '') {
            $enSlug = Str::slug($data['id_title'] . '-en-' . time());
        }

        $this->validateUniqueSlug('id', $idSlug);
        $this->validateUniqueSlug('en', $enSlug);

        try {
            DB::beginTransaction();

            $payload = [
                'news_category_id' => $data['news_category_id'],
                'status' => News::STATUS_DRAFT,
                'published_at' => null,
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

            $createdNews = News::create($payload);

            NewsTranslation::create([
                'news_id' => $createdNews->id,
                'locale' => 'id',
                'title' => $data['id_title'],
                'slug' => $idSlug,
                'excerpt' => $data['id_excerpt'] ?? null,
                'content' => $contentId,
                'content_blocks' => $blocksId,
            ]);

            NewsTranslation::create([
                'news_id' => $createdNews->id,
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
                        'news_id' => $createdNews->id,
                        'image_path' => $uploader->upload(
                            $image,
                            'images/news/gallery',
                            2
                        ),
                        'caption' => null,
                        'sort_order' => $index,
                    ]);
                }
            }

            if (function_exists('news_log')) {
                news_log($createdNews->id, 'created', 'Berita dibuat dan disimpan sebagai draft oleh writer');
            }

            DB::commit();

            return redirect()
                ->route('writer.news.edit', $createdNews)
                ->with('success', 'Berita berhasil disimpan sebagai draft.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer News store failed', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan berita. Silakan cek kembali data yang diinput.');
        }
    }

    public function show(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'logs.user',
        ]);

        return view('writer.news.show', [
            'newsItem' => $news,
            'statuses' => News::statuses(),
        ]);
    }

    public function edit(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'category',
            'logs.user',
        ]);

        $categories = NewsCategory::query()
            ->where('is_active', true)
            ->where('slug', '!=', 'tjsl')
            ->orderBy('sort_order')
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

        $enTitle = trim($translatedTitle) !== '' ? trim($translatedTitle) : $data['id_title'];
        $enExcerpt = trim($translatedExcerpt) !== '' ? trim($translatedExcerpt) : ($data['id_excerpt'] ?? '');
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
                'status' => $news->status ?: News::STATUS_DRAFT,
                'is_visible' => $news->status === News::STATUS_PUBLISHED,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
            ];

            if ($news->status !== News::STATUS_PUBLISHED) {
                $payload['published_at'] = null;
            }

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
                news_log($news->id, 'updated', 'Berita diperbarui oleh writer');
            }

            DB::commit();

            return redirect()
                ->route('writer.news.edit', $news)
                ->with('success', 'Perubahan berita berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer News update failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui berita. Silakan cek kembali data yang diinput.');
        }
    }

    public function preview(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'author',
        ]);

        return view('writer.news.preview', [
            'newsItem' => $news,
            'locale' => 'id',
            'translation' => $news->getTranslationByLocale('id'),
        ]);
    }

    public function sendPreviewWhatsapp(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->loadMissing([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'category',
        ]);

        $translation = $news->getTranslationByLocale('id');

        if (! $translation || trim((string) $translation->title) === '') {
            return redirect()
                ->route('writer.news.edit', $news)
                ->with('error', 'Judul berita wajib diisi sebelum mengirim preview ke reviewer.');
        }

        $phone = config('services.news_whatsapp.reviewer');

        $waUrl = $this->makeWhatsappLink(
            $phone,
            $this->buildPreviewWhatsappMessage($news, $translation->title)
        );

        if (! $waUrl) {
            return redirect()
                ->route('writer.news.show', $news)
                ->with('error', 'Nomor WhatsApp reviewer belum dikonfigurasi di file .env.');
        }

        return redirect()->away($waUrl);
    }

    public function publish(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->loadMissing('translations');

        $translation = $news->getTranslationByLocale('id');

        if (! $translation || trim((string) $translation->title) === '') {
            return redirect()
                ->route('writer.news.edit', $news)
                ->with('error', 'Judul berita wajib diisi sebelum publish.');
        }

        try {
            $news->update([
                'status' => News::STATUS_PUBLISHED,
                'published_at' => now(),
                'is_visible' => true,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
            ]);

            if (function_exists('news_log')) {
                news_log($news->id, 'published', 'Berita dipublish oleh writer');
            }

            return redirect()
                ->route('writer.news.show', $news)
                ->with('success', 'Berita berhasil dipublish ke website publik.');
        } catch (\Throwable $e) {
            Log::error('Writer News publish failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()->with('error', 'Gagal publish berita.');
        }
    }

    public function unpublish(Request $request, News $news)
    {
        $this->authorizeWriter($request, $news);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        if ($news->status !== News::STATUS_PUBLISHED) {
            return back()->with('error', 'Berita ini belum dalam status published.');
        }

        try {
            $news->update([
                'status' => News::STATUS_DRAFT,
                'published_at' => null,
                'is_visible' => false,
            ]);

            if (function_exists('news_log')) {
                news_log($news->id, 'unpublished', 'Berita ditarik dari publik oleh writer');
            }

            return redirect()
                ->route('writer.news.show', $news)
                ->with('success', 'Berita berhasil ditarik dari website publik dan kembali menjadi draft.');
        } catch (\Throwable $e) {
            Log::error('Writer News unpublish failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()->with('error', 'Gagal unpublish berita.');
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
                news_log($news->id, 'deleted', 'Berita dihapus oleh writer');
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
                ->with('success', 'Berita berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Writer News destroy failed', [
                'message' => $e->getMessage(),
                'news_id' => $news->id,
                'user_id' => $request->session()->get('user_id'),
            ]);

            return back()->with('error', 'Gagal menghapus berita.');
        }
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

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
            'block_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function normalizeBlocks(Request $request, PublicImageUploader $uploader): array
    {
        $blocks = $request->input('blocks', []);
        $result = [];

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
            }

            if ($type === 'text') {
                $body = trim((string) ($block['body'] ?? ''));
                if ($body !== '') {
                    $result[] = [
                        'type' => 'text',
                        'body' => $body,
                    ];
                }
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
            if (($block['type'] ?? null) === 'heading') {
                $html .= '<h2>' . e($block['title'] ?? '') . '</h2>';
            }

            if (($block['type'] ?? null) === 'text') {
                $paragraphs = preg_split("/\n{2,}/", trim((string) ($block['body'] ?? '')));
                foreach ($paragraphs as $paragraph) {
                    $paragraph = trim($paragraph);
                    if ($paragraph !== '') {
                        $html .= '<p>' . nl2br(e($paragraph)) . '</p>';
                    }
                }
            }

            if (($block['type'] ?? null) === 'image' && ! empty($block['image'])) {
                $caption = trim((string) ($block['caption'] ?? ''));
                $html .= '<figure>';
                $html .= '<img src="' . asset($block['image']) . '" alt="' . e($caption) . '">';
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
        $q = NewsTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug);

        if ($excludeNewsId) {
            $q->where('news_id', '!=', $excludeNewsId);
        }

        if ($q->exists()) {
            abort(422, "Slug ({$slug}) sudah dipakai untuk locale {$locale}.");
        }
    }

    private function buildPreviewWhatsappMessage(News $news, string $title): string
    {
        $previewUrl = route('login', [
            'redirect' => route('reviewer.news.preview', $news),
        ]);

        return "Assalamu’alaikum Warahmatullahi Wabarakatuh, Pak MTQ.\n\n"
            . "Mohon izin, saya ingin meminta waktu Bapak untuk melakukan peninjauan terhadap draft berita sebelum dipublikasikan pada website resmi.\n\n"
            . "Berikut detail berita:\n"
            . "• Judul Berita : {$title}\n"
            . "• Kategori     : " . ($news->category?->name ?? '-') . "\n"
            . "• Status       : {$news->status_label}\n\n"
            . "Silakan mengakses preview melalui tautan berikut:\n"
            . "{$previewUrl}\n\n"
            . "Catatan:\n"
            . "Link di atas akan mengarahkan Bapak ke halaman login terlebih dahulu. Setelah login sebagai reviewer, Bapak akan langsung diarahkan ke halaman preview berita tersebut.\n\n"
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

    private function authorizeWriter(Request $request, News $news): void
    {
        $userId = (int) $request->session()->get('user_id');

        abort_if($userId <= 0, 403);
        abort_if((int) $news->created_by !== $userId, 403);
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