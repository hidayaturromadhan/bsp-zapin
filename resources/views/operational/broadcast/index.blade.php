@extends('layouts.operational')

@section('title', 'Broadcast TV')

@section('content')
    <div class="op-page-head">
        <div>
            <div class="op-breadcrumb">
                <span>Operational</span>
                <span class="op-breadcrumb-sep">/</span>
                <span>Broadcast TV</span>
            </div>
            <h1 class="op-page-title">Broadcast TV</h1>
            <p class="op-page-desc">
                Kelola running text yang tampil di halaman TV monitoring. Anda bisa menentukan label, isi pesan,
                urutan tampil, status aktif, dan periode tampil.
            </p>
        </div>

        <div class="op-actions">
            <a href="{{ route('operational.broadcast.create') }}" class="op-btn op-btn--primary">Tambah Broadcast</a>
        </div>
    </div>

    @if(session('success'))
        <div class="op-alert op-alert--success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="op-alert op-alert--danger">
            <div>
                <strong>Terjadi kesalahan.</strong>
                <div style="margin-top:6px;">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="op-grid-3" style="margin-bottom: 20px;">
        <div class="op-stat">
            <div class="op-stat-label">Total Broadcast</div>
            <div class="op-stat-value">{{ number_format($summary['count'] ?? 0, 0, ',', '.') }}</div>
            <div class="op-stat-sub">Jumlah semua item broadcast</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Broadcast Aktif</div>
            <div class="op-stat-value">{{ number_format($summary['active_count'] ?? 0, 0, ',', '.') }}</div>
            <div class="op-stat-sub">Akan tampil di TV jika masih dalam periode</div>
        </div>

        <div class="op-stat">
            <div class="op-stat-label">Broadcast Nonaktif</div>
            <div class="op-stat-value">{{ number_format($summary['inactive_count'] ?? 0, 0, ',', '.') }}</div>
            <div class="op-stat-sub">Tidak akan tampil di running text</div>
        </div>
    </div>

    <div class="op-card">
        <div class="op-card-head">
            <div>
                <h2 class="op-card-title">Daftar Broadcast</h2>
                <p class="op-card-desc">Filter dan kelola pesan broadcast TV monitoring.</p>
            </div>
        </div>

        <div class="op-card-body">
            <form method="GET" action="{{ route('operational.broadcast.index') }}" class="op-form-grid" style="margin-bottom: 20px;">
                <div class="op-field">
                    <label class="op-label">Pencarian</label>
                    <input type="text" name="search" class="op-input" value="{{ $filters['search'] ?? '' }}" placeholder="Cari label atau isi broadcast">
                </div>

                <div class="op-field">
                    <label class="op-label">Status</label>
                    <select name="status" class="op-select">
                        <option value="">Semua status</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="op-field full">
                    <div class="op-actions">
                        <button type="submit" class="op-btn op-btn--primary">Terapkan Filter</button>
                        <a href="{{ route('operational.broadcast.index') }}" class="op-btn op-btn--soft">Reset</a>
                    </div>
                </div>
            </form>

            @if($records->count())
                <div class="op-table-wrap">
                    <table class="op-table">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Label</th>
                                <th>Isi Broadcast</th>
                                <th>Status</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                                <tr>
                                    <td>{{ $record->sort_order }}</td>
                                    <td>{{ $record->label ?: '-' }}</td>
                                    <td style="min-width:320px;">{{ $record->message }}</td>
                                    <td>
                                        @if($record->is_active)
                                            <span class="op-badge op-badge--green">Aktif</span>
                                        @else
                                            <span class="op-badge op-badge--red">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($record->starts_at)->format('d-m-Y H:i') ?: '-' }}</td>
                                    <td>{{ optional($record->ends_at)->format('d-m-Y H:i') ?: '-' }}</td>
                                    <td>{{ optional($record->created_at)->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <div class="op-actions">
                                            <a href="{{ route('operational.broadcast.edit', $record) }}" class="op-btn op-btn-xs op-btn-edit">Edit</a>

                                            <form method="POST" action="{{ route('operational.broadcast.destroy', $record) }}" onsubmit="return confirm('Yakin hapus broadcast ini?')">
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
                    <div class="op-empty-title">Belum ada data broadcast</div>
                    <div>Tambahkan broadcast baru agar bisa tampil di TV monitoring.</div>
                </div>
            @endif
        </div>
    </div>
@endsection