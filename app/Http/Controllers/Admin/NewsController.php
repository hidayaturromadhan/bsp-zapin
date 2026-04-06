<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentVersion;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsImage;
use App\Models\NewsTranslation;
use App\Services\ContentVersionService;
use App\Services\NewsAutoTranslator;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $cat = $request->query('cat');

        $categories = NewsCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $news = News::query()
            ->with([
                'category',
                'translations' => fn ($qr) => $qr->whereIn('locale', ['id', 'en']),
                'images',
            ])
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('translations', function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%");
                });
            })
            ->when($cat, fn ($qr) => $qr->where('news_category_id', $cat))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'categories', 'q', 'cat'));
    }

    public function create()
    {
        $categories = NewsCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $blocks = old('blocks', [
            ['type' => 'heading', 'title' => ''],
            ['type' => 'text', 'body' => ''],
        ]);

        return view('admin.news.create', compact('categories', 'blocks'));
    }

    public function store(Request $request, NewsAutoTranslator $translator, PublicImageUploader $uploader)
    {
        $data = $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],

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
                'status' => $data['status'],
                'published_at' => $data['published_at'] ?? null,
                'is_featured' => (bool) ($data['is_featured'] ?? false),
                'is_visible' => (bool) ($data['is_visible'] ?? false),
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
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(News $news)
    {
        $news->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
        ]);

        $categories = NewsCategory::where('is_active', true)
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

        return view('admin.news.edit', compact('news', 'categories', 'tId', 'tEn', 'blocks'));
    }

    public function update(
        Request $request,
        News $news,
        ContentVersionService $versioner,
        NewsAutoTranslator $translator,
        PublicImageUploader $uploader
    ) {
        $data = $request->validate([
            'news_category_id' => ['required', 'exists:news_categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],

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

        $versioner->snapshotNews($news);

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
                'status' => $data['status'],
                'published_at' => $data['published_at'] ?? null,
                'is_featured' => (bool) ($data['is_featured'] ?? false),
                'is_visible' => (bool) ($data['is_visible'] ?? false),
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
        });

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diupdate.');
    }

    public function destroy(News $news, PublicImageUploader $uploader)
    {
        $uploader->delete($news->featured_image);

        foreach ($news->images as $image) {
            $uploader->delete($image->image_path);
        }

        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function versions(News $news)
    {
        $perPage = 10;

        $bundles = ContentVersion::query()
            ->where('entity_type', 'news')
            ->where('entity_id', $news->id)
            ->select('bundle_id')
            ->groupBy('bundle_id')
            ->orderByDesc(DB::raw('MAX(id)'))
            ->paginate($perPage);

        $bundleIds = $bundles->pluck('bundle_id');

        $versions = ContentVersion::query()
            ->whereIn('bundle_id', $bundleIds)
            ->orderByDesc('id')
            ->get()
            ->groupBy('bundle_id');

        return view('admin.news.versions', [
            'news' => $news,
            'bundles' => $bundles,
            'versions' => $versions,
        ]);
    }

    public function restoreVersion(News $news, int $version, ContentVersionService $svc)
    {
        $svc->snapshotNews($news);
        $svc->restoreNews($news, $version);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'Versi berhasil direstore.');
    }

    public function restoreBundle(News $news, string $bundle, ContentVersionService $svc)
    {
        $svc->snapshotNews($news);
        $svc->restoreNewsBundle($news, $bundle);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'Bundle berhasil direstore.');
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
}