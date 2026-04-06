<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function show(string $locale, string $slug)
    {
        $page = Page::query()
            ->with('translations')
            ->where('is_active', true)
            ->whereHas('translations', function ($q) use ($locale, $slug) {
                $q->where('locale', $locale)
                    ->where('slug', $slug);
            })
            ->first();

        if (! $page && $locale === 'en') {
            $page = Page::query()
                ->with('translations')
                ->where('is_active', true)
                ->whereHas('translations', function ($q) use ($slug) {
                    $q->where('locale', 'id')
                        ->where('slug', $slug);
                })
                ->first();
        }

        abort_if(! $page, 404);

        $translation = method_exists($page, 'getTranslationByLocale')
            ? $page->getTranslationByLocale($locale)
            : ($page->translations->firstWhere('locale', $locale)
                ?? $page->translations->firstWhere('locale', 'id'));

        $metaTitle = $translation?->title
            ? $translation->title . ' - BSP Zapin'
            : 'BSP Zapin';

        $metaDescription = $translation?->content
            ? Str::limit(trim(strip_tags($translation->content)), 160)
            : ($locale === 'id'
                ? 'Informasi halaman PT Bumi Siak Pusako Zapin.'
                : 'Page information of PT Bumi Siak Pusako Zapin.');

        $metaImage = $page->cover_image
            ? asset($page->cover_image)
            : asset('images/logo.png');

        return view('web.page', [
            'page' => $page,
            'translation' => $translation,
            'locale' => $locale,
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'metaImage' => $metaImage,
        ]);
    }
}