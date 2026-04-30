@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Dashboard Pelapor WBS</h2>

    <div class="wbs-grid wbs-grid-4" style="margin-bottom:22px;">
        <div class="wbs-stat">
            <div class="wbs-stat-label">Total Laporan</div>
            <div class="wbs-stat-value">{{ $totalReports }}</div>
        </div>
        <div class="wbs-stat">
            <div class="wbs-stat-label">Laporan Masuk</div>
            <div class="wbs-stat-value">{{ $laporanMasuk }}</div>
        </div>
        <div class="wbs-stat">
            <div class="wbs-stat-label">Dalam Proses</div>
            <div class="wbs-stat-value">{{ $dalamProses }}</div>
        </div>
        <div class="wbs-stat">
            <div class="wbs-stat-label">Selesai</div>
            <div class="wbs-stat-value">{{ $selesai }}</div>
        </div>
    </div>

    <div class="wbs-card" style="margin-bottom:22px;">
        <div class="wbs-toolbar" style="margin-bottom:0;">
            <div>
                <h3 style="margin:0 0 8px; font-size:28px;">Portal Pelapor WBS</h3>
                <div style="color:#64748b; line-height:1.8;">
                    Gunakan menu di bawah untuk membuat laporan baru atau melihat laporan yang sudah pernah dikirim.
                </div>
            </div>

            <div class="wbs-toolbar-right">
                <a href="{{ route('wbs.pelapor.reports.create') }}" class="wbs-btn wbs-btn-primary">Buat Laporan Baru</a>
                <a href="{{ route('wbs.pelapor.reports.index') }}" class="wbs-btn wbs-btn-light">Lihat Laporan Saya</a>
            </div>
        </div>
    </div>

    <div class="wbs-card">
        <h3 style="margin:0 0 16px; font-size:24px;">Laporan Terbaru</h3>

        @if($latestReports->count())
            <div class="wbs-table-wrap">
                <table class="wbs-table">
                    <thead>
                        <tr>
                            <th>No. Laporan</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestReports as $report)
                            <tr>
                                <td>{{ $report->report_number }}</td>
                                <td>{{ $report->category_label }}</td>
                                <td>{{ $report->title }}</td>
                                <td><span class="wbs-badge">{{ $report->status_label }}</span></td>
                                <td>{{ optional($report->submitted_at)->format('d-m-Y H:i') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('wbs.pelapor.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="wbs-empty">Belum ada laporan.</div>
        @endif
    </div>
@endsection