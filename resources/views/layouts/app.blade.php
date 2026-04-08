<!doctype html>
<html lang="{{ in_array(request()->segment(1), ['id','en']) ? request()->segment(1) : 'id' }}">
<head>
    @php
        $locale = in_array(request()->segment(1), ['id', 'en'])
            ? request()->segment(1)
            : 'id';

        $metaTitle = $metaTitle ?? config('app.name', 'BSP Zapin');
        $metaDescription = $metaDescription ?? 'PT Bumi Siak Pusako Zapin - Perusahaan energi nasional yang berkomitmen pada pengelolaan sumber daya alam secara berkelanjutan dan bertanggung jawab.';
        $metaImage = $metaImage ?? asset('images/logo.png');
        $currentUrl = url()->current();

        $route = request()->route();
        $routeName = optional($route)->getName();
        $routeParams = $route ? $route->parameters() : [];

        $switchSeoLocaleUrl = function (string $targetLocale) use ($locale, $routeName, $routeParams) {
            if (! $routeName) return route('web.home', ['locale' => $targetLocale]);
            if ($routeName === 'web.home') return route('web.home', ['locale' => $targetLocale]);

            if ($routeName === 'page.show' && !empty($routeParams['slug'])) {
                $slug = $routeParams['slug'];
                $page = \App\Models\Page::query()->with('translations')->where('is_active', true)
                    ->whereHas('translations', function ($q) use ($locale, $slug) { $q->where('locale', $locale)->where('slug', $slug); })->first();
                if (! $page && $locale === 'en') {
                    $page = \App\Models\Page::query()->with('translations')->where('is_active', true)
                        ->whereHas('translations', function ($q) use ($slug) { $q->where('locale', 'id')->where('slug', $slug); })->first();
                }
                if ($page) {
                    $translation = method_exists($page, 'getTranslationByLocale') ? $page->getTranslationByLocale($targetLocale)
                        : ($page->translations->firstWhere('locale', $targetLocale) ?? $page->translations->firstWhere('locale', 'id'));
                    if ($translation?->slug) return route('page.show', ['locale' => $targetLocale, 'slug' => $translation->slug]);
                }
                return route('web.home', ['locale' => $targetLocale]);
            }

            if ($routeName === 'profil.show' && !empty($routeParams['slug'])) {
                $slug = $routeParams['slug'];
                $page = \App\Models\Page::query()->with('translations')->where('is_active', true)->where('menu_group', 'profil')
                    ->whereHas('translations', function ($q) use ($locale, $slug) { $q->where('locale', $locale)->where('slug', $slug); })->first();
                if (! $page && $locale === 'en') {
                    $page = \App\Models\Page::query()->with('translations')->where('is_active', true)->where('menu_group', 'profil')
                        ->whereHas('translations', function ($q) use ($slug) { $q->where('locale', 'id')->where('slug', $slug); })->first();
                }
                if ($page) {
                    $translation = method_exists($page, 'getTranslationByLocale') ? $page->getTranslationByLocale($targetLocale)
                        : ($page->translations->firstWhere('locale', $targetLocale) ?? $page->translations->firstWhere('locale', 'id'));
                    if ($translation?->slug) return route('profil.show', ['locale' => $targetLocale, 'slug' => $translation->slug]);
                }
                return route('profil.index', ['locale' => $targetLocale]);
            }

            if ($routeName === 'news.show' && !empty($routeParams['slug'])) {
                $slug = $routeParams['slug'];
                $news = \App\Models\News::query()->with('translations')->where('is_visible', true)->where('status', 'published')
                    ->whereHas('translations', function ($q) use ($locale, $slug) { $q->where('locale', $locale)->where('slug', $slug); })->first();
                if (! $news && $locale === 'en') {
                    $news = \App\Models\News::query()->with('translations')->where('is_visible', true)->where('status', 'published')
                        ->whereHas('translations', function ($q) use ($slug) { $q->where('locale', 'id')->where('slug', $slug); })->first();
                }
                if ($news) {
                    $translation = method_exists($news, 'getTranslationByLocale') ? $news->getTranslationByLocale($targetLocale)
                        : ($news->translations->firstWhere('locale', $targetLocale) ?? $news->translations->firstWhere('locale', 'id'));
                    if ($translation?->slug) return route('news.show', ['locale' => $targetLocale, 'slug' => $translation->slug]);
                }
                return route('media_publikasi.index', ['locale' => $targetLocale]);
            }

            if (in_array($routeName, ['profil.index', 'media_publikasi.index', 'tjsl.index', 'wbs.index'], true))
                return route($routeName, array_merge($routeParams, ['locale' => $targetLocale]));

            return route('web.home', ['locale' => $targetLocale]);
        };

        $hrefLangId = $switchSeoLocaleUrl('id');
        $hrefLangEn = $switchSeoLocaleUrl('en');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ $locale === 'id' ? 'id_ID' : 'en_US' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <link rel="canonical" href="{{ $currentUrl }}">
    <link rel="alternate" hreflang="id" href="{{ $hrefLangId }}">
    <link rel="alternate" hreflang="en" href="{{ $hrefLangEn }}">
    <link rel="alternate" hreflang="x-default" href="{{ route('web.home', ['locale' => 'id']) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
</head>
<body>

@php
    $locale = in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id';
    $menus = function_exists('navbar_menus') ? navbar_menus() : collect();
    $currentUrl = rtrim(url()->current(), '/');
    $currentRoute = request()->route();
    $currentRouteName = optional($currentRoute)->getName();
    $routeParams = $currentRoute ? $currentRoute->parameters() : [];
    $currentPage = null;
    $currentNews = null;

    if ($currentRouteName === 'page.show' && !empty($routeParams['slug'])) {
        $currentPage = \App\Models\Page::query()->with('translations')->where('is_active', true)
            ->whereHas('translations', function ($q) use ($locale, $routeParams) { $q->where('locale', $locale)->where('slug', $routeParams['slug']); })->first();
        if (! $currentPage && $locale === 'en') {
            $currentPage = \App\Models\Page::query()->with('translations')->where('is_active', true)
                ->whereHas('translations', function ($q) use ($routeParams) { $q->where('locale', 'id')->where('slug', $routeParams['slug']); })->first();
        }
    }

    if ($currentRouteName === 'news.show' && !empty($routeParams['slug'])) {
        $currentNews = \App\Models\News::query()->with('translations')->where('is_visible', true)->where('status', 'published')
            ->whereHas('translations', function ($q) use ($locale, $routeParams) { $q->where('locale', $locale)->where('slug', $routeParams['slug']); })->first();
        if (! $currentNews && $locale === 'en') {
            $currentNews = \App\Models\News::query()->with('translations')->where('is_visible', true)->where('status', 'published')
                ->whereHas('translations', function ($q) use ($routeParams) { $q->where('locale', 'id')->where('slug', $routeParams['slug']); })->first();
        }
    }

    $normalizeUrl = function (?string $url) { return rtrim((string) $url, '/'); };

    $menuPointsToCurrentEntity = function ($menuItem) use ($currentPage, $currentNews) {
        if ($currentPage && ((isset($menuItem->type) && $menuItem->type === 'page' && (int)($menuItem->page_id ?? 0) === (int)$currentPage->id) || ((int)($menuItem->page_id ?? 0) === (int)$currentPage->id))) return true;
        if ($currentNews && ((isset($menuItem->type) && $menuItem->type === 'news' && (int)($menuItem->news_id ?? 0) === (int)$currentNews->id) || ((int)($menuItem->news_id ?? 0) === (int)$currentNews->id))) return true;
        return false;
    };

    $routeMatchesMenu = function ($menuItem, string $locale) use ($currentRouteName, $routeParams, $normalizeUrl, $currentUrl, $menuPointsToCurrentEntity) {
        if ($menuPointsToCurrentEntity($menuItem)) return true;
        $menuUrl = function_exists('menu_url') ? menu_url($menuItem, $locale) : '#';
        $menuUrl = $normalizeUrl($menuUrl);
        if ($menuUrl !== '' && $menuUrl === $currentUrl) return true;
        if (! $currentRouteName) return false;
        if ($currentRouteName === 'web.home') return $menuUrl === $normalizeUrl(route('web.home', ['locale' => $locale]));
        if ($currentRouteName === 'profil.index') return $menuUrl === $normalizeUrl(route('profil.index', ['locale' => $locale]));
        if ($currentRouteName === 'media_publikasi.index') return $menuUrl === $normalizeUrl(route('media_publikasi.index', ['locale' => $locale]));
        if ($currentRouteName === 'tjsl.index') return $menuUrl === $normalizeUrl(route('tjsl.index', ['locale' => $locale]));
        if ($currentRouteName === 'wbs.index') return $menuUrl === $normalizeUrl(route('wbs.index', ['locale' => $locale]));
        if ($currentRouteName === 'profil.show' && isset($routeParams['slug'])) return $menuUrl === $normalizeUrl(route('profil.show', ['locale' => $locale, 'slug' => $routeParams['slug']]));
        return false;
    };

    $isMenuActive = function ($menuItem, string $locale) use ($routeMatchesMenu) {
        if ($routeMatchesMenu($menuItem, $locale)) return true;
        if (!empty($menuItem->children) && $menuItem->children->count()) {
            foreach ($menuItem->children as $child) { if ($routeMatchesMenu($child, $locale)) return true; }
        }
        return false;
    };

    $switchLocaleUrl = function (string $targetLocale) use ($locale, $currentRouteName, $routeParams) {
        if (! $currentRouteName) return route('web.home', ['locale' => $targetLocale]);
        if ($currentRouteName === 'web.home') return route('web.home', ['locale' => $targetLocale]);

        if ($currentRouteName === 'page.show') {
            $slug = $routeParams['slug'] ?? null;
            if ($slug) {
                $page = \App\Models\Page::query()->with('translations')->where('is_active', true)
                    ->whereHas('translations', function ($q) use ($locale, $slug) { $q->where('locale', $locale)->where('slug', $slug); })->first();
                if (! $page && $locale === 'en') {
                    $page = \App\Models\Page::query()->with('translations')->where('is_active', true)
                        ->whereHas('translations', function ($q) use ($slug) { $q->where('locale', 'id')->where('slug', $slug); })->first();
                }
                if ($page) {
                    $t = method_exists($page, 'getTranslationByLocale') ? $page->getTranslationByLocale($targetLocale)
                        : ($page->translations->firstWhere('locale', $targetLocale) ?? $page->translations->firstWhere('locale', 'id'));
                    if ($t?->slug) return route('page.show', ['locale' => $targetLocale, 'slug' => $t->slug]);
                }
            }
            return route('web.home', ['locale' => $targetLocale]);
        }

        if ($currentRouteName === 'news.show') {
            $slug = $routeParams['slug'] ?? null;
            if ($slug) {
                $news = \App\Models\News::query()->with('translations')->where('is_visible', true)->where('status', 'published')
                    ->whereHas('translations', function ($q) use ($locale, $slug) { $q->where('locale', $locale)->where('slug', $slug); })->first();
                if (! $news && $locale === 'en') {
                    $news = \App\Models\News::query()->with('translations')->where('is_visible', true)->where('status', 'published')
                        ->whereHas('translations', function ($q) use ($slug) { $q->where('locale', 'id')->where('slug', $slug); })->first();
                }
                if ($news) {
                    $t = method_exists($news, 'getTranslationByLocale') ? $news->getTranslationByLocale($targetLocale)
                        : ($news->translations->firstWhere('locale', $targetLocale) ?? $news->translations->firstWhere('locale', 'id'));
                    if ($t?->slug) return route('news.show', ['locale' => $targetLocale, 'slug' => $t->slug]);
                }
            }
            return route('media_publikasi.index', ['locale' => $targetLocale]);
        }

        if (in_array($currentRouteName, ['profil.index', 'media_publikasi.index', 'tjsl.index', 'wbs.index'], true))
            return route($currentRouteName, array_merge($routeParams, ['locale' => $targetLocale]));

        if ($currentRouteName === 'profil.show')
            return route('profil.show', array_merge($routeParams, ['locale' => $targetLocale]));

        return route('web.home', ['locale' => $targetLocale]);
    };

    $idSwitchUrl = $switchLocaleUrl('id');
    $enSwitchUrl = $switchLocaleUrl('en');
@endphp

<style>
    :root {
        --g900: #173f08;
        --g800: #1e5210;
        --g700: #21560e;
        --g500: #2f7d32;
        --g200: #c8e6c9;
        --g100: #eef5eb;
        --g50:  #f4f9f2;
        --gold: #9a6f0a;
        --gold-lt: #d4a843;
        --text: #111827;
        --text2: #374151;
        --text3: #6b7280;
        --line: #e5e7eb;
        --line2: #f3f4f6;
        --bg: #f8f9fb;
        --white: #ffffff;
        --nav-h: 66px;
        --font: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif;
        --ease: cubic-bezier(.4,0,.2,1);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: var(--font);
        font-size: 15px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    a { color: inherit; text-decoration: none; }
    :focus-visible { outline: 2px solid var(--g500); outline-offset: 2px; }

    /* ══════════════════════════════════════
       NAVBAR
    ══════════════════════════════════════ */
    .n-bar {
        position: sticky;
        top: 0;
        z-index: 900;
        background: rgba(255,255,255,.96);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--line);
        transition: box-shadow .2s var(--ease);
    }

    .n-bar.scrolled {
        box-shadow: 0 4px 20px rgba(0,0,0,.07);
    }

    .n-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 28px;
        height: var(--nav-h);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Brand */
    .n-brand {
        display: flex;
        align-items: center;
        gap: 11px;
        flex-shrink: 0;
        text-decoration: none;
        margin-right: 8px;
    }

    .n-brand-logo {
        width: 46px;
        height: 46px;
        object-fit: contain;
        flex-shrink: 0;
        transition: transform .2s var(--ease);
    }

    .n-brand:hover .n-brand-logo { transform: scale(1.05); }

    .n-brand-text { line-height: 1; }

    .n-brand-name {
        display: block;
        font-size: 15px;
        font-weight: 700;
        color: var(--gold);
        letter-spacing: -.01em;
        white-space: nowrap;
    }

    .n-brand-sub {
        display: block;
        font-size: 10.5px;
        font-weight: 400;
        color: var(--g500);
        font-style: italic;
        letter-spacing: .02em;
        margin-top: 2px;
        white-space: nowrap;
    }

    /* Desktop nav */
    .n-menu {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
    }

    .n-link,
    .n-dd-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 36px;
        padding: 0 11px;
        border-radius: 8px;
        font-family: var(--font);
        font-size: 13px;
        font-weight: 500;
        color: var(--text2);
        white-space: nowrap;
        cursor: pointer;
        border: none;
        background: transparent;
        transition: background .15s var(--ease), color .15s var(--ease);
        text-decoration: none;
    }

    .n-link:hover, .n-dd-btn:hover {
        background: var(--g50);
        color: var(--g900);
    }

    .n-link.is-active, .n-dd-btn.is-active {
        color: var(--g900);
        font-weight: 700;
        background: var(--g100);
    }

    .n-link.is-active::after, .n-dd-btn.is-active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 10px;
        right: 10px;
        height: 2px;
        border-radius: 2px 2px 0 0;
        background: var(--g500);
    }

    /* Dropdown */
    .n-dd { position: relative; }

    .n-dd-caret {
        width: 14px;
        height: 14px;
        opacity: .5;
        flex-shrink: 0;
        transition: transform .18s var(--ease), opacity .18s;
    }

    .n-dd:hover .n-dd-caret,
    .n-dd.is-open .n-dd-caret { opacity: .8; }
    .n-dd.is-open .n-dd-caret { transform: rotate(180deg); }

    .n-panel {
        position: absolute;
        top: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%) translateY(-6px);
        min-width: 220px;
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 14px;
        box-shadow: 0 12px 32px rgba(0,0,0,.1), 0 2px 8px rgba(0,0,0,.04);
        padding: 6px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s var(--ease), transform .18s var(--ease);
        z-index: 1100;
    }

    .n-dd.is-open .n-panel {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }

    .n-panel a {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text2);
        transition: background .12s, color .12s;
    }

    .n-panel a:hover { background: var(--g50); color: var(--g900); }

    .n-panel a.is-active {
        background: var(--g100);
        color: var(--g900);
        font-weight: 700;
    }

    .n-panel a.is-active::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--g500);
        flex-shrink: 0;
    }

    /* Right side */
    .n-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        margin-left: 4px;
    }

    .n-lang {
        display: flex;
        align-items: center;
        gap: 3px;
        background: var(--line2);
        padding: 3px;
        border-radius: 9px;
    }

    .n-lang a {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text3);
        transition: background .14s, color .14s, box-shadow .14s;
        white-space: nowrap;
    }

    .n-lang a img {
        width: 16px;
        height: 12px;
        object-fit: cover;
        border-radius: 2px;
        display: block;
    }

    .n-lang a:hover { color: var(--text); }

    .n-lang a.is-active {
        background: var(--white);
        color: var(--g900);
        font-weight: 700;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
    }

    /* Burger */
    .n-burger {
        display: none;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: transparent;
        cursor: pointer;
        flex-shrink: 0;
        margin-left: auto;
    }

    .n-burger-icon {
        width: 18px;
        height: 12px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .n-burger-icon span {
        display: block;
        width: 100%;
        height: 1.5px;
        background: var(--text2);
        border-radius: 2px;
        transform-origin: center;
        transition: transform .22s var(--ease), opacity .22s var(--ease), width .22s var(--ease);
    }

    .n-burger.is-open .n-burger-icon span:nth-child(1) { transform: translateY(5.25px) rotate(45deg); }
    .n-burger.is-open .n-burger-icon span:nth-child(2) { opacity: 0; width: 0; }
    .n-burger.is-open .n-burger-icon span:nth-child(3) { transform: translateY(-5.25px) rotate(-45deg); }

    /* ══════════════════════════════════════
       MOBILE DRAWER
    ══════════════════════════════════════ */
    .n-drawer-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.28);
        z-index: 800;
        opacity: 0;
        transition: opacity .22s var(--ease);
    }

    .n-drawer-overlay.is-open {
        display: block;
        opacity: 1;
    }

    .n-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: min(320px, 90vw);
        background: var(--white);
        z-index: 850;
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform .26s var(--ease);
        box-shadow: -8px 0 32px rgba(0,0,0,.1);
    }

    .n-drawer.is-open { transform: translateX(0); }

    .n-drawer-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid var(--line2);
        flex-shrink: 0;
    }

    .n-drawer-brand {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .n-drawer-brand img {
        width: 34px;
        height: 34px;
        object-fit: contain;
    }

    .n-drawer-brand-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--gold);
        line-height: 1.2;
    }

    .n-drawer-brand-sub {
        font-size: 10px;
        color: var(--g500);
        font-style: italic;
    }

    .n-drawer-close {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--line);
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text3);
        font-size: 18px;
        transition: background .14s, color .14s;
        flex-shrink: 0;
    }

    .n-drawer-close:hover { background: var(--g50); color: var(--g900); }

    .n-drawer-body {
        flex: 1;
        overflow-y: auto;
        padding: 10px 12px;
        -webkit-overflow-scrolling: touch;
    }

    .n-drawer-link {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 11px 13px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text2);
        transition: background .14s, color .14s;
        border: none;
        background: transparent;
        cursor: pointer;
        text-align: left;
        gap: 8px;
        font-family: var(--font);
        text-decoration: none;
    }

    .n-drawer-link:hover { background: var(--g50); color: var(--g900); }

    .n-drawer-link.is-active {
        background: var(--g100);
        color: var(--g900);
        font-weight: 700;
        border-left: 3px solid var(--g500);
        padding-left: 10px;
    }

    .n-drawer-link-arrow {
        margin-left: auto;
        font-size: 11px;
        opacity: .45;
        transition: transform .18s var(--ease);
    }

    .n-drawer-dd.is-open .n-drawer-link-arrow { transform: rotate(180deg); }

    .n-drawer-sub {
        overflow: hidden;
        max-height: 0;
        transition: max-height .22s var(--ease);
    }

    .n-drawer-dd.is-open .n-drawer-sub { max-height: 600px; }

    .n-drawer-sub-inner {
        padding: 3px 0 4px 12px;
    }

    .n-drawer-sub-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text3);
        text-decoration: none;
        transition: background .12s, color .12s;
    }

    .n-drawer-sub-link::before {
        content: '';
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--line);
        flex-shrink: 0;
        transition: background .12s;
    }

    .n-drawer-sub-link:hover { background: var(--g50); color: var(--g900); }
    .n-drawer-sub-link:hover::before { background: var(--g500); }

    .n-drawer-sub-link.is-active {
        color: var(--g900);
        font-weight: 700;
        background: var(--g50);
    }

    .n-drawer-sub-link.is-active::before { background: var(--g500); }

    .n-drawer-foot {
        padding: 14px 16px 20px;
        border-top: 1px solid var(--line2);
        flex-shrink: 0;
    }

    .n-drawer-foot-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--text3);
        margin-bottom: 10px;
    }

    .n-drawer-lang {
        display: flex;
        gap: 8px;
    }

    .n-drawer-lang a {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 12px;
        border-radius: 9px;
        border: 1px solid var(--line);
        font-size: 13px;
        font-weight: 600;
        color: var(--text3);
        transition: all .14s;
    }

    .n-drawer-lang a img {
        width: 18px;
        height: 13px;
        object-fit: cover;
        border-radius: 2px;
    }

    .n-drawer-lang a:hover { border-color: var(--g200); color: var(--g900); background: var(--g50); }

    .n-drawer-lang a.is-active {
        border-color: var(--g500);
        background: var(--g100);
        color: var(--g900);
    }

    /* ══════════════════════════════════════
       MAIN CONTENT
    ══════════════════════════════════════ */
    .n-main {
        max-width: 1280px;
        margin: 0 auto;
        padding: 32px 28px 64px;
        min-height: 60vh;
    }

    /* ══════════════════════════════════════
       FOOTER
    ══════════════════════════════════════ */
    .f-bar {
        background: #0e2a04;
        background-image: radial-gradient(ellipse at 20% 50%, rgba(23,63,8,.8) 0%, transparent 60%),
                          radial-gradient(ellipse at 80% 20%, rgba(21,53,8,.6) 0%, transparent 50%);
        color: rgba(255,255,255,.65);
        margin-top: 0;
    }

    .f-top {
        max-width: 1280px;
        margin: 0 auto;
        padding: 60px 32px 52px;
        display: grid;
        grid-template-columns: 2.2fr 1fr 1fr 1.3fr;
        gap: 52px;
    }

    /* Brand col */
    .f-brand { display: flex; flex-direction: column; }

    .f-brand-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .f-brand-row img {
        width: 46px;
        height: 46px;
        object-fit: contain;
        filter: brightness(1.15);
    }

    .f-brand-name {
        display: block;
        font-size: 15.5px;
        font-weight: 700;
        color: var(--gold-lt);
        line-height: 1.25;
        letter-spacing: -.01em;
    }

    .f-brand-sub {
        display: block;
        font-size: 11px;
        color: rgba(255,255,255,.38);
        font-style: italic;
        margin-top: 2px;
    }

    .f-brand-desc {
        font-size: 13.5px;
        line-height: 1.78;
        color: rgba(255,255,255,.48);
        margin-bottom: 22px;
    }

    .f-divider {
        width: 40px;
        height: 2px;
        background: rgba(212,168,67,.4);
        border-radius: 2px;
        margin-bottom: 20px;
    }

    /* Social */
    .f-social { display: flex; gap: 8px; margin-bottom: 0; }

    .f-social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        color: rgba(255,255,255,.55);
        text-decoration: none;
        transition: background .15s, color .15s, border-color .15s, transform .15s;
    }

    .f-social-btn:hover {
        background: rgba(255,255,255,.12);
        color: #fff;
        border-color: rgba(255,255,255,.2);
        transform: translateY(-2px);
    }

    /* Footer columns */
    .f-col-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255,255,255,.3);
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255,255,255,.06);
    }

    .f-col ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .f-col ul li a {
        font-size: 13.5px;
        color: rgba(255,255,255,.55);
        text-decoration: none;
        transition: color .13s, padding-left .13s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .f-col ul li a::before {
        content: '';
        width: 0;
        height: 1px;
        background: var(--gold-lt);
        transition: width .18s var(--ease);
        flex-shrink: 0;
    }

    .f-col ul li a:hover { color: #fff; }
    .f-col ul li a:hover::before { width: 10px; }

    /* Contact */
    .f-contact { display: flex; flex-direction: column; gap: 16px; }

    .f-contact-item { display: flex; gap: 12px; align-items: flex-start; }

    .f-contact-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.08);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: rgba(255,255,255,.4);
    }

    .f-contact-text {
        font-size: 13px;
        color: rgba(255,255,255,.5);
        line-height: 1.6;
    }

    .f-contact-text strong {
        display: block;
        font-size: 11.5px;
        font-weight: 700;
        color: rgba(255,255,255,.65);
        letter-spacing: .03em;
        margin-bottom: 3px;
        text-transform: uppercase;
    }

    .f-contact-text a {
        color: rgba(255,255,255,.55);
        text-decoration: none;
        transition: color .13s;
    }

    .f-contact-text a:hover { color: var(--gold-lt); }

    /* Footer badge strip */
    .f-badges {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 32px 36px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .f-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px 5px 8px;
        border-radius: 999px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.08);
        font-size: 11.5px;
        color: rgba(255,255,255,.4);
        font-weight: 500;
    }

    .f-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--g500);
        flex-shrink: 0;
    }

    /* Footer bottom */
    .f-bottom { border-top: 1px solid rgba(255,255,255,.06); }

    .f-bottom-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 18px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .f-copy { font-size: 12px; color: rgba(255,255,255,.25); }

    .f-bottom-links { display: flex; gap: 20px; }

    .f-bottom-links a {
        font-size: 12px;
        color: rgba(255,255,255,.28);
        text-decoration: none;
        transition: color .13s;
    }

    .f-bottom-links a:hover { color: rgba(255,255,255,.6); }

    /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
    @media (max-width: 1060px) {
        .n-menu { display: none; }
        .n-right { display: none; }
        .n-burger { display: flex; }

        .n-inner { padding: 0 18px; }

        .f-top { grid-template-columns: 1fr 1fr; gap: 36px 40px; }
    }

    @media (max-width: 680px) {
        .n-main { padding: 24px 16px 48px; }
        .n-inner { padding: 0 16px; height: 58px; }

        .n-brand-logo { width: 40px; height: 40px; }
        .n-brand-name { font-size: 13.5px; }
        .n-brand-sub { font-size: 10px; }

        .f-top { grid-template-columns: 1fr; gap: 32px; padding: 40px 20px 36px; }
        .f-badges { padding: 0 20px 28px; }
        .f-bottom-inner { padding: 16px 20px; flex-direction: column; align-items: flex-start; gap: 10px; }
    }
</style>

{{-- Drawer overlay --}}
<div class="n-drawer-overlay" id="nOverlay"></div>

<header class="n-bar" id="nBar">
    <div class="n-inner">
        <a href="{{ route('web.home', ['locale' => $locale]) }}" class="n-brand">
            <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin" class="n-brand-logo">
            <div class="n-brand-text">
                <span class="n-brand-name">PT Bumi Siak Pusako Zapin</span>
                <span class="n-brand-sub">the energy company</span>
            </div>
        </a>

        <nav class="n-menu" aria-label="Navigasi utama">
            @foreach($menus as $menu)
                @php
                    $label = $locale === 'id' ? $menu->label_id : ($menu->label_en ?: $menu->label_id);
                    $url = function_exists('menu_url') ? menu_url($menu, $locale) : '#';
                    $isActive = function_exists('menu_is_active') ? menu_is_active($menu, $locale) : false;
                @endphp

                @if($menu->children->count())
                    <div class="n-dd" data-dd>
                        <button type="button" class="n-dd-btn {{ $isActive ? 'is-active' : '' }}" aria-expanded="false">
                            {{ $label }}
                            <svg class="n-dd-caret" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                                <polyline points="4 6 8 10 12 6"/>
                            </svg>
                        </button>
                        <div class="n-panel" role="menu">
                            @foreach($menu->children as $child)
                                @php
                                    $cl = $locale === 'id' ? $child->label_id : ($child->label_en ?: $child->label_id);
                                    $cu = function_exists('menu_url') ? menu_url($child, $locale) : '#';
                                    $ca = function_exists('menu_is_active') ? menu_is_active($child, $locale) : false;
                                @endphp
                                <a href="{{ $cu }}" class="{{ $ca ? 'is-active' : '' }}" role="menuitem">{{ $cl }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $url }}" class="n-link {{ $isActive ? 'is-active' : '' }}">{{ $label }}</a>
                @endif
            @endforeach
        </nav>

        <div class="n-right">
            <div class="n-lang" role="group" aria-label="Pilih bahasa">
                <a href="{{ $idSwitchUrl }}" class="{{ $locale === 'id' ? 'is-active' : '' }}" title="Bahasa Indonesia">
                    <img src="https://flagcdn.com/w40/id.png" alt="ID"> ID
                </a>
                <a href="{{ $enSwitchUrl }}" class="{{ $locale === 'en' ? 'is-active' : '' }}" title="English">
                    <img src="https://flagcdn.com/w40/gb.png" alt="EN"> EN
                </a>
            </div>
        </div>

        <button class="n-burger" id="nBurger" aria-label="Buka menu" aria-expanded="false" aria-controls="nDrawer">
            <div class="n-burger-icon">
                <span></span><span></span><span></span>
            </div>
        </button>
    </div>
</header>

{{-- Mobile Drawer --}}
<div class="n-drawer" id="nDrawer" role="dialog" aria-modal="true" aria-label="Navigasi" aria-hidden="true">
    <div class="n-drawer-head">
        <div class="n-drawer-brand">
            <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
            <div>
                <div class="n-drawer-brand-name">BSP Zapin</div>
                <div class="n-drawer-brand-sub">the energy company</div>
            </div>
        </div>
        <button class="n-drawer-close" id="nDrawerClose" aria-label="Tutup menu">&#x2715;</button>
    </div>

    <div class="n-drawer-body">
        @foreach($menus as $menu)
            @php
                $label = $locale === 'id' ? $menu->label_id : ($menu->label_en ?: $menu->label_id);
                $url = function_exists('menu_url') ? menu_url($menu, $locale) : '#';
                $isActive = $isMenuActive($menu, $locale);
            @endphp

            @if($menu->children->count())
                <div class="n-drawer-dd {{ $isActive ? 'is-open' : '' }}">
                    <button type="button" class="n-drawer-link {{ $isActive ? 'is-active' : '' }}">
                        {{ $label }}
                        <span class="n-drawer-link-arrow">▼</span>
                    </button>
                    <div class="n-drawer-sub">
                        <div class="n-drawer-sub-inner">
                            @foreach($menu->children as $child)
                                @php
                                    $cl = $locale === 'id' ? $child->label_id : ($child->label_en ?: $child->label_id);
                                    $cu = function_exists('menu_url') ? menu_url($child, $locale) : '#';
                                    $ca = $isMenuActive($child, $locale);
                                @endphp
                                <a href="{{ $cu }}" class="n-drawer-sub-link {{ $ca ? 'is-active' : '' }}">{{ $cl }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ $url }}" class="n-drawer-link {{ $isActive ? 'is-active' : '' }}">{{ $label }}</a>
            @endif
        @endforeach
    </div>

    <div class="n-drawer-foot">
        <div class="n-drawer-foot-label">{{ $locale === 'id' ? 'Bahasa' : 'Language' }}</div>
        <div class="n-drawer-lang">
            <a href="{{ $idSwitchUrl }}" class="{{ $locale === 'id' ? 'is-active' : '' }}">
                <img src="https://flagcdn.com/w40/id.png" alt="ID"> Bahasa Indonesia
            </a>
            <a href="{{ $enSwitchUrl }}" class="{{ $locale === 'en' ? 'is-active' : '' }}">
                <img src="https://flagcdn.com/w40/gb.png" alt="EN"> English
            </a>
        </div>
    </div>
</div>

<main class="n-main">
    @yield('content')
</main>

<footer class="f-bar">
    <div class="f-top">
        <div class="f-brand">
            <div class="f-brand-row">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
                <div>
                    <span class="f-brand-name">PT Bumi Siak Pusako Zapin</span>
                    <span class="f-brand-sub">the energy company</span>
                </div>
            </div>
            <div class="f-divider"></div>
            <p class="f-brand-desc">
                {{ $locale === 'id'
                    ? 'Perusahaan energi nasional yang berkomitmen pada pengelolaan sumber daya alam secara berkelanjutan dan bertanggung jawab demi generasi mendatang.'
                    : 'A national energy company committed to sustainable and responsible management of natural resources for future generations.' }}
            </p>
            <div class="f-social">
                <a href="https://www.instagram.com/bspzapin/" target="_blank" rel="noopener noreferrer" class="f-social-btn" title="Instagram" aria-label="Instagram">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="2" y="2" width="20" height="20" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                    </svg>
                </a>
                <a href="https://www.youtube.com/@BSPZAPIN" target="_blank" rel="noopener noreferrer" class="f-social-btn" title="YouTube" aria-label="YouTube">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.96-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
                        <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="currentColor" stroke="none"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="f-col">
            <div class="f-col-title">{{ $locale === 'id' ? 'Navigasi' : 'Navigation' }}</div>
            <ul>
                @foreach($menus->take(6) as $menu)
                    @php
                        $fl = $locale === 'id' ? $menu->label_id : ($menu->label_en ?: $menu->label_id);
                        $fu = function_exists('menu_url') ? menu_url($menu, $locale) : '#';
                    @endphp
                    <li><a href="{{ $fu }}">{{ $fl }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="f-col">
            <div class="f-col-title">{{ $locale === 'id' ? 'Informasi' : 'Information' }}</div>
            <ul>
                <li><a href="{{ route('profil.index', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Tentang Kami' : 'About Us' }}</a></li>
                <li><a href="{{ route('tjsl.index', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'TJSL / CSR' : 'CSR' }}</a></li>
                <li><a href="{{ route('wbs.index', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Whistleblowing' : 'Whistleblowing' }}</a></li>
                <li><a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Media & Publikasi' : 'Media & Publication' }}</a></li>
                <li><a href="{{ route('legal.privacy', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}</a></li>
                <li><a href="{{ route('legal.terms', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Syarat & Ketentuan' : 'Terms & Conditions' }}</a></li>
            </ul>
        </div>

        <div class="f-col">
            <div class="f-col-title">{{ $locale === 'id' ? 'Kontak' : 'Contact' }}</div>
            <div class="f-contact">
                <div class="f-contact-item">
                    <div class="f-contact-icon-wrap">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="f-contact-text">
                        <strong>{{ $locale === 'id' ? 'Alamat' : 'Address' }}</strong>
                        Gedung Surya Dumai Lt. 6,<br>
                        Jl. Jend. Sudirman No. 395,<br>
                        Pekanbaru, Riau 28116
                    </div>
                </div>
                <div class="f-contact-item">
                    <div class="f-contact-icon-wrap">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="f-contact-text">
                        <strong>Email</strong>
                        <a href="mailto:support@bspzapin.co.id">support@bspzapin.co.id</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="f-badges">
        <div class="f-badge"><span class="f-badge-dot"></span>{{ $locale === 'id' ? 'Sektor Hilir Migas' : 'Downstream Oil & Gas' }}</div>
        <div class="f-badge"><span class="f-badge-dot"></span>{{ $locale === 'id' ? 'Berdiri sejak 2010' : 'Est. 2010' }}</div>
        <div class="f-badge"><span class="f-badge-dot"></span>Pekanbaru, Riau</div>
        <div class="f-badge"><span class="f-badge-dot"></span>ISO Certified</div>
    </div>

    <div class="f-bottom">
        <div class="f-bottom-inner">
            <span class="f-copy">
                &copy; {{ date('Y') }} PT Bumi Siak Pusako Zapin.
                {{ $locale === 'id' ? 'Hak cipta dilindungi.' : 'All rights reserved.' }}
            </span>
            <div class="f-bottom-links">
                <a href="{{ route('legal.privacy', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}</a>
                <a href="{{ route('legal.terms', ['locale' => $locale]) }}">{{ $locale === 'id' ? 'Syarat & Ketentuan' : 'Terms' }}</a>
                <a href="{{ url('/sitemap.xml') }}">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<script>
(function () {
    /* ── Scroll shadow ── */
    var bar = document.getElementById('nBar');
    if (bar) {
        var onScroll = function() { bar.classList.toggle('scrolled', window.scrollY > 10); };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ── Desktop dropdowns ── */
    var dds = Array.from(document.querySelectorAll('.n-bar [data-dd]'));

    function closeAllDd(except) {
        dds.forEach(function(dd) {
            if (dd === except) return;
            dd.classList.remove('is-open');
            var btn = dd.querySelector(':scope > .n-dd-btn');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }

    dds.forEach(function(dd) {
        var btn = dd.querySelector(':scope > .n-dd-btn');
        if (!btn) return;
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var open = !dd.classList.contains('is-open');
            closeAllDd(dd);
            dd.classList.toggle('is-open', open);
            btn.setAttribute('aria-expanded', String(open));
        });
    });

    document.addEventListener('click', function() { closeAllDd(null); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeAllDd(null); });

    /* ── Mobile drawer ── */
    var burger = document.getElementById('nBurger');
    var drawer = document.getElementById('nDrawer');
    var overlay = document.getElementById('nOverlay');
    var closeBtn = document.getElementById('nDrawerClose');

    function openDrawer() {
        drawer.classList.add('is-open');
        overlay.classList.add('is-open');
        burger.classList.add('is-open');
        burger.setAttribute('aria-expanded', 'true');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawer.classList.remove('is-open');
        overlay.classList.remove('is-open');
        burger.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    if (burger) burger.addEventListener('click', function(e) { e.stopPropagation(); drawer.classList.contains('is-open') ? closeDrawer() : openDrawer(); });
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (overlay) overlay.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeDrawer(); });

    /* ── Drawer accordion ── */
    var drawerDds = Array.from(document.querySelectorAll('#nDrawer .n-drawer-dd'));
    drawerDds.forEach(function(dd) {
        var btn = dd.querySelector(':scope > .n-drawer-link');
        if (!btn) return;
        btn.addEventListener('click', function() {
            var open = !dd.classList.contains('is-open');
            drawerDds.forEach(function(d) { if (d !== dd) d.classList.remove('is-open'); });
            dd.classList.toggle('is-open', open);
        });
    });
})();
</script>

</body>
</html>