@extends('layouts.admin')

@section('title', 'Monitoring News')

@section('content')

@php
    $statusOptions = [
        'draft' => 'Draft',
        'in_review' => 'In Review',
        'rejected' => 'Rejected',
        'published' => 'Published',
        'archived' => 'Archived',
    ];
@endphp

<div class="a-page-head">
    <div class="a-page-head-copy">
        <div class="a-breadcrumb">
            <span>Admin</span>
            <span class="a-breadcrumb-sep">›</span>
            <span>News</span>
        </div>
        <h1 class="a-page-title">Monitoring News</h1>
        <p class="a-page-desc">Admin hanya dapat melihat detail berita, status review, dan audit logs.</p>
    </div>
</div>

<div class="a-card" style="margin-bottom:20px;">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Filter</div>
            <div class="a-card-desc">Cari berita berdasarkan judul, kategori, atau status.</div>
        </div>
    </div>

    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.news.index') }}" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;">
            <div>
                <label class="a-label">Cari Judul</label>
                <input type="text" name="q" class="a-input" value="{{ $q }}" placeholder="Cari berita...">
            </div>

            <div>
                <label class="a-label">Kategori</label>
                <select name="cat" class="a-input">
                    <option value="">Semua kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) $cat === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="a-label">Status</label>
                <select name="status" class="a-input">
                    <option value="">Semua status</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="a-btn a-btn--primary">Filter</button>
                <a href="{{ route('admin.news.index') }}" class="a-btn a-btn--secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="a-card">
    <div class="a-card-head">
        <div>
            <div class="a-card-title">Daftar News</div>
            <div class="a-card-desc">Total {{ $news->total() }} berita</div>
        </div>
    </div>

    <div class="a-table-wrap">
        <table class="a-table">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Judul</th>
                    <th width="180">Kategori</th>
                    <th width="120">Status</th>
                    <th width="170">Published At</th>
                    <th width="180">Reviewer</th>
                    <th width="180">Author</th>
                    <th width="170" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                    @php
                        $tId = $item->translations->firstWhere('locale', 'id');
                    @endphp
                    <tr>
                        <td>{{ $news->firstItem() + $loop->index }}</td>
                        <td>
                            <div style="font-weight:700">{{ $tId->title ?? '-' }}</div>
                            @if($item->review_note)
                                <div style="margin-top:4px;font-size:12px;color:var(--text3)">
                                    Note: {{ $item->review_note }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $item->category->name ?? '-' }}</td>
                        <td>
                            @if($item->status === 'published')
                                <span class="a-badge a-badge--green">Published</span>
                            @elseif($item->status === 'in_review')
                                <span class="a-badge a-badge--blue">In Review</span>
                            @elseif($item->status === 'rejected')
                                <span class="a-badge a-badge--red">Rejected</span>
                            @elseif($item->status === 'draft')
                                <span class="a-badge a-badge--gray">Draft</span>
                            @else
                                <span class="a-badge a-badge--gray">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $item->published_at ? $item->published_at->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $item->reviewer->name ?? '-' }}</td>
                        <td>{{ $item->author->name ?? '-' }}</td>
                        <td style="text-align:center">
                            <div style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap;">
                                <a href="{{ route('admin.news.show', $item) }}" class="a-btn a-btn--secondary a-btn--sm">
                                    Detail
                                </a>
                                <a href="{{ route('admin.news.logs', $item) }}" class="a-btn a-btn--secondary a-btn--sm">
                                    Logs
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="a-empty">
                                <div class="a-empty-title">Belum ada berita</div>
                                <div class="a-empty-desc">Belum ada data news yang dapat ditampilkan.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:16px">
    {{ $news->links() }}
</div>

@endsection