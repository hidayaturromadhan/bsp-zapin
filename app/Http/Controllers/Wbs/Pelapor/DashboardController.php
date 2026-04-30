<?php

namespace App\Http\Controllers\Wbs\Pelapor;

use App\Http\Controllers\Controller;
use App\Models\WbsReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $totalReports = WbsReport::query()
            ->where('user_id', $user->id)
            ->count();

        $laporanMasuk = WbsReport::query()
            ->where('user_id', $user->id)
            ->where('status', WbsReport::STATUS_LAPORAN_MASUK)
            ->count();

        $dalamProses = WbsReport::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                WbsReport::STATUS_DITELAAH,
                WbsReport::STATUS_PERLU_KLARIFIKASI,
                WbsReport::STATUS_DALAM_PROSES,
                WbsReport::STATUS_DALAM_INVESTIGASI,
            ])
            ->count();

        $selesai = WbsReport::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                WbsReport::STATUS_SELESAI,
                WbsReport::STATUS_DITUTUP,
                WbsReport::STATUS_DI_LUAR_RUANG_LINGKUP,
            ])
            ->count();

        $latestReports = WbsReport::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->take(5)
            ->get();

        return view('wbs.pelapor.dashboard', [
            'pageTitle' => 'Dashboard Pelapor WBS',
            'user' => $user,
            'totalReports' => $totalReports,
            'laporanMasuk' => $laporanMasuk,
            'dalamProses' => $dalamProses,
            'selesai' => $selesai,
            'latestReports' => $latestReports,
        ]);
    }
}