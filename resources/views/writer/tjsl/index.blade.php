@extends('layouts.writer')

@section('title', 'Writer TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Writer</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>TJSL</span>
        </div>
        <h1 class="a-page-title">Program TJSL Saya</h1>
        <p class="a-page-desc">Kelola draft, preview, kirim link preview ke reviewer, dan publish TJSL secara mandiri.</p>
    </div>

    <a href="{{ route('writer.tjsl.create') }}" class="a-btn a-btn--primary">
        Tambah TJSL
    </a>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="a-alert a-alert--danger">{{ session('error') }}</div>
@endif

<div class="a-card" style="margin-bottom:16px">
    <form method="GET" action="{{ route('writer.tjsl.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end">
        <div style="flex:1;min-width:220px">
            <label class="a-label">Search</label>
            <input type="text" name="search" value="{{ $search }}" class="a-input" placeholder="Cari judul / tahun">
        </div>

        <div style="min-width:220px">
            <label class="a-label">Status</label>
            <select name="status" class="a-input">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="a-btn a-btn--secondary">Filter</button>
        <a href="{{ route('writer.tjsl.index') }}" class="a-btn a-btn--light">Reset</a>
    </form>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar TJSL</div>
            <div class="a-card-desc">Total {{ $programs->total() }} program</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="48">#</th>
                    <th width="90">Gambar</th>
                    <th width="90">Tahun</th>
                    <th>Judul</th>
                    <th width="140">Status</th>
                    <th width="160">Tanggal</th>
                    <th width="260" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    @php
                        $trId = $program->translations->firstWhere('locale', 'id');
                        $trEn = $program->translations->firstWhere('locale', 'en');

                        $badgeClass = match($program->status) {
                            'draft' => 'a-badge--gray',
                            'published' => 'a-badge--green',
                            default => 'a-badge--gray',
                        };
                    @endphp

                    <tr>
                        <td>{{ $programs->firstItem() + $loop->index }}</td>

                        <td>
                            @if($program->featured_image)
                                <img src="{{ asset($program->featured_image) }}" alt="TJSL" style="width:64px;height:46px;object-fit:cover;border-radius:10px">
                            @else
                                <div style="width:64px;height:46px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-size:11px;color:#6b7280">
                                    No Image
                                </div>
                            @endif
                        </td>

                        <td style="font-weight:700">{{ $program->year }}</td>

                        <td>
                            <div style="font-weight:800;color:#111827">{{ $trId->title ?? '-' }}</div>

                            @if($trEn?->title)
                                <div style="font-size:12px;color:#6b7280;margin-top:3px">
                                    EN otomatis: {{ $trEn->title }}
                                </div>
                            @else
                                <div style="font-size:12px;color:#b45309;margin-top:3px">
                                    EN belum tersedia
                                </div>
                            @endif

                            <div style="font-size:12px;color:#9ca3af;margin-top:4px">
                                Galeri: {{ $program->images->count() }} foto
                            </div>
                        </td>

                        <td>
                            <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
                        </td>

                        <td>
                            <div style="font-size:12px;color:#6b7280">
                                Dibuat:<br>
                                <strong>{{ $program->created_at?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>
                            <div style="font-size:12px;color:#6b7280;margin-top:4px">
                                Publish:<br>
                                <strong>{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>
                        </td>

                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap">
                                <a href="{{ route('writer.tjsl.show', $program) }}" class="a-btn a-btn--secondary a-btn--sm">
                                    Detail
                                </a>

                                <a href="{{ route('writer.tjsl.preview', $program) }}" class="a-btn a-btn--light a-btn--sm" target="_blank">
                                    Preview
                                </a>

                                <a href="{{ route('writer.tjsl.edit', $program) }}" class="a-btn a-btn--primary a-btn--sm">
                                    Edit
                                </a>

                                <a href="{{ route('writer.tjsl.send-preview-whatsapp', $program) }}" class="a-btn a-btn--secondary a-btn--sm" target="_blank">
                                    Kirim WA
                                </a>

                                @if($program->status === 'draft')
                                    <form method="POST" action="{{ route('writer.tjsl.publish', $program) }}" onsubmit="return confirm('Publish TJSL ini ke website publik?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="a-btn a-btn--primary a-btn--sm">
                                            Publish
                                        </button>
                                    </form>
                                @endif

                                @if($program->status === 'published')
                                    <form method="POST" action="{{ route('writer.tjsl.unpublish', $program) }}" onsubmit="return confirm('Tarik TJSL ini dari website publik?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="a-btn a-btn--secondary a-btn--sm">
                                            Unpublish
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('writer.tjsl.destroy', $program) }}" onsubmit="return confirm('Hapus program TJSL ini?')">
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
                                <div class="a-empty-title">Belum ada program TJSL</div>
                                <div class="a-empty-desc">Mulai dengan membuat draft TJSL baru.</div>
                                <a href="{{ route('writer.tjsl.create') }}" class="a-btn a-btn--primary">Tambah TJSL</a>
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