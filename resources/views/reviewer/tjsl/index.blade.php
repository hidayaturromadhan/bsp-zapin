@extends('layouts.reviewer')

@section('title', 'Reviewer TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Reviewer</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>TJSL</span>
        </div>
        <h1 class="a-page-title">Preview Program TJSL</h1>
        <p class="a-page-desc">Reviewer hanya melihat preview hasil konten TJSL yang dikirim oleh writer.</p>
    </div>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="a-alert a-alert--danger">{{ session('error') }}</div>
@endif

<div class="a-card" style="margin-bottom:16px">
    <form method="GET" action="{{ route('reviewer.tjsl.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end">
        <div style="flex:1;min-width:220px">
            <label class="a-label">Search</label>
            <input type="text" name="search" value="{{ $search }}" class="a-input" placeholder="Cari judul / tahun / writer">
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
        <a href="{{ route('reviewer.tjsl.index') }}" class="a-btn a-btn--light">Reset</a>
    </form>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Preview TJSL</div>
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
                    <th width="180">Writer</th>
                    <th width="140">Status</th>
                    <th width="170">Tanggal</th>
                    <th width="160" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    @php
                        $trId = $program->translations->firstWhere('locale', 'id');

                        $badgeClass = match($program->status) {
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
                            <div style="font-size:12px;color:#9ca3af;margin-top:4px">
                                Galeri: {{ $program->images->count() }} foto
                            </div>
                        </td>

                        <td>
                            <div style="font-weight:700">{{ $program->author?->name ?? '-' }}</div>
                            <div style="font-size:12px;color:#6b7280">{{ $program->author?->email ?? '-' }}</div>
                        </td>

                        <td>
                            <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
                        </td>

                        <td>
                            <div style="font-size:12px;color:#6b7280">
                                Update:<br>
                                <strong>{{ $program->updated_at?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>

                            <div style="font-size:12px;color:#6b7280;margin-top:4px">
                                Publish:<br>
                                <strong>{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>
                        </td>

                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap">
                                <a href="{{ route('reviewer.tjsl.show', $program) }}" class="a-btn a-btn--secondary a-btn--sm">
                                    Detail
                                </a>

                                <a href="{{ route('reviewer.tjsl.preview', $program) }}" class="a-btn a-btn--primary a-btn--sm" target="_blank">
                                    Preview
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="a-empty">
                                <div class="a-empty-title">Belum ada preview TJSL</div>
                                <div class="a-empty-desc">Link preview dari writer akan mengarah ke halaman preview yang hanya bisa diakses reviewer setelah login.</div>
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