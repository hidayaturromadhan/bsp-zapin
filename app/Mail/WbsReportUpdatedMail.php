<?php

namespace App\Mail;

use App\Models\WbsReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WbsReportUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public WbsReport $report;

    public function __construct(WbsReport $report)
    {
        $this->report = $report;
    }

    public function build()
    {
        return $this
            ->subject('Update Status Laporan WBS - ' . $this->report->report_number)
            ->view('emails.wbs.report-updated');
    }
}