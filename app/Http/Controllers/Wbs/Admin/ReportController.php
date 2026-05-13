<?php

namespace App\Http\Controllers\Wbs\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WbsReportUpdatedMail;
use App\Models\User;
use App\Models\WbsReport;
use App\Services\WbsNotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = trim((string) $request->query('status'));
        $category = trim((string) $request->query('category'));
        $userId = trim((string) $request->query('user_id'));
        $month = trim((string) $request->query('month'));
        $year = trim((string) $request->query('year'));

        $reports = $this->filteredReportsQuery($search, $status, $category, $userId, $month, $year)
            ->paginate(10)
            ->withQueryString();

        $pelaporUsers = User::query()
            ->whereIn('role', ['pelapor', 'user'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('wbs.admin.reports.index', [
            'pageTitle' => 'Monitoring Laporan WBS',
            'reports' => $reports,
            'pelaporUsers' => $pelaporUsers,
            'statusOptions' => WbsReport::statusOptions(),
            'categoryOptions' => WbsReport::categoryOptions(),
            'monthOptions' => $this->monthOptions(),
            'yearOptions' => $this->yearOptions(),
            'filters' => [
                'search' => $search,
                'status' => $status,
                'category' => $category,
                'user_id' => $userId,
                'month' => $month,
                'year' => $year,
            ],
        ]);
    }

    public function show(WbsReport $report)
    {
        $report->load(['user', 'attachments']);

        return view('wbs.admin.reports.show', [
            'pageTitle' => 'Detail Laporan WBS',
            'report' => $report,
            'statusOptions' => WbsReport::statusOptions(),
        ]);
    }

    public function edit(WbsReport $report)
    {
        $report->load(['user', 'attachments']);

        return view('wbs.admin.reports.edit', [
            'pageTitle' => 'Update Status Laporan WBS',
            'report' => $report,
            'statusOptions' => WbsReport::statusOptions(),
        ]);
    }

    public function update(Request $request, WbsReport $report)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(WbsReport::statusOptions()))],
            'admin_notes' => ['nullable', 'string'],
            'follow_up_result' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $report->status = $data['status'];
            $report->admin_notes = $data['admin_notes'] ?? null;
            $report->follow_up_result = $data['follow_up_result'] ?? null;

            if (in_array($report->status, [
                WbsReport::STATUS_DITELAAH,
                WbsReport::STATUS_PERLU_KLARIFIKASI,
                WbsReport::STATUS_DALAM_PROSES,
                WbsReport::STATUS_DALAM_INVESTIGASI,
            ], true) && ! $report->processed_at) {
                $report->processed_at = now();
            }

            if (in_array($report->status, [
                WbsReport::STATUS_SELESAI,
                WbsReport::STATUS_DITUTUP,
                WbsReport::STATUS_DI_LUAR_RUANG_LINGKUP,
            ], true)) {
                $report->closed_at = now();
            } else {
                $report->closed_at = null;
            }

            $report->save();

            DB::commit();

            WbsNotificationService::notifyPelaporReportUpdatedByAdmin($report);

            /*
            |--------------------------------------------------------------------------
            | QUEUE EMAIL UPDATE STATUS KE PELAPOR
            |--------------------------------------------------------------------------
            | Email tidak dikirim langsung saat admin update status.
            | Email dimasukkan ke tabel jobs dan diproses oleh queue worker / cron.
            |--------------------------------------------------------------------------
            */
            $this->sendReportUpdatedNotificationToPelapor($report);

            return redirect()
                ->route('wbs.admin.reports.show', $report->id)
                ->with('success', 'Status dan catatan laporan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui status laporan WBS admin', [
                'message' => $e->getMessage(),
                'report_id' => $report->id,
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'report' => 'Gagal memperbarui status laporan.',
                ]);
        }
    }

    public function updateStatus(Request $request, WbsReport $report)
    {
        return $this->update($request, $report);
    }

    public function destroy(WbsReport $report)
    {
        DB::beginTransaction();

        try {
            $report->load('attachments');

            foreach ($report->attachments as $attachment) {
                $this->deletePhysicalFile($attachment->file_path);
            }

            $uploadDir = public_path('uploads/wbs/reports/' . $report->id);
            if (is_dir($uploadDir)) {
                File::deleteDirectory($uploadDir);
            }

            if ($report->pdf_path) {
                $this->deletePhysicalFile($report->pdf_path);
            }

            $report->delete();

            DB::commit();

            return redirect()
                ->route('wbs.admin.reports.index')
                ->with('success', 'Laporan berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menghapus laporan WBS admin', [
                'message' => $e->getMessage(),
                'report_id' => $report->id,
            ]);

            return back()->withErrors([
                'report' => 'Gagal menghapus laporan.',
            ]);
        }
    }

    public function exportPdf(WbsReport $report)
    {
        $report->load(['user', 'attachments']);

        $pdf = Pdf::loadView('wbs.admin.reports.pdf', [
            'report' => $report,
        ])->setPaper('a4', 'portrait');

        $directory = public_path('generated/wbs/reports');

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $safeReportNumber = preg_replace('/[^A-Za-z0-9\-_]/', '-', $report->report_number);
        $fileName = 'WBS-' . $safeReportNumber . '.pdf';
        $relativePath = 'generated/wbs/reports/' . $fileName;
        $fullPath = public_path($relativePath);

        file_put_contents($fullPath, $pdf->output());

        $report->pdf_path = $relativePath;
        $report->save();

        return redirect()
            ->route('wbs.admin.reports.show', $report->id)
            ->with('success', 'PDF berhasil dibuat.')
            ->with('pdf_url', asset($relativePath));
    }

    public function exportFilteredPdf(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = trim((string) $request->query('status'));
        $category = trim((string) $request->query('category'));
        $userId = trim((string) $request->query('user_id'));
        $month = trim((string) $request->query('month'));
        $year = trim((string) $request->query('year'));

        $reports = $this->filteredReportsQuery($search, $status, $category, $userId, $month, $year)->get();

        if ($reports->isEmpty()) {
            return redirect()
                ->route('wbs.admin.reports.index', $request->query())
                ->withErrors([
                    'report' => 'Tidak ada data laporan sesuai filter untuk diexport.',
                ]);
        }

        $selectedPelapor = $userId !== '' ? User::query()->find($userId) : null;

        $pdf = Pdf::loadView('wbs.admin.reports.pdf-filtered', [
            'reports' => $reports,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'category' => $category,
                'user_id' => $userId,
                'month' => $month,
                'year' => $year,
            ],
            'statusOptions' => WbsReport::statusOptions(),
            'categoryOptions' => WbsReport::categoryOptions(),
            'monthOptions' => $this->monthOptions(),
            'yearOptions' => $this->yearOptions(),
            'selectedPelapor' => $selectedPelapor,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        $directory = public_path('generated/wbs/reports');

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $fileName = 'WBS-filtered-' . now()->format('Ymd-His') . '.pdf';
        $relativePath = 'generated/wbs/reports/' . $fileName;
        $fullPath = public_path($relativePath);

        file_put_contents($fullPath, $pdf->output());

        return redirect()
            ->route('wbs.admin.reports.index', $request->query())
            ->with('success', 'PDF laporan sesuai filter berhasil dibuat.')
            ->with('pdf_url', asset($relativePath));
    }

    protected function filteredReportsQuery(
        ?string $search,
        ?string $status,
        ?string $category,
        ?string $userId,
        ?string $month = null,
        ?string $year = null
    ): Builder {
        return WbsReport::query()
            ->with('user')
            ->withCount('attachments')
            ->when(filled($search), function (Builder $query) use ($search) {
                $query->where(function (Builder $sub) use ($search) {
                    $sub->where('report_number', 'like', '%' . $search . '%')
                        ->orWhere('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when(filled($status), fn (Builder $query) => $query->where('status', $status))
            ->when(filled($category), fn (Builder $query) => $query->where('category', $category))
            ->when(filled($userId), fn (Builder $query) => $query->where('user_id', $userId))
            ->when(filled($month), fn (Builder $query) => $query->whereMonth('submitted_at', (int) $month))
            ->when(filled($year), fn (Builder $query) => $query->whereYear('submitted_at', (int) $year))
            ->latest('id');
    }

    protected function monthOptions(): array
    {
        return [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }

    protected function yearOptions(): array
    {
        $currentYear = (int) now()->format('Y');
        $startYear = $currentYear - 5;
        $endYear = $currentYear + 1;

        $years = [];

        for ($year = $endYear; $year >= $startYear; $year--) {
            $years[(string) $year] = (string) $year;
        }

        return $years;
    }

    protected function deletePhysicalFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $absolutePath = public_path($relativePath);

        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    protected function sendReportUpdatedNotificationToPelapor(WbsReport $report): void
    {
        try {
            $report->refresh();
            $report->load('user');

            if (! $report->user || ! $report->user->email) {
                Log::warning('Email pelapor tidak tersedia untuk notifikasi update WBS.', [
                    'report_id' => $report->id,
                ]);

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | QUEUE EMAIL UPDATE STATUS KE PELAPOR
            |--------------------------------------------------------------------------
            | Email dimasukkan ke tabel jobs dan dikirim oleh queue worker.
            |--------------------------------------------------------------------------
            */
            Mail::to($report->user->email)
                ->queue(new WbsReportUpdatedMail($report));
        } catch (\Throwable $mailError) {
            Log::error('Gagal memasukkan email notifikasi update WBS ke queue pelapor', [
                'message' => $mailError->getMessage(),
                'report_id' => $report->id,
            ]);
        }
    }
}