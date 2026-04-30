@extends('layouts.operational')

@section('title', 'Dashboard Operasional')

@section('content')
    <div class="op-page-head">
        <div>
            <div class="op-breadcrumb">
                <span>Operational</span>
                <span class="op-breadcrumb-sep">›</span>
                <span>Dashboard</span>
            </div>
            <h1 class="op-page-title">Dashboard Operasional</h1>
            <p class="op-page-desc">
                Dashboard ini menampilkan ringkasan seluruh data operasional: Flow Gas, Crude, dan VITOL
                dalam satu halaman yang rapi, mudah dibaca, dan responsive.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="op-alert op-alert--success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="flex-shrink:0;margin-top:2px">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="op-card" style="margin-bottom:20px;">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Filter Dashboard</h2>
                <div class="op-card-desc">Filter bulan dan tahun untuk membaca data Flow Gas, Crude, dan VITOL.</div>
            </div>
            <a href="{{ route('operational.tv', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="op-btn op-btn--primary" target="_blank">
                TV Display
            </a>
        </div>

        <div class="op-card-body">
            <form method="GET" action="{{ route('operational.dashboard') }}" class="op-form-grid">
                <div class="op-field">
                    <label class="op-label">Bulan</label>
                    <select name="month" class="op-select">
                        @foreach($monthOptions as $monthNumber => $monthLabel)
                            <option value="{{ $monthNumber }}" {{ (int) $selectedMonth === (int) $monthNumber ? 'selected' : '' }}>
                                {{ $monthLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field">
                    <label class="op-label">Tahun</label>
                    <select name="year" class="op-select">
                        @foreach($yearOptions as $year)
                            <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field full" style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('operational.dashboard') }}" class="op-btn op-btn--soft">Reset</a>
                    <button type="submit" class="op-btn op-btn--primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="op-grid-4" style="margin-bottom:20px;">
        <div class="op-stat">
            <div class="op-stat-label">Total Item Operasional</div>
            <div class="op-stat-value">{{ number_format($totalOperationalItems, 0, ',', '.') }}</div>
            <div class="op-stat-sub">Gabungan data Flow Gas, Crude, dan VITOL pada filter aktif.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Flow Gas MSCF</div>
            <div class="op-stat-value">{{ number_format($gasTotalMscf, 4, ',', '.') }}</div>
            <div class="op-stat-sub">Total MSCF untuk Flowcomp A dan Flowcomp B.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Crude Production</div>
            <div class="op-stat-value">{{ number_format($crudeTotalProduction, 4, ',', '.') }}</div>
            <div class="op-stat-sub">Total produksi crude pada bulan dan tahun terpilih.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">VITOL Quantity</div>
            <div class="op-stat-value">{{ number_format($vitolTotalQuantity, 4, ',', '.') }}</div>
            <div class="op-stat-sub">Total quantity VITOL pada tahun terpilih.</div>
        </div>
    </div>

    <div class="op-grid-2" style="margin-bottom:20px;">
        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h2 class="op-card-title">Flow Gas Daily</h2>
                    <div class="op-card-desc">Grafik harian Flow Gas berdasarkan total MSCF pada bulan terpilih.</div>
                </div>
            </div>
            <div class="op-card-body">
                <canvas id="flowGasDailyChart" height="220"></canvas>
            </div>
        </div>

        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h2 class="op-card-title">Crude Daily</h2>
                    <div class="op-card-desc">Grafik produksi crude untuk 14 hari terakhir.</div>
                </div>
            </div>
            <div class="op-card-body">
                <canvas id="crudeDailyChart" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="op-grid-2" style="margin-bottom:20px;">
        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h2 class="op-card-title">Flow Gas Monthly</h2>
                    <div class="op-card-desc">Rata-rata daily MSCF per bulan pada tahun terpilih.</div>
                </div>
            </div>
            <div class="op-card-body">
                <canvas id="flowGasMonthlyChart" height="220"></canvas>
            </div>
        </div>

        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h2 class="op-card-title">VITOL Monthly</h2>
                    <div class="op-card-desc">Rekap quantity VITOL per bulan pada tahun terpilih.</div>
                </div>
            </div>
            <div class="op-card-body">
                <canvas id="vitolMonthlyChart" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="op-card" style="margin-bottom:20px;">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Flow Gas Yearly</h2>
                <div class="op-card-desc">Perbandingan total MSCF Flow Gas antar tahun.</div>
            </div>
        </div>
        <div class="op-card-body">
            <canvas id="flowGasYearlyChart" height="120"></canvas>
        </div>
    </div>

    <div class="op-grid-3" style="margin-bottom:20px;">
        <div class="op-stat">
            <div class="op-stat-label">Flow Gas Records</div>
            <div class="op-stat-value">{{ number_format($gasTotalRecords, 0, ',', '.') }}</div>
            <div class="op-stat-sub">Jumlah record Flow Gas di periode aktif.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Flow Gas MMBTU</div>
            <div class="op-stat-value">{{ number_format($gasTotalMmbtu, 4, ',', '.') }}</div>
            <div class="op-stat-sub">Akumulasi energi gas di periode aktif.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Flow Gas FIX</div>
            <div class="op-stat-value">{{ number_format($gasTotalFix, 4, ',', '.') }}</div>
            <div class="op-stat-sub">Akumulasi nilai FIX di periode aktif.</div>
        </div>
    </div>

    <div class="op-grid-2" style="margin-bottom:20px;">
        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h2 class="op-card-title">Ringkasan Flow Gas per Kategori</h2>
                    <div class="op-card-desc">Menampilkan total Flowcomp A dan Flowcomp B.</div>
                </div>
            </div>
            <div class="op-card-body">
                @if($gasCategorySummary->count())
                    <div class="op-table-wrap">
                        <table class="op-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>MSCF</th>
                                    <th>MMBTU</th>
                                    <th>FIX</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gasCategorySummary as $item)
                                    <tr>
                                        <td>{{ $item->category->name ?? '-' }}</td>
                                        <td>{{ number_format((float) $item->total_mscf, 4, ',', '.') }}</td>
                                        <td>{{ number_format((float) $item->total_mmbtu, 4, ',', '.') }}</td>
                                        <td>{{ number_format((float) $item->total_fix, 4, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="op-empty">
                        <div class="op-empty-title">Belum ada ringkasan Flow Gas</div>
                        <div>Tidak ada data Flow Gas pada periode yang dipilih.</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="op-card">
            <div class="op-card-head">
                <div>
                    <h2 class="op-card-title">Crude Terbaru</h2>
                    <div class="op-card-desc">8 data produksi crude terbaru.</div>
                </div>
            </div>
            <div class="op-card-body">
                @if($recentCrudeRecords->count())
                    <div class="op-table-wrap">
                        <table class="op-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produksi</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentCrudeRecords as $record)
                                    <tr>
                                        <td>{{ optional($record->record_date)->format('d-m-Y') }}</td>
                                        <td>{{ number_format((float) $record->production, 4, ',', '.') }}</td>
                                        <td>{{ $record->notes ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="op-empty">
                        <div class="op-empty-title">Belum ada data crude</div>
                        <div>Silakan input data crude terlebih dahulu.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="op-card">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">VITOL Terbaru</h2>
                <div class="op-card-desc">Data VITOL terbaru berdasarkan tahun dan bulan.</div>
            </div>
        </div>
        <div class="op-card-body">
            @if($recentVitolRecords->count())
                <div class="op-table-wrap">
                    <table class="op-table">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentVitolRecords as $record)
                                <tr>
                                    <td>{{ $record->year }}</td>
                                    <td>{{ $record->month_label }}</td>
                                    <td>{{ number_format((float) $record->quantity, 4, ',', '.') }}</td>
                                    <td>{{ $record->unit }}</td>
                                    <td>{{ $record->notes ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="op-empty">
                    <div class="op-empty-title">Belum ada data VITOL</div>
                    <div>Silakan input data VITOL terlebih dahulu.</div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        const gridColor = isDarkMode ? 'rgba(148,163,184,0.16)' : 'rgba(15,23,42,0.08)';
        const tickColor = isDarkMode ? '#cbd5e1' : '#475569';

        function buildBarChart(canvasId, labels, data, label, bgColor, borderColor) {
            const el = document.getElementById(canvasId);
            if (!el) return;

            new Chart(el, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: bgColor,
                        borderColor: borderColor,
                        borderWidth: 1.2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: tickColor }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: tickColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: tickColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }

        function buildLineChart(canvasId, labels, data, label, bgColor, borderColor) {
            const el = document.getElementById(canvasId);
            if (!el) return;

            new Chart(el, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        fill: true,
                        tension: 0.35,
                        backgroundColor: bgColor,
                        borderColor: borderColor,
                        pointBackgroundColor: borderColor,
                        pointBorderColor: borderColor,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: tickColor }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: tickColor },
                            grid: { color: gridColor }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: tickColor },
                            grid: { color: gridColor }
                        }
                    }
                }
            });
        }

        buildLineChart(
            'flowGasDailyChart',
            @json($gasDailyChartLabels),
            @json($gasDailyChartValues),
            'MSCF',
            'rgba(234, 179, 8, 0.18)',
            'rgba(234, 179, 8, 1)'
        );

        buildBarChart(
            'crudeDailyChart',
            @json($crudeDailyChartLabels),
            @json($crudeDailyChartValues),
            'Produksi',
            'rgba(75, 85, 99, 0.78)',
            'rgba(55, 65, 81, 1)'
        );

        buildLineChart(
            'flowGasMonthlyChart',
            @json($gasMonthlyChartLabels),
            @json($gasMonthlyChartValues),
            'Avg Daily MSCF',
            'rgba(22, 163, 74, 0.18)',
            'rgba(22, 163, 74, 1)'
        );

        buildBarChart(
            'vitolMonthlyChart',
            @json($vitolMonthlyChartLabels),
            @json($vitolMonthlyChartValues),
            'Quantity',
            'rgba(37, 99, 235, 0.78)',
            'rgba(29, 78, 216, 1)'
        );

        buildBarChart(
            'flowGasYearlyChart',
            @json($gasYearlyChartLabels),
            @json($gasYearlyChartValues),
            'MSCF',
            'rgba(212, 168, 67, 0.82)',
            'rgba(180, 132, 28, 1)'
        );
    </script>
@endpush