@extends('layouts.writer')

@section('title', 'Detail News')

@section('content')
@php
    $tId = $news->translations->firstWhere('locale', 'id');
    $tEn = $news->translations->firstWhere('locale', 'en');

    $statusClass = match($news->status) {
        'published' => 'wn-badge--published',
        'archived' => 'wn-badge--archived',
        'rejected' => 'wn-badge--archived',
        'pending_review' => 'wn-badge--review',
        default => 'wn-badge--draft',
    };

    $statusLabel = $news->status_label ?? ucfirst(str_replace('_', ' ', $news->status ?? 'draft'));
@endphp

<style>
    .wn-page {
        max-width: 1180px;
    }

    .wn-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .wn-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .wn-breadcrumb span:first-child {
        color: #173f08;
    }

    .wn-breadcrumb-sep {
        color: #94a3b8;
    }

    .wn-title {
        margin: 0;
        font-size: 30px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.04em;
        line-height: 1.15;
    }

    .wn-subtitle {
        margin-top: 8px;
        font-size: 14px;
        color: #64748b;
        line-height: 1.75;
        max-width: 760px;
    }

    .wn-head-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .wn-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 15px;
        border-radius: 14px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        transition:
            transform .16s ease,
            background .16s ease,
            border-color .16s ease,
            color .16s ease,
            box-shadow .16s ease;
        white-space: nowrap;
        line-height: 1;
    }

    .wn-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .wn-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .08);
    }

    .wn-btn--primary {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        border-color: #173f08;
        color: #fff;
        box-shadow: 0 10px 22px rgba(23, 63, 8, .16);
    }

    .wn-btn--primary:hover {
        background: linear-gradient(135deg, #102d06 0%, #173f08 100%);
        border-color: #102d06;
        color: #fff;
    }

    .wn-btn--light {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    .wn-btn--light:hover {
        background: #eef6eb;
        color: #173f08;
        border-color: rgba(23, 63, 8, .25);
    }

    .wn-btn--preview {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .wn-btn--preview:hover {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .wn-btn--wa {
        background: #ecfdf5;
        color: #047857;
        border-color: #a7f3d0;
    }

    .wn-btn--wa:hover {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .wn-btn--success {
        background: #ecfdf3;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wn-btn--success:hover {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .wn-btn--warning {
        background: #fffbeb;
        border-color: #fde68a;
        color: #b45309;
    }

    .wn-btn--warning:hover {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .wn-btn--danger {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #be123c;
    }

    .wn-btn--danger:hover {
        background: #ffe4e6;
        color: #9f1239;
        border-color: #fda4af;
    }

    .wn-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 330px;
        gap: 20px;
        align-items: start;
    }

    .wn-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    }

    .wn-card + .wn-card {
        margin-top: 16px;
    }

    .wn-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .wn-card-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        min-width: 0;
    }

    .wn-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #eef6eb;
        color: #173f08;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: inset 0 0 0 1px rgba(23, 63, 8, .08);
    }

    .wn-card-icon svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
    }

    .wn-card-title {
        margin: 0;
        font-size: 17px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.02em;
        line-height: 1.25;
    }

    .wn-card-desc {
        margin-top: 4px;
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
    }

    .wn-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 0 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .wn-badge--draft {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .wn-badge--review {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .wn-badge--published {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wn-badge--archived {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .wn-hero-img {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        display: block;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .wn-no-img {
        width: 100%;
        min-height: 260px;
        border-radius: 18px;
        border: 1px dashed #cbd5e1;
        background:
            radial-gradient(circle at 20% 20%, rgba(23, 63, 8, .08), transparent 34%),
            #f8fafc;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 900;
        text-align: center;
        padding: 20px;
    }

    .wn-content-title {
        margin: 0 0 10px;
        color: #111827;
        font-size: 24px;
        line-height: 1.25;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .wn-excerpt {
        margin: 0 0 18px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.85;
        padding-bottom: 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .wn-richtext {
        color: #334155;
        font-size: 14px;
        line-height: 1.9;
    }

    .wn-richtext h2 {
        margin: 24px 0 10px;
        color: #0f172a;
        font-size: 20px;
        line-height: 1.25;
        font-weight: 900;
        letter-spacing: -.025em;
    }

    .wn-richtext h2:first-child {
        margin-top: 0;
    }

    .wn-richtext p {
        margin: 0 0 14px;
        text-align: justify;
        text-justify: inter-word;
    }

    .wn-richtext figure {
        margin: 24px 0;
    }

    .wn-richtext figure img {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        display: block;
    }

    .wn-richtext figcaption {
        margin-top: 8px;
        text-align: center;
        font-size: 12px;
        color: #64748b;
        line-height: 1.6;
    }

    .wn-side-row {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .wn-side-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .wn-side-label {
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 5px;
    }

    .wn-side-value {
        font-size: 14px;
        color: #111827;
        line-height: 1.6;
        font-weight: 800;
        word-break: break-word;
    }

    .wn-side-actions {
        display: grid;
        gap: 9px;
    }

    .wn-side-actions .wn-btn,
    .wn-side-actions form button {
        width: 100%;
    }

    .wn-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 12px;
    }

    .wn-gallery-card {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .035);
    }

    .wn-gallery-card img {
        width: 100%;
        height: 125px;
        object-fit: cover;
        display: block;
        background: #f8fafc;
    }

    .wn-gallery-caption {
        padding: 11px;
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
        font-weight: 700;
    }

    .wn-log-list {
        display: grid;
        gap: 10px;
    }

    .wn-log-item {
        padding: 13px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .wn-log-action {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin-bottom: 5px;
        text-transform: capitalize;
    }

    .wn-log-action-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .10);
        flex-shrink: 0;
    }

    .wn-log-meta {
        font-size: 12px;
        color: #64748b;
        line-height: 1.65;
        padding-left: 16px;
    }

    .wn-empty {
        padding: 30px 16px;
        border-radius: 18px;
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        color: #64748b;
        text-align: center;
        font-weight: 800;
        font-size: 13px;
        line-height: 1.65;
    }

    .wn-note-box {
        display: grid;
        gap: 12px;
    }

    .wn-note-item {
        padding: 13px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .wn-note-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 5px;
    }

    .wn-note-label svg {
        width: 15px;
        height: 15px;
        stroke: currentColor;
    }

    .wn-note-value {
        color: #111827;
        font-size: 13px;
        line-height: 1.7;
        font-weight: 700;
    }

    @media (max-width: 980px) {
        .wn-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .wn-title {
            font-size: 24px;
        }

        .wn-head-actions {
            width: 100%;
            justify-content: stretch;
        }

        .wn-head-actions .wn-btn {
            width: 100%;
        }

        .wn-card {
            padding: 16px;
            border-radius: 18px;
        }

        .wn-card-title-wrap {
            width: 100%;
        }

        .wn-content-title {
            font-size: 20px;
        }

        .wn-richtext p {
            text-align: left;
        }
    }
</style>

<div class="wn-page">
    <div class="wn-head">
        <div>
            <div class="wn-breadcrumb">
                <span>Writer</span>
                <span class="wn-breadcrumb-sep">›</span>
                <span>News</span>
                <span class="wn-breadcrumb-sep">›</span>
                <span>Detail</span>
            </div>

            <h1 class="wn-title">Detail News</h1>

            <div class="wn-subtitle">
                Lihat detail draft atau konten published. Dari halaman ini writer bisa preview, mengirim link ke reviewer, mengedit, publish, unpublish, atau menghapus news.
            </div>
        </div>

        <div class="wn-head-actions">
            <a href="{{ route('writer.news.index') }}" class="wn-btn wn-btn--light">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/>
                    <path d="m12 19-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            <a href="{{ route('writer.news.edit', $news) }}" class="wn-btn wn-btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                </svg>
                Edit
            </a>

            <a href="{{ route('writer.news.preview', $news) }}" target="_blank" class="wn-btn wn-btn--preview">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                Preview
            </a>
        </div>
    </div>

    <div class="wn-layout">
        <div>
            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m21 15-5-5L5 21"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Featured Image</h2>
                            <div class="wn-card-desc">Gambar utama yang akan tampil pada halaman berita.</div>
                        </div>
                    </div>
                </div>

                @if($news->featured_image)
                    <img src="{{ asset($news->featured_image) }}" alt="{{ $tId?->title ?? 'Featured image' }}" class="wn-hero-img">
                @else
                    <div class="wn-no-img">Belum ada featured image</div>
                @endif
            </div>

            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                <path d="M8 7h8"/>
                                <path d="M8 11h8"/>
                                <path d="M8 15h5"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Konten Indonesia</h2>
                            <div class="wn-card-desc">Konten utama yang dibuat oleh writer.</div>
                        </div>
                    </div>

                    <span class="wn-badge wn-badge--draft">ID</span>
                </div>

                <h2 class="wn-content-title">{{ $tId?->title ?? '-' }}</h2>

                @if($tId?->excerpt)
                    <p class="wn-excerpt">{{ $tId->excerpt }}</p>
                @endif

                <div class="wn-richtext">
                    {!! $tId?->content ?: '<p>Konten belum tersedia.</p>' !!}
                </div>
            </div>

            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 8h14"/>
                                <path d="M5 12h10"/>
                                <path d="M5 16h8"/>
                                <path d="M3 4h18v18H3z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Konten English Otomatis</h2>
                            <div class="wn-card-desc">Hasil terjemahan otomatis dari konten Indonesia.</div>
                        </div>
                    </div>

                    <span class="wn-badge wn-badge--published">EN</span>
                </div>

                <h2 class="wn-content-title">{{ $tEn?->title ?? '-' }}</h2>

                @if($tEn?->excerpt)
                    <p class="wn-excerpt">{{ $tEn->excerpt }}</p>
                @endif

                <div class="wn-richtext">
                    {!! $tEn?->content ?: '<p>Konten English belum tersedia.</p>' !!}
                </div>
            </div>

            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Galeri News</h2>
                            <div class="wn-card-desc">{{ $news->images->count() }} gambar galeri tersimpan.</div>
                        </div>
                    </div>
                </div>

                @if($news->images->count())
                    <div class="wn-gallery-grid">
                        @foreach($news->images as $image)
                            <div class="wn-gallery-card">
                                <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?: ($tId?->title ?? 'News gallery') }}">
                                <div class="wn-gallery-caption">
                                    {{ $image->caption ?: 'Tanpa caption' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wn-empty">Belum ada gambar galeri.</div>
                @endif
            </div>

            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v4l3 3"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Log Aktivitas</h2>
                            <div class="wn-card-desc">Riwayat perubahan news.</div>
                        </div>
                    </div>
                </div>

                @if($news->logs->count())
                    <div class="wn-log-list">
                        @foreach($news->logs->take(8) as $log)
                            <div class="wn-log-item">
                                <div class="wn-log-action">
                                    <span class="wn-log-action-dot"></span>
                                    {{ str_replace('_', ' ', $log->action) }}
                                </div>

                                <div class="wn-log-meta">
                                    {{ $log->note ?: '-' }}<br>
                                    Oleh: {{ $log->user?->name ?? '-' }} • {{ $log->created_at?->format('d M Y H:i') ?? '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wn-empty">Belum ada log aktivitas.</div>
                @endif
            </div>
        </div>

        <div>
            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4"/>
                                <path d="M12 8h.01"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Informasi News</h2>
                            <div class="wn-card-desc">Status dan metadata konten.</div>
                        </div>
                    </div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Status</div>
                    <div class="wn-side-value">
                        <span class="wn-badge {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Kategori</div>
                    <div class="wn-side-value">{{ $news->category?->name ?? '-' }}</div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Writer</div>
                    <div class="wn-side-value">{{ $news->author?->name ?? '-' }}</div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Tanggal Publish</div>
                    <div class="wn-side-value">{{ $news->published_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Visible</div>
                    <div class="wn-side-value">{{ $news->is_visible ? 'Ya' : 'Tidak' }}</div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Featured</div>
                    <div class="wn-side-value">{{ $news->is_featured ? 'Ya' : 'Tidak' }}</div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Dibuat</div>
                    <div class="wn-side-value">{{ $news->created_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>

                <div class="wn-side-row">
                    <div class="wn-side-label">Diperbarui</div>
                    <div class="wn-side-value">{{ $news->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                </div>
            </div>

            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Aksi Writer</h2>
                            <div class="wn-card-desc">Kontrol penuh ada di writer.</div>
                        </div>
                    </div>
                </div>

                <div class="wn-side-actions">
                    <a href="{{ route('writer.news.edit', $news) }}" class="wn-btn wn-btn--primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                        </svg>
                        Edit News
                    </a>

                    <a href="{{ route('writer.news.preview', $news) }}" target="_blank" class="wn-btn wn-btn--preview">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Preview Tampilan Website
                    </a>

                    <a href="{{ route('writer.news.send-preview-whatsapp', $news) }}" class="wn-btn wn-btn--wa">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-1.24 4.38A8.5 8.5 0 0 1 12 20.5a8.38 8.38 0 0 1-4.38-1.24L3 20l.76-4.62A8.38 8.38 0 0 1 2.5 11.5a8.5 8.5 0 0 1 17 0Z"/>
                        </svg>
                        Kirim Preview ke Reviewer
                    </a>

                    @if($news->status !== 'published')
                        <form
                            method="POST"
                            action="{{ route('writer.news.publish', $news) }}"
                            class="js-confirm-submit"
                            data-title="Publish news ini?"
                            data-text="News akan tampil di website publik."
                            data-confirm="Ya, Publish"
                            data-type="publish"
                            data-icon="question"
                        >
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="wn-btn wn-btn--success">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Publish News
                            </button>
                        </form>
                    @else
                        <form
                            method="POST"
                            action="{{ route('writer.news.unpublish', $news) }}"
                            class="js-confirm-submit"
                            data-title="Unpublish news ini?"
                            data-text="News akan disembunyikan dari website publik dan dikembalikan ke draft."
                            data-confirm="Ya, Unpublish"
                            data-type="unpublish"
                            data-icon="warning"
                        >
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="wn-btn wn-btn--warning">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3l18 18"/>
                                    <path d="M10.6 10.6A3 3 0 0 0 14 14"/>
                                    <path d="M9.9 4.25A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18.3 18.3 0 0 1-3.1 4.5"/>
                                    <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.4-.95"/>
                                </svg>
                                Unpublish
                            </button>
                        </form>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('writer.news.destroy', $news) }}"
                        class="js-delete-form"
                        data-title="Hapus news ini?"
                        data-text="Data yang dihapus tidak dapat dikembalikan."
                        data-confirm="Ya, Hapus"
                        data-type="delete"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="wn-btn wn-btn--danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/>
                                <path d="M8 6V4h8v2"/>
                                <path d="M19 6l-1 16H6L5 6"/>
                                <path d="M10 11v6"/>
                                <path d="M14 11v6"/>
                            </svg>
                            Hapus News
                        </button>
                    </form>
                </div>
            </div>

            <div class="wn-card">
                <div class="wn-card-head">
                    <div class="wn-card-title-wrap">
                        <div class="wn-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L22 4"/>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="wn-card-title">Catatan Alur</h2>
                            <div class="wn-card-desc">Mekanisme review informal.</div>
                        </div>
                    </div>
                </div>

                <div class="wn-note-box">
                    <div class="wn-note-item">
                        <div class="wn-note-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            Reviewer
                        </div>
                        <div class="wn-note-value">
                            Reviewer hanya melihat preview konten, bukan approve/reject di sistem.
                        </div>
                    </div>

                    <div class="wn-note-item">
                        <div class="wn-note-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-1.24 4.38A8.5 8.5 0 0 1 12 20.5a8.38 8.38 0 0 1-4.38-1.24L3 20l.76-4.62A8.38 8.38 0 0 1 2.5 11.5a8.5 8.5 0 0 1 17 0Z"/>
                            </svg>
                            WhatsApp
                        </div>
                        <div class="wn-note-value">
                            Tombol WhatsApp akan membuka chat langsung berisi link preview.
                        </div>
                    </div>

                    <div class="wn-note-item">
                        <div class="wn-note-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            Publish
                        </div>
                        <div class="wn-note-value">
                            Jika sudah sesuai, writer dapat publish langsung dari halaman ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection