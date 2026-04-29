@extends('layouts.operational')

@section('title', 'Data Flow Gas')

@section('content')
    <div class="op-page-head">
        <div>
            <div class="op-breadcrumb">
                <span>Operational</span>
                <span class="op-breadcrumb-sep">›</span>
                <span>Flow Gas</span>
            </div>
            <h1 class="op-page-title">Data Flow Gas Harian</h1>
            <p class="op-page-desc">
                Kelola data harian flow gas berdasarkan kategori Flowcomp A dan Flowcomp B.
                Halaman ini khusus modul flow gas dan tidak dicampur dengan crude atau VITOL.
            </p>
        </div>

        <a href="{{ route('operational.flow-gas.create') }}" class="op-btn op-btn--primary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Data
        </a>
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

    @if($errors->any())
        <div class="op-alert op-alert--danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="flex-shrink:0;margin-top:2px">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                <div style="font-weight:800; margin-bottom:4px;">Terjadi kesalahan validasi.</div>
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li style="margin:3px 0;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="op-grid-4" style="margin-bottom:20px;">
        <div class="op-stat">
            <div class="op-stat-label">Total Record</div>
            <div class="op-stat-value">{{ number_format($summary['count'], 0, ',', '.') }}</div>
            <div class="op-stat-sub">Jumlah data hasil filter aktif.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Total MSCF</div>
            <div class="op-stat-value">{{ number_format((float) $summary['total_mscf'], 4, ',', '.') }}</div>
            <div class="op-stat-sub">Akumulasi volume gas dari hasil filter.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Total MMBTU</div>
            <div class="op-stat-value">{{ number_format((float) $summary['total_mmbtu'], 4, ',', '.') }}</div>
            <div class="op-stat-sub">Akumulasi energi gas dari hasil filter.</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Total FIX</div>
            <div class="op-stat-value">{{ number_format((float) $summary['total_fix'], 4, ',', '.') }}</div>
            <div class="op-stat-sub">Akumulasi nilai fix dari hasil filter.</div>
        </div>
    </div>

    <div class="op-card" style="margin-bottom:20px;">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Filter Data</h2>
                <div class="op-card-desc">Filter berdasarkan kategori, tanggal, bulan, tahun, dan pencarian catatan.</div>
            </div>
        </div>
        <div class="op-card-body">
            <form method="GET" action="{{ route('operational.flow-gas.index') }}" class="op-form-grid">
                <div class="op-field">
                    <label class="op-label">Kategori</label>
                    <select name="category_id" class="op-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) ($filters['category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field">
                    <label class="op-label">Tanggal</label>
                    <input type="date" name="date" class="op-input" value="{{ $filters['date'] ?? '' }}">
                </div>

                <div class="op-field">
                    <label class="op-label">Bulan</label>
                    <select name="month" class="op-select">
                        <option value="">Semua Bulan</option>
                        @foreach($monthOptions as $monthNumber => $monthLabel)
                            <option value="{{ $monthNumber }}" {{ (string) ($filters['month'] ?? '') === (string) $monthNumber ? 'selected' : '' }}>
                                {{ $monthLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field">
                    <label class="op-label">Tahun</label>
                    <select name="year" class="op-select">
                        <option value="">Semua Tahun</option>
                        @foreach($yearOptions as $year)
                            <option value="{{ $year }}" {{ (string) ($filters['year'] ?? '') === (string) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="op-field full">
                    <label class="op-label">Cari Catatan / Kategori</label>
                    <input type="text" name="search" class="op-input" value="{{ $filters['search'] ?? '' }}" placeholder="Cari notes, kategori, atau kode...">
                </div>

                <div class="op-field full" style="display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('operational.flow-gas.index') }}" class="op-btn op-btn--soft">Reset Filter</a>
                    <button type="submit" class="op-btn op-btn--primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="op-card">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Daftar Data Harian</h2>
                <div class="op-card-desc">Semua data flow gas harian yang sudah masuk ke sistem operasional.</div>
            </div>
        </div>
        <div class="op-card-body">
            @if($records->count())
                <div class="op-table-wrap">
                    <table class="op-table">
                        <thead>
                            <tr>
                                <th style="width:70px;">#</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>MSCF</th>
                                <th>MMBTU</th>
                                <th>FIX</th>
                                <th>Catatan</th>
                                <th style="width:180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{ $records->firstItem() + $loop->index }}</td>
                                    <td>{{ optional($record->record_date)->format('d-m-Y') }}</td>
                                    <td><span class="op-badge op-badge--green">{{ $record->category->name ?? '-' }}</span></td>
                                    <td>{{ number_format((float) $record->mscf, 4, ',', '.') }}</td>
                                    <td>{{ number_format((float) $record->mmbtu, 4, ',', '.') }}</td>
                                    <td>{{ number_format((float) $record->fix, 4, ',', '.') }}</td>
                                    <td>{{ $record->notes ?: '-' }}</td>
                                    <td>
                                        <div class="op-actions">
                                            <a href="{{ route('operational.flow-gas.edit', $record->id) }}" class="op-btn op-btn-xs op-btn-edit">Edit</a>

                                            <form action="{{ route('operational.flow-gas.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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

                <div class="op-pagination">
                    {{ $records->links() }}
                </div>
            @else
                <div class="op-empty">
                    <div class="op-empty-title">Belum ada data flow gas</div>
                    <div>Silakan tambahkan data baru atau ubah filter untuk menampilkan hasil yang sesuai.</div>
                </div>
            @endif
        </div>
    </div>
@endsection
