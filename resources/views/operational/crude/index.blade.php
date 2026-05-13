@extends('layouts.operational')

@section('title', 'Data Crude')

@section('content')
<div class="op-page-head">
    <div>
        <div class="op-breadcrumb">
            <span>Operational</span>
            <span>›</span>
            <span>Crude</span>
        </div>

        <h1 class="op-page-title">Data Produksi Crude Oil</h1>
        <p class="op-page-desc">
            Kelola data produksi crude oil harian berdasarkan Vacuum Truck dan Road Tank.
        </p>
    </div>

    <a href="{{ route('operational.crude.create') }}" class="op-btn op-btn--primary">
        Tambah Data
    </a>
</div>

@if(session('success'))
    <div class="op-alert op-alert--success">
        {{ session('success') }}
    </div>
@endif

<div class="op-grid-3" style="margin-bottom:20px;">
    <div class="op-stat">
        <div class="op-stat-label">Total Record</div>
        <div class="op-stat-value">{{ number_format($summary['count'], 0, ',', '.') }}</div>
        <div class="op-stat-sub">Jumlah data crude sesuai filter.</div>
    </div>

    <div class="op-stat">
        <div class="op-stat-label">Vacuum Truck</div>
        <div class="op-stat-value">{{ number_format((float) $summary['total_vacuum_truck'], 2, ',', '.') }}</div>
        <div class="op-stat-sub">Total nilai Vacuum Truck sesuai filter.</div>
    </div>

    <div class="op-stat">
        <div class="op-stat-label">Road Tank</div>
        <div class="op-stat-value">{{ number_format((float) $summary['total_road_tank'], 2, ',', '.') }}</div>
        <div class="op-stat-sub">Total nilai Road Tank sesuai filter.</div>
    </div>
</div>

<div class="op-card" style="margin-bottom:20px;">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Grafik Produksi Crude Oil</h2>
            <div class="op-card-desc">
                Dalam satu bar terdapat dua kondisi: Vacuum Truck dan Road Tank.
            </div>
        </div>
    </div>

    <div class="op-card-body">
        <div style="height:380px; position:relative;">
            <canvas id="crudeStackedChart"></canvas>
        </div>
    </div>
</div>

<div class="op-card" style="margin-bottom:20px;">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Filter Data</h2>
            <div class="op-card-desc">
                Filter berdasarkan tanggal, bulan, tahun, dan catatan.
            </div>
        </div>
    </div>

    <div class="op-card-body">
        <form method="GET" action="{{ route('operational.crude.index') }}" class="op-form-grid">
            <div class="op-field">
                <label class="op-label">Tanggal</label>
                <input
                    type="date"
                    name="date"
                    class="op-input"
                    value="{{ $filters['date'] ?? '' }}"
                >
            </div>

            <div class="op-field">
                <label class="op-label">Bulan</label>
                <select name="month" class="op-select">
                    <option value="">Semua Bulan</option>
                    @foreach($monthOptions as $k => $v)
                        <option value="{{ $k }}" {{ (string)($filters['month'] ?? '') === (string)$k ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="op-field">
                <label class="op-label">Tahun</label>
                <select name="year" class="op-select">
                    <option value="">Semua Tahun</option>
                    @foreach($yearOptions as $year)
                        <option value="{{ $year }}" {{ (string)($filters['year'] ?? '') === (string)$year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="op-field">
                <label class="op-label">Cari Catatan</label>
                <input
                    type="text"
                    name="search"
                    class="op-input"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="Cari catatan..."
                >
            </div>

            <div class="op-field full" style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('operational.crude.index') }}" class="op-btn op-btn--soft">
                    Reset
                </a>

                <button type="submit" class="op-btn op-btn--primary">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="op-card">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Daftar Data Crude Oil</h2>
            <div class="op-card-desc">
                Data produksi harian dengan pemisahan Vacuum Truck dan Road Tank.
            </div>
        </div>
    </div>

    <div class="op-card-body">
        @if($records->count())
            <div class="op-table-wrap">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Vacuum Truck</th>
                            <th>Road Tank</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $records->firstItem() + $loop->index }}</td>

                                <td>
                                    {{ optional($record->record_date)->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ number_format((float) $record->vacuum_truck, 4, ',', '.') }}
                                </td>

                                <td>
                                    {{ number_format((float) $record->road_tank, 4, ',', '.') }}
                                </td>

                                <td>
                                    {{ $record->notes ?: '-' }}
                                </td>

                                <td>
                                    <div class="op-actions">
                                        <a href="{{ route('operational.crude.edit', $record->id) }}" class="op-btn op-btn-xs op-btn-edit">
                                            Edit
                                        </a>

                                        <form action="{{ route('operational.crude.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="op-btn op-btn-xs op-btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="op-pagination">
                {{ $records->links() }}
            </div>
        @else
            <div class="op-empty">
                <div class="op-empty-title">Belum ada data crude</div>
                <div>Silakan tambahkan data produksi crude.</div>
            </div>
        @endif
    </div>
</div>

<style>
    .op-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    @media (max-width: 1100px) {
        .op-grid-3 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .op-grid-3 {
            grid-template-columns: 1fr;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
(function () {
    const canvas = document.getElementById('crudeStackedChart');

    if (!canvas) {
        return;
    }

    const chartLabels = @json($chartLabels);
    const vacuumTruckValues = @json($chartVacuumTruckValues);
    const roadTankValues = @json($chartRoadTankValues);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Vacuum Truck',
                    data: vacuumTruckValues,
                    backgroundColor: '#111827',
                    borderColor: '#111827',
                    borderWidth: 1,
                    borderRadius: {
                        topLeft: 0,
                        topRight: 0,
                        bottomLeft: 8,
                        bottomRight: 8
                    },
                    stack: 'crude_stack',
                    barPercentage: 0.72,
                    categoryPercentage: 0.7
                },
                {
                    label: 'Road Tank',
                    data: roadTankValues,
                    backgroundColor: '#9ca3af',
                    borderColor: '#6b7280',
                    borderWidth: 1,
                    borderRadius: {
                        topLeft: 8,
                        topRight: 8,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    stack: 'crude_stack',
                    barPercentage: 0.72,
                    categoryPercentage: 0.7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 14,
                        boxHeight: 14,
                        usePointStyle: true,
                        pointStyle: 'rectRounded',
                        padding: 18,
                        color: '#334155',
                        font: {
                            size: 12,
                            weight: '700'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#ffffff',
                    bodyColor: '#e5e7eb',
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: true,
                    callbacks: {
                        label: function (context) {
                            const value = Number(context.raw || 0);

                            return context.dataset.label + ': ' + value.toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 4
                            });
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#475569',
                        font: {
                            size: 11,
                            weight: '700'
                        },
                        maxRotation: 0,
                        autoSkip: true
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, .22)'
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            size: 11,
                            weight: '700'
                        },
                        callback: function (value) {
                            return Number(value).toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
})();
</script>
@endsection