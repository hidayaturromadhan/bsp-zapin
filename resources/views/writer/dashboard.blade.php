@extends('layouts.writer')

@section('title', 'Writer Dashboard')

@section('content')
<style>
    .wd-page {
        max-width: 1180px;
    }

    .wd-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .wd-kicker {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 30px;
        padding: 0 12px;
        border-radius: 999px;
        background: #eef6eb;
        color: #173f08;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .wd-kicker-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #173f08;
        box-shadow: 0 0 0 4px rgba(23, 63, 8, .10);
    }

    .wd-title {
        margin: 0;
        font-size: clamp(28px, 3vw, 38px);
        font-weight: 900;
        color: #111827;
        letter-spacing: -.05em;
        line-height: 1.1;
    }

    .wd-subtitle {
        margin: 9px 0 0;
        font-size: 14px;
        color: #64748b;
        line-height: 1.75;
        max-width: 720px;
    }

    .wd-head-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .wd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 0 16px;
        border-radius: 14px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        cursor: pointer;
        transition:
            transform .16s ease,
            background .16s ease,
            border-color .16s ease,
            color .16s ease,
            box-shadow .16s ease;
        white-space: nowrap;
        line-height: 1;
    }

    .wd-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    .wd-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .08);
    }

    .wd-btn--primary {
        background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
        border-color: #173f08;
        color: #fff;
        box-shadow: 0 10px 22px rgba(23, 63, 8, .16);
    }

    .wd-btn--primary:hover {
        background: linear-gradient(135deg, #102d06 0%, #173f08 100%);
        border-color: #102d06;
        color: #fff;
    }

    .wd-btn--light {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    .wd-btn--light:hover {
        background: #eef6eb;
        color: #173f08;
        border-color: rgba(23, 63, 8, .25);
    }

    .wd-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .wd-card {
        position: relative;
        overflow: hidden;
        background: #fff;
        border-radius: 22px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
        min-height: 142px;
        transition:
            transform .18s ease,
            box-shadow .18s ease,
            border-color .18s ease;
    }

    .wd-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 38px rgba(15, 23, 42, .09);
        border-color: #d6e4d2;
    }

    .wd-card::after {
        content: "";
        position: absolute;
        right: -42px;
        bottom: -52px;
        width: 132px;
        height: 132px;
        border-radius: 999px;
        opacity: .10;
        background: currentColor;
        pointer-events: none;
    }

    .wd-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        position: relative;
        z-index: 2;
    }

    .wd-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .wd-card-icon svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
    }

    .wd-card-title {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        font-weight: 900;
        letter-spacing: .03em;
        text-transform: uppercase;
    }

    .wd-card-value {
        position: relative;
        z-index: 2;
        margin-top: 8px;
        font-size: 36px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.05em;
        line-height: 1;
    }

    .wd-card-caption {
        position: relative;
        z-index: 2;
        margin-top: 8px;
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.6;
    }

    .wd-card--draft {
        color: #475569;
    }

    .wd-card--draft .wd-card-icon {
        background: #f1f5f9;
        color: #475569;
    }

    .wd-card--review {
        color: #b45309;
    }

    .wd-card--review .wd-card-icon {
        background: #fffbeb;
        color: #b45309;
    }

    .wd-card--published {
        color: #15803d;
    }

    .wd-card--published .wd-card-icon {
        background: #f0fdf4;
        color: #15803d;
    }

    .wd-card--rejected {
        color: #b91c1c;
    }

    .wd-card--rejected .wd-card-icon {
        background: #fef2f2;
        color: #b91c1c;
    }

    .wd-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 20px;
        align-items: start;
    }

    .wd-panel {
        background: #fff;
        border-radius: 22px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    }

    .wd-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .wd-panel-title {
        margin: 0;
        font-size: 20px;
        font-weight: 900;
        color: #111827;
        letter-spacing: -.03em;
    }

    .wd-panel-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 900;
        color: #173f08;
        text-decoration: none;
    }

    .wd-panel-link:hover {
        text-decoration: underline;
    }

    .wd-list {
        display: grid;
        gap: 0;
    }

    .wd-item {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .wd-item:first-child {
        padding-top: 2px;
    }

    .wd-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .wd-item-main {
        min-width: 0;
    }

    .wd-item-title {
        font-size: 16px;
        font-weight: 900;
        line-height: 1.45;
        color: #111827;
        letter-spacing: -.02em;
        margin-bottom: 6px;
    }

    .wd-item-excerpt {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .wd-item-meta {
        margin-top: 9px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        font-size: 12.5px;
        color: #64748b;
        align-items: center;
    }

    .wd-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 900;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .wd-badge--draft {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }

    .wd-badge--review {
        background: #fffbeb;
        color: #b45309;
        border-color: #fde68a;
    }

    .wd-badge--published {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .wd-badge--rejected {
        background: #fef2f2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .wd-badge--default {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .wd-item-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .wd-mini-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 11px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 900;
        text-decoration: none;
        white-space: nowrap;
        transition:
            transform .16s ease,
            background .16s ease,
            border-color .16s ease,
            color .16s ease;
    }

    .wd-mini-btn:hover {
        transform: translateY(-1px);
        background: #eef6eb;
        color: #173f08;
        border-color: rgba(23, 63, 8, .25);
    }

    .wd-side-list {
        display: grid;
        gap: 12px;
    }

    .wd-side-item {
        padding: 14px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .wd-side-label {
        font-size: 12px;
        font-weight: 900;
        color: #64748b;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .wd-side-value {
        font-size: 15px;
        font-weight: 900;
        color: #111827;
        line-height: 1.5;
    }

    .wd-side-desc {
        margin-top: 6px;
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.6;
    }

    .wd-empty {
        padding: 38px 20px;
        text-align: center;
        color: #64748b;
    }

    .wd-empty-icon {
        width: 68px;
        height: 68px;
        border-radius: 22px;
        background: #eef6eb;
        color: #173f08;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: inset 0 0 0 1px rgba(23, 63, 8, .08);
    }

    .wd-empty-icon svg {
        width: 30px;
        height: 30px;
        stroke: currentColor;
    }

    .wd-empty-title {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
        margin-bottom: 6px;
    }

    .wd-empty-desc {
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 16px;
    }

    @media (max-width: 1100px) {
        .wd-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wd-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .wd-head {
            display: grid;
            gap: 16px;
        }

        .wd-head-actions,
        .wd-btn {
            width: 100%;
        }

        .wd-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .wd-card {
            min-height: auto;
            padding: 18px;
            border-radius: 18px;
        }

        .wd-panel {
            padding: 16px;
            border-radius: 18px;
        }

        .wd-item {
            grid-template-columns: 1fr;
            align-items: start;
        }

        .wd-item-action {
            justify-content: flex-start;
        }

        .wd-mini-btn {
            width: 100%;
        }
    }
</style>

<div class="wd-page">
    <div class="wd-head">
        <div>
            <div class="wd-kicker">
                <span class="wd-kicker-dot"></span>
                Writer Area
            </div>

            <h1 class="wd-title">Writer Dashboard</h1>

            <p class="wd-subtitle">
                Kelola draft, pantau status review, dan lanjutkan penulisan berita perusahaan sebelum dipublikasikan.
            </p>
        </div>

        <div class="wd-head-actions">
            <a href="{{ route('writer.news.create') }}" class="wd-btn wd-btn--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>
                Tulis News
            </a>

            <a href="{{ route('writer.news.index') }}" class="wd-btn wd-btn--light">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                    <path d="M8 7h8"/>
                    <path d="M8 11h8"/>
                    <path d="M8 15h5"/>
                </svg>
                Semua News
            </a>
        </div>
    </div>

    <div class="wd-grid">
        <div class="wd-card wd-card--draft">
            <div class="wd-card-top">
                <div>
                    <p class="wd-card-title">Draft</p>
                    <div class="wd-card-value">{{ $draft ?? 0 }}</div>
                </div>

                <div class="wd-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                    </svg>
                </div>
            </div>

            <div class="wd-card-caption">
                Berita yang masih disusun dan belum dikirim ke reviewer.
            </div>
        </div>

        <div class="wd-card wd-card--review">
            <div class="wd-card-top">
                <div>
                    <p class="wd-card-title">Pending Review</p>
                    <div class="wd-card-value">{{ $inReview ?? 0 }}</div>
                </div>

                <div class="wd-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
            </div>

            <div class="wd-card-caption">
                Berita yang sudah dikirim dan menunggu validasi reviewer.
            </div>
        </div>

        <div class="wd-card wd-card--published">
            <div class="wd-card-top">
                <div>
                    <p class="wd-card-title">Published</p>
                    <div class="wd-card-value">{{ $published ?? 0 }}</div>
                </div>

                <div class="wd-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
            </div>

            <div class="wd-card-caption">
                Berita yang sudah dipublikasikan di halaman website.
            </div>
        </div>

        <div class="wd-card wd-card--rejected">
            <div class="wd-card-top">
                <div>
                    <p class="wd-card-title">Rejected</p>
                    <div class="wd-card-value">{{ $rejected ?? 0 }}</div>
                </div>

                <div class="wd-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </div>
            </div>

            <div class="wd-card-caption">
                Berita yang dikembalikan reviewer untuk diperbaiki.
            </div>
        </div>
    </div>

    <div class="wd-layout">
        <div class="wd-panel">
            <div class="wd-panel-head">
                <h2 class="wd-panel-title">News Terbaru</h2>

                <a href="{{ route('writer.news.index') }}" class="wd-panel-link">
                    Lihat semua
                    <span>→</span>
                </a>
            </div>

            @if($myNews->count())
                <div class="wd-list">
                    @foreach($myNews as $news)
                        @php
                            $translation = method_exists($news, 'getTranslationByLocale')
                                ? $news->getTranslationByLocale('id')
                                : ($news->translations->firstWhere('locale', 'id') ?? $news->translations->first());

                            $title = $translation?->title ?? 'Tanpa Judul';
                            $excerpt = $translation?->excerpt ?? '';
                            $status = $news->status ?? 'draft';

                            $badgeClass = match ($status) {
                                'draft' => 'wd-badge--draft',
                                'pending_review' => 'wd-badge--review',
                                'published' => 'wd-badge--published',
                                'rejected' => 'wd-badge--rejected',
                                default => 'wd-badge--default',
                            };

                            $statusLabel = match ($status) {
                                'draft' => 'Draft',
                                'pending_review' => 'Pending Review',
                                'published' => 'Published',
                                'rejected' => 'Rejected',
                                default => ucfirst(str_replace('_', ' ', $status)),
                            };
                        @endphp

                        <div class="wd-item">
                            <div class="wd-item-main">
                                <div class="wd-item-title">{{ $title }}</div>

                                @if($excerpt)
                                    <div class="wd-item-excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($excerpt), 130) }}
                                    </div>
                                @endif

                                <div class="wd-item-meta">
                                    <span class="wd-badge {{ $badgeClass }}">{{ $statusLabel }}</span>

                                    @if($news->category?->name)
                                        <span>{{ $news->category->name }}</span>
                                    @endif

                                    @if($news->updated_at)
                                        <span>Update {{ $news->updated_at->format('d M Y H:i') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="wd-item-action">
                                <a href="{{ route('writer.news.edit', $news) }}" class="wd-mini-btn">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="wd-empty">
                    <div class="wd-empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            <path d="M8 7h8"/>
                            <path d="M8 11h8"/>
                            <path d="M8 15h5"/>
                        </svg>
                    </div>

                    <div class="wd-empty-title">Belum ada berita</div>

                    <div class="wd-empty-desc">
                        Mulai tulis berita pertama untuk mengisi konten publikasi website.
                    </div>

                    <a href="{{ route('writer.news.create') }}" class="wd-btn wd-btn--primary">
                        Tulis News
                    </a>
                </div>
            @endif
        </div>

        <div class="wd-panel">
            <div class="wd-panel-head">
                <h2 class="wd-panel-title">Ringkasan</h2>
            </div>

            <div class="wd-side-list">
                <div class="wd-side-item">
                    <div class="wd-side-label">Total News</div>
                    <div class="wd-side-value">{{ $totalNews ?? 0 }} Berita</div>
                    <div class="wd-side-desc">
                        Total seluruh berita yang tercatat di sistem writer.
                    </div>
                </div>

                <div class="wd-side-item">
                    <div class="wd-side-label">Alur Penulisan</div>
                    <div class="wd-side-value">Draft → Review → Publish</div>
                    <div class="wd-side-desc">
                        Susun konten berita, kirim ke reviewer, lalu berita akan dipublikasikan setelah validasi.
                    </div>
                </div>

                <div class="wd-side-item">
                    <div class="wd-side-label">Tips</div>
                    <div class="wd-side-value">Periksa judul & gambar</div>
                    <div class="wd-side-desc">
                        Pastikan judul, excerpt, featured image, dan isi berita sudah sesuai sebelum dikirim.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection