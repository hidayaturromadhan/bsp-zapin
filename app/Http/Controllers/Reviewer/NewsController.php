<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\NewsTranslation;
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
        $q = trim((string) $request->query('q'));
        $status = trim((string) $request->query('status'));

        $news = News::query()
            ->with([
                'category',
                'author',
                'reviewer',
                'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
                'images',
                'logs.user',
            ])
            ->withoutTjsl()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('translations', function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%");
                });
            })
            ->when($status !== '', fn ($qr) => $qr->where('status', $status))
            ->orderByRaw("
                CASE status
                    WHEN 'in_review' THEN 1
                    WHEN 'rejected' THEN 2
                    WHEN 'published' THEN 3
                    WHEN 'draft' THEN 4
                    WHEN 'archived' THEN 5
                    ELSE 6
                END
            ")
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('reviewer.news.index', compact('news', 'q', 'status'));
    }

    public function edit(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images',
            'category',
            'author',
            'reviewer',
            'logs.user',
        ]);

        $tId = $news->translations->firstWhere('locale', 'id')
            ?? new NewsTranslation(['locale' => 'id']);

        $tEn = $news->translations->firstWhere('locale', 'en')
            ?? new NewsTranslation(['locale' => 'en']);

        $blocks = old('blocks', $tId->content_blocks ?: [
            ['type' => 'heading', 'title' => ''],
            ['type' => 'text', 'body' => ''],
        ]);

        return view('reviewer.news.edit', compact('news', 'tId', 'tEn', 'blocks'));
    }

    public function update(
        Request $request,
        News $news,
        NewsAutoTranslator $translator,
        PublicImageUploader $uploader
    ) {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $data = $request->validate([
            'published_at' => ['nullable', 'date'],
            'review_note' => ['nullable', 'string'],

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

        DB::transaction(function () use (
            $request,
            $news,
            $data,
            $uploader,
            $idSlug,
            $blocksId,
            $contentId,
            $enTitle,
            $enExcerpt,
            $enSlug,
            $translatedBlocks,
            $translatedContent
        ) {
            $payload = [
                'review_note' => array_key_exists('review_note', $data)
                    ? $data['review_note']
                    : $news->review_note,
            ];

            if (!empty($data['published_at'])) {
                $payload['published_at'] = Carbon::parse(
                    $data['published_at'],
                    config('app.timezone', 'Asia/Jakarta')
                );
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

            if (function_exists('news_log')) {
                news_log($news->id, 'updated', 'Revisi oleh reviewer');
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
                    if (!$image) {
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
            ->route('reviewer.news.edit', $news)
            ->with('success', 'Perubahan reviewer berhasil disimpan.');
    }

    public function review(Request $request, News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $userId = (int) $request->session()->get('user_id');
        abort_if($userId <= 0, 403);

        $data = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'review_note' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($data['action'] === 'approve') {
            $publishedAtInput = !empty($data['published_at'])
                ? Carbon::parse($data['published_at'], config('app.timezone', 'Asia/Jakarta'))
                : $news->published_at;

            if (!$publishedAtInput) {
                return back()
                    ->withErrors(['published_at' => 'Tanggal publish wajib diisi oleh writer atau reviewer saat approve.'])
                    ->withInput();
            }

            $news->update([
                'status' => 'published',
                'published_at' => $publishedAtInput,
                'is_visible' => true,
                'reviewed_by' => $userId,
                'reviewed_at' => now(),
                'review_note' => $data['review_note'] ?? null,
            ]);

            if (function_exists('news_log')) {
                news_log($news->id, 'approved', $data['review_note'] ?? null);
            }

            return back()->with('success', 'Berita berhasil di-approve dan akan tayang mengikuti jadwal publish yang tersimpan.');
        }

        $news->update([
            'status' => 'rejected',
            'is_visible' => false,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'review_note' => $data['review_note'] ?? null,
        ]);

        if (function_exists('news_log')) {
            news_log($news->id, 'rejected', $data['review_note'] ?? null);
        }

        return back()->with('success', 'Berita berhasil di-reject.');
    }

    public function logs(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'logs.user',
        ]);

        return view('reviewer.news.logs', compact('news'));
    }

    public function destroy(News $news, PublicImageUploader $uploader)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        if (function_exists('news_log')) {
            news_log($news->id, 'deleted', 'Berita dihapus oleh reviewer');
        }

        $uploader->delete($news->featured_image);

        foreach ($news->images as $image) {
            $uploader->delete($image->image_path);
        }

        $news->delete();

        return redirect()
            ->route('reviewer.news.index')
            ->with('success', 'Berita berhasil dihapus.');
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
}