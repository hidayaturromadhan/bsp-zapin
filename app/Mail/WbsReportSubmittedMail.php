<?php

namespace App\Mail;

use App\Models\WbsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WbsReportSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public WbsReport $report;
    public string $action;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(WbsReport $report, string $action = 'created')
    {
        $this->report = $report;
        $this->action = $action;
    }

    public function build()
    {
        $this->report->loadMissing('user');

        $subject = $this->action === 'updated'
            ? 'Laporan WBS Diperbarui Pelapor - ' . $this->report->report_number
            : 'Laporan WBS Baru - ' . $this->report->report_number;

        $mail = $this
            ->subject($subject)
            ->view('emails.wbs.report-submitted');

        if ($this->report->user && $this->report->user->email) {
            $mail->replyTo(
                $this->report->user->email,
                $this->report->user->name ?? $this->report->user->email
            );
        }

        return $mail;
    }
}