@extends('layouts.reviewer')

@section('content')
<style>
    .rn-page { max-width: 1180px; }
    .rn-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .rn-title { margin:0; font-size:30px; font-weight:800; color:#111827; letter-spacing:-.03em; }
    .rn-subtitle { margin-top:6px; font-size:14px; color:#6b7280; line-height:1.7; }

    .rn-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:38px;
        padding:0 13px;
        border-radius:10px;
        border:1px solid #d1d5db;
        background:#fff;
        color:#111827;
        font-size:13px;
        font-weight:800;
        cursor:pointer;
        text-decoration:none;
        transition:.18s ease;
        white-space:nowrap;
    }

    .rn-btn:hover { transform:translateY(-1px); }

    .rn-btn--primary {
        background:#173f08;
        border-color:#173f08;
        color:#fff;
    }

    .rn-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:18px;
        box-shadow:0 10px 24px rgba(15,23,42,.04);
    }

    .rn-filter {
        margin-bottom:16px;
    }

    .rn-filter-form {
        display:grid;
        grid-template-columns:minmax(0,1fr) 220px auto auto;
        gap:10px;
        align-items:end;
    }

    .rn-label {
        display:block;
        margin-bottom:7px;
        font-size:12px;
        font-weight:800;
        color:#374151;
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    .rn-input,
    .rn-select {
        width:100%;
        min-height:42px;
        border:1px solid #d1d5db;
        border-radius:10px;
        padding:0 12px;
        font:inherit;
        color:#111827;
        background:#fff;
        box-sizing:border-box;
    }

    .rn-input:focus,
    .rn-select:focus {
        outline:none;
        border-color:#7aa46d;
        box-shadow:0 0 0 4px rgba(47,125,50,.08);
    }

    .rn-table-wrap {
        overflow-x:auto;
    }

    .rn-table {
        width:100%;
        border-collapse:collapse;
        min-width:980px;
    }

    .rn-table th {
        text-align:left;
        padding:12px 10px;
        font-size:12px;
        font-weight:900;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.04em;
        border-bottom:1px solid #e5e7eb;
        background:#f8fafc;
        white-space:nowrap;
    }

    .rn-table td {
        padding:13px 10px;
        border-bottom:1px solid #f1f5f9;
        vertical-align:top;
        font-size:13px;
        color:#374151;
    }

    .rn-table tr:hover td {
        background:#fbfdfb;
    }

    .rn-thumb {
        width:72px;
        height:52px;
        object-fit:cover;
        border-radius:10px;
        border:1px solid #e5e7eb;
        background:#f8fafc;
        display:block;
    }

    .rn-no-thumb {
        width:72px;
        height:52px;
        border-radius:10px;
        border:1px solid #e5e7eb;
        background:#f8fafc;
        color:#94a3b8;
        font-size:11px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
    }

    .rn-news-title {
        font-weight:900;
        color:#111827;
        line-height:1.45;
        margin-bottom:4px;
    }

    .rn-news-meta {
        font-size:12px;
        color:#64748b;
        line-height:1.6;
    }

    .rn-badge {
        display:inline-flex;
        align-items:center;
        min-height:26px;
        padding:0 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        white-space:nowrap;
    }

    .rn-badge--draft {
        background:#f1f5f9;
        color:#334155;
    }

    .rn-badge--published {
        background:#dcfce7;
        color:#166534;
    }

    .rn-badge--archived {
        background:#fee2e2;
        color:#991b1b;
    }

    .rn-row-actions {
        display:flex;
        gap:6px;
        flex-wrap:wrap;
        justify-content:center;
    }

    .rn-empty {
        text-align:center;
        padding:48px 18px;
        color:#64748b;
    }

    .rn-empty-title {
        font-size:17px;
        font-weight:900;
        color:#111827;
        margin-bottom:6px;
    }

    .rn-alert-success {
        margin-bottom:16px;
        padding:12px 14px;
        border-radius:12px;
        background:#eef8ee;
        color:#17603a;
        border:1px solid #cfe9d3;
        font-size:14px;
        font-weight:700;
    }

    .rn-alert-danger {
        margin-bottom:16px;
        padding:12px 14px;
        border-radius:12px;
        background:#fff4f4;
        color:#b42318;
        border:1px solid #f3c6c6;
        font-size:14px;
        font-weight:700;
    }

    @media (max-width: 860px) {
        .rn-filter-form {
            grid-template-columns:1fr;
        }
    }
</style>

<div class="rn-page">
    <div class="rn-head">
        <div>
            <h1 class="rn-title">Preview News</h1>
            <div class="rn-subtitle">
                Reviewer dapat melihat detail dan preview tampilan berita sebelum atau setelah dipublish. Kontrol edit dan publish tetap berada di writer.
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rn-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="rn-alert-danger">{{ session('error') }}</div>
    @endif

    <div class="rn-card rn-filter">
        <form method="GET" action="{{ route('reviewer.news.index') }}" class="rn-filter-form">
            <div>
                <label class="rn-label">Pencarian</label>
                <input
                    type="text"
                    name="q"
                    class="rn-input"
                    value="{{ $q ?? request('q') }}"
                    placeholder="Cari judul, kategori, writer, atau isi berita"
                >
            </div>

            <div>
                <label class="rn-label">Status</label>
                <select name="status" class="rn-select">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(($status ?? request('status')) === 'draft')>Draft</option>
                    <option value="published" @selected(($status ?? request('status')) === 'published')>Published</option>
                    <option value="archived" @selected(($status ?? request('status')) === 'archived')>Archived</option>
                </select>
            </div>

            <button type="submit" class="rn-btn rn-btn--primary">Filter</button>
            <a href="{{ route('reviewer.news.index') }}" class="rn-btn">Reset</a>
        </form>
    </div>

    <div class="rn-card">
        <div class="rn-table-wrap">
            <table class="rn-table">
                <thead>
                    <tr>
                        <th width="48">#</th>
                        <th width="92">Gambar</th>
                        <th>Judul</th>
                        <th width="150">Kategori</th>
                        <th width="170">Writer</th>
                        <th width="120">Status</th>
                        <th width="170">Publikasi</th>
                        <th width="170" style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                        @php
                            $translation = $item->translations->firstWhere('locale', 'id')
                                ?? $item->translations->firstWhere('locale', 'en');

                            $badgeClass = match($item->status) {
                                'published' => 'rn-badge--published',
                                'archived' => 'rn-badge--archived',
                                default => 'rn-badge--draft',
                            };
                        @endphp

                        <tr>
                            <td>{{ $news->firstItem() + $loop->index }}</td>

                            <td>
                                @if($item->featured_image)
                                    <img src="{{ asset($item->featured_image) }}" alt="Featured" class="rn-thumb">
                                @else
                                    <div class="rn-no-thumb">No Image</div>
                                @endif
                            </td>

                            <td>
                                <div class="rn-news-title">{{ $translation?->title ?? '-' }}</div>

                                <div class="rn-news-meta">
                                    Slug: {{ $translation?->slug ?? '-' }}<br>
                                    Galeri: {{ $item->images->count() }} gambar
                                </div>
                            </td>

                            <td>
                                <div style="font-weight:800;color:#111827">
                                    {{ $item->category?->name ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <div style="font-weight:800;color:#111827">
                                    {{ $item->author?->name ?? '-' }}
                                </div>
                                <div class="rn-news-meta">
                                    {{ $item->author?->email ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="rn-badge {{ $badgeClass }}">
                                    {{ $item->status_label ?? ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>

                            <td>
                                <div class="rn-news-meta">
                                    Visible: <strong>{{ $item->is_visible ? 'Ya' : 'Tidak' }}</strong><br>
                                    Publish: <strong>{{ $item->published_at?->format('d M Y H:i') ?? '-' }}</strong>
                                </div>
                            </td>

                            <td>
                                <div class="rn-row-actions">
                                    <a href="{{ route('reviewer.news.show', $item) }}" class="rn-btn rn-btn--primary">Detail</a>
                                    <a href="{{ route('reviewer.news.preview', $item) }}" class="rn-btn" target="_blank">Preview</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="rn-empty">
                                    <div class="rn-empty-title">Belum ada news</div>
                                    <div>News yang dibuat writer akan tampil di sini untuk preview reviewer.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($news->hasPages())
        <div class="r-pagination">
            {{ $news->links() }}
        </div>
    @endif
</div>
@endsection