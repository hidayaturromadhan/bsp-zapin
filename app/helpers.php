<?php

use App\Models\PageTranslation;
use App\Models\NewsTranslation;

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        $seg = request()->segment(1);
        return in_array($seg, ['id', 'en']) ? $seg : 'id';
    }
}

if (! function_exists('locale_switch_data')) {
    /**
     * Return data untuk switch locale:
     * [
     *   'url' => string,
     *   'available' => bool
     * ]
     *
     * available=false artinya pasangan translation tidak ada (untuk detail page/news).
     */
    function locale_switch_data(string $targetLocale): array
    {
        $targetLocale = in_array($targetLocale, ['id', 'en']) ? $targetLocale : 'id';

        $route = request()->route();
        if (! $route) {
            return [
                'url' => route('web.home', ['locale' => $targetLocale]),
                'available' => true,
            ];
        }

        $name = $route->getName();
        $params = $route->parameters();

        // ========== DETAIL PAGE ==========
        if ($name === 'page.show') {
            $currentLocale = $params['locale'] ?? current_locale();
            $slug = $params['slug'] ?? null;

            if (! $slug) {
                return ['url' => route('web.home', ['locale' => $targetLocale]), 'available' => true];
            }

            $current = PageTranslation::query()
                ->select(['id', 'page_id', 'locale', 'slug'])
                ->where('locale', $currentLocale)
                ->where('slug', $slug)
                ->first();

            if (! $current) {
                return ['url' => route('web.home', ['locale' => $targetLocale]), 'available' => true];
            }

            $other = PageTranslation::query()
                ->select(['id', 'page_id', 'locale', 'slug'])
                ->where('page_id', $current->page_id)
                ->where('locale', $targetLocale)
                ->first();

            if ($other) {
                return [
                    'url' => route('page.show', ['locale' => $targetLocale, 'slug' => $other->slug]),
                    'available' => true,
                ];
            }

            // pasangan tidak ada → disable
            return [
                'url' => route('web.home', ['locale' => $targetLocale]),
                'available' => false,
            ];
        }

        // ========== DETAIL NEWS ==========
        if ($name === 'news.show') {
            $currentLocale = $params['locale'] ?? current_locale();
            $slug = $params['slug'] ?? null;

            if (! $slug) {
                return ['url' => route('web.home', ['locale' => $targetLocale]), 'available' => true];
            }

            $current = NewsTranslation::query()
                ->select(['id', 'news_id', 'locale', 'slug'])
                ->where('locale', $currentLocale)
                ->where('slug', $slug)
                ->first();

            if (! $current) {
                return ['url' => route('web.home', ['locale' => $targetLocale]), 'available' => true];
            }

            $other = NewsTranslation::query()
                ->select(['id', 'news_id', 'locale', 'slug'])
                ->where('news_id', $current->news_id)
                ->where('locale', $targetLocale)
                ->first();

            if ($other) {
                return [
                    'url' => route('news.show', ['locale' => $targetLocale, 'slug' => $other->slug]),
                    'available' => true,
                ];
            }

            // pasangan tidak ada → disable
            return [
                'url' => route('web.home', ['locale' => $targetLocale]),
                'available' => false,
            ];
        }

        // ========== ROUTE LAIN (LIST/PROFIL/HOME/WBS) ==========
        // route lain biasanya aman: hanya ganti locale param
        if (isset($params['locale'])) {
            $params['locale'] = $targetLocale;
            try {
                return ['url' => route($name, $params), 'available' => true];
            } catch (\Throwable $e) {
                return ['url' => route('web.home', ['locale' => $targetLocale]), 'available' => true];
            }
        }

        return ['url' => route('web.home', ['locale' => $targetLocale]), 'available' => true];
    }
}

if (! function_exists('switch_locale_url')) {
    // kompatibilitas: kalau kamu sudah pakai switch_locale_url sebelumnya
    function switch_locale_url(string $targetLocale): string
    {
        return locale_switch_data($targetLocale)['url'];
    }
}