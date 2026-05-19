<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache statistik utama 5 menit
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            $hasLogs = class_exists(NewsAuditLog::class);

            return [
                'totalNews'    => News::count(),
                'published'    => News::where('status', 'published')->count(),
                'inReview'     => News::where('status', 'in_review')->count(),
                'rejected'     => News::where('status', 'rejected')->count(),
                'draft'        => News::where('status', 'draft')->count(),
                'totalUsers'   => User::count(),
                'activeUsers'  => User::where('is_active', true)->count(),
                'totalLogs'    => $hasLogs ? NewsAuditLog::count() : 0,
            ];
        });

        // Cache data grafik news 7 hari terakhir
        $chartNews = Cache::remember('admin_dashboard_chart_news', 600, function () {
            $days = collect();

            for ($i = 6; $i >= 0; $i--) {
                $targetDate = now()->subDays($i);

                $days->push([
                    'date'  => $targetDate->format('d M'),
                    'count' => News::whereDate('created_at', $targetDate->toDateString())->count(),
                ]);
            }

            return $days;
        });

        // Cache data grafik status
        $chartStatus = Cache::remember('admin_dashboard_chart_status', 600, function () {
            return [
                'published' => News::where('status', 'published')->count(),
                'in_review' => News::where('status', 'in_review')->count(),
                'draft'     => News::where('status', 'draft')->count(),
                'rejected'  => News::where('status', 'rejected')->count(),
            ];
        });

        // Cache aktivitas terakhir
        $logs = Cache::remember('admin_dashboard_logs', 120, function () {
            if (!class_exists(NewsAuditLog::class)) {
                return collect();
            }

            return NewsAuditLog::with(['user', 'news'])
                ->latest()
                ->take(10)
                ->get();
        });

        return view('admin.dashboard', array_merge($stats, [
            'logs'        => $logs,
            'chartNews'   => $chartNews,
            'chartStatus' => $chartStatus,
        ]));
    }
}