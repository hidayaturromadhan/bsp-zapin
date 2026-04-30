@extends('layouts.wbs')

@section('content')
    <h2 class="wbs-page-title">Dashboard Admin WBS</h2>

    <div class="wbs-grid wbs-grid-4" style="margin-bottom: 22px;">
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

    <div class="wbs-grid wbs-grid-2" style="margin-bottom: 22px;">
        <div class="wbs-card">
            <h3 class="wbs-card-title">Trend Laporan 6 Bulan</h3>
            <canvas id="monthlyChart" height="130"></canvas>
        </div>

        <div class="wbs-card">
            <h3 class="wbs-card-title">Kategori Laporan</h3>
            <canvas id="categoryChart" height="130"></canvas>
        </div>
    </div>

    <div class="wbs-grid wbs-grid-2" style="margin-bottom: 22px;">
        <div class="wbs-card">
            <h3 class="wbs-card-title">Status Laporan</h3>
            <canvas id="statusChart" height="130"></canvas>
        </div>

        <div class="wbs-card">
            <h3 class="wbs-card-title">Monitoring WBS</h3>
            <p class="wbs-card-subtitle">
                Admin WBS hanya melakukan monitoring, update status, catatan, tindak lanjut, export PDF, dan pengelolaan laporan yang masuk dari pelapor.
            </p>

            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:18px;">
                <a href="{{ route('wbs.admin.reports.index') }}" class="wbs-btn wbs-btn-primary">Kelola Laporan</a>
                <a href="{{ route('wbs.admin.reports.export-filtered-pdf') }}" class="wbs-btn wbs-btn-light">Export Semua PDF</a>
            </div>
        </div>
    </div>

    <div class="wbs-card">
        <h3 class="wbs-card-title">Laporan Terbaru</h3>

        @if($latestReports->count())
            <div class="wbs-table-wrap">
                <table class="wbs-table">
                    <thead>
                        <tr>
                            <th>No. Laporan</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($latestReports as $report)
                            <tr>
                                <td>{{ $report->report_number }}</td>
                                <td>{{ $report->user->name ?? '-' }}</td>
                                <td>{{ $report->category_label }}</td>
                                <td>{{ $report->title }}</td>
                                <td><span class="wbs-badge">{{ $report->status_label }}</span></td>
                                <td>
                                    <a href="{{ route('wbs.admin.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">Detail</a>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json($monthlyLabels);
        const monthlyValues = @json($monthlyValues);
        const categoryLabels = @json($categoryLabels);
        const categoryValues = @json($categoryValues);
        const statusLabels = @json($statusLabels);
        const statusValues = @json($statusValues);

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: monthlyValues,
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Kategori',
                    data: categoryValues,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
@endsection