@extends('layouts.admin')

@section('title', 'Manajemen GCG')

@section('content')

{{-- Page Header --}}
<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>GCG</span>
        </div>
        <h1 class="a-page-title">Manajemen GCG</h1>
        <p class="a-page-desc">Kelola kategori dan dokumen Good Corporate Governance</p>
    </div>
    <a href="{{ route('admin.gcg.create') }}" class="a-btn a-btn--primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Kategori
    </a>
</div>

{{-- Table --}}
<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Kategori GCG</div>
            <div class="a-card-desc">Total {{ $categories->total() }} kategori</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="48">#</th>
                    <th>Nama Kategori (ID)</th>
                    <th>Nama Kategori (EN)</th>
                    <th style="text-align:center" width="100">Dokumen</th>
                    <th style="text-align:center" width="100">Status</th>
                    <th style="text-align:center" width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    @php
                        $trId = $cat->translations->firstWhere('locale','id');
                        $trEn = $cat->translations->firstWhere('locale','en');
                    @endphp
                    <tr>
                        <td style="color:var(--text3)">{{ $categories->firstItem() + $loop->index }}</td>
                        <td style="font-weight:600">{{ $trId->name ?? '-' }}</td>
                        <td style="color:var(--text2)">{{ $trEn->name ?? '-' }}</td>
                        <td style="text-align:center">
                            <span class="a-badge a-badge--blue">{{ $cat->documents->count() }} dok</span>
                        </td>
                        <td style="text-align:center">
                            @if($cat->is_active)
                                <span class="a-badge a-badge--green">Aktif</span>
                            @else
                                <span class="a-badge a-badge--red">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('admin.gcg.edit', $cat) }}"
                                   class="a-btn a-btn--secondary a-btn--sm">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.gcg.destroy', $cat) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="a-btn a-btn--danger a-btn--sm"
                                            data-confirm="Hapus kategori ini?"
                                            data-confirm-text="Semua dokumen di dalamnya juga akan ikut terhapus."
                                            data-confirm-type="delete"
                                            data-confirm-ok="Ya, hapus kategori">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6"/><path d="M14 11v6"/>
                                            <path d="M9 6V4h6v2"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="a-empty">
                                <div class="a-empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                    </svg>
                                </div>
                                <div class="a-empty-title">Belum ada kategori GCG</div>
                                <div class="a-empty-desc">Mulai dengan menambahkan kategori pertama Anda</div>
                                <a href="{{ route('admin.gcg.create') }}" class="a-btn a-btn--primary">Tambah Kategori</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div style="margin-top:16px">
    {{ $categories->links('vendor.pagination.admin') }}
</div>

@endsection