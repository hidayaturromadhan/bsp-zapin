@extends('layouts.admin')

@section('title', 'Monitoring TJSL')

@section('content')

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>TJSL</span>
        </div>
        <h1 class="a-page-title">Monitoring TJSL</h1>
        <p class="a-page-desc">Admin hanya dapat memantau aktivitas TJSL yang dikelola writer dan reviewer.</p>
    </div>
</div>

@if(session('success'))
    <div class="a-alert a-alert--success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="a-alert a-alert--danger">{{ session('error') }}</div>
@endif

<div style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:12px;margin-bottom:16px">
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Total</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['total'] }}</div>
    </div>
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Draft</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['draft'] }}</div>
    </div>
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Review</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['submitted'] }}</div>
    </div>
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Revisi</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['revision'] }}</div>
    </div>
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Approved</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['approved'] }}</div>
    </div>
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Rejected</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['rejected'] }}</div>
    </div>
    <div class="a-card">
        <div style="font-size:12px;color:#6b7280">Published</div>
        <div style="font-size:24px;font-weight:900">{{ $summary['published'] }}</div>
    </div>
</div>

<div class="a-card" style="margin-bottom:16px">
    <form method="GET" action="{{ route('admin.tjsl.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end">
        <div style="flex:1;min-width:220px">
            <label class="a-label">Search</label>
            <input type="text" name="search" value="{{ $search }}" class="a-input" placeholder="Cari judul / tahun / writer / reviewer">
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
        <a href="{{ route('admin.tjsl.index') }}" class="a-btn a-btn--light">Reset</a>
    </form>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar Aktivitas TJSL</div>
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
                    <th width="170">Writer</th>
                    <th width="170">Reviewer</th>
                    <th width="145">Status</th>
                    <th width="160">Aktivitas</th>
                    <th width="110" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    @php
                        $trId = $program->translations->firstWhere('locale', 'id');
                        $trEn = $program->translations->firstWhere('locale', 'en');

                        $badgeClass = match($program->status) {
                            'draft' => 'a-badge--gray',
                            'submitted' => 'a-badge--blue',
                            'revision' => 'a-badge--orange',
                            'approved' => 'a-badge--green',
                            'rejected' => 'a-badge--red',
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
                                <div style="font-size:12px;color:#2563eb;margin-top:3px">
                                    EN otomatis tersedia
                                </div>
                            @else
                                <div style="font-size:12px;color:#b45309;margin-top:3px">
                                    EN belum tersedia
                                </div>
                            @endif
                        </td>

                        <td>
                            <div style="font-weight:700">{{ $program->author?->name ?? '-' }}</div>
                            <div style="font-size:12px;color:#6b7280">{{ $program->author?->email ?? '-' }}</div>
                        </td>

                        <td>
                            <div style="font-weight:700">{{ $program->reviewer?->name ?? '-' }}</div>
                            <div style="font-size:12px;color:#6b7280">{{ $program->reviewer?->email ?? '-' }}</div>
                        </td>

                        <td>
                            <span class="a-badge {{ $badgeClass }}">{{ $program->status_label }}</span>
                        </td>

                        <td>
                            <div style="font-size:12px;color:#6b7280">
                                Submit: <strong>{{ $program->submitted_at?->format('d M Y H:i') ?? '-' }}</strong><br>
                                Review: <strong>{{ $program->reviewed_at?->format('d M Y H:i') ?? '-' }}</strong><br>
                                Publish: <strong>{{ $program->published_at?->format('d M Y H:i') ?? '-' }}</strong>
                            </div>
                        </td>

                        <td style="text-align:center">
                            <a href="{{ route('admin.tjsl.show', $program) }}" class="a-btn a-btn--primary a-btn--sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="a-empty">
                                <div class="a-empty-title">Belum ada aktivitas TJSL</div>
                                <div class="a-empty-desc">Aktivitas writer dan reviewer akan tampil di sini.</div>
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