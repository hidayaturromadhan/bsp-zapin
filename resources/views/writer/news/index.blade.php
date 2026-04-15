@extends('layouts.writer')

@section('content')
<style>
    .ww-page {
        max-width: 1280px;
    }

    .ww-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .ww-title {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.03em;
    }

    .ww-subtitle {
        margin-top: 6px;
        font-size: 14px;
        color: #6b7280;
    }

    .ww-create {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 16px;
        border-radius: 12px;
        background: #173f08;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
    }

    .ww-filter {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 18px;
        padding: 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(15,23,42,.04);
    }

    .ww-input,
    .ww-select {
        min-height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0 12px;
        font: inherit;
        background: #fff;
        color: #111827;
    }

    .ww-input {
        min-width: 260px;
        flex: 1 1 320px;
    }

    .ww-select {
        min-width: 180px;
    }

    .ww-btn {
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
        color: #111827;
        font: inherit;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .ww-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(15,23,42,.04);
    }

    .ww-table-wrap {
        overflow-x: auto;
    }

    .ww-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1180px;
    }

    .ww-table th,
    .ww-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
    }

    .ww-table th {
        background: #f8fafc;
        color: #111827;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .ww-thumb {
        width: 132px;
        height: 74px;
        object-fit: cover;
        border-radius: 10px;
        display: block;
        background: #eef2f7;
        border: 1px solid #e5e7eb;
    }

    .ww-title-cell {
        min-width: 320px;
    }

    .ww-row-title {
        font-size: 18px;
        font-weight: 800;
        line-height: 1.4;
        color: #111827;
        margin-bottom: 8px;
    }

    .ww-row-meta {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.7;
    }

    .ww-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .ww-badge.status-in_review {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .ww-badge.status-published {
        background: #e9f8ee;
        color: #17603a;
    }

    .ww-badge.status-rejected {
        background: #fee2e2;
        color: #b42318;
    }

    .ww-badge.status-draft,
    .ww-badge.status-archived,
    .ww-badge.status-default {
        background: #f3f4f6;
        color: #4b5563;
    }

    .ww-note {
        padding: 10px 12px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        color: #374151;
        line-height: 1.7;
    }

    .ww-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ww-link {
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

    .ww-pagination {
        margin-top: 18px;
        display: flex;
        justify-content: center;
    }
</style>

<div class="ww-page">
    <div class="ww-head">
        <div>
            <h1 class="ww-title">My News</h1>
            <div class="ww-subtitle">Kelola berita milikmu, jadwal publish target, dan status review.</div>
        </div>

        <a href="{{ route('writer.news.create') }}" class="ww-create">Buat News</a>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#eef8ee;color:#17603a;border:1px solid #cfe9d3;font-size:14px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('writer.news.index') }}" class="ww-filter">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Cari judul..."
            class="ww-input"
        >

        <select name="status" class="ww-select">
            <option value="">Semua status</option>
            <option value="in_review" {{ $status === 'in_review' ? 'selected' : '' }}>In Review</option>
            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>

        <button type="submit" class="ww-btn">Filter</button>
        <a href="{{ route('writer.news.index') }}" class="ww-btn">Reset</a>
    </form>

    <div class="ww-card">
        <div class="ww-table-wrap">
            <table class="ww-table">
                <thead>
                    <tr>
                        <th style="width:150px;">Gambar</th>
                        <th>Judul</th>
                        <th style="width:150px;">Kategori</th>
                        <th style="width:130px;">Status</th>
                        <th style="width:180px;">Publish Target</th>
                        <th style="width:180px;">Reviewer</th>
                        <th style="width:260px;">Catatan</th>
                        <th style="width:170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $n)
                        @php
                            $tId = $n->translations->firstWhere('locale', 'id');
                            $statusClass = in_array($n->status, ['in_review', 'published', 'rejected', 'draft', 'archived'])
                                ? 'status-' . $n->status
                                : 'status-default';
                            $statusLabel = ucfirst(str_replace('_', ' ', $n->status));
                        @endphp
                        <tr>
                            <td>
                                @if($n->featured_image)
                                    <img src="{{ asset($n->featured_image) }}" alt="Thumbnail" class="ww-thumb">
                                @else
                                    <div class="ww-thumb" style="display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:12px;">No Image</div>
                                @endif
                            </td>

                            <td class="ww-title-cell">
                                <div class="ww-row-title">{{ $tId?->title ?? '-' }}</div>
                                <div class="ww-row-meta">
                                    Slug: /{{ $tId?->slug ?? '-' }}<br>
                                    Dibuat: {{ $n->created_at?->format('Y-m-d H:i') ?? '-' }}
                                </div>
                            </td>

                            <td>{{ $n->category?->name ?? '-' }}</td>

                            <td>
                                <span class="ww-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>

                            <td>
                                {{ $n->published_at?->format('Y-m-d H:i') ?? '-' }}
                            </td>

                            <td>
                                {{ $n->reviewer?->name ?? '-' }}
                            </td>

                            <td>
                                <div class="ww-note">
                                    {{ $n->review_note ?: '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="ww-actions">
                                    <a href="{{ route('writer.news.edit', $n) }}" class="ww-link">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; color:#6b7280; padding:28px 16px;">
                                Belum ada berita.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ww-pagination">
        {{ $news->links() }}
    </div>
</div>
@endsection