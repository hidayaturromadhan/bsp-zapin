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
                $qr->whereHas('translations', function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($qr) => $qr->where('status', $status))
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

        $data = $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'published_at' => ['nullable', 'date'],
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

        $category = NewsCategory::query()->findOrFail($data['news_category_id']);
        abort_if($category->slug === 'tjsl', 422, 'Kategori TJSL tidak dikelola dari modul news.');

        $idSlug = trim((string) ($data['id_slug'] ?? '')) !== ''
            ? trim((string) $data['id_slug'])
            : Str::slug($data['id_title']);

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
            $enSlug = Str::slug($data['id_title'] . '-en');
        }

        $this->validateUniqueSlug('id', $idSlug);
        $this->validateUniqueSlug('en', $enSlug);

        DB::transaction(function () use (
            $request,
            $data,
            $uploader,
            $user,
            $idSlug,
            $blocksId,
            $contentId,
            $enTitle,
            $enSlug,
            $enExcerpt,
            $translatedBlocks,
            $translatedContent
        ) {
            $payload = [
                'news_category_id' => $data['news_category_id'],
                'status' => 'in_review',
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

            if (function_exists('news_log')) {
                news_log($news->id, 'created', 'Berita dibuat oleh writer');
                news_log($news->id, 'submitted', 'Berita dikirim ke reviewer');
            }

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
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        return redirect()
            ->route('writer.news.index')
            ->with('success', 'Berita berhasil dibuat dan dikirim ke reviewer.');
    }

    public function edit(Request $request, News $news)
    {
        $userId = (int) $request->session()->get('user_id');
        abort_if($userId <= 0, 403);
        abort_if((int) $news->created_by !== $userId, 403);
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
        $userId = (int) $request->session()->get('user_id');
        abort_if($userId <= 0, 403);
        abort_if((int) $news->created_by !== $userId, 403);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $data = $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'published_at' => ['nullable', 'date'],
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

        $category = NewsCategory::query()->findOrFail($data['news_category_id']);
        abort_if($category->slug === 'tjsl', 422, 'Kategori TJSL tidak dikelola dari modul news.');

        $idSlug = trim((string) ($data['id_slug'] ?? '')) !== ''
            ? trim((string) $data['id_slug'])
            : Str::slug($data['id_title']);

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
            $enSlug = Str::slug($data['id_title'] . '-en');
        }

        $this->validateUniqueSlug('id', $idSlug, $news->id);
        $this->validateUniqueSlug('en', $enSlug, $news->id);

        DB::transaction(function () use (
            $request,
            $news,
            $data,
            $uploader,
            $idSlug,
            $blocksId,
            $contentId,
            $enTitle,
            $enSlug,
            $enExcerpt,
            $translatedBlocks,
            $translatedContent
        ) {
            $payload = [
                'news_category_id' => $data['news_category_id'],
                'status' => 'in_review',
                'published_at' => !empty($data['published_at'])
                    ? Carbon::parse($data['published_at'], config('app.timezone', 'Asia/Jakarta'))
                    : null,
                'is_visible' => false,
                'reviewed_by' => null,
                'reviewed_at' => null,
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

            if (function_exists('news_log')) {
                news_log($news->id, 'updated', 'Writer memperbarui berita');
                news_log($news->id, 'submitted', 'Perubahan dikirim ulang ke reviewer');
            }

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
        });

        return redirect()
            ->route('writer.news.edit', $news)
            ->with('success', 'Perubahan berhasil disimpan dan dikirim ulang ke reviewer.');
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

            if (($block['type'] ?? null) === 'image' && !empty($block['image'])) {
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

    private function sessionUser(Request $request): ?User
    {
        $userId = (int) $request->session()->get('user_id');

        if ($userId <= 0) {
            return null;
        }

        return User::find($userId);
    }
}