<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id() ?? $request->session()->get('user_id');

        $baseQuery = News::query();

        /*
         * Jika tabel news kamu punya kolom writer_id / created_by / user_id,
         * kamu bisa aktifkan filter ini sesuai nama kolom yang benar.
         *
         * Contoh:
         * $baseQuery->where('writer_id', $userId);
         *
         * Saat ini dibiarkan global agar tidak merusak mekanisme yang sudah ada.
         */

        $draft = (clone $baseQuery)
            ->where('status', 'draft')
            ->count();

        $inReview = (clone $baseQuery)
            ->where('status', 'pending_review')
            ->count();

        $published = (clone $baseQuery)
            ->where('status', 'published')
            ->count();

        $rejected = (clone $baseQuery)
            ->where('status', 'rejected')
            ->count();

        $totalNews = (clone $baseQuery)
            ->count();

        $myNews = News::query()
            ->with(['translations', 'category'])
            ->latest()
            ->take(8)
            ->get();

        return view('writer.dashboard', [
            'draft' => $draft,
            'inReview' => $inReview,
            'published' => $published,
            'rejected' => $rejected,
            'totalNews' => $totalNews,
            'myNews' => $myNews,
        ]);
    }
}