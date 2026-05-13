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

        $laporanBulanIni = WbsReport::query()
            ->whereNotNull('submitted_at')
            ->whereBetween('submitted_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        $laporanHariIni = WbsReport::query()
            ->whereNotNull('submitted_at')
            ->whereDate('submitted_at', today())
            ->count();

        $butuhTindakLanjut = WbsReport::query()
            ->whereIn('status', [
                WbsReport::STATUS_LAPORAN_MASUK,
                WbsReport::STATUS_DITELAAH,
                WbsReport::STATUS_PERLU_KLARIFIKASI,
                WbsReport::STATUS_DALAM_PROSES,
                WbsReport::STATUS_DALAM_INVESTIGASI,
            ])
            ->count();

        $latestReports = WbsReport::query()
            ->with('user')
            ->latest('id')
            ->take(8)
            ->get();

        /*
         * Grafik kategori dibatasi maksimal 6 kategori.
         * Jika lebih dari 6, sisanya digabung menjadi "Lainnya".
         */
        $categoryRaw = WbsReport::query()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $categoryOptions = WbsReport::categoryOptions();

        $categoryLimited = $categoryRaw->take(6);
        $categoryOthers = $categoryRaw->slice(6)->sum('total');

        $categoryLabels = $categoryLimited
            ->map(fn ($item) => $categoryOptions[$item->category] ?? ucfirst(str_replace('_', ' ', $item->category)))
            ->values();

        $categoryValues = $categoryLimited
            ->pluck('total')
            ->map(fn ($value) => (int) $value)
            ->values();

        if ($categoryOthers > 0) {
            $categoryLabels->push('Lainnya');
            $categoryValues->push((int) $categoryOthers);
        }

        /*
         * Grafik status dibatasi maksimal 6 status.
         * Jika lebih dari 6, sisanya digabung menjadi "Lainnya".
         */
        $statusRaw = WbsReport::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $statusOptions = WbsReport::statusOptions();

        $statusLimited = $statusRaw->take(6);
        $statusOthers = $statusRaw->slice(6)->sum('total');

        $statusLabels = $statusLimited
            ->map(fn ($item) => $statusOptions[$item->status] ?? ucfirst(str_replace('_', ' ', $item->status)))
            ->values();

        $statusValues = $statusLimited
            ->pluck('total')
            ->map(fn ($value) => (int) $value)
            ->values();

        if ($statusOthers > 0) {
            $statusLabels->push('Lainnya');
            $statusValues->push((int) $statusOthers);
        }

        /*
         * Trend laporan dibatasi 6 bulan terakhir agar grafik tetap bersih.
         */
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
            'laporanBulanIni' => $laporanBulanIni,
            'laporanHariIni' => $laporanHariIni,
            'butuhTindakLanjut' => $butuhTindakLanjut,

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