<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $status = trim((string) $request->query('status'));

        $news = News::query()
            ->with([
                'category',
                'author',
                'reviewer',
                'translations' => fn ($query) => $query->whereIn('locale', ['id', 'en']),
                'images',
                'logs.user',
            ])
            ->withoutTjsl()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('translations', function ($translation) use ($q) {
                        $translation->where('title', 'like', "%{$q}%")
                            ->orWhere('excerpt', 'like', "%{$q}%")
                            ->orWhere('content', 'like', "%{$q}%");
                    })
                    ->orWhereHas('category', function ($category) use ($q) {
                        $category->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('author', function ($author) use ($q) {
                        $author->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderByRaw("
                CASE status
                    WHEN 'draft' THEN 1
                    WHEN 'published' THEN 2
                    WHEN 'archived' THEN 3
                    WHEN 'in_review' THEN 4
                    WHEN 'rejected' THEN 5
                    ELSE 6
                END
            ")
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('reviewer.news.index', compact('news', 'q', 'status'));
    }

    public function show(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'reviewer',
            'translations' => fn ($query) => $query->whereIn('locale', ['id', 'en']),
            'images',
            'logs.user',
        ]);

        return view('reviewer.news.show', compact('news'));
    }

    public function preview(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'translations' => fn ($query) => $query->whereIn('locale', ['id', 'en']),
            'images',
        ]);

        $locale = 'id';
        $translation = $news->getTranslationByLocale($locale);

        return view('web.news.preview', [
            'news' => $news,
            'locale' => $locale,
            'translation' => $translation,
            'metaTitle' => ($translation?->title ?: 'Preview News') . ' - BSP Zapin',
            'metaDescription' => $translation?->excerpt ?: 'Preview News BSP Zapin',
            'metaImage' => $news->featured_image ? asset($news->featured_image) : asset('images/logo.png'),
        ]);
    }

    public function logs(News $news)
    {
        abort_if(optional($news->category)->slug === 'tjsl', 404);

        $news->load([
            'category',
            'author',
            'translations' => fn ($query) => $query->whereIn('locale', ['id', 'en']),
            'logs.user',
        ]);

        return view('reviewer.news.logs', compact('news'));
    }
}