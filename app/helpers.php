<?php

use App\Models\NewsTranslation;
use App\Models\PageTranslation;
use App\Models\NewsAuditLog;

if (! function_exists('current_locale')) {
    function current_locale(): string
    {
        $seg = request()->segment(1);
        return in_array($seg, ['id', 'en'], true) ? $seg : 'id';
    }
}

if (! function_exists('current_role')) {
    function current_role(): ?string
    {
        return session('user_role');
    }
}

if (! function_exists('is_admin')) {
    function is_admin(): bool
    {
        return current_role() === 'admin';
    }
}

if (! function_exists('is_reviewer')) {
    function is_reviewer(): bool
    {
        return in_array(current_role(), ['admin', 'reviewer'], true);
    }
}

if (! function_exists('is_writer')) {
    function is_writer(): bool
    {
        return current_role() === 'writer';
    }
}

if (! function_exists('is_admin_panel_user')) {
    function is_admin_panel_user(): bool
    {
        return in_array(current_role(), ['admin', 'reviewer', 'writer'], true);
    }
}

if (! function_exists('display_role_name')) {
    function display_role_name(?string $role = null): string
    {
        $role = $role ?: current_role();

        return match ($role) {
            'admin' => 'Administrator',
            'reviewer' => 'Reviewer',
            'writer' => 'Writer',
            'wbs_officer' => 'WBS Officer',
            default => 'User',
        };
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
     * available=false artinya pasangan translation tidak ada.
     */
    function locale_switch_data(string $targetLocale): array
    {
        $targetLocale = in_array($targetLocale, ['id', 'en'], true) ? $targetLocale : 'id';

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
                return [
                    'url' => route('web.home', ['locale' => $targetLocale]),
                    'available' => true,
                ];
            }

            $current = PageTranslation::query()
                ->select(['id', 'page_id', 'locale', 'slug'])
                ->where('locale', $currentLocale)
                ->where('slug', $slug)
                ->first();

            if (! $current) {
                return [
                    'url' => route('web.home', ['locale' => $targetLocale]),
                    'available' => true,
                ];
            }

            $other = PageTranslation::query()
                ->select(['id', 'page_id', 'locale', 'slug'])
                ->where('page_id', $current->page_id)
                ->where('locale', $targetLocale)
                ->first();

            if ($other) {
                return [
                    'url' => route('page.show', [
                        'locale' => $targetLocale,
                        'slug' => $other->slug,
                    ]),
                    'available' => true,
                ];
            }

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
                return [
                    'url' => route('web.home', ['locale' => $targetLocale]),
                    'available' => true,
                ];
            }

            $current = NewsTranslation::query()
                ->select(['id', 'news_id', 'locale', 'slug'])
                ->where('locale', $currentLocale)
                ->where('slug', $slug)
                ->first();

            if (! $current) {
                return [
                    'url' => route('web.home', ['locale' => $targetLocale]),
                    'available' => true,
                ];
            }

            $other = NewsTranslation::query()
                ->select(['id', 'news_id', 'locale', 'slug'])
                ->where('news_id', $current->news_id)
                ->where('locale', $targetLocale)
                ->first();

            if ($other) {
                return [
                    'url' => route('news.show', [
                        'locale' => $targetLocale,
                        'slug' => $other->slug,
                    ]),
                    'available' => true,
                ];
            }

            return [
                'url' => route('web.home', ['locale' => $targetLocale]),
                'available' => false,
            ];
        }

        // ========== ROUTE YANG PUNYA LOCALE ==========
        if (isset($params['locale'])) {
            $params['locale'] = $targetLocale;

            try {
                return [
                    'url' => route($name, $params),
                    'available' => true,
                ];
            } catch (\Throwable $e) {
                return [
                    'url' => route('web.home', ['locale' => $targetLocale]),
                    'available' => true,
                ];
            }
        }

        return [
            'url' => route('web.home', ['locale' => $targetLocale]),
            'available' => true,
        ];
    }
}

if (! function_exists('switch_locale_url')) {
    function switch_locale_url(string $targetLocale): string
    {
        return locale_switch_data($targetLocale)['url'];
    }
}

if (! function_exists('news_log')) {
    function news_log(int $newsId, string $action, ?string $note = null): void
    {
        NewsAuditLog::create([
            'news_id' => $newsId,
            'user_id' => session('user_id'),
            'action' => $action,
            'note' => $note,
        ]);
    }
}