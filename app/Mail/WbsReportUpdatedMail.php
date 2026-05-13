<?php

namespace App\Mail;

use App\Models\WbsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WbsReportUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public WbsReport $report;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(WbsReport $report)
    {
        $this->report = $report;
    }

    public function build()
    {
        $this->report->loadMissing('user');

        return $this
            ->subject('Update Status Laporan WBS - ' . $this->report->report_number)
            ->view('emails.wbs.report-updated');
    }
}