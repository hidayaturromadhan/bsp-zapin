<?php

use App\Models\Menu;
use App\Models\News;
use App\Models\Page;

if (! function_exists('navbar_menus')) {
    function navbar_menus()
    {
        return Menu::whereNull('parent_id')
            ->where('is_active', 1)
            ->with([
                'children' => function ($q) {
                    $q->where('is_active', 1)
                        ->orderBy('sort_order');
                },
                'page.translations',
                'news.translations',
                'children.page.translations',
                'children.news.translations',
            ])
            ->orderBy('sort_order')
            ->get();
    }
}

if (! function_exists('menu_translation_for_locale')) {
    function menu_translation_for_locale($model, string $locale)
    {
        if (! $model || ! isset($model->translations)) {
            return null;
        }

        return $model->translations->firstWhere('locale', $locale)
            ?? $model->translations->firstWhere('locale', 'id');
    }
}

if (! function_exists('menu_url')) {
    function menu_url($menu, $locale)
    {
        if ($menu->type === 'page' && $menu->page) {
            $translation = menu_translation_for_locale($menu->page, $locale);

            if ($translation) {
                if ($menu->page->menu_group === 'profil') {
                    return route('profil.show', [
                        'locale' => $locale,
                        'slug' => $translation->slug,
                    ]);
                }

                return route('page.show', [
                    'locale' => $locale,
                    'slug' => $translation->slug,
                ]);
            }
        }

        if ($menu->type === 'news' && $menu->news) {
            $translation = menu_translation_for_locale($menu->news, $locale);

            if ($translation) {
                return route('news.show', [
                    'locale' => $locale,
                    'slug' => $translation->slug,
                ]);
            }
        }

        if ($menu->type === 'url' && $menu->url) {
            $url = trim($menu->url);

            if ($url === '#' || str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                return $url;
            }

            if (preg_match('#^/(id|en)(/.*)?$#', $url, $matches)) {
                $path = $matches[2] ?? '';
                return '/' . $locale . $path;
            }

            if (str_starts_with($url, '/')) {
                return '/' . $locale . $url;
            }

            return '/' . $locale . '/' . ltrim($url, '/');
        }

        return '#';
    }
}

if (! function_exists('menu_normalize_path')) {
    function menu_normalize_path(?string $url): string
    {
        return rtrim((string) parse_url((string) $url, PHP_URL_PATH), '/');
    }
}

if (! function_exists('menu_is_media_publication_item')) {
    function menu_is_media_publication_item($menu, string $locale): bool
    {
        $menuUrl = menu_url($menu, $locale);
        $path = strtolower(menu_normalize_path($menuUrl));
        $labelId = strtolower((string) ($menu->label_id ?? ''));
        $labelEn = strtolower((string) ($menu->label_en ?? ''));

        $knownPaths = [
            strtolower(menu_normalize_path(route('media_publikasi.index', ['locale' => $locale]))),
            '/id/media-publikasi',
            '/en/media-publikasi',
            '/id/publikasi',
            '/en/publications',
            '/id/media-publikasi/',
            '/en/media-publikasi/',
        ];

        if (in_array($path, array_map(fn ($v) => rtrim($v, '/'), $knownPaths), true)) {
            return true;
        }

        if (str_contains($path, 'media-publikasi') || str_contains($path, 'publikasi')) {
            return true;
        }

        if (str_contains($labelEn, 'media') || str_contains($labelEn, 'publication')) {
            return true;
        }

        if (str_contains($labelId, 'publikasi')) {
            return true;
        }

        return false;
    }
}

if (! function_exists('menu_is_tjsl_item')) {
    function menu_is_tjsl_item($menu, string $locale): bool
    {
        $menuUrl = menu_url($menu, $locale);
        $path = strtolower(menu_normalize_path($menuUrl));
        $labelId = strtolower((string) ($menu->label_id ?? ''));
        $labelEn = strtolower((string) ($menu->label_en ?? ''));

        $knownPaths = [
            strtolower(menu_normalize_path(route('tjsl.index', ['locale' => $locale]))),
            '/id/tjsl',
            '/en/tjsl',
        ];

        if (in_array($path, array_map(fn ($v) => rtrim($v, '/'), $knownPaths), true)) {
            return true;
        }

        if (str_contains($path, 'tjsl')) {
            return true;
        }

        if (str_contains($labelId, 'tjsl') || str_contains($labelEn, 'tjsl') || str_contains($labelEn, 'csr')) {
            return true;
        }

        return false;
    }
}

if (! function_exists('menu_resolve_active_page')) {
    function menu_resolve_active_page(string $locale, string $slug): ?Page
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

        return $page;
    }
}

if (! function_exists('menu_resolve_active_news')) {
    function menu_resolve_active_news(string $locale, string $slug): ?News
    {
        $news = News::query()
            ->with(['translations', 'category'])
            ->where('is_visible', true)
            ->where('status', 'published')
            ->whereHas('translations', function ($q) use ($locale, $slug) {
                $q->where('locale', $locale)
                    ->where('slug', $slug);
            })
            ->first();

        if (! $news && $locale === 'en') {
            $news = News::query()
                ->with(['translations', 'category'])
                ->where('is_visible', true)
                ->where('status', 'published')
                ->whereHas('translations', function ($q) use ($slug) {
                    $q->where('locale', 'id')
                        ->where('slug', $slug);
                })
                ->first();
        }

        return $news;
    }
}

if (! function_exists('menu_is_active')) {
    function menu_is_active($menu, string $locale): bool
    {
        $route = request()->route();
        $routeName = optional($route)->getName();
        $params = $route ? $route->parameters() : [];
        $currentUrl = rtrim(url()->current(), '/');
        $menuUrl = rtrim((string) menu_url($menu, $locale), '/');

        // exact URL
        if ($menuUrl !== '' && $menuUrl !== '#' && $menuUrl === $currentUrl) {
            return true;
        }

        // home
        if ($routeName === 'web.home') {
            return $menuUrl === rtrim(route('web.home', ['locale' => $locale]), '/');
        }

        // page static
        if ($routeName === 'page.show' && ! empty($params['slug'])) {
            $activePage = menu_resolve_active_page($locale, $params['slug']);

            if ($activePage) {
                if ($menu->type === 'page' && (int) ($menu->page_id ?? 0) === (int) $activePage->id) {
                    return true;
                }

                if ($menu->type === 'url') {
                    $menuPath = menu_normalize_path($menuUrl);
                    $currentPath = menu_normalize_path($currentUrl);

                    if ($menuPath !== '' && $menuPath === $currentPath) {
                        return true;
                    }
                }
            }
        }

        // profile detail
        if ($routeName === 'profil.show' && ! empty($params['slug'])) {
            $slug = $params['slug'];

            if ($menu->type === 'page' && $menu->page && $menu->page->menu_group === 'profil') {
                $translation = menu_translation_for_locale($menu->page, $locale);

                if ($translation && $translation->slug === $slug) {
                    return true;
                }

                $idTranslation = menu_translation_for_locale($menu->page, 'id');
                if ($locale === 'en' && $idTranslation && $idTranslation->slug === $slug) {
                    return true;
                }
            }
        }

        // news detail
        if ($routeName === 'news.show' && ! empty($params['slug'])) {
            $activeNews = menu_resolve_active_news($locale, $params['slug']);

            if ($activeNews) {
                if ($menu->type === 'news' && (int) ($menu->news_id ?? 0) === (int) $activeNews->id) {
                    return true;
                }

                $categorySlug = strtolower((string) ($activeNews->category->slug ?? ''));

                if ($categorySlug === 'tjsl') {
                    if (menu_is_tjsl_item($menu, $locale)) {
                        return true;
                    }
                } else {
                    if (menu_is_media_publication_item($menu, $locale)) {
                        return true;
                    }
                }
            }
        }

        // list routes
        if ($routeName === 'profil.index') {
            return $menuUrl === rtrim(route('profil.index', ['locale' => $locale]), '/');
        }

        if ($routeName === 'media_publikasi.index') {
            if (menu_is_media_publication_item($menu, $locale)) {
                return true;
            }

            return $menuUrl === rtrim(route('media_publikasi.index', ['locale' => $locale]), '/');
        }

        if ($routeName === 'tjsl.index') {
            if (menu_is_tjsl_item($menu, $locale)) {
                return true;
            }

            return $menuUrl === rtrim(route('tjsl.index', ['locale' => $locale]), '/');
        }

        if ($routeName === 'wbs.index') {
            return $menuUrl === rtrim(route('wbs.index', ['locale' => $locale]), '/');
        }

        // children recursive
        if (! empty($menu->children) && $menu->children->count()) {
            foreach ($menu->children as $child) {
                if (menu_is_active($child, $locale)) {
                    return true;
                }
            }
        }

        return false;
    }
}