<?php

namespace App\Services;

use App\Models\ContentVersion;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\News;
use App\Models\NewsTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ContentVersionService
{
    /**
     * Snapshot 1 bundle (GLOBAL + ID + EN) untuk Page
     */
    public function snapshotPage(Page $page): string
    {
        $page->load(['translations' => fn($q) => $q->whereIn('locale', ['id','en'])]);

        $by = session('user_id');
        $bundleId = (string) Str::uuid();

        // GLOBAL
        ContentVersion::create([
            'bundle_id' => $bundleId,
            'entity_type' => 'page',
            'entity_id' => $page->id,
            'locale' => null,
            'payload' => [
                'is_active' => $page->is_active,
                'cover_image' => $page->cover_image,
                'parent_id' => $page->parent_id,
                'sort_order' => $page->sort_order,
                'menu_group' => $page->menu_group,
            ],
            'created_by' => $by,
        ]);

        // translations
        foreach (['id','en'] as $loc) {
            $t = $page->translations->firstWhere('locale', $loc);

            ContentVersion::create([
                'bundle_id' => $bundleId,
                'entity_type' => 'page',
                'entity_id' => $page->id,
                'locale' => $loc,
                'payload' => [
                    'title' => $t?->title,
                    'slug' => $t?->slug,
                    'content' => $t?->content,
                ],
                'created_by' => $by,
            ]);
        }

        return $bundleId;
    }

    /**
     * Restore 1 bundle untuk Page (GLOBAL + ID + EN)
     */
    public function restorePageBundle(Page $page, string $bundleId): void
    {
        $rows = ContentVersion::query()
            ->where('entity_type', 'page')
            ->where('entity_id', $page->id)
            ->where('bundle_id', $bundleId)
            ->get();

        if ($rows->isEmpty()) {
            abort(404, 'Bundle tidak ditemukan.');
        }

        DB::transaction(function () use ($page, $rows) {
            // GLOBAL dulu
            $global = $rows->firstWhere('locale', null);
            if ($global) {
                $p = $global->payload ?? [];
                $page->update([
                    'is_active' => (bool)($p['is_active'] ?? $page->is_active),
                    'cover_image' => $p['cover_image'] ?? $page->cover_image,
                    'parent_id' => $p['parent_id'] ?? $page->parent_id,
                    'sort_order' => $p['sort_order'] ?? $page->sort_order,
                    'menu_group' => $p['menu_group'] ?? $page->menu_group,
                ]);
            }

            // per locale
            foreach (['id','en'] as $loc) {
                $tRow = $rows->firstWhere('locale', $loc);
                if (!$tRow) continue;

                $p = $tRow->payload ?? [];
                PageTranslation::updateOrCreate(
                    ['page_id' => $page->id, 'locale' => $loc],
                    [
                        'title' => $p['title'] ?? '',
                        'slug' => $p['slug'] ?? '',
                        'content' => $p['content'] ?? null,
                    ]
                );
            }
        });
    }

    /**
     * Snapshot 1 bundle (GLOBAL + ID + EN) untuk News
     */
    public function snapshotNews(News $news): string
    {
        $news->load(['translations' => fn($q) => $q->whereIn('locale', ['id','en'])]);

        $by = session('user_id');
        $bundleId = (string) Str::uuid();

        // GLOBAL
        ContentVersion::create([
            'bundle_id' => $bundleId,
            'entity_type' => 'news',
            'entity_id' => $news->id,
            'locale' => null,
            'payload' => [
                'news_category_id' => $news->news_category_id,
                'featured_image' => $news->featured_image,
                'is_featured' => $news->is_featured,
                'is_visible' => $news->is_visible,
                'status' => $news->status,
                'published_at' => optional($news->published_at)->toISOString(),
            ],
            'created_by' => $by,
        ]);

        // translations
        foreach (['id','en'] as $loc) {
            $t = $news->translations->firstWhere('locale', $loc);

            ContentVersion::create([
                'bundle_id' => $bundleId,
                'entity_type' => 'news',
                'entity_id' => $news->id,
                'locale' => $loc,
                'payload' => [
                    'title' => $t?->title,
                    'slug' => $t?->slug,
                    'excerpt' => $t?->excerpt,
                    'content' => $t?->content,
                    'seo_title' => $t?->seo_title,
                    'seo_description' => $t?->seo_description,
                ],
                'created_by' => $by,
            ]);
        }

        return $bundleId;
    }

    /**
     * Restore 1 bundle untuk News (GLOBAL + ID + EN)
     */
    public function restoreNewsBundle(News $news, string $bundleId): void
    {
        $rows = ContentVersion::query()
            ->where('entity_type', 'news')
            ->where('entity_id', $news->id)
            ->where('bundle_id', $bundleId)
            ->get();

        if ($rows->isEmpty()) {
            abort(404, 'Bundle tidak ditemukan.');
        }

        DB::transaction(function () use ($news, $rows) {
            // GLOBAL dulu
            $global = $rows->firstWhere('locale', null);
            if ($global) {
                $p = $global->payload ?? [];
                $news->update([
                    'news_category_id' => $p['news_category_id'] ?? $news->news_category_id,
                    'featured_image' => $p['featured_image'] ?? $news->featured_image,
                    'is_featured' => (bool)($p['is_featured'] ?? $news->is_featured),
                    'is_visible' => (bool)($p['is_visible'] ?? $news->is_visible),
                    'status' => $p['status'] ?? $news->status,
                    'published_at' => ($p['published_at'] ?? null) ? \Carbon\Carbon::parse($p['published_at']) : $news->published_at,
                ]);
            }

            // per locale
            foreach (['id','en'] as $loc) {
                $tRow = $rows->firstWhere('locale', $loc);
                if (!$tRow) continue;

                $p = $tRow->payload ?? [];
                NewsTranslation::updateOrCreate(
                    ['news_id' => $news->id, 'locale' => $loc],
                    [
                        'title' => $p['title'] ?? '',
                        'slug' => $p['slug'] ?? '',
                        'excerpt' => $p['excerpt'] ?? null,
                        'content' => $p['content'] ?? null,
                        'seo_title' => $p['seo_title'] ?? null,
                        'seo_description' => $p['seo_description'] ?? null,
                    ]
                );
            }
        });
    }
}