@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="a-page-head">
    <div>
        <div class="a-breadcrumb">
            <span>Admin Panel</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Dashboard</span>
        </div>
        <h1 class="a-page-title">Dashboard</h1>
        <p class="a-page-desc">Ringkasan aktivitas dan statistik konten BSP Zapin CMS.</p>
    </div>

    <div style="display:flex;align-items:center;gap:8px;">
        <span style="font-size:12px;color:var(--text3);">
            Data di-cache · diperbarui otomatis
        </span>
        <a href="{{ route('admin.dashboard') }}" class="a-btn a-btn--secondary a-btn--sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M23 4v6h-6"/><path d="M1 20v-6h6"/>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
            </svg>
            Refresh
        </a>
    </div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="a-stats-grid" style="grid-template-columns:repeat(4,1fr);">

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Total News</span>
            <div class="a-stat-icon" style="background:#e8f5e9;color:var(--g700);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                    <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ $totalNews }}</div>
        <div class="a-stat-sub">semua berita</div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Published</span>
            <div class="a-stat-icon" style="background:#dcfce7;color:#166534;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value" style="color:#166534;">{{ $published }}</div>
        <div class="a-stat-sub">tayang di website</div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">In Review</span>
            <div class="a-stat-icon" style="background:#fef9c3;color:#854d0e;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value" style="color:#854d0e;">{{ $inReview }}</div>
        <div class="a-stat-sub">menunggu review</div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Rejected</span>
            <div class="a-stat-icon" style="background:#fee2e2;color:#991b1b;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value" style="color:#991b1b;">{{ $rejected }}</div>
        <div class="a-stat-sub">ditolak reviewer</div>
    </div>

</div>

{{-- Second row stats --}}
<div class="a-stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Draft</span>
            <div class="a-stat-icon" style="background:#f3f4f6;color:#374151;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ $draft }}</div>
        <div class="a-stat-sub">belum dikirim</div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Total User</span>
            <div class="a-stat-icon" style="background:#dbeafe;color:#1e40af;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ $totalUsers }}</div>
        <div class="a-stat-sub">
            <span style="color:#166534;font-weight:700;">{{ $activeUsers }}</span> aktif
        </div>
    </div>

    <div class="a-stat">
        <div class="a-stat-top">
            <span class="a-stat-label">Total Log</span>
            <div class="a-stat-icon" style="background:#f3e8ff;color:#6b21a8;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
            </div>
        </div>
        <div class="a-stat-value">{{ $totalLogs }}</div>
        <div class="a-stat-sub">aktivitas tercatat</div>
    </div>

</div>

{{-- ── CHARTS ROW ── --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;margin-bottom:24px;">

    {{-- Line chart: news 7 hari --}}
    <div class="a-card" style="margin-bottom:0;">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Berita Ditambahkan</div>
                <div class="a-card-desc">Jumlah berita baru per hari dalam 7 hari terakhir.</div>
            </div>
        </div>
        <div class="a-card-body" style="padding-bottom:18px;">
            <canvas id="chartLine" height="90"></canvas>
        </div>
    </div>

    {{-- Donut chart: status --}}
    <div class="a-card" style="margin-bottom:0;">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Distribusi Status</div>
                <div class="a-card-desc">Proporsi status seluruh berita.</div>
            </div>
        </div>
        <div class="a-card-body" style="display:flex;flex-direction:column;align-items:center;gap:16px;">
            <canvas id="chartDonut" width="180" height="180" style="max-width:180px;max-height:180px;"></canvas>

            <div style="width:100%;display:flex;flex-direction:column;gap:7px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="display:flex;align-items:center;gap:7px;font-size:12.5px;color:var(--text2);">
                        <span style="width:10px;height:10px;border-radius:50%;background:#166534;flex-shrink:0;"></span>
                        Published
                    </span>
                    <span style="font-size:12.5px;font-weight:700;color:var(--text);">{{ $chartStatus['published'] }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="display:flex;align-items:center;gap:7px;font-size:12.5px;color:var(--text2);">
                        <span style="width:10px;height:10px;border-radius:50%;background:#b45309;flex-shrink:0;"></span>
                        In Review
                    </span>
                    <span style="font-size:12.5px;font-weight:700;color:var(--text);">{{ $chartStatus['in_review'] }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="display:flex;align-items:center;gap:7px;font-size:12.5px;color:var(--text2);">
                        <span style="width:10px;height:10px;border-radius:50%;background:#6b7280;flex-shrink:0;"></span>
                        Draft
                    </span>
                    <span style="font-size:12.5px;font-weight:700;color:var(--text);">{{ $chartStatus['draft'] }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="display:flex;align-items:center;gap:7px;font-size:12.5px;color:var(--text2);">
                        <span style="width:10px;height:10px;border-radius:50%;background:#991b1b;flex-shrink:0;"></span>
                        Rejected
                    </span>
                    <span style="font-size:12.5px;font-weight:700;color:var(--text);">{{ $chartStatus['rejected'] }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── RECENT ACTIVITY LOG ── --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Aktivitas Terakhir</div>
            <div class="a-card-desc">10 log aktivitas terbaru pada berita.</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th>Berita</th>
                    <th>Aksi</th>
                    <th>User</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="max-width:320px;">
                            <span style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600;color:var(--text);">
                                {{ optional($log->news)->getTranslationByLocale('id')->title ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $actionMap = [
                                    'approved' => ['green',  'Approved'],
                                    'rejected' => ['red',    'Rejected'],
                                    'submitted'=> ['yellow', 'Submitted'],
                                    'published'=> ['green',  'Published'],
                                    'draft'    => ['gray',   'Draft'],
                                ];
                                [$color, $label] = $actionMap[$log->action] ?? ['gray', strtoupper($log->action)];
                            @endphp
                            <span class="a-badge a-badge--{{ $color }}">{{ $label }}</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="
                                    width:28px;height:28px;border-radius:8px;flex-shrink:0;
                                    background:var(--g800);color:#fff;
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:11px;font-weight:800;">
                                    {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span style="font-size:13px;color:var(--text2);">{{ $log->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="white-space:nowrap;font-size:13px;color:var(--text3);">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="a-empty">
                                <div class="a-empty-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <div class="a-empty-title">Belum ada aktivitas</div>
                                <p class="a-empty-desc">Log aktivitas akan muncul setelah ada perubahan status berita.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
(function () {
    function initAdminDashboardCharts() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js gagal dimuat. Pastikan koneksi internet aktif atau file Chart.js tersedia.');
            return;
        }

        var g500 = '#2f7d32';
        var textColor = '#6b7280';
        var lineColor = '#e5e7eb';

        var lineLabels = @json($chartNews->pluck('date')->values());
        var lineData = @json($chartNews->pluck('count')->values());

        var donutData = [
            Number({{ (int) ($chartStatus['published'] ?? 0) }}),
            Number({{ (int) ($chartStatus['in_review'] ?? 0) }}),
            Number({{ (int) ($chartStatus['draft'] ?? 0) }}),
            Number({{ (int) ($chartStatus['rejected'] ?? 0) }})
        ];

        var ctxLine = document.getElementById('chartLine');
        if (ctxLine) {
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: lineLabels,
                    datasets: [{
                        label: 'Berita baru',
                        data: lineData,
                        borderColor: g500,
                        backgroundColor: function (ctx) {
                            var chart = ctx.chart;
                            var area = chart.chartArea;

                            if (!area) {
                                return 'rgba(47,125,50,0.12)';
                            }

                            var gradient = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
                            gradient.addColorStop(0, 'rgba(47,125,50,0.18)');
                            gradient.addColorStop(1, 'rgba(47,125,50,0)');
                            return gradient;
                        },
                        borderWidth: 2.5,
                        pointBackgroundColor: g500,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.42
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#111827',
                            bodyColor: '#374151',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(ctx) {
                                    return ' ' + ctx.parsed.y + ' berita';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 12
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            suggestedMax: Math.max.apply(null, lineData) < 5 ? 5 : undefined,
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 12
                                },
                                stepSize: 1,
                                precision: 0
                            },
                            grid: {
                                color: lineColor
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        var ctxDonut = document.getElementById('chartDonut');
        if (ctxDonut) {
            var totalDonut = donutData.reduce(function (sum, value) {
                return sum + value;
            }, 0);

            var finalDonutData = totalDonut > 0 ? donutData : [1];

            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: totalDonut > 0
                        ? ['Published', 'In Review', 'Draft', 'Rejected']
                        : ['Belum ada data'],
                    datasets: [{
                        data: finalDonutData,
                        backgroundColor: totalDonut > 0
                            ? ['#166534', '#b45309', '#6b7280', '#991b1b']
                            : ['#e5e7eb'],
                        hoverBackgroundColor: totalDonut > 0
                            ? ['#15803d', '#d97706', '#9ca3af', '#b91c1c']
                            : ['#e5e7eb'],
                        borderWidth: 0,
                        hoverOffset: totalDonut > 0 ? 6 : 0
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: totalDonut > 0,
                            backgroundColor: '#fff',
                            titleColor: '#111827',
                            bodyColor: '#374151',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10
                        }
                    }
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminDashboardCharts);
    } else {
        initAdminDashboardCharts();
    }
})();
</script>

@endsection