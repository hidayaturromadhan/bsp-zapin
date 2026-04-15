<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->session()->get('user_id');

        return view('writer.dashboard', [
            'draft' => News::where('status', 'draft')->count(),
            'inReview' => News::where('status', 'in_review')->count(),
            'published' => News::where('status', 'published')->count(),
            'myNews' => News::with('translations')
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}