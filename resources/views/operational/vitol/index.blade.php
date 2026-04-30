@extends('layouts.operational')

@section('title', 'Data VITOL')

@section('content')
<div class="op-page-head">
    <div>
        <div class="op-breadcrumb">
            <span>Operational</span>
            <span>›</span>
            <span>VITOL</span>
        </div>
        <h1 class="op-page-title">Data Produksi VITOL</h1>
        <p class="op-page-desc">Kelola data VITOL bulanan seperti sistem lama.</p>
    </div>
    <a href="{{ route('operational.vitol.create') }}" class="op-btn op-btn--primary">+ Tambah Data</a>
</div>

@if(session('success'))
    <div class="op-alert op-alert--success">{{ session('success') }}</div>
@endif

<div class="op-grid-2" style="margin-bottom:20px;">
    <div class="op-stat">
        <div class="op-stat-label">Total Record</div>
        <div class="op-stat-value">{{ number_format($summary['count'], 0, ',', '.') }}</div>
        <div class="op-stat-sub">Jumlah data VITOL hasil filter.</div>
    </div>
    <div class="op-stat">
        <div class="op-stat-label">Total Quantity</div>
        <div class="op-stat-value">{{ number_format((float) $summary['total_quantity'], 4, ',', '.') }}</div>
        <div class="op-stat-sub">Akumulasi quantity VITOL.</div>
    </div>
</div>

<div class="op-card" style="margin-bottom:20px;">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Filter Data</h2>
            <div class="op-card-desc">Filter berdasarkan tahun, bulan, dan pencarian data.</div>
        </div>
    </div>
    <div class="op-card-body">
        <form method="GET" action="{{ route('operational.vitol.index') }}" class="op-form-grid">
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

            <div class="op-field full">
                <label class="op-label">Cari Unit / Catatan</label>
                <input type="text" name="search" class="op-input" value="{{ $filters['search'] ?? '' }}" placeholder="Cari unit atau catatan...">
            </div>

            <div class="op-field full" style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('operational.vitol.index') }}" class="op-btn op-btn--soft">Reset</a>
                <button type="submit" class="op-btn op-btn--primary">Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="op-card">
    <div class="op-card-head">
        <div>
            <h2 class="op-card-title">Daftar Data VITOL</h2>
            <div class="op-card-desc">Data VITOL tersimpan per bulan dan tahun.</div>
        </div>
    </div>
    <div class="op-card-body">
        @if($records->count())
            <div class="op-table-wrap">
                <table class="op-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Quantity</th>
                            <th>Satuan</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{ $records->firstItem() + $loop->index }}</td>
                                <td>{{ $record->year }}</td>
                                <td>{{ $record->month_label }}</td>
                                <td>{{ number_format((float) $record->quantity, 4, ',', '.') }}</td>
                                <td>{{ $record->unit }}</td>
                                <td>{{ $record->notes ?: '-' }}</td>
                                <td>
                                    <div class="op-actions">
                                        <a href="{{ route('operational.vitol.edit', $record->id) }}" class="op-btn op-btn-xs op-btn-edit">Edit</a>
                                        <form action="{{ route('operational.vitol.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="op-btn op-btn-xs op-btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="op-pagination">{{ $records->links() }}</div>
        @else
            <div class="op-empty">
                <div class="op-empty-title">Belum ada data VITOL</div>
                <div>Silakan tambahkan data VITOL bulanan.</div>
            </div>
        @endif
    </div>
</div>
@endsection