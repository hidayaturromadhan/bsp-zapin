<?php

namespace App\Http\Controllers\Admin;

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
            ->with(['translations', 'images', 'author', 'reviewer'])
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
                        })
                        ->orWhereHas('reviewer', function ($reviewer) use ($search) {
                            $reviewer->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => TjslProgram::count(),
            'draft' => TjslProgram::where('status', TjslProgram::STATUS_DRAFT)->count(),
            'submitted' => TjslProgram::where('status', TjslProgram::STATUS_SUBMITTED)->count(),
            'revision' => TjslProgram::where('status', TjslProgram::STATUS_REVISION)->count(),
            'approved' => TjslProgram::where('status', TjslProgram::STATUS_APPROVED)->count(),
            'rejected' => TjslProgram::where('status', TjslProgram::STATUS_REJECTED)->count(),
            'published' => TjslProgram::where('status', TjslProgram::STATUS_PUBLISHED)->count(),
        ];

        return view('admin.tjsl.index', [
            'programs' => $programs,
            'statuses' => TjslProgram::statuses(),
            'status' => $status,
            'search' => $search,
            'summary' => $summary,
        ]);
    }

    public function show(TjslProgram $tjsl)
    {
        $tjsl->load(['translations', 'images', 'author', 'reviewer']);

        return view('admin.tjsl.show', [
            'program' => $tjsl,
            'statuses' => TjslProgram::statuses(),
        ]);
    }
}