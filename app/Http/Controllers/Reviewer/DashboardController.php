<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\News;

class DashboardController extends Controller
{
    public function index()
    {
        return view('reviewer.dashboard', [
            'pending' => News::where('status', 'in_review')->count(),
            'approvedToday' => News::where('status', 'published')
                ->whereDate('reviewed_at', today())
                ->count(),
            'rejected' => News::where('status', 'rejected')->count(),
            'pendingNews' => News::with(['translations', 'author'])
                ->where('status', 'in_review')
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}