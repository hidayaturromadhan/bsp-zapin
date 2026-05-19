<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function mediaPublikasi(Request $request, string $locale)
    {
        $locale = $this->normalizeLocale($locale);

        $q = trim((string) $request->query('q', ''));
        $year = trim((string) $request->query('year', ''));
        $sort = trim((string) $request->query('sort', 'latest'));

        if (! in_array($sort, ['latest', 'oldest'], true)) {
            $sort = 'latest';
        }

        $newsQuery = $this->basePublishedNewsQuery($locale)
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('translations', function ($translationQuery) use ($q) {
                    $translationQuery
                        ->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                });
            })
            ->when($year !== '' && preg_match('/^\d{4}$/', $year), function ($query) use ($year) {
                $query->whereYear('published_at', $year);
            });

        if ($sort === 'oldest') {
            $newsQuery
                ->orderBy('published_at')
                ->orderBy('id');
        } else {
            $newsQuery
                ->orderByDesc('published_at')
                ->orderByDesc('id');
        }

        $news = $newsQuery
            ->paginate(4)
            ->withQueryString();

        $recentPosts = $this->basePublishedNewsQuery($locale)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(function ($item) use ($locale) {
                $translation = $item->getTranslationByLocale($locale);

                $item->display_title = $translation?->title ?? 'News';
                $item->display_slug = $translation?->slug;
                $item->display_excerpt = $translation?->excerpt;
                $item->display_image_url = $this->resolvePublicAssetUrl($item->featured_image ?: 'images/logo.png');

                return $item;
            });

        $years = News::query()
            ->publicPublished()
            ->withoutTjsl()
            ->select(DB::raw('YEAR(published_at) as publish_year'))
            ->whereNotNull('published_at')
            ->groupBy(DB::raw('YEAR(published_at)'))
            ->orderByDesc(DB::raw('YEAR(published_at)'))
            ->pluck('publish_year')
            ->filter()
            ->values();

        $metaTitle = $locale === 'id'
            ? 'Media & Publikasi - BSP Zapin'
            : 'Media & Publications - BSP Zapin';

        if ($q !== '') {
            $metaTitle = ($locale === 'id' ? 'Hasil Pencarian: ' : 'Search Results: ') . $q . ' - BSP Zapin';
        }

        if ($year !== '') {
            $metaTitle = ($locale === 'id' ? 'Media & Publikasi Tahun ' : 'Media & Publications ') . $year . ' - BSP Zapin';
        }

        $metaDescription = $locale === 'id'
            ? 'Daftar berita terbaru, publikasi resmi, dan informasi perusahaan PT Bumi Siak Pusako Zapin.'
            : 'Latest news, official publications, and company information from PT Bumi Siak Pusako Zapin.';

        return view('web.media_publikasi.index', [
            'news' => $news,
            'recentPosts' => $recentPosts,
            'years' => $years,
            'q' => $q,
            'year' => $year,
            'sort' => $sort,
            'locale' => $locale,
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'metaImage' => asset('images/logo.png'),
        ]);
    }

    public function show(Request $request, string $locale, string $slug)
    {
        $locale = $this->normalizeLocale($locale);

        $news = $this->findPublishedNewsBySlug($locale, $slug);

        if (! $news && $locale === 'en') {
            $news = $this->findPublishedNewsBySlug('id', $slug);
        }

        if (! $news && $locale === 'id') {
            $news = $this->findPublishedNewsBySlug('en', $slug);
        }

        abort_if(! $news, 404);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $translation = $news->getTranslationByLocale($locale);

        abort_if(! $translation, 404);

        /*
        |--------------------------------------------------------------------------
        | FIX & OPTIMIZE DETAIL NEWS
        |--------------------------------------------------------------------------
        | Semua proses berat dipindahkan ke controller:
        | - normalisasi URL gambar content
        | - normalisasi URL featured image
        | - normalisasi URL gallery image
        | - query related news
        |--------------------------------------------------------------------------
        */
        $contentHtml = $this->normalizeContentImageSources((string) ($translation->content ?? ''));

        $coverImage = $news->featured_image
            ? $this->resolvePublicAssetUrl($news->featured_image)
            : asset('images/logo.png');

        $news->images->each(function ($image) {
            $image->resolved_image_url = $this->resolvePublicAssetUrl($image->image_path);
        });

        $relatedNews = $this->relatedNewsForDetail($news, $locale);

        $metaTitleBase = $translation->title ?: 'BSP Zapin';

        $metaTitle = $metaTitleBase . ' - BSP Zapin';

        $metaDescription = $translation->excerpt
            ?: ($contentHtml
                ? Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($contentHtml))), 160)
                : ($locale === 'id'
                    ? 'Informasi berita PT Bumi Siak Pusako Zapin.'
                    : 'News information of PT Bumi Siak Pusako Zapin.'));

        $metaImage = $coverImage;

        return view('web.news.show', [
            'news' => $news,
            'translation' => $translation,
            'contentHtml' => $contentHtml,
            'coverImage' => $coverImage,
            'relatedNews' => $relatedNews,
            'locale' => $locale,
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'metaImage' => $metaImage,
        ]);
    }

    private function basePublishedNewsQuery(string $locale)
    {
        $locales = array_values(array_unique([$locale, 'id', 'en']));

        return News::query()
            ->with([
                'translations' => function ($query) use ($locales) {
                    $query->whereIn('locale', $locales);
                },
                'category',
                'images',
            ])
            ->publicPublished()
            ->withoutTjsl()
            ->whereHas('translations', function ($query) use ($locales) {
                $query->whereIn('locale', $locales)
                    ->whereNotNull('slug')
                    ->where('slug', '!=', '')
                    ->whereNotNull('title')
                    ->where('title', '!=', '');
            });
    }

    private function findPublishedNewsBySlug(string $locale, string $slug): ?News
    {
        return News::query()
            ->with([
                'translations',
                'category',
                'images',
            ])
            ->publicPublished()
            ->withoutTjsl()
            ->whereHas('translations', function ($query) use ($locale, $slug) {
                $query->where('locale', $locale)
                    ->where('slug', $slug);
            })
            ->first();
    }

    private function relatedNewsForDetail(News $news, string $locale)
    {
        $locales = array_values(array_unique([$locale, 'id', 'en']));

        return News::query()
            ->with([
                'category',
                'translations' => function ($query) use ($locales) {
                    $query->whereIn('locale', $locales);
                },
            ])
            ->publicPublished()
            ->withoutTjsl()
            ->where('id', '!=', $news->id)
            ->when($news->news_category_id, function ($query) use ($news) {
                $query->orderByRaw('CASE WHEN news_category_id = ? THEN 0 ELSE 1 END', [$news->news_category_id]);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(function ($item) use ($locale) {
                $translation = method_exists($item, 'getTranslationByLocale')
                    ? $item->getTranslationByLocale($locale)
                    : $item->translations->firstWhere('locale', $locale);

                if (! $translation) {
                    $translation = $item->translations->firstWhere('locale', 'id')
                        ?: $item->translations->firstWhere('locale', 'en')
                        ?: $item->translations->first();
                }

                $item->display_title = $translation?->title ?? 'News';
                $item->display_slug = $translation?->slug;
                $item->display_image_url = $this->resolvePublicAssetUrl($item->featured_image ?: 'images/logo.png');

                return $item;
            })
            ->filter(function ($item) {
                return filled($item->display_slug);
            })
            ->values();
    }

    private function normalizeLocale(string $locale): string
    {
        return in_array($locale, ['id', 'en'], true) ? $locale : 'id';
    }

    private function normalizeContentImageSources(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        return preg_replace_callback(
            '/<img\b([^>]*?)\bsrc=(["\'])(.*?)\2([^>]*)>/i',
            function ($matches) {
                $before = $matches[1] ?? '';
                $quote = $matches[2] ?? '"';
                $src = $matches[3] ?? '';
                $after = $matches[4] ?? '';

                $fixedSrc = $this->resolvePublicAssetUrl($src);

                return '<img' . $before . 'src=' . $quote . e($fixedSrc) . $quote . $after . '>';
            },
            $html
        ) ?: $html;
    }

    private function resolvePublicAssetUrl(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return asset('images/logo.png');
        }

        $path = html_entity_decode($path, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $path = str_replace('\\', '/', $path);

        if (
            str_starts_with($path, 'data:') ||
            str_starts_with($path, 'blob:')
        ) {
            return $path;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            $parsedPath = parse_url($path, PHP_URL_PATH);

            if (is_string($parsedPath) && $parsedPath !== '') {
                $cleanParsedPath = ltrim($parsedPath, '/');

                if (
                    str_starts_with($cleanParsedPath, 'images/') ||
                    str_starts_with($cleanParsedPath, 'storage/') ||
                    str_starts_with($cleanParsedPath, 'uploads/') ||
                    str_starts_with($cleanParsedPath, 'public/images/') ||
                    str_starts_with($cleanParsedPath, 'public/storage/')
                ) {
                    return asset($this->cleanPublicAssetPath($cleanParsedPath));
                }
            }

            return $path;
        }

        return asset($this->cleanPublicAssetPath($path));
    }

    private function cleanPublicAssetPath(string $path): string
    {
        $path = trim($path);
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#/+#', '/', $path) ?: $path;
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        if (str_starts_with($path, 'public_html/')) {
            $path = substr($path, strlen('public_html/'));
        }

        return $path;
    }
}