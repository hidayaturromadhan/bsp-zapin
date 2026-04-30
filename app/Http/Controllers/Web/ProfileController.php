<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageTranslation;

class ProfileController extends Controller
{
    public function index(string $locale)
    {
        $menu = Page::query()
            ->where('menu_group', 'profil')
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($page) use ($locale) {
                $translation = $page->translations->firstWhere('locale', $locale)
                    ?? $page->translations->firstWhere('locale', 'id');

                if (! $translation) {
                    return null;
                }

                $translation->setRelation('page', $page);

                return $translation;
            })
            ->filter()
            ->values();

        if ($menu->isEmpty()) {
            abort(404);
        }

        $page = $menu->first();

        return $this->renderProfilePage($page, $menu, $locale);
    }

    public function show(string $locale, string $slug)
    {
        $translation = PageTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->whereHas('page', function ($q) {
                $q->where('menu_group', 'profil')
                    ->where('is_active', true);
            })
            ->with(['page.translations'])
            ->first();

        if (! $translation && $locale === 'en') {
            $translation = PageTranslation::query()
                ->where('locale', 'id')
                ->where('slug', $slug)
                ->whereHas('page', function ($q) {
                    $q->where('menu_group', 'profil')
                        ->where('is_active', true);
                })
                ->with(['page.translations'])
                ->first();
        }

        abort_if(! $translation, 404);

        $pageModel = $translation->page;

        $page = $translation;

        if ($pageModel) {
            $page->setRelation('page', $pageModel);
        }

        $menu = Page::query()
            ->where('menu_group', 'profil')
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($item) use ($locale) {
                $itemTranslation = $item->translations->firstWhere('locale', $locale)
                    ?? $item->translations->firstWhere('locale', 'id');

                if (! $itemTranslation) {
                    return null;
                }

                $itemTranslation->setRelation('page', $item);

                return $itemTranslation;
            })
            ->filter()
            ->values();

        return $this->renderProfilePage($page, $menu, $locale);
    }

    private function renderProfilePage($page, $menu, string $locale)
    {
        $structuredContent = $this->decodeStructuredContent($page?->content);
        $template = $structuredContent['template'] ?? null;

        return view('web.profile', [
            'page' => $page,
            'menu' => $menu,
            'locale' => $locale,
            'structuredContent' => $structuredContent,
            'isAboutPage' => $template === 'about_us',
            'isVisionMissionPage' => $template === 'vision_mission',
            'isHistoryPage' => $template === 'history',
            'isShareholderPage' => $template === 'shareholder',
            'isOrganizationStructurePage' => $template === 'organization_structure',
            'isHsePage' => $template === 'hse',
        ]);
    }

    private function decodeStructuredContent(?string $content): array
    {
        if (! $content) {
            return [];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }
}