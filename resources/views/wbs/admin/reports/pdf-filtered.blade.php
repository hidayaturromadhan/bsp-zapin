<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Laporan WBS</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 24px 24px 34px 24px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .page {
            width: 100%;
        }

        .top-header {
            width: 100%;
            border-bottom: 3px solid #173f08;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .top-header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .top-header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 64px;
        }

        .logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
        }

        .company-title {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #173f08;
            line-height: 1.2;
        }

        .company-subtitle {
            margin: 2px 0 0;
            font-size: 10px;
            color: #6b7280;
            font-style: italic;
        }

        .document-meta {
            text-align: right;
            font-size: 9px;
            color: #6b7280;
            line-height: 1.5;
        }

        .document-title-box {
            margin: 14px 0 12px;
            padding: 12px 14px;
            background: #f8fbf7;
            border: 1px solid #dbe8d5;
            border-left: 5px solid #173f08;
            border-radius: 6px;
        }

        .document-title {
            margin: 0;
            font-size: 19px;
            line-height: 1.25;
            font-weight: bold;
            color: #111827;
        }

        .document-subtitle {
            margin: 4px 0 0;
            font-size: 10.5px;
            color: #4b5563;
        }

        .section-title {
            margin: 0 0 7px;
            font-size: 11px;
            font-weight: bold;
            color: #173f08;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .table-note {
            margin: 0 0 7px;
            font-size: 9.2px;
            color: #6b7280;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #d1d5db;
            padding: 5px 5px;
            vertical-align: top;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .report-table th {
            background: #173f08;
            color: #ffffff;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: .015em;
        }

        .report-table td {
            font-size: 8px;
            color: #111827;
        }

        .report-table tbody tr:nth-child(even) td {
            background: #f9fafb;
        }

        .col-number {
            width: 13%;
        }

        .col-pelapor {
            width: 12%;
        }

        .col-email {
            width: 15%;
        }

        .col-category {
            width: 12%;
        }

        .col-title {
            width: 21%;
        }

        .col-status {
            width: 10%;
        }

        .col-attachment {
            width: 7%;
            text-align: center;
        }

        .col-date {
            width: 10%;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 999px;
            font-size: 7.6px;
            font-weight: bold;
            background: #eef5eb;
            color: #173f08;
            border: 1px solid #dbe8d5;
        }

        .attachment-count {
            display: inline-block;
            min-width: 16px;
            padding: 2px 4px;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            text-align: center;
            font-weight: bold;
        }

        .empty-row td {
            padding: 18px 10px;
            text-align: center;
            color: #6b7280;
            background: #f9fafb;
            font-style: italic;
        }

        .footer {
            position: fixed;
            left: 24px;
            right: 24px;
            bottom: 12px;
            border-top: 1px solid #d1d5db;
            padding-top: 6px;
            font-size: 8px;
            color: #6b7280;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            border: none;
            padding: 0;
        }

        .footer-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
@php
    $logoPath = public_path('images/logo.png');
@endphp

<div class="footer">
    <table class="footer-table">
        <tr>
            <td>
                PT Bumi Siak Pusako Zapin - Whistleblowing System
            </td>
            <td class="footer-right">
                Dokumen ini dicetak otomatis oleh sistem WBS
            </td>
        </tr>
    </table>
</div>

<div class="page">
    <div class="top-header">
        <table class="top-header-table">
            <tr>
                <td class="logo-cell">
                    @if(file_exists($logoPath))
                        <img src="{{ $logoPath }}" alt="Logo BSP Zapin" class="logo">
                    @endif
                </td>

                <td>
                    <h1 class="company-title">PT Bumi Siak Pusako Zapin</h1>
                    <p class="company-subtitle">the energy company</p>
                    <p style="margin: 5px 0 0; font-size: 9px; color: #4b5563;">
                        Whistleblowing System - Rekapitulasi Laporan
                    </p>
                </td>

                <td class="document-meta">
                    <div><strong>Tanggal Export</strong></div>
                    <div>{{ $generatedAt->format('d-m-Y H:i:s') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="document-title-box">
        <h2 class="document-title">Export Laporan WBS</h2>
        <p class="document-subtitle">
            Rekap laporan Whistleblowing System.
        </p>
    </div>

    <div class="section-title">Daftar Laporan</div>

    <p class="table-note">
        Total data pada tabel: <strong>{{ $reports->count() }}</strong> laporan.
    </p>

    <table class="report-table">
        <thead>
            <tr>
                <th class="col-number">No. Laporan</th>
                <th class="col-pelapor">Pelapor</th>
                <th class="col-email">Email</th>
                <th class="col-category">Kategori</th>
                <th class="col-title">Judul</th>
                <th class="col-status">Status</th>
                <th class="col-attachment">Lampiran</th>
                <th class="col-date">Dikirim</th>
            </tr>
        </thead>

        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td class="text-bold">{{ $report->report_number }}</td>
                    <td>{{ $report->user->name ?? '-' }}</td>
                    <td>{{ $report->user->email ?? '-' }}</td>
                    <td>{{ $report->category_label }}</td>
                    <td>{{ $report->title }}</td>
                    <td>
                        <span class="status-badge">
                            {{ $report->status_label }}
                        </span>
                    </td>
                    <td class="col-attachment">
                        <span class="attachment-count">
                            {{ $report->attachments_count }}
                        </span>
                    </td>
                    <td>{{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="8">
                        Tidak ada data laporan yang sesuai dengan filter export.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>