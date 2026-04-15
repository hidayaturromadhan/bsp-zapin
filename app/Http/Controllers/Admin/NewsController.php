<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $cat = $request->query('cat');
        $status = trim((string) $request->query('status'));

        $categories = NewsCategory::where('is_active', true)
            ->where('slug', '!=', 'tjsl')
            ->orderBy('sort_order')
            ->get();

        $news = News::query()
            ->with([
                'category',
                'author',
                'reviewer',
                'translations' => fn ($qr) => $qr->whereIn('locale', ['id', 'en']),
                'logs.user',
            ])
            ->withoutTjsl()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('translations', function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                      ->orWhere('excerpt', 'like', "%{$q}%");
                });
            })
            ->when($cat, fn ($qr) => $qr->where('news_category_id', $cat))
            ->when($status !== '', fn ($qr) => $qr->where('status', $status))
            ->orderByRaw("
                CASE status
                    WHEN 'in_review' THEN 1
                    WHEN 'rejected' THEN 2
                    WHEN 'draft' THEN 3
                    WHEN 'published' THEN 4
                    WHEN 'archived' THEN 5
                    ELSE 6
                END
            ")
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'categories', 'q', 'cat', 'status'));
    }

    public function show(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'reviewer',
            'images',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'logs.user',
        ]);

        $tId = $news->translations->firstWhere('locale', 'id');
        $tEn = $news->translations->firstWhere('locale', 'en');

        return view('admin.news.show', compact('news', 'tId', 'tEn'));
    }

    public function logs(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'reviewer',
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'logs.user',
        ]);

        return view('admin.news.logs', compact('news'));
    }
}