<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\TjslProgram;
use Illuminate\Http\Request;

class TjslController extends Controller
{
    public function index(Request $request)
    {
        $status = trim((string) $request->query('status'));
        $search = trim((string) $request->query('search'));

        $programs = TjslProgram::query()
            ->with(['translations', 'images', 'author'])
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('year', 'like', "%{$search}%")
                        ->orWhereHas('translations', function ($tr) use ($search) {
                            $tr->where('title', 'like', "%{$search}%")
                                ->orWhere('summary', 'like', "%{$search}%")
                                ->orWhere('content', 'like', "%{$search}%");
                        })
                        ->orWhereHas('author', function ($author) use ($search) {
                            $author->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('reviewer.tjsl.index', [
            'programs' => $programs,
            'statuses' => TjslProgram::statuses(),
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(TjslProgram $tjsl)
    {
        $tjsl->load(['translations', 'images', 'author']);

        return view('reviewer.tjsl.show', [
            'program' => $tjsl,
            'statuses' => TjslProgram::statuses(),
        ]);
    }

    public function preview(TjslProgram $tjsl)
    {
        $tjsl->load([
            'translations' => fn ($q) => $q->whereIn('locale', ['id', 'en']),
            'images' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            'author',
        ]);

        return view('web.tjsl.index', [
            'locale' => 'id',
            'programs' => collect([$tjsl]),
            'activeProgram' => $tjsl,
            'activeTranslation' => $tjsl->getTranslation('id'),
            'metaTitle' => 'Preview TJSL - BSP Zapin',
            'metaDescription' => $tjsl->getTranslation('id')?->summary ?? 'Preview TJSL',
            'metaImage' => $tjsl->featured_image ? asset($tjsl->featured_image) : asset('images/logo.png'),
        ]);
    }
}