<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Laporan WBS</title>

    <style>
        @page {
            margin: 24px 24px 34px 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
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
            font-size: 15px;
            font-weight: bold;
            color: #173f08;
            line-height: 1.2;
        }

        .company-subtitle {
            margin: 2px 0 0;
            font-size: 9.5px;
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
            font-size: 18px;
            line-height: 1.25;
            font-weight: bold;
            color: #111827;
        }

        .document-subtitle {
            margin: 4px 0 0;
            font-size: 10px;
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

        .filter-box {
            margin-bottom: 12px;
            padding: 10px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 6px;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-table td {
            border: none;
            padding: 4px 6px;
            vertical-align: top;
            font-size: 9.5px;
        }

        .filter-label {
            width: 95px;
            font-weight: bold;
            color: #374151;
        }

        .filter-value {
            color: #111827;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .summary-grid td {
            width: 25%;
            padding: 9px 10px;
            border: 1px solid #dbe8d5;
            background: #f8fbf7;
            vertical-align: top;
        }

        .summary-label {
            font-size: 8.5px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #173f08;
            line-height: 1.2;
        }

        .summary-small {
            font-size: 9px;
            color: #4b5563;
            margin-top: 2px;
        }

        .table-note {
            margin: 0 0 7px;
            font-size: 9px;
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
            padding: 6px 6px;
            vertical-align: top;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .report-table th {
            background: #173f08;
            color: #ffffff;
            font-weight: bold;
            font-size: 8.8px;
            text-transform: uppercase;
            letter-spacing: .025em;
        }

        .report-table td {
            font-size: 8.8px;
            color: #111827;
        }

        .report-table tbody tr:nth-child(even) td {
            background: #f9fafb;
        }

        .col-number {
            width: 12%;
        }

        .col-pelapor {
            width: 13%;
        }

        .col-email {
            width: 15%;
        }

        .col-category {
            width: 11%;
        }

        .col-title {
            width: 22%;
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
            padding: 2px 6px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: bold;
            background: #eef5eb;
            color: #173f08;
            border: 1px solid #dbe8d5;
        }

        .attachment-count {
            display: inline-block;
            min-width: 18px;
            padding: 2px 5px;
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

        .text-muted {
            color: #6b7280;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
@php
    $logoPath = public_path('images/logo.png');

    $totalReports = $reports->count();
    $totalAttachments = $reports->sum('attachments_count');

    $statusSummary = $reports
        ->groupBy('status_label')
        ->map(fn ($items) => $items->count());

    $categorySummary = $reports
        ->groupBy('category_label')
        ->map(fn ($items) => $items->count());

    $dominantStatus = $statusSummary->sortDesc()->keys()->first() ?: '-';
    $dominantCategory = $categorySummary->sortDesc()->keys()->first() ?: '-';
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
                    <div style="margin-top: 4px;">Format: PDF</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="document-title-box">
        <h2 class="document-title">Export Laporan WBS</h2>
        <p class="document-subtitle">
            Rekap laporan Whistleblowing System berdasarkan filter yang dipilih pada panel admin.
        </p>
    </div>

    <div class="section-title">Ringkasan Export</div>

    <table class="summary-grid">
        <tr>
            <td>
                <div class="summary-label">Total Laporan</div>
                <div class="summary-value">{{ $totalReports }}</div>
                <div class="summary-small">Data dalam export</div>
            </td>

            <td>
                <div class="summary-label">Total Lampiran</div>
                <div class="summary-value">{{ $totalAttachments }}</div>
                <div class="summary-small">Seluruh file terlampir</div>
            </td>

            <td>
                <div class="summary-label">Status Dominan</div>
                <div class="summary-value" style="font-size: 11px;">{{ $dominantStatus }}</div>
                <div class="summary-small">{{ $dominantStatus !== '-' ? ($statusSummary[$dominantStatus] ?? 0) . ' laporan' : '-' }}</div>
            </td>

            <td>
                <div class="summary-label">Kategori Dominan</div>
                <div class="summary-value" style="font-size: 11px;">{{ $dominantCategory }}</div>
                <div class="summary-small">{{ $dominantCategory !== '-' ? ($categorySummary[$dominantCategory] ?? 0) . ' laporan' : '-' }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Filter Data</div>

    <div class="filter-box">
        <table class="filter-table">
            <tr>
                <td class="filter-label">Search</td>
                <td class="filter-value">: {{ $filters['search'] ?: '-' }}</td>

                <td class="filter-label">Pelapor</td>
                <td class="filter-value">: {{ $selectedPelapor?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="filter-label">Status</td>
                <td class="filter-value">: {{ $filters['status'] ? ($statusOptions[$filters['status']] ?? $filters['status']) : '-' }}</td>

                <td class="filter-label">Kategori</td>
                <td class="filter-value">: {{ $filters['category'] ? ($categoryOptions[$filters['category']] ?? $filters['category']) : '-' }}</td>
            </tr>
        </table>
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