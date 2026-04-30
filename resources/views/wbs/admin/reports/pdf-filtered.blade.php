<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Laporan WBS</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
            line-height: 1.5;
        }

        .header {
            margin-bottom: 16px;
        }

        .header h1 {
            margin: 0 0 4px;
            font-size: 18px;
        }

        .header p {
            margin: 0;
            font-size: 11px;
            color: #374151;
        }

        .filter-box {
            margin-bottom: 14px;
            padding: 10px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }

        .filter-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-box td {
            padding: 4px 6px;
            vertical-align: top;
            border: none;
        }

        .label {
            width: 120px;
            font-weight: bold;
        }

        .summary {
            margin-bottom: 10px;
            font-size: 11px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #d1d5db;
            padding: 6px 7px;
            vertical-align: top;
            text-align: left;
        }

        .report-table th {
            background: #f3f4f6;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Export Laporan WBS</h1>
        <p>Dibuat pada: {{ $generatedAt->format('d-m-Y H:i:s') }}</p>
    </div>

    <div class="filter-box">
        <table>
            <tr>
                <td class="label">Search</td>
                <td>: {{ $filters['search'] ?: '-' }}</td>
                <td class="label">Pelapor</td>
                <td>: {{ $selectedPelapor?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>: {{ $filters['status'] ? ($statusOptions[$filters['status']] ?? $filters['status']) : '-' }}</td>
                <td class="label">Kategori</td>
                <td>: {{ $filters['category'] ? ($categoryOptions[$filters['category']] ?? $filters['category']) : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        Total data: {{ $reports->count() }} laporan
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>No. Laporan</th>
                <th>Pelapor</th>
                <th>Email</th>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Lampiran</th>
                <th>Dikirim</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->report_number }}</td>
                    <td>{{ $report->user->name ?? '-' }}</td>
                    <td>{{ $report->user->email ?? '-' }}</td>
                    <td>{{ $report->category_label }}</td>
                    <td>{{ $report->title }}</td>
                    <td>{{ $report->status_label }}</td>
                    <td>{{ $report->attachments_count }}</td>
                    <td>{{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>