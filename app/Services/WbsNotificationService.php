<?php

namespace App\Services;

use App\Models\User;
use App\Models\WbsNotification;
use App\Models\WbsReport;
use Illuminate\Support\Facades\Auth;

class WbsNotificationService
{
    public static function notifyAdminsReportCreated(WbsReport $report): void
    {
        $admins = User::query()
            ->whereIn('role', ['wbs_admin', 'wbs_officer'])
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            WbsNotification::create([
                'user_id' => $admin->id,
                'actor_id' => $report->user_id,
                'wbs_report_id' => $report->id,
                'title' => 'Laporan WBS baru',
                'message' => 'Laporan baru masuk: ' . $report->report_number,
                'url' => route('wbs.admin.reports.show', $report->id),
            ]);
        }
    }

    public static function notifyAdminsReportUpdatedByPelapor(WbsReport $report): void
    {
        $admins = User::query()
            ->whereIn('role', ['wbs_admin', 'wbs_officer'])
            ->where('is_active', true)
            ->get();

        foreach ($admins as $admin) {
            WbsNotification::create([
                'user_id' => $admin->id,
                'actor_id' => $report->user_id,
                'wbs_report_id' => $report->id,
                'title' => 'Laporan diperbarui pelapor',
                'message' => 'Pelapor memperbarui laporan: ' . $report->report_number,
                'url' => route('wbs.admin.reports.show', $report->id),
            ]);
        }
    }

    public static function notifyPelaporReportUpdatedByAdmin(WbsReport $report): void
    {
        WbsNotification::create([
            'user_id' => $report->user_id,
            'actor_id' => Auth::id(),
            'wbs_report_id' => $report->id,
            'title' => 'Status laporan diperbarui',
            'message' => 'Status laporan ' . $report->report_number . ' menjadi: ' . $report->status_label,
            'url' => route('wbs.pelapor.reports.show', $report->id),
        ]);
    }
}