<?php

namespace App\Http\Controllers\Wbs\Pelapor;

use App\Http\Controllers\Controller;
use App\Mail\WbsReportSubmittedMail;
use App\Models\WbsReport;
use App\Models\WbsReportAttachment;
use App\Services\WbsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->query('search'));
        $status = trim((string) $request->query('status'));

        $reports = WbsReport::query()
            ->where('user_id', $user->id)
            ->withCount('attachments')
            ->search($search)
            ->filterStatus($status)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('wbs.pelapor.reports.index', [
            'pageTitle' => 'Laporan Saya',
            'reports' => $reports,
            'statusOptions' => WbsReport::statusOptions(),
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function create()
    {
        return view('wbs.pelapor.reports.create', [
            'pageTitle' => 'Buat Laporan WBS',
            'categoryOptions' => WbsReport::categoryOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'auth' => 'Sesi login tidak ditemukan. Silakan login kembali.',
                ]);
        }

        $data = $this->validateReport($request);

        try {
            $report = DB::transaction(function () use ($request, $data, $user) {
                $report = WbsReport::create([
                    'report_number' => $this->generateReportNumber(),
                    'user_id' => $user->id,
                    'category' => $data['category'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'involved_parties' => $data['involved_parties'] ?? null,
                    'location' => $data['location'] ?? null,
                    'incident_date' => $data['incident_date'] ?? null,
                    'chronology' => $data['chronology'] ?? null,
                    'estimated_loss' => $data['estimated_loss'] ?? null,
                    'has_evidence' => (bool) ($data['has_evidence'] ?? false),
                    'reported_before' => (bool) ($data['reported_before'] ?? false),
                    'reported_to_other_party' => (bool) ($data['reported_to_other_party'] ?? false),
                    'status' => WbsReport::STATUS_LAPORAN_MASUK,
                    'submitted_at' => now(),
                ]);

                $this->storeAttachments($request, $report);

                return $report;
            });

            WbsNotificationService::notifyAdminsReportCreated($report);
            $this->sendReportNotificationToAdmin($report, 'created');

            return redirect()
                ->route('wbs.pelapor.reports.show', $report->id)
                ->with('success', 'Laporan berhasil dikirim.');
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan laporan WBS pelapor', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => $user->id,
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'report' => app()->environment('local')
                        ? 'Gagal menyimpan laporan: ' . $e->getMessage()
                        : 'Gagal menyimpan laporan.',
                ]);
        }
    }

    public function show(WbsReport $report)
    {
        $this->authorizePelaporReport($report);

        $report->load('attachments');

        return view('wbs.pelapor.reports.show', [
            'pageTitle' => 'Detail Laporan WBS',
            'report' => $report,
        ]);
    }

    public function edit(WbsReport $report)
    {
        $this->authorizePelaporReport($report);

        if (! $report->canBeEditedByPelapor()) {
            return redirect()
                ->route('wbs.pelapor.reports.show', $report->id)
                ->withErrors([
                    'report' => 'Laporan tidak dapat diubah karena sudah mulai ditangani oleh admin WBS.',
                ]);
        }

        $report->load('attachments');

        return view('wbs.pelapor.reports.edit', [
            'pageTitle' => 'Edit Laporan WBS',
            'report' => $report,
            'categoryOptions' => WbsReport::categoryOptions(),
        ]);
    }

    public function update(Request $request, WbsReport $report)
    {
        $this->authorizePelaporReport($report);

        if (! $report->canBeEditedByPelapor()) {
            return redirect()
                ->route('wbs.pelapor.reports.show', $report->id)
                ->withErrors([
                    'report' => 'Laporan tidak dapat diperbarui karena sudah mulai ditangani oleh admin WBS.',
                ]);
        }

        $data = $this->validateReport($request);

        try {
            DB::transaction(function () use ($request, $report, $data) {
                $report->update([
                    'category' => $data['category'],
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'involved_parties' => $data['involved_parties'] ?? null,
                    'location' => $data['location'] ?? null,
                    'incident_date' => $data['incident_date'] ?? null,
                    'chronology' => $data['chronology'] ?? null,
                    'estimated_loss' => $data['estimated_loss'] ?? null,
                    'has_evidence' => (bool) ($data['has_evidence'] ?? false),
                    'reported_before' => (bool) ($data['reported_before'] ?? false),
                    'reported_to_other_party' => (bool) ($data['reported_to_other_party'] ?? false),
                ]);

                if ($request->filled('delete_attachment_ids')) {
                    $attachmentIds = array_filter((array) $request->input('delete_attachment_ids'));
                    $attachments = $report->attachments()->whereIn('id', $attachmentIds)->get();

                    foreach ($attachments as $attachment) {
                        $this->deletePhysicalFile($attachment->file_path);
                        $attachment->delete();
                    }
                }

                $this->storeAttachments($request, $report);
            });

            WbsNotificationService::notifyAdminsReportUpdatedByPelapor($report);
            $this->sendReportNotificationToAdmin($report, 'updated');

            return redirect()
                ->route('wbs.pelapor.reports.show', $report->id)
                ->with('success', 'Laporan berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('Gagal update laporan WBS pelapor', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'report_id' => $report->id,
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'report' => app()->environment('local')
                        ? 'Gagal memperbarui laporan: ' . $e->getMessage()
                        : 'Gagal memperbarui laporan.',
                ]);
        }
    }

    public function destroy(WbsReport $report)
    {
        $this->authorizePelaporReport($report);

        if (! $report->canBeEditedByPelapor()) {
            return redirect()
                ->route('wbs.pelapor.reports.show', $report->id)
                ->withErrors([
                    'report' => 'Laporan tidak dapat dihapus karena sudah mulai ditangani oleh admin WBS.',
                ]);
        }

        try {
            DB::transaction(function () use ($report) {
                $report->load('attachments');

                foreach ($report->attachments as $attachment) {
                    $this->deletePhysicalFile($attachment->file_path);
                }

                $directory = public_path('uploads/wbs/reports/' . $report->id);
                if (is_dir($directory)) {
                    File::deleteDirectory($directory);
                }

                $report->delete();
            });

            return redirect()
                ->route('wbs.pelapor.reports.index')
                ->with('success', 'Laporan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('Gagal hapus laporan WBS pelapor', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'report_id' => $report->id,
                'user_id' => Auth::id(),
            ]);

            return back()->withErrors([
                'report' => app()->environment('local')
                    ? 'Gagal menghapus laporan: ' . $e->getMessage()
                    : 'Gagal menghapus laporan.',
            ]);
        }
    }

    protected function validateReport(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'in:' . implode(',', array_keys(WbsReport::categoryOptions()))],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'involved_parties' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'incident_date' => ['nullable', 'date'],
            'chronology' => ['nullable', 'string'],
            'estimated_loss' => ['nullable', 'string', 'max:255'],
            'has_evidence' => ['nullable', 'boolean'],
            'reported_before' => ['nullable', 'boolean'],
            'reported_to_other_party' => ['nullable', 'boolean'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx', 'max:5120'],
            'delete_attachment_ids' => ['nullable', 'array'],
            'delete_attachment_ids.*' => ['nullable', 'integer'],
        ]);
    }

    protected function authorizePelaporReport(WbsReport $report): void
    {
        abort_if((int) $report->user_id !== (int) Auth::id(), 403, 'Anda tidak memiliki akses ke laporan ini.');
    }

    protected function generateReportNumber(): string
    {
        do {
            $number = 'WBS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (WbsReport::query()->where('report_number', $number)->exists());

        return $number;
    }

    protected function storeAttachments(Request $request, WbsReport $report): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        $uploadDirectory = public_path('uploads/wbs/reports/' . $report->id);

        if (! is_dir($uploadDirectory)) {
            File::ensureDirectoryExists($uploadDirectory, 0775, true);
        }

        foreach ($request->file('attachments') as $file) {
            if (! $file || ! $file->isValid()) {
                throw new \RuntimeException('Salah satu file lampiran tidak valid.');
            }

            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            $extension = $file->getClientOriginalExtension() ?: 'bin';
            $safeFileName = time() . '_' . Str::random(12) . '.' . $extension;

            $file->move($uploadDirectory, $safeFileName);

            WbsReportAttachment::create([
                'wbs_report_id' => $report->id,
                'original_name' => $originalName,
                'file_name' => $safeFileName,
                'file_path' => 'uploads/wbs/reports/' . $report->id . '/' . $safeFileName,
                'file_disk' => 'public_path',
                'mime_type' => $mimeType,
                'file_size' => $fileSize ?: 0,
            ]);
        }
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

    protected function sendReportNotificationToAdmin(WbsReport $report, string $action): void
    {
        try {
            $report->refresh();
            $report->load('user');

            Mail::to(config('wbs.admin_email'))
                ->send(new WbsReportSubmittedMail($report, $action));
        } catch (Throwable $mailError) {
            Log::error('Gagal mengirim email notifikasi laporan WBS ke admin', [
                'message' => $mailError->getMessage(),
                'report_id' => $report->id,
                'action' => $action,
            ]);
        }
    }
}