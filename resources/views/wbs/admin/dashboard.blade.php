@extends('layouts.wbs')

@section('content')
    <style>
        .wbs-dashboard-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .wbs-dashboard-title {
            margin: 0;
            font-size: 30px;
            line-height: 1.15;
            font-weight: 900;
            letter-spacing: -.04em;
            color: var(--text-primary);
        }

        .wbs-dashboard-desc {
            margin: 8px 0 0;
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.7;
            max-width: 720px;
        }

        .wbs-dashboard-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .wbs-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .wbs-stat-card {
            position: relative;
            overflow: hidden;
            min-height: 128px;
            padding: 20px;
            border-radius: 24px;
            border: 1px solid var(--border);
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .11), transparent 34%),
                var(--surface);
            box-shadow: var(--shadow-xs);
            transition:
                transform var(--dur) var(--ease),
                box-shadow var(--dur) var(--ease),
                border-color var(--dur) var(--ease),
                background var(--dur) var(--ease);
        }

        .wbs-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
            border-color: var(--brand-border);
        }

        .wbs-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 15px;
            background: var(--brand-light);
            border: 1px solid var(--brand-border);
            color: var(--brand);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .wbs-stat-icon svg {
            width: 21px;
            height: 21px;
            stroke: currentColor;
        }

        .wbs-stat-label {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .055em;
            margin-bottom: 6px;
        }

        .wbs-stat-value {
            color: var(--text-primary);
            font-size: 32px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .wbs-stat-hint {
            margin-top: 8px;
            color: var(--text-secondary);
            font-size: 12.5px;
            line-height: 1.5;
        }

        .wbs-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(320px, .9fr);
            gap: 18px;
            margin-bottom: 22px;
            align-items: stretch;
        }

        .wbs-dashboard-grid-secondary {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(320px, .9fr);
            gap: 18px;
            margin-bottom: 22px;
            align-items: stretch;
        }

        .wbs-dashboard-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 22px;
            box-shadow: var(--shadow-xs);
            min-width: 0;
            overflow: hidden;
            transition:
                box-shadow var(--dur) var(--ease),
                border-color var(--dur) var(--ease),
                background var(--dur) var(--ease);
        }

        .wbs-dashboard-card:hover {
            box-shadow: var(--shadow-sm);
            border-color: var(--border-strong);
        }

        .wbs-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 16px;
        }

        .wbs-card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 900;
            color: var(--text-primary);
            letter-spacing: -.025em;
            line-height: 1.25;
        }

        .wbs-card-subtitle {
            margin: 5px 0 0;
            color: var(--text-muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .wbs-chart-wrap {
            position: relative;
            height: 270px;
            width: 100%;
        }

        .wbs-chart-wrap.small {
            height: 250px;
        }

        .wbs-info-panel {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .wbs-info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            padding: 13px 14px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: var(--surface-alt);
        }

        .wbs-info-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 800;
            line-height: 1.4;
        }

        .wbs-info-value {
            font-size: 18px;
            color: var(--text-primary);
            font-weight: 900;
            line-height: 1;
        }

        .wbs-monitor-copy {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.75;
            margin: 0;
        }

        .wbs-monitor-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .wbs-latest-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .wbs-table-report-number {
            color: var(--brand);
            font-weight: 900;
            white-space: nowrap;
        }

        .wbs-table-title {
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.45;
            min-width: 220px;
        }

        .wbs-table-user {
            font-weight: 800;
            color: var(--text-primary);
        }

        .wbs-table-email {
            display: block;
            margin-top: 2px;
            color: var(--text-muted);
            font-size: 12px;
            word-break: break-word;
        }

        .wbs-empty-dashboard {
            padding: 42px 20px;
            border-radius: 18px;
            background: var(--surface-alt);
            border: 1px dashed var(--border-strong);
            color: var(--text-muted);
            text-align: center;
            font-size: 14px;
            line-height: 1.7;
        }

        .wbs-empty-dashboard strong {
            display: block;
            color: var(--text-primary);
            font-size: 17px;
            margin-bottom: 4px;
        }

        @media (max-width: 1280px) {
            .wbs-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .wbs-dashboard-grid,
            .wbs-dashboard-grid-secondary {
                grid-template-columns: 1fr;
            }

            .wbs-chart-wrap {
                height: 300px;
            }
        }

        @media (max-width: 720px) {
            .wbs-dashboard-title {
                font-size: 24px;
            }

            .wbs-stat-grid {
                grid-template-columns: 1fr;
            }

            .wbs-dashboard-card {
                padding: 18px;
                border-radius: 20px;
            }

            .wbs-chart-wrap,
            .wbs-chart-wrap.small {
                height: 260px;
            }

            .wbs-dashboard-actions,
            .wbs-dashboard-actions .wbs-btn,
            .wbs-monitor-actions,
            .wbs-monitor-actions .wbs-btn {
                width: 100%;
            }

            .wbs-latest-head .wbs-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .wbs-chart-wrap,
            .wbs-chart-wrap.small {
                height: 235px;
            }
        }
    </style>

    <div class="wbs-dashboard-head">
        <div>
            <h2 class="wbs-dashboard-title">Dashboard Admin WBS</h2>
            <p class="wbs-dashboard-desc">
                Pantau laporan masuk, status penanganan, tren laporan, dan tindak lanjut WBS dalam satu dashboard ringkas.
            </p>
        </div>

        <div class="wbs-dashboard-actions">
            <a href="{{ route('wbs.admin.reports.index') }}" class="wbs-btn wbs-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                    <path d="M14 3v5h5"></path>
                </svg>
                Kelola Laporan
            </a>

            <a href="{{ route('wbs.admin.reports.export-filtered-pdf') }}" class="wbs-btn wbs-btn-light">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <path d="M7 10l5 5 5-5"></path>
                    <path d="M12 15V3"></path>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <div class="wbs-stat-grid">
        <div class="wbs-stat-card">
            <div class="wbs-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9">
                    <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                    <path d="M14 3v5h5"></path>
                </svg>
            </div>
            <div class="wbs-stat-label">Total Laporan</div>
            <div class="wbs-stat-value">{{ $totalReports }}</div>
            <div class="wbs-stat-hint">Seluruh laporan yang masuk ke sistem WBS.</div>
        </div>

        <div class="wbs-stat-card">
            <div class="wbs-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9">
                    <path d="M4 4h16v16H4Z"></path>
                    <path d="M8 9h8"></path>
                    <path d="M8 13h5"></path>
                </svg>
            </div>
            <div class="wbs-stat-label">Laporan Masuk</div>
            <div class="wbs-stat-value">{{ $laporanMasuk }}</div>
            <div class="wbs-stat-hint">Laporan baru yang belum masuk proses lanjutan.</div>
        </div>

        <div class="wbs-stat-card">
            <div class="wbs-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9">
                    <path d="M12 8v5l3 2"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                </svg>
            </div>
            <div class="wbs-stat-label">Dalam Proses</div>
            <div class="wbs-stat-value">{{ $dalamProses }}</div>
            <div class="wbs-stat-hint">Laporan yang sedang ditelaah, klarifikasi, atau investigasi.</div>
        </div>

        <div class="wbs-stat-card">
            <div class="wbs-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9">
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
            </div>
            <div class="wbs-stat-label">Selesai</div>
            <div class="wbs-stat-value">{{ $selesai }}</div>
            <div class="wbs-stat-hint">Laporan yang sudah selesai, ditutup, atau di luar ruang lingkup.</div>
        </div>
    </div>

    <div class="wbs-dashboard-grid">
        <div class="wbs-dashboard-card">
            <div class="wbs-card-head">
                <div>
                    <h3 class="wbs-card-title">Trend Laporan 6 Bulan</h3>
                    <p class="wbs-card-subtitle">Grafik dibatasi 6 bulan terakhir agar mudah dibaca.</p>
                </div>
            </div>

            <div class="wbs-chart-wrap">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="wbs-dashboard-card">
            <div class="wbs-card-head">
                <div>
                    <h3 class="wbs-card-title">Ringkasan Bulan Ini</h3>
                    <p class="wbs-card-subtitle">Informasi cepat untuk memantau beban laporan terkini.</p>
                </div>
            </div>

            <div class="wbs-info-panel">
                <div class="wbs-info-item">
                    <div class="wbs-info-label">Laporan Bulan Ini</div>
                    <div class="wbs-info-value">{{ $laporanBulanIni }}</div>
                </div>

                <div class="wbs-info-item">
                    <div class="wbs-info-label">Laporan Hari Ini</div>
                    <div class="wbs-info-value">{{ $laporanHariIni }}</div>
                </div>

                <div class="wbs-info-item">
                    <div class="wbs-info-label">Butuh Tindak Lanjut</div>
                    <div class="wbs-info-value">{{ $butuhTindakLanjut }}</div>
                </div>
            </div>

            <p class="wbs-monitor-copy" style="margin-top:16px;">
                Prioritaskan laporan dengan status laporan masuk, perlu klarifikasi, dalam proses, atau dalam investigasi.
            </p>

            <div class="wbs-monitor-actions">
                <a href="{{ route('wbs.admin.reports.index') }}" class="wbs-btn wbs-btn-primary">
                    Buka Monitoring
                </a>
            </div>
        </div>
    </div>

    <div class="wbs-dashboard-grid-secondary">
        <div class="wbs-dashboard-card">
            <div class="wbs-card-head">
                <div>
                    <h3 class="wbs-card-title">Kategori Laporan</h3>
                    <p class="wbs-card-subtitle">Maksimal 6 kategori teratas. Sisanya digabung sebagai “Lainnya”.</p>
                </div>
            </div>

            <div class="wbs-chart-wrap small">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="wbs-dashboard-card">
            <div class="wbs-card-head">
                <div>
                    <h3 class="wbs-card-title">Status Laporan</h3>
                    <p class="wbs-card-subtitle">Distribusi status laporan berdasarkan data terbaru.</p>
                </div>
            </div>

            <div class="wbs-chart-wrap small">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="wbs-dashboard-card">
        <div class="wbs-latest-head">
            <div>
                <h3 class="wbs-card-title">Laporan Terbaru</h3>
                <p class="wbs-card-subtitle">Menampilkan maksimal 8 laporan terbaru yang masuk ke sistem.</p>
            </div>

            <a href="{{ route('wbs.admin.reports.index') }}" class="wbs-btn wbs-btn-light">
                Lihat Semua
            </a>
        </div>

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
                                <td>
                                    <span class="wbs-table-report-number">
                                        {{ $report->report_number }}
                                    </span>
                                </td>

                                <td>
                                    <span class="wbs-table-user">
                                        {{ $report->user->name ?? '-' }}
                                    </span>

                                    @if($report->user?->email)
                                        <span class="wbs-table-email">
                                            {{ $report->user->email }}
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $report->category_label }}</td>

                                <td>
                                    <div class="wbs-table-title">
                                        {{ $report->title }}
                                    </div>
                                </td>

                                <td>
                                    <span class="wbs-badge">
                                        {{ $report->status_label }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('wbs.admin.reports.show', $report->id) }}" class="wbs-btn wbs-btn-light">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="wbs-empty-dashboard">
                <strong>Belum ada laporan</strong>
                Data laporan terbaru akan muncul setelah pelapor mengirim laporan WBS.
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (function () {
            const monthlyLabels = @json($monthlyLabels);
            const monthlyValues = @json($monthlyValues);
            const categoryLabels = @json($categoryLabels);
            const categoryValues = @json($categoryValues);
            const statusLabels = @json($statusLabels);
            const statusValues = @json($statusValues);

            const root = getComputedStyle(document.documentElement);

            const colors = {
                brand: root.getPropertyValue('--brand').trim() || '#2563eb',
                brandDark: root.getPropertyValue('--brand-dark').trim() || '#1d4ed8',
                brandLight: root.getPropertyValue('--brand-light').trim() || '#eff6ff',
                textPrimary: root.getPropertyValue('--text-primary').trim() || '#0f172a',
                textSecondary: root.getPropertyValue('--text-secondary').trim() || '#475569',
                textMuted: root.getPropertyValue('--text-muted').trim() || '#94a3b8',
                border: root.getPropertyValue('--border').trim() || '#e2e8f0',
                surface: root.getPropertyValue('--surface').trim() || '#ffffff'
            };

            const palette = [
                '#2563eb',
                '#16a34a',
                '#f59e0b',
                '#dc2626',
                '#7c3aed',
                '#0891b2',
                '#64748b'
            ];

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        backgroundColor: colors.textPrimary,
                        titleColor: colors.surface,
                        bodyColor: colors.surface,
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: true
                    }
                }
            };

            const monthlyCanvas = document.getElementById('monthlyChart');
            const categoryCanvas = document.getElementById('categoryChart');
            const statusCanvas = document.getElementById('statusChart');

            if (monthlyCanvas) {
                new Chart(monthlyCanvas, {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: monthlyValues,
                            borderColor: colors.brand,
                            backgroundColor: 'rgba(37, 99, 235, .12)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: colors.brand,
                            pointBorderColor: colors.surface,
                            pointBorderWidth: 2,
                            tension: 0.35,
                            fill: true
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: colors.textMuted,
                                    font: {
                                        size: 11,
                                        weight: '700'
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: colors.textMuted,
                                    font: {
                                        size: 11,
                                        weight: '700'
                                    }
                                },
                                grid: {
                                    color: colors.border
                                }
                            }
                        }
                    }
                });
            }

            if (categoryCanvas) {
                new Chart(categoryCanvas, {
                    type: 'bar',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: categoryValues,
                            backgroundColor: palette,
                            borderWidth: 0,
                            borderRadius: 10,
                            maxBarThickness: 42
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: colors.textMuted,
                                    font: {
                                        size: 10,
                                        weight: '700'
                                    },
                                    callback: function (value) {
                                        const label = this.getLabelForValue(value);
                                        return label.length > 14 ? label.substring(0, 14) + '…' : label;
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: colors.textMuted,
                                    font: {
                                        size: 11,
                                        weight: '700'
                                    }
                                },
                                grid: {
                                    color: colors.border
                                }
                            }
                        }
                    }
                });
            }

            if (statusCanvas) {
                new Chart(statusCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusValues,
                            backgroundColor: palette,
                            borderColor: colors.surface,
                            borderWidth: 3,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        ...commonOptions,
                        cutout: '64%',
                        plugins: {
                            ...commonOptions.plugins,
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: colors.textSecondary,
                                    boxWidth: 12,
                                    boxHeight: 12,
                                    padding: 14,
                                    font: {
                                        size: 11,
                                        weight: '700'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script>
@endsection