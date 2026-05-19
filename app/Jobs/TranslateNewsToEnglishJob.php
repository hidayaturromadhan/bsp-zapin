<?php

namespace App\Jobs;

use App\Models\News;
use App\Models\NewsTranslation;
use App\Services\NewsAutoTranslator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TranslateNewsToEnglishJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 180;

    public function __construct(
        public int $newsId
    ) {
        //
    }

    public function handle(NewsAutoTranslator $translator): void
    {
        $news = News::query()
            ->with([
                'translations' => function ($query) {
                    $query->whereIn('locale', ['id', 'en']);
                },
            ])
            ->find($this->newsId);

        if (! $news) {
            Log::warning('TranslateNewsToEnglishJob skipped: news not found.', [
                'news_id' => $this->newsId,
            ]);

            return;
        }

        $translationId = $news->translations->firstWhere('locale', 'id');

        if (! $translationId) {
            Log::warning('TranslateNewsToEnglishJob skipped: ID translation not found.', [
                'news_id' => $this->newsId,
            ]);

            return;
        }

        $titleId = trim((string) ($translationId->title ?? ''));
        $excerptId = trim((string) ($translationId->excerpt ?? ''));

        $blocksId = is_array($translationId->content_blocks)
            ? $translationId->content_blocks
            : [];

        if ($titleId === '') {
            Log::warning('TranslateNewsToEnglishJob skipped: ID title empty.', [
                'news_id' => $this->newsId,
            ]);

            return;
        }

        try {
            $translatedTitle = $translator->translateText($titleId, 'id', 'en');
            $translatedExcerpt = $translator->translateText($excerptId, 'id', 'en');
            $translatedBlocks = $translator->translateBlocks($blocksId, 'id', 'en');

            $enTitle = trim((string) $translatedTitle) !== ''
                ? trim((string) $translatedTitle)
                : $titleId;

            $enExcerpt = trim((string) $translatedExcerpt) !== ''
                ? trim((string) $translatedExcerpt)
                : $excerptId;

            $translatedContent = $this->blocksToHtml($translatedBlocks);

            $currentEn = NewsTranslation::query()
                ->where('news_id', $news->id)
                ->where('locale', 'en')
                ->first();

            $existingSlug = trim((string) ($currentEn?->slug ?? ''));

            $enSlug = $existingSlug !== ''
                ? $existingSlug
                : $this->makeUniqueSlug(
                    'en',
                    $this->makeSlug(null, $enTitle, 'en-' . $news->id),
                    $news->id
                );

            DB::transaction(function () use (
                $news,
                $enTitle,
                $enSlug,
                $enExcerpt,
                $translatedContent,
                $translatedBlocks
            ) {
                NewsTranslation::updateOrCreate(
                    [
                        'news_id' => $news->id,
                        'locale' => 'en',
                    ],
                    [
                        'title' => $enTitle,
                        'slug' => $enSlug,
                        'excerpt' => $enExcerpt !== '' ? $enExcerpt : null,
                        'content' => $translatedContent,
                        'content_blocks' => $translatedBlocks,
                    ]
                );
            });

            /*
            |--------------------------------------------------------------------------
            | PENTING
            |--------------------------------------------------------------------------
            | Jangan panggil news_log() di Job.
            | Queue berjalan tanpa session user, sehingga user_id akan null dan gagal
            | insert ke news_audit_logs.
            |--------------------------------------------------------------------------
            */
            Log::info('TranslateNewsToEnglishJob completed.', [
                'news_id' => $news->id,
                'title_id' => $titleId,
                'title_en' => $enTitle,
            ]);
        } catch (\Throwable $e) {
            Log::error('TranslateNewsToEnglishJob failed.', [
                'news_id' => $this->newsId,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
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
}