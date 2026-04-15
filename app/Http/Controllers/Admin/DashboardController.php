<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsAuditLog;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalNews' => News::count(),
            'published' => News::where('status', 'published')->count(),
            'inReview' => News::where('status', 'in_review')->count(),
            'rejected' => News::where('status', 'rejected')->count(),
            'logs' => class_exists(NewsAuditLog::class)
                ? NewsAuditLog::with('user', 'news')->latest()->take(10)->get()
                : collect(),
            'totalUsers' => User::count(),
            'totalLogs' => class_exists(NewsAuditLog::class)
                ? NewsAuditLog::count()
                : 0,
        ]);
    }
}