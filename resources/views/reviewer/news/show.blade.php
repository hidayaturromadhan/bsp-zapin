@extends('layouts.reviewer')

@section('content')
@php
    $tId = $news->translations->firstWhere('locale', 'id');
    $tEn = $news->translations->firstWhere('locale', 'en');

    $badgeClass = match($news->status) {
        'published' => 'rn-badge--published',
        'archived' => 'rn-badge--archived',
        default => 'rn-badge--draft',
    };
@endphp

<style>
    .rn-page { max-width:1180px; }
    .rn-head { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .rn-title { margin:0; font-size:30px; font-weight:800; color:#111827; letter-spacing:-.03em; }
    .rn-subtitle { margin-top:6px; font-size:14px; color:#6b7280; line-height:1.7; }
    .rn-head-actions { display:flex; gap:8px; flex-wrap:wrap; }

    .rn-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:22px;
        box-shadow:0 10px 24px rgba(15,23,42,.04);
        margin-bottom:16px;
    }

    .rn-card-head {
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
        margin-bottom:16px;
    }

    .rn-card-title {
        font-size:18px;
        font-weight:900;
        color:#111827;
        margin:0;
    }

    .rn-card-desc {
        font-size:13px;
        color:#64748b;
        line-height:1.6;
        margin-top:4px;
    }

    .rn-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:40px;
        padding:0 14px;
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

    .rn-badge {
        display:inline-flex;
        align-items:center;
        min-height:28px;
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

    .rn-info-grid {
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:14px;
    }

    .rn-info-box {
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:12px;
        background:#f8fafc;
    }

    .rn-info-label {
        font-size:11px;
        font-weight:900;
        color:#64748b;
        text-transform:uppercase;
        letter-spacing:.05em;
        margin-bottom:5px;
    }

    .rn-info-value {
        font-size:13px;
        font-weight:800;
        color:#111827;
        line-height:1.5;
    }

    .rn-hero-img {
        width:100%;
        max-height:360px;
        object-fit:cover;
        border-radius:16px;
        border:1px solid #e5e7eb;
        background:#f8fafc;
    }

    .rn-no-image {
        width:100%;
        min-height:220px;
        border-radius:16px;
        border:1px dashed #cbd5e1;
        background:#f8fafc;
        color:#94a3b8;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:800;
    }

    .rn-content-title {
        margin:0 0 8px;
        font-size:24px;
        font-weight:900;
        color:#111827;
        letter-spacing:-.02em;
        line-height:1.25;
    }

    .rn-content-excerpt {
        color:#64748b;
        line-height:1.8;
        margin:0 0 18px;
    }

    .rn-content-body {
        color:#374151;
        line-height:1.85;
        font-size:14px;
    }

    .rn-content-body h2 {
        font-size:20px;
        font-weight:900;
        color:#111827;
        margin:20px 0 10px;
    }

    .rn-content-body p {
        margin:0 0 14px;
    }

    .rn-content-body figure {
        margin:18px 0;
    }

    .rn-content-body figure img {
        width:100%;
        max-height:460px;
        object-fit:cover;
        border-radius:14px;
        border:1px solid #e5e7eb;
    }

    .rn-content-body figcaption {
        margin-top:8px;
        font-size:12px;
        color:#64748b;
        text-align:center;
    }

    .rn-gallery-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(170px,1fr));
        gap:12px;
    }

    .rn-gallery-card {
        border:1px solid #e5e7eb;
        border-radius:14px;
        overflow:hidden;
        background:#fff;
    }

    .rn-gallery-card img {
        width:100%;
        height:130px;
        object-fit:cover;
        display:block;
    }

    .rn-gallery-caption {
        padding:10px;
        font-size:12px;
        color:#64748b;
        line-height:1.5;
    }

    .rn-note-box {
        padding:14px;
        border-radius:14px;
        border:1px solid #dbeafe;
        background:#eff6ff;
        color:#1e40af;
        line-height:1.7;
        font-size:13px;
        margin-bottom:16px;
    }

    @media (max-width:900px) {
        .rn-info-grid {
            grid-template-columns:repeat(2,minmax(0,1fr));
        }
    }

    @media (max-width:560px) {
        .rn-info-grid {
            grid-template-columns:1fr;
        }
    }
</style>

<div class="rn-page">
    <div class="rn-head">
        <div>
            <h1 class="rn-title">Detail Preview News</h1>
            <div class="rn-subtitle">
                Halaman ini hanya untuk melihat dan meninjau konten. Perubahan, publish, dan unpublish dilakukan oleh writer.
            </div>
        </div>

        <div class="rn-head-actions">
            <a href="{{ route('reviewer.news.index') }}" class="rn-btn">Kembali</a>
            <a href="{{ route('reviewer.news.preview', $news) }}" class="rn-btn rn-btn--primary" target="_blank">Lihat Preview</a>
        </div>
    </div>

    <div class="rn-note-box">
        Reviewer hanya memiliki akses baca dan preview. Jika ada masukan atau koreksi, sampaikan langsung kepada writer melalui WhatsApp atau jalur komunikasi yang digunakan.
    </div>

    <div class="rn-card">
        <div class="rn-card-head">
            <div>
                <h2 class="rn-card-title">Informasi News</h2>
                <div class="rn-card-desc">Informasi umum konten yang dikirim untuk ditinjau.</div>
            </div>

            <span class="rn-badge {{ $badgeClass }}">
                {{ $news->status_label ?? ucfirst(str_replace('_', ' ', $news->status)) }}
            </span>
        </div>

        <div class="rn-info-grid">
            <div class="rn-info-box">
                <div class="rn-info-label">Kategori</div>
                <div class="rn-info-value">{{ $news->category?->name ?? '-' }}</div>
            </div>

            <div class="rn-info-box">
                <div class="rn-info-label">Writer</div>
                <div class="rn-info-value">{{ $news->author?->name ?? '-' }}</div>
            </div>

            <div class="rn-info-box">
                <div class="rn-info-label">Publish Date</div>
                <div class="rn-info-value">{{ $news->published_at?->format('d M Y H:i') ?? '-' }}</div>
            </div>

            <div class="rn-info-box">
                <div class="rn-info-label">Visible</div>
                <div class="rn-info-value">{{ $news->is_visible ? 'Ya' : 'Tidak' }}</div>
            </div>
        </div>
    </div>

    <div class="rn-card">
        <div class="rn-card-head">
            <div>
                <h2 class="rn-card-title">Featured Image</h2>
                <div class="rn-card-desc">Gambar utama berita.</div>
            </div>
        </div>

        @if($news->featured_image)
            <img src="{{ asset($news->featured_image) }}" alt="Featured image" class="rn-hero-img">
        @else
            <div class="rn-no-image">Belum ada featured image</div>
        @endif
    </div>

    <div class="rn-card">
        <div class="rn-card-head">
            <div>
                <h2 class="rn-card-title">Konten Indonesia</h2>
                <div class="rn-card-desc">Konten utama yang akan tampil pada bahasa Indonesia.</div>
            </div>

            <span class="rn-badge rn-badge--draft">ID</span>
        </div>

        <h2 class="rn-content-title">{{ $tId?->title ?? '-' }}</h2>

        @if($tId?->excerpt)
            <p class="rn-content-excerpt">{{ $tId->excerpt }}</p>
        @endif

        <div class="rn-content-body">
            {!! $tId?->content ?: '<p>-</p>' !!}
        </div>
    </div>

    <div class="rn-card">
        <div class="rn-card-head">
            <div>
                <h2 class="rn-card-title">Konten English Otomatis</h2>
                <div class="rn-card-desc">Hasil auto translate dari konten Indonesia.</div>
            </div>

            <span class="rn-badge rn-badge--published">EN</span>
        </div>

        <h2 class="rn-content-title">{{ $tEn?->title ?? '-' }}</h2>

        @if($tEn?->excerpt)
            <p class="rn-content-excerpt">{{ $tEn->excerpt }}</p>
        @endif

        <div class="rn-content-body">
            {!! $tEn?->content ?: '<p>-</p>' !!}
        </div>
    </div>

    <div class="rn-card">
        <div class="rn-card-head">
            <div>
                <h2 class="rn-card-title">Galeri</h2>
                <div class="rn-card-desc">{{ $news->images->count() }} gambar galeri tambahan.</div>
            </div>
        </div>

        @if($news->images->count())
            <div class="rn-gallery-grid">
                @foreach($news->images as $image)
                    <div class="rn-gallery-card">
                        <img src="{{ asset($image->image_path) }}" alt="Gallery image">
                        <div class="rn-gallery-caption">
                            {{ $image->caption ?: 'Tanpa caption' }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="color:#64748b;font-size:14px">Belum ada gambar galeri.</div>
        @endif
    </div>

    @if($news->logs->count())
        <div class="rn-card">
            <div class="rn-card-head">
                <div>
                    <h2 class="rn-card-title">Riwayat Aktivitas</h2>
                    <div class="rn-card-desc">Log perubahan berita.</div>
                </div>
            </div>

            <div style="display:grid;gap:10px">
                @foreach($news->logs as $log)
                    <div style="padding:12px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc">
                        <div style="font-weight:900;color:#111827">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </div>
                        <div style="font-size:13px;color:#64748b;line-height:1.6;margin-top:4px">
                            {{ $log->note ?: '-' }}<br>
                            Oleh: {{ $log->user?->name ?? '-' }} • {{ $log->created_at?->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection