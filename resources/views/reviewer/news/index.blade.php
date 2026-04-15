@extends('layouts.reviewer')

@section('content')
<style>
    .rq-page {
        max-width: 1280px;
    }

    .rq-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .rq-title {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.03em;
        line-height: 1.15;
    }

    .rq-subtitle {
        margin-top: 6px;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.7;
        max-width: 760px;
    }

    .rq-filter {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) 220px 120px 120px;
        gap: 10px;
        align-items: center;
        margin-bottom: 18px;
        padding: 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 6px 16px rgba(15,23,42,.04);
    }

    .rq-input,
    .rq-select,
    .rq-textarea {
        min-height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0 12px;
        font: inherit;
        background: #fff;
        color: #111827;
        width: 100%;
        box-sizing: border-box;
    }

    .rq-textarea {
        min-height: 72px;
        padding: 10px 12px;
        resize: vertical;
        width: 100%;
    }

    .rq-btn {
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
        width: 100%;
        transition: .18s ease;
    }

    .rq-btn:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }

    .rq-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(15,23,42,.04);
    }

    .rq-table-wrap {
        width: 100%;
        overflow: hidden;
    }

    .rq-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .rq-table th,
    .rq-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        vertical-align: top;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .rq-table th {
        background: #f8fafc;
        color: #111827;
        font-size: 13px;
        font-weight: 800;
        white-space: normal;
    }

    .rq-table tbody tr:hover {
        background: #fcfdfc;
    }

    .rq-col-no { width: 60px; }
    .rq-col-image { width: 120px; }
    .rq-col-title { width: 26%; }
    .rq-col-category { width: 120px; }
    .rq-col-status { width: 120px; }
    .rq-col-review { width: 27%; }
    .rq-col-action { width: 220px; }

    .rq-no {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
    }

    .rq-thumb {
        width: 100%;
        max-width: 100px;
        height: 64px;
        object-fit: cover;
        border-radius: 10px;
        display: block;
        background: #eef2f7;
        border: 1px solid #e5e7eb;
    }

    .rq-thumb-empty {
        width: 100%;
        max-width: 100px;
        height: 64px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 11px;
        background: #eef2f7;
        border: 1px solid #e5e7eb;
        text-align: center;
        padding: 6px;
    }

    .rq-title-cell {
        min-width: 0;
    }

    .rq-row-title {
        font-size: 16px;
        font-weight: 800;
        line-height: 1.5;
        color: #111827;
        margin-bottom: 8px;
    }

    .rq-row-meta {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.75;
    }

    .rq-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .rq-badge.status-in_review {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .rq-badge.status-published {
        background: #e9f8ee;
        color: #17603a;
    }

    .rq-badge.status-rejected {
        background: #fee2e2;
        color: #b42318;
    }

    .rq-badge.status-draft,
    .rq-badge.status-archived,
    .rq-badge.status-default {
        background: #f3f4f6;
        color: #4b5563;
    }

    .rq-review-box {
        padding: 10px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .rq-review-meta {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.65;
        margin-bottom: 8px;
    }

    .rq-review-note {
        font-size: 13px;
        line-height: 1.7;
        color: #374151;
        padding: 8px 10px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e5e7eb;
        margin-bottom: 8px;
    }

    .rq-review-form {
        display: grid;
        gap: 8px;
        margin-top: 10px;
    }

    .rq-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .rq-link {
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
        transition: .18s ease;
    }

    .rq-link:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }

    .rq-danger {
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
        transition: .18s ease;
    }

    .rq-danger:hover {
        background: #ffe8e8;
    }

    .rq-success {
        min-height: 36px;
        padding: 0 12px;
        border-radius: 9px;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: 1px solid #b7e2c2;
        background: #edf9f0;
        color: #17603a;
        transition: .18s ease;
    }

    .rq-success:hover {
        background: #e1f4e6;
    }

    .rq-publish-info {
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
        line-height: 1.6;
    }

    .rq-pagination {
        margin-top: 18px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 1180px) {
        .rq-filter {
            grid-template-columns: minmax(0, 1fr) 1fr;
        }
    }

    @media (max-width: 860px) {
        .rq-filter {
            grid-template-columns: 1fr;
        }

        .rq-card {
            border: none;
            background: transparent;
            box-shadow: none;
        }

        .rq-table-wrap {
            overflow: visible;
        }

        .rq-table,
        .rq-table thead,
        .rq-table tbody,
        .rq-table th,
        .rq-table td,
        .rq-table tr {
            display: block;
            width: 100%;
        }

        .rq-table thead {
            display: none;
        }

        .rq-table tbody {
            display: grid;
            gap: 14px;
        }

        .rq-table tr {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(15,23,42,.04);
            overflow: hidden;
        }

        .rq-table td {
            border-bottom: 1px solid #eef2f7;
            padding: 12px 14px;
        }

        .rq-table td:last-child {
            border-bottom: 0;
        }

        .rq-table td::before {
            content: attr(data-label);
            display: block;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 6px;
        }

        .rq-thumb,
        .rq-thumb-empty {
            max-width: 140px;
        }

        .rq-col-no,
        .rq-col-image,
        .rq-col-title,
        .rq-col-category,
        .rq-col-status,
        .rq-col-review,
        .rq-col-action {
            width: auto;
        }
    }
</style>

<div class="rq-page">
    <div class="rq-head">
        <div>
            <h1 class="rq-title">Review Queue</h1>
            <div class="rq-subtitle">Kelola berita yang masuk ke antrian review dan gunakan jadwal publish dari writer atau override bila perlu.</div>
        </div>
    </div>

    @if(session('success'))
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#eef8ee;color:#17603a;border:1px solid #cfe9d3;font-size:14px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#fff4f4;color:#b42318;border:1px solid #f3c6c6;font-size:14px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li style="margin:4px 0;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('reviewer.news.index') }}" class="rq-filter">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Cari judul (ID/EN)..."
            class="rq-input"
        >

        <select name="status" class="rq-select">
            <option value="">Semua status</option>
            <option value="in_review" {{ $status === 'in_review' ? 'selected' : '' }}>In Review</option>
            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
        </select>

        <button type="submit" class="rq-btn">Filter</button>
        <a href="{{ route('reviewer.news.index') }}" class="rq-btn">Reset</a>
    </form>

    <div class="rq-card">
        <div class="rq-table-wrap">
            <table class="rq-table">
                <thead>
                    <tr>
                        <th class="rq-col-no">No</th>
                        <th class="rq-col-image">Gambar</th>
                        <th class="rq-col-title">Judul (ID / EN)</th>
                        <th class="rq-col-category">Kategori</th>
                        <th class="rq-col-status">Status</th>
                        <th class="rq-col-review">Review</th>
                        <th class="rq-col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $index => $n)
                        @php
                            $tId = $n->translations->firstWhere('locale', 'id');
                            $tEn = $n->translations->firstWhere('locale', 'en');
                            $statusClass = in_array($n->status, ['in_review', 'published', 'rejected', 'draft', 'archived'])
                                ? 'status-' . $n->status
                                : 'status-default';
                            $statusLabel = ucfirst(str_replace('_', ' ', $n->status));
                            $rowNumber = ($news->firstItem() ?? 1) + $index;
                        @endphp

                        <tr>
                            <td data-label="No">
                                <div class="rq-no">{{ $rowNumber }}</div>
                            </td>

                            <td data-label="Gambar">
                                @if($n->featured_image)
                                    <img src="{{ asset($n->featured_image) }}" alt="Thumbnail" class="rq-thumb">
                                @else
                                    <div class="rq-thumb-empty">No Image</div>
                                @endif
                            </td>

                            <td class="rq-title-cell" data-label="Judul">
                                <div class="rq-row-title">
                                    {{ $tId?->title ?? '-' }}
                                    <span style="color:#9ca3af; font-weight:600;">/</span>
                                    {{ $tEn?->title ?? '-' }}
                                </div>

                                <div class="rq-row-meta">
                                    ID: /{{ $tId?->slug ?? '-' }}<br>
                                    EN: /{{ $tEn?->slug ?? '-' }}<br>
                                    Penulis: {{ $n->author?->name ?? '-' }}
                                </div>
                            </td>

                            <td data-label="Kategori">
                                {{ $n->category?->name ?? '-' }}
                            </td>

                            <td data-label="Status">
                                <span class="rq-badge {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td data-label="Review">
                                <div class="rq-review-box">
                                    <div class="rq-review-meta">
                                        Reviewer: {{ $n->reviewer?->name ?? '-' }}<br>
                                        Reviewed: {{ $n->reviewed_at?->format('Y-m-d H:i') ?? '-' }}<br>
                                        Jadwal Writer: {{ $n->published_at?->format('Y-m-d H:i') ?? '-' }}<br>
                                        Visible: {{ $n->is_visible ? 'Ya' : 'Tidak' }}
                                    </div>

                                    @if(!empty($n->review_note))
                                        <div class="rq-review-note">{{ $n->review_note }}</div>
                                    @endif

                                    <div class="rq-publish-info">
                                        Approve akan memakai jadwal writer jika field override di bawah dibiarkan kosong.
                                    </div>

                                    <form method="POST" action="{{ route('reviewer.news.review', $n) }}" class="rq-review-form">
                                        @csrf

                                        <input
                                            type="datetime-local"
                                            name="published_at"
                                            class="rq-input"
                                            value=""
                                        >

                                        <textarea
                                            name="review_note"
                                            class="rq-textarea"
                                            placeholder="Catatan review / alasan penolakan (opsional)"
                                        >{{ $n->review_note }}</textarea>

                                        <div class="rq-actions">
                                            <button
                                                type="submit"
                                                name="action"
                                                value="approve"
                                                class="rq-success"
                                                onclick="return confirm('Approve berita ini?')"
                                            >
                                                Approve
                                            </button>

                                            <button
                                                type="submit"
                                                name="action"
                                                value="reject"
                                                class="rq-danger"
                                                onclick="return confirm('Reject berita ini?')"
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>

                            <td data-label="Aksi">
                                <div class="rq-actions">
                                    <a href="{{ route('reviewer.news.edit', $n) }}" class="rq-link">Edit Detail</a>
                                    <a href="{{ route('reviewer.news.logs', $n) }}" class="rq-link">Logs</a>

                                    <form method="POST" action="{{ route('reviewer.news.destroy', $n) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="rq-danger"
                                            onclick="return confirm('Hapus berita ini?')"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#6b7280; padding:28px 16px;">
                                Tidak ada data untuk direview.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rq-pagination">
        {{ $news->links() }}
    </div>
</div>
@endsection