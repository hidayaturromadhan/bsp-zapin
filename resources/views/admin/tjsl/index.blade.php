@extends('layouts.admin')

@section('title', 'Manajemen TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>TJSL</span>
        </div>
        <h1 class="a-page-title">Manajemen TJSL</h1>
        <p class="a-page-desc">Kelola program TJSL per tahun beserta galeri dokumentasi</p>
    </div>

    <a href="{{ route('admin.tjsl.create') }}" class="a-btn a-btn--primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Program TJSL
    </a>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Program TJSL</div>
            <div class="a-card-desc">Total {{ $programs->total() }} program</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="48">#</th>
                    <th width="90">Tahun</th>
                    <th>Judul (ID)</th>
                    <th>Judul (EN)</th>
                    <th width="100" style="text-align:center">Galeri</th>
                    <th width="100" style="text-align:center">Status</th>
                    <th width="150" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    @php
                        $trId = $program->translations->firstWhere('locale', 'id');
                        $trEn = $program->translations->firstWhere('locale', 'en');
                    @endphp
                    <tr>
                        <td>{{ $programs->firstItem() + $loop->index }}</td>
                        <td style="font-weight:700">{{ $program->year }}</td>
                        <td style="font-weight:600">{{ $trId->title ?? '-' }}</td>
                        <td>{{ $trEn->title ?? '-' }}</td>
                        <td style="text-align:center">
                            <span class="a-badge a-badge--blue">{{ $program->images->count() }} foto</span>
                        </td>
                        <td style="text-align:center">
                            @if($program->is_active)
                                <span class="a-badge a-badge--green">Aktif</span>
                            @else
                                <span class="a-badge a-badge--red">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('admin.tjsl.edit', $program) }}" class="a-btn a-btn--secondary a-btn--sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.tjsl.destroy', $program) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus program TJSL ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="a-btn a-btn--danger a-btn--sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="a-empty">
                                <div class="a-empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                    </svg>
                                </div>
                                <div class="a-empty-title">Belum ada program TJSL</div>
                                <div class="a-empty-desc">Mulai dengan menambahkan program TJSL per tahun</div>
                                <a href="{{ route('admin.tjsl.create') }}" class="a-btn a-btn--primary">Tambah Program</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px">
    {{ $programs->links() }}
</div>

@endsection