@extends('layouts.admin')

@section('title', 'Hubungan Investor')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>Hubungan Investor</span>
        </div>
        <h1 class="a-page-title">Hubungan Investor</h1>
        <p class="a-page-desc">Kelola laporan tahunan dan dokumen investor relations</p>
    </div>

    <a href="{{ route('admin.investor-relations.create') }}" class="a-btn a-btn--primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Dokumen
    </a>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Dokumen Investor</div>
            <div class="a-card-desc">Total {{ $documents->total() }} dokumen</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="48">#</th>
                    <th>Judul (ID)</th>
                    <th>Judul (EN)</th>
                    <th width="90" style="text-align:center">Tahun</th>
                    <th width="90" style="text-align:center">Urutan</th>
                    <th width="100" style="text-align:center">Status</th>
                    <th width="140" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                    @php
                        $trId = $doc->translations->firstWhere('locale', 'id');
                        $trEn = $doc->translations->firstWhere('locale', 'en');
                    @endphp
                    <tr>
                        <td>{{ $documents->firstItem() + $loop->index }}</td>
                        <td style="font-weight:600">{{ $trId->title ?? '-' }}</td>
                        <td>{{ $trEn->title ?? '-' }}</td>
                        <td style="text-align:center">{{ $doc->year ?: '-' }}</td>
                        <td style="text-align:center">{{ $doc->sort_order }}</td>
                        <td style="text-align:center">
                            @if($doc->is_active)
                                <span class="a-badge a-badge--green">Aktif</span>
                            @else
                                <span class="a-badge a-badge--red">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('admin.investor-relations.edit', $doc) }}"
                                   class="a-btn a-btn--secondary a-btn--sm">Edit</a>

                                <form action="{{ route('admin.investor-relations.destroy', $doc) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="a-btn a-btn--danger a-btn--sm"
                                            data-confirm="Hapus dokumen investor ini?"
                                            data-confirm-text="Dokumen dan file terkait akan dihapus permanen."
                                            data-confirm-type="delete"
                                            data-confirm-ok="Ya, hapus dokumen">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="a-empty">
                                <div class="a-empty-title">Belum ada dokumen investor</div>
                                <div class="a-empty-desc">Mulai dengan menambahkan laporan tahunan pertama</div>
                                <a href="{{ route('admin.investor-relations.create') }}" class="a-btn a-btn--primary">
                                    Tambah Dokumen
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px">
    {{ $documents->links('vendor.pagination.admin') }}
</div>

@endsection