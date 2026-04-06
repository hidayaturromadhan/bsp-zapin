<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function mediaPublikasi(Request $request, string $locale)
    {
        $news = $this->buildPublishedNewsQuery($locale)
            ->paginate(12)
            ->withQueryString();

        return view('web.media_publikasi.index', [
            'news' => $news,
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

    public function tjsl(Request $request, string $locale)
    {
        $news = $this->buildPublishedNewsQuery($locale)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'tjsl');
            })
            ->paginate(12)
            ->withQueryString();

        return view('web.tjsl.index', [
            'news' => $news,
            'locale' => $locale,
            'metaTitle' => $locale === 'id'
                ? 'TJSL - BSP Zapin'
                : 'TJSL / CSR - BSP Zapin',
            'metaDescription' => $locale === 'id'
                ? 'Informasi kegiatan Tanggung Jawab Sosial dan Lingkungan PT Bumi Siak Pusako Zapin.'
                : 'Information about CSR / TJSL activities of PT Bumi Siak Pusako Zapin.',
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

        $translation = $news->getTranslationByLocale($locale);

        $metaTitle = ($translation?->seo_title ?: $translation?->title)
            ? ($translation->seo_title ?: $translation->title) . ' - BSP Zapin'
            : 'BSP Zapin';

        $metaDescription = $translation?->seo_description
            ?: ($translation?->excerpt
                ?: ($translation?->content
                    ? Str::limit(trim(strip_tags($translation->content)), 160)
                    : ($locale === 'id'
                        ? 'Informasi berita PT Bumi Siak Pusako Zapin.'
                        : 'News information of PT Bumi Siak Pusako Zapin.')));

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

    private function buildPublishedNewsQuery(string $locale)
    {
        return News::query()
            ->with([
                'translations' => fn ($q) => $q->whereIn('locale', [$locale, 'id']),
                'category',
                'images',
            ])
            ->where('is_visible', true)
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    private function findPublishedNewsBySlug(string $locale, string $slug): ?News
    {
        return News::query()
            ->with([
                'translations',
                'category',
                'images',
            ])
            ->where('is_visible', true)
            ->where('status', 'published')
            ->whereHas('translations', function ($q) use ($locale, $slug) {
                $q->where('locale', $locale)
                    ->where('slug', $slug);
            })
            ->first();
    }
}