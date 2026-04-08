@extends('layouts.admin')

@section('title', 'Highlight GCG')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>GCG Highlight</span>
        </div>
        <h1 class="a-page-title">Highlight GCG</h1>
        <p class="a-page-desc">Kelola label highlight yang tampil di halaman GCG website.</p>
    </div>

    <a href="{{ route('admin.gcg-highlight-items.create') }}" class="a-btn a-btn--primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Highlight
    </a>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Highlight</div>
            <div class="a-card-desc">Total {{ $items->count() }} highlight terdaftar</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Label</th>
                    <th style="width:110px;text-align:center;">Urutan</th>
                    <th style="width:120px;text-align:center;">Status</th>
                    <th style="width:180px;text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="color:var(--text3)">{{ $loop->iteration }}</td>

                        <td>
                            <div style="font-weight:700;color:var(--text);line-height:1.35;">
                                {{ $item->label_id }}
                            </div>
                            <div style="margin-top:4px;font-size:12px;color:var(--text3);">
                                EN otomatis: {{ $item->label_en ?: '-' }}
                            </div>
                        </td>

                        <td style="text-align:center;">
                            <span class="a-badge a-badge--blue">{{ $item->sort_order }}</span>
                        </td>

                        <td style="text-align:center;">
                            @if($item->is_active)
                                <span class="a-badge a-badge--green">Aktif</span>
                            @else
                                <span class="a-badge a-badge--gray">Nonaktif</span>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                                <a href="{{ route('admin.gcg-highlight-items.edit', $item) }}" class="a-btn a-btn--secondary a-btn--sm">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('admin.gcg-highlight-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus highlight ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="a-btn a-btn--danger a-btn--sm">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6"/>
                                            <path d="M14 11v6"/>
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
                        <td colspan="5">
                            <div class="a-empty">
                                <div class="a-empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                        <rect x="3" y="5" width="18" height="14" rx="3"/>
                                        <path d="M7 12h10"/>
                                    </svg>
                                </div>
                                <div class="a-empty-title">Belum ada highlight</div>
                                <div class="a-empty-desc">Tambahkan highlight agar tampil di hero halaman GCG.</div>
                                <a href="{{ route('admin.gcg-highlight-items.create') }}" class="a-btn a-btn--primary">
                                    Tambah Highlight
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection