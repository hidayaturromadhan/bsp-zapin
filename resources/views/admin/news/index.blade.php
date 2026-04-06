@extends('layouts.admin')

@section('content')
<style>
    .news-admin-page {
        max-width: 1280px;
    }

    .news-admin-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .news-admin-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #111827;
    }

    .news-admin-subtitle {
        margin-top: 6px;
        font-size: 14px;
        color: #6b7280;
    }

    .news-admin-create {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        background: #173f08;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
    }

    .news-admin-create:hover {
        background: #21560e;
    }

    .news-admin-alert {
        margin-bottom: 16px;
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
    }

    .news-admin-alert.success {
        background: #eef8ee;
        color: #17603a;
        border: 1px solid #cfe9d3;
    }

    .news-admin-filter {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 18px;
        padding: 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(15, 23, 42, .04);
    }

    .news-admin-input,
    .news-admin-select {
        min-height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0 12px;
        font: inherit;
        background: #fff;
        color: #111827;
    }

    .news-admin-input {
        min-width: 260px;
        flex: 1 1 280px;
    }

    .news-admin-select {
        min-width: 190px;
    }

    .news-admin-btn {
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
        color: #111827;
        font: inherit;
        font-weight: 700;
        cursor: pointer;
    }

    .news-admin-btn:hover {
        background: #f9fafb;
    }

    .news-admin-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
    }

    .news-admin-table-wrap {
        overflow-x: auto;
    }

    .news-admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 980px;
    }

    .news-admin-table th,
    .news-admin-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
    }

    .news-admin-table th {
        background: #f8fafc;
        color: #111827;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .news-admin-table tr:last-child td {
        border-bottom: none;
    }

    .news-admin-thumb {
        width: 132px;
        height: 74px;
        object-fit: cover;
        border-radius: 10px;
        display: block;
        background: #eef2f7;
        border: 1px solid #e5e7eb;
    }

    .news-admin-title-cell {
        min-width: 340px;
    }

    .news-admin-row-title {
        font-size: 18px;
        font-weight: 800;
        line-height: 1.4;
        color: #111827;
        margin-bottom: 8px;
    }

    .news-admin-row-meta {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.7;
    }

    .news-admin-preview {
        margin-top: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 13px;
    }

    .news-admin-preview a {
        color: #173f08;
        text-decoration: none;
        font-weight: 700;
    }

    .news-admin-preview a:hover {
        text-decoration: underline;
    }

    .news-admin-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .news-admin-badge.status-draft {
        background: #f3f4f6;
        color: #374151;
    }

    .news-admin-badge.status-published {
        background: #e9f8ee;
        color: #17603a;
    }

    .news-admin-badge.status-archived {
        background: #fef3c7;
        color: #92400e;
    }

    .news-admin-badge.visible-yes {
        background: #e9f8ee;
        color: #17603a;
    }

    .news-admin-badge.visible-no {
        background: #fef2f2;
        color: #b42318;
    }

    .news-admin-scheduled {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #b8860b;
    }

    .news-admin-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .news-admin-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 12px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid #d1d5db;
        color: #111827;
        background: #fff;
    }

    .news-admin-link:hover {
        background: #f9fafb;
    }

    .news-admin-danger {
        min-height: 36px;
        padding: 0 12px;
        border-radius: 9px;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid #efc8c8;
        background: #fff5f5;
        color: #b42318;
    }

    .news-admin-danger:hover {
        background: #feecec;
    }

    .news-admin-pagination {
        margin-top: 18px;
        display: flex;
        justify-content: center;
    }
</style>

<div class="news-admin-page">
    <div class="news-admin-head">
        <div>
            <h1 class="news-admin-title">News</h1>
            <div class="news-admin-subtitle">Kelola berita bilingual untuk website perusahaan.</div>
        </div>

        <a href="{{ route('admin.news.create') }}" class="news-admin-create">+ Tambah News</a>
    </div>

    @if(session('success'))
        <div class="news-admin-alert success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.news.index') }}" class="news-admin-filter">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Cari judul (ID/EN)..."
            class="news-admin-input"
        >

        <select name="cat" class="news-admin-select">
            <option value="">Semua kategori</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ (string) $cat === (string) $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="news-admin-btn">Filter</button>
    </form>

    <div class="news-admin-card">
        <div class="news-admin-table-wrap">
            <table class="news-admin-table">
                <thead>
                    <tr>
                        <th style="width:150px;">Gambar</th>
                        <th>Judul (ID / EN)</th>
                        <th style="width:140px;">Kategori</th>
                        <th style="width:130px;">Status</th>
                        <th style="width:110px;">Visible</th>
                        <th style="width:120px;">Publish</th>
                        <th style="width:190px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $n)
                        @php
                            $tId = $n->translations->firstWhere('locale', 'id');
                            $tEn = $n->translations->firstWhere('locale', 'en');

                            $publishDate = $n->published_at?->format('Y-m-d');
                            $isScheduled = $n->published_at && $n->published_at->isFuture();

                            $statusClass = match($n->status) {
                                'published' => 'status-published',
                                'archived' => 'status-archived',
                                default => 'status-draft',
                            };
                        @endphp

                        <tr>
                            <td>
                                @if($n->featured_image)
                                    <img src="{{ asset($n->featured_image) }}" alt="Thumbnail" class="news-admin-thumb">
                                @else
                                    <div class="news-admin-thumb" style="display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:12px;">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <td class="news-admin-title-cell">
                                <div class="news-admin-row-title">
                                    {{ $tId?->title ?? '-' }}
                                    <span style="color:#9ca3af; font-weight:600;">/</span>
                                    {{ $tEn?->title ?? '-' }}
                                </div>

                                <div class="news-admin-row-meta">
                                    ID: /{{ $tId?->slug ?? '-' }}<br>
                                    EN: /{{ $tEn?->slug ?? '-' }}
                                </div>

                                <div class="news-admin-preview">
                                    @if($tId?->slug)
                                        <a target="_blank" href="{{ route('news.show', ['locale' => 'id', 'slug' => $tId->slug]) }}">Preview ID</a>
                                    @endif

                                    @if($tEn?->slug)
                                        <a target="_blank" href="{{ route('news.show', ['locale' => 'en', 'slug' => $tEn->slug]) }}">Preview EN</a>
                                    @endif
                                </div>
                            </td>

                            <td>
                                {{ $n->category?->name ?? '-' }}
                            </td>

                            <td>
                                <span class="news-admin-badge {{ $statusClass }}">
                                    {{ ucfirst($n->status) }}
                                </span>

                                @if($isScheduled)
                                    <div class="news-admin-scheduled">Scheduled</div>
                                @endif
                            </td>

                            <td>
                                <span class="news-admin-badge {{ $n->is_visible ? 'visible-yes' : 'visible-no' }}">
                                    {{ $n->is_visible ? 'Yes' : 'Hidden' }}
                                </span>
                            </td>

                            <td>
                                {{ $publishDate ?? '-' }}
                            </td>

                            <td>
                                <div class="news-admin-actions">
                                    <a href="{{ route('admin.news.edit', $n) }}" class="news-admin-link">Edit</a>
                                    <a href="{{ route('admin.news.versions', $n) }}" class="news-admin-link">Versions</a>

                                    <form method="POST" action="{{ route('admin.news.destroy', $n) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="news-admin-danger" onclick="return confirm('Hapus berita ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#6b7280; padding:28px 16px;">
                                Belum ada data news.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="news-admin-pagination">
        {{ $news->links() }}
    </div>
</div>
@endsection