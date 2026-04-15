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
        $q = trim((string) $request->query('q', ''));
        $year = trim((string) $request->query('year', ''));
        $sort = trim((string) $request->query('sort', 'latest'));

        $newsQuery = $this->basePublishedNewsQuery($locale)
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('translations', function ($translationQuery) use ($q) {
                    $translationQuery
                        ->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                });
            })
            ->when($year !== '', function ($query) use ($year) {
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
            ->get();

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

        return view('web.media_publikasi.index', [
            'news' => $news,
            'recentPosts' => $recentPosts,
            'years' => $years,
            'q' => $q,
            'year' => $year,
            'sort' => $sort,
            'locale' => $locale,
            'metaTitle' => $locale === 'id'
                ? 'Media & Publikasi - BSP Zapin'
                : 'Media & Publications - BSP Zapin',
            'metaDescription' => $locale === 'id'
                ? 'Daftar berita terbaru, publikasi resmi, dan informasi perusahaan PT Bumi Siak Pusako Zapin.'
                : 'Latest news, official publications, and company information from PT Bumi Siak Pusako Zapin.',
            'metaImage' => asset('images/logo.png'),
        ]);
    }

    public function show(Request $request, string $locale, string $slug)
    {
        $news = $this->findPublishedNewsBySlug($locale, $slug);

        if (! $news && $locale === 'en') {
            $news = $this->findPublishedNewsBySlug('id', $slug);
        }

        abort_if(! $news, 404);
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $translation = $news->getTranslationByLocale($locale);

        $metaTitleBase = $translation?->seo_title
            ?? $translation?->title
            ?? 'BSP Zapin';

        $metaTitle = $metaTitleBase . ' - BSP Zapin';

        $metaDescription = $translation?->seo_description
            ?? $translation?->excerpt
            ?? ($translation?->content
                ? Str::limit(trim(strip_tags($translation->content)), 160)
                : ($locale === 'id'
                    ? 'Informasi berita PT Bumi Siak Pusako Zapin.'
                    : 'News information of PT Bumi Siak Pusako Zapin.'));

        $metaImage = $news->featured_image
            ? asset($news->featured_image)
            : asset('images/logo.png');

        return view('web.news.show', [
            'news' => $news,
            'translation' => $translation,
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
                $query->whereIn('locale', $locales);
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
}