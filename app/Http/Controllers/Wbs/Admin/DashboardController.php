<?php

namespace App\Http\Controllers\Wbs\Admin;

use App\Http\Controllers\Controller;
use App\Models\WbsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalReports = WbsReport::query()->count();

        $laporanMasuk = WbsReport::query()
            ->where('status', WbsReport::STATUS_LAPORAN_MASUK)
            ->count();

        $dalamProses = WbsReport::query()
            ->whereIn('status', [
                WbsReport::STATUS_DITELAAH,
                WbsReport::STATUS_PERLU_KLARIFIKASI,
                WbsReport::STATUS_DALAM_PROSES,
                WbsReport::STATUS_DALAM_INVESTIGASI,
            ])
            ->count();

        $selesai = WbsReport::query()
            ->whereIn('status', [
                WbsReport::STATUS_SELESAI,
                WbsReport::STATUS_DITUTUP,
                WbsReport::STATUS_DI_LUAR_RUANG_LINGKUP,
            ])
            ->count();

        $latestReports = WbsReport::query()
            ->with('user')
            ->latest('id')
            ->take(8)
            ->get();

        $categoryRaw = WbsReport::query()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $categoryLabels = $categoryRaw
            ->map(fn ($item) => WbsReport::categoryOptions()[$item->category] ?? ucfirst(str_replace('_', ' ', $item->category)))
            ->values();

        $categoryValues = $categoryRaw
            ->pluck('total')
            ->map(fn ($value) => (int) $value)
            ->values();

        $statusRaw = WbsReport::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $statusLabels = $statusRaw
            ->map(fn ($item) => WbsReport::statusOptions()[$item->status] ?? ucfirst(str_replace('_', ' ', $item->status)))
            ->values();

        $statusValues = $statusRaw
            ->pluck('total')
            ->map(fn ($value) => (int) $value)
            ->values();

        $monthlyRaw = WbsReport::query()
            ->selectRaw('DATE_FORMAT(submitted_at, "%Y-%m") as month_key, COUNT(*) as total')
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $monthlyLabels = collect();
        $monthlyValues = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');

            $monthlyLabels->push($date->translatedFormat('M Y'));
            $monthlyValues->push((int) ($monthlyRaw[$key]->total ?? 0));
        }

        return view('wbs.admin.dashboard', [
            'pageTitle' => 'Dashboard Admin WBS',
            'user' => Auth::user(),
            'totalReports' => $totalReports,
            'laporanMasuk' => $laporanMasuk,
            'dalamProses' => $dalamProses,
            'selesai' => $selesai,
            'latestReports' => $latestReports,
            'categoryLabels' => $categoryLabels,
            'categoryValues' => $categoryValues,
            'statusLabels' => $statusLabels,
            'statusValues' => $statusValues,
            'monthlyLabels' => $monthlyLabels,
            'monthlyValues' => $monthlyValues,
        ]);
    }
}