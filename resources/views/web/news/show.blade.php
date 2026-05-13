@extends('layouts.app')

@section('title', $metaTitle ?? ($translation?->title ?? 'News Detail'))

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');

    $title = $translation?->title ?? ($locale === 'id' ? 'Detail Berita' : 'News Detail');
    $excerpt = $translation?->excerpt ?? '';
    $content = $translation?->content ?? '';
    $currentUrl = request()->fullUrl();
    $coverImage = $news->featured_image ? asset($news->featured_image) : asset('images/logo.png');

    $shareText = $title . ' - BSP Zapin';
    $encodedUrl = urlencode($currentUrl);
    $encodedText = urlencode($shareText);

    $relatedNews = \App\Models\News::query()
        ->with([
            'category',
            'translations' => function ($query) use ($locale) {
                $query->whereIn('locale', array_values(array_unique([$locale, 'id', 'en'])));
            },
        ])
        ->publicPublished()
        ->withoutTjsl()
        ->where('id', '!=', $news->id)
        ->when($news->news_category_id, function ($query) use ($news) {
            $query->orderByRaw('CASE WHEN news_category_id = ? THEN 0 ELSE 1 END', [$news->news_category_id]);
        })
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->limit(3)
        ->get();
@endphp

@push('meta')
    <link rel="canonical" href="{{ $currentUrl }}">
    <meta name="description" content="{{ $metaDescription ?? ($excerpt ?: Str::limit(strip_tags($content), 160)) }}">
    <meta property="og:title" content="{{ $metaTitle ?? $title . ' - BSP Zapin' }}">
    <meta property="og:description" content="{{ $metaDescription ?? ($excerpt ?: Str::limit(strip_tags($content), 160)) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:image" content="{{ $coverImage }}">
    @if($news->published_at)
        <meta property="article:published_time" content="{{ $news->published_at->toIso8601String() }}">
    @endif
    @if($news->updated_at)
        <meta property="article:modified_time" content="{{ $news->updated_at->toIso8601String() }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
@endpush

<style>
.n-main {
    max-width: none !important;
    width: 100% !important;
    padding: 0 !important;
}

.news-page {
    background: #f8fafc;
    color: #111827;
    width: 100%;
}

/* ─── HERO ─────────────────────────────────────────────────── */
.news-hero {
    background:
        radial-gradient(circle at 10% 20%, rgba(255,255,255,.13), transparent 28%),
        radial-gradient(circle at 88% 22%, rgba(255,214,130,.18), transparent 28%),
        linear-gradient(135deg, #102d06 0%, #173f08 54%, #21560e 100%);
    padding: 40px 0 112px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.news-hero::after {
    content: "";
    position: absolute;
    inset: auto -90px -180px auto;
    width: 380px;
    height: 380px;
    border-radius: 999px;
    background: rgba(255,255,255,.06);
}

.news-shell {
    max-width: 1040px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 2;
}

/* ─── BREADCRUMB ────────────────────────────────────────────── */
.news-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 22px;
    font-size: 13px;
    color: rgba(255,255,255,.65);
}

.news-breadcrumb a {
    color: rgba(255,255,255,.78);
    text-decoration: none;
    font-weight: 700;
}

.news-breadcrumb a:hover { color: #fff; }

.news-breadcrumb-sep { color: rgba(255,255,255,.4); }

/* ─── HERO HEADER ───────────────────────────────────────────── */
.news-head { max-width: 900px; }

.news-category {
    display: inline-flex;
    align-items: center;
    min-height: 32px;
    padding: 0 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.13);
    color: #f6d28b;
    font-weight: 900;
    font-size: 12px;
    letter-spacing: .04em;
    margin-bottom: 16px;
}

.news-title {
    margin: 0 0 18px;
    font-size: clamp(34px, 5vw, 58px);
    line-height: 1.08;
    font-weight: 900;
    letter-spacing: -.055em;
    color: #fff;
}

.news-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    font-size: 13px;
    color: rgba(255,255,255,.72);
}

.news-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

/* ─── CONTENT WRAP ──────────────────────────────────────────── */
.news-content-wrap {
    margin-top: -74px;
    padding-bottom: 74px;
    position: relative;
    z-index: 5;
}

/* ─── COVER IMAGE ───────────────────────────────────────────── */
.news-cover {
    margin-bottom: 26px;
    border-radius: 26px;
    overflow: hidden;
    border: 1px solid rgba(229,231,235,.78);
    background: #fff;
    box-shadow: 0 22px 48px rgba(15, 23, 42, .18);
}

.news-cover img {
    width: 100%;
    max-height: 480px;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    display: block;
}

.news-cover-empty {
    width: 100%;
    min-height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(circle at 20% 20%, rgba(23,63,8,.12), transparent 35%),
        #eef5eb;
    color: #173f08;
    font-weight: 900;
}

/* ─── SHARE CARD ────────────────────────────────────────────── */
.news-share-card {
    margin: 0 0 24px;
    background: #ffffff;
    border: 1px solid #e5eee2;
    border-radius: 22px;
    padding: 18px 20px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    flex-wrap: wrap;
}

.news-share-title {
    margin: 0;
    font-size: 14px;
    font-weight: 900;
    color: #173f08;
    letter-spacing: -.01em;
}

.news-share-desc {
    margin: 3px 0 0;
    font-size: 13px;
    color: #64748b;
    line-height: 1.6;
}

.news-share-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

/* ─── SHARE BUTTONS ─────────────────────────────────────────── */
.news-share-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 1px solid #e5e7eb;
    background: #f8fafc;
    color: #334155;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform .18s ease, background .18s ease, border-color .18s ease, box-shadow .18s ease;
    flex-shrink: 0;
    padding: 0;
}

.news-share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, .12);
}

.news-share-btn svg {
    width: 18px;
    height: 18px;
    display: block;
    flex-shrink: 0;
}

/* Platform colors */
.news-share-btn.whatsapp  { background: #ecfdf5; color: #25D366; border-color: #bbf7d0; }
.news-share-btn.whatsapp:hover  { background: #25D366; color: #fff; border-color: #25D366; }

.news-share-btn.facebook  { background: #eff6ff; color: #1877F2; border-color: #bfdbfe; }
.news-share-btn.facebook:hover  { background: #1877F2; color: #fff; border-color: #1877F2; }

.news-share-btn.twitter   { background: #f8fafc; color: #0f172a; border-color: #e2e8f0; }
.news-share-btn.twitter:hover   { background: #0f172a; color: #fff; border-color: #0f172a; }

.news-share-btn.telegram  { background: #f0f9ff; color: #229ED9; border-color: #bae6fd; }
.news-share-btn.telegram:hover  { background: #229ED9; color: #fff; border-color: #229ED9; }

.news-share-btn.linkedin  { background: #eff6ff; color: #0A66C2; border-color: #bfdbfe; }
.news-share-btn.linkedin:hover  { background: #0A66C2; color: #fff; border-color: #0A66C2; }

/* Copy button — pill shape to fit label */
.news-share-btn.copy {
    width: auto;
    border-radius: 999px;
    padding: 0 14px;
    gap: 6px;
    background: #fff7ed;
    color: #c2410c;
    border-color: #fed7aa;
    font-size: 13px;
    font-weight: 800;
}

.news-share-btn.copy:hover { background: #c2410c; color: #fff; border-color: #c2410c; }

/* ─── EXCERPT ───────────────────────────────────────────────── */
.news-excerpt {
    margin: 0 0 24px;
    font-size: 17px;
    line-height: 1.9;
    color: #334155;
    padding: 20px 22px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e5eee2;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
}

/* ─── ARTICLE BODY ──────────────────────────────────────────── */
.news-body {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    padding: clamp(24px, 4vw, 42px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
    color: #374151;
    font-size: 16px;
    line-height: 1.95;
}

.news-body > *:first-child { margin-top: 0 !important; }
.news-body > *:last-child  { margin-bottom: 0 !important; }

.news-body h1,
.news-body h2,
.news-body h3,
.news-body h4,
.news-body h5,
.news-body h6 {
    color: #111827;
    line-height: 1.28;
    margin-top: 1.8em;
    margin-bottom: .75em;
    font-weight: 900;
    letter-spacing: -.025em;
}

.news-body h1 { font-size: 34px; }
.news-body h2 { font-size: 30px; }
.news-body h3 { font-size: 24px; }
.news-body h4 { font-size: 20px; }

.news-body p {
    margin: 0 0 1.2em;
    text-align: justify;
    text-justify: inter-word;
}

.news-body ul,
.news-body ol {
    margin: 0 0 1.4em 1.4em;
    padding: 0;
}

.news-body li { margin-bottom: .65em; }

.news-body blockquote {
    margin: 1.8em 0;
    padding: 16px 18px;
    border-left: 4px solid #2f7d32;
    background: #f8fbf7;
    border-radius: 12px;
    color: #374151;
}

.news-body a {
    color: #1d4ed8;
    text-decoration: underline;
    text-underline-offset: 2px;
}

.news-body img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    display: block;
}

.news-body figure { margin: 2em 0; }

.news-body figure img {
    width: 100%;
    max-height: 460px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
}

.news-body figcaption {
    margin-top: 10px;
    font-size: 13px;
    color: #64748b;
    text-align: center;
    line-height: 1.7;
}

/* ─── GALLERY ───────────────────────────────────────────────── */
.news-gallery-section { margin-top: 28px; }

.news-gallery-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    padding: clamp(20px, 3vw, 30px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
}

.news-gallery-head { margin-bottom: 18px; }

.news-gallery-title {
    margin: 0 0 7px;
    font-size: 25px;
    font-weight: 900;
    color: #111827;
    letter-spacing: -.025em;
}

.news-gallery-desc {
    margin: 0;
    font-size: 14px;
    color: #64748b;
    line-height: 1.7;
}

.news-gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.news-gallery-item {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    overflow: hidden;
}

.news-gallery-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    background: #eef2f7;
}

.news-gallery-caption {
    padding: 12px 14px;
    font-size: 13px;
    color: #64748b;
    line-height: 1.7;
}

/* ─── RELATED NEWS ──────────────────────────────────────────── */
.news-related-section { margin-top: 32px; }

.news-related-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    padding: clamp(20px, 3vw, 30px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
}

.news-related-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.news-related-title {
    margin: 0 0 6px;
    font-size: 25px;
    font-weight: 900;
    color: #111827;
    letter-spacing: -.025em;
}

.news-related-desc {
    margin: 0;
    font-size: 14px;
    color: #64748b;
    line-height: 1.7;
}

.news-related-more {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    padding: 0 14px;
    border-radius: 999px;
    background: #f8fbf7;
    color: #173f08;
    text-decoration: none;
    font-size: 13px;
    font-weight: 900;
    border: 1px solid #dfeadd;
    white-space: nowrap;
}

.news-related-more:hover {
    background: #173f08;
    color: #fff;
}

/* 3-column card grid */
.news-related-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

.news-related-item {
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.news-related-item:hover {
    transform: translateY(-4px);
    border-color: #cddfc9;
    box-shadow: 0 16px 32px rgba(15, 23, 42, .10);
}

/* Thumbnail — fixed 16:9 proportion */
.news-related-thumb {
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #eef5eb;
    flex-shrink: 0;
}

.news-related-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .35s ease;
}

.news-related-item:hover .news-related-thumb img {
    transform: scale(1.04);
}

.news-related-body {
    padding: 14px 15px 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.news-related-category {
    display: inline-flex;
    align-items: center;
    min-height: 22px;
    padding: 0 9px;
    border-radius: 999px;
    background: #f8fbf7;
    color: #2f7d32;
    border: 1px solid #dfeadd;
    font-size: 10px;
    font-weight: 900;
    letter-spacing: .03em;
    margin-bottom: 8px;
    align-self: flex-start;
}

.news-related-name {
    margin: 0;
    color: #111827;
    font-size: 14px;
    line-height: 1.45;
    font-weight: 800;
    letter-spacing: -.02em;
    flex: 1;
}

.news-related-meta {
    margin-top: 10px;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.news-related-meta svg {
    width: 13px;
    height: 13px;
    opacity: .7;
    flex-shrink: 0;
}

/* ─── MISC ──────────────────────────────────────────────────── */
.news-empty {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 28px;
    color: #64748b;
    line-height: 1.7;
}

.news-back-section {
    margin-top: 28px;
    display: flex;
    justify-content: center;
}

.news-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 46px;
    padding: 0 20px;
    border-radius: 999px;
    background: #173f08;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 900;
    transition: background .18s ease;
}

.news-back-btn:hover { background: #21560e; color: #fff; }

.news-back-btn svg { width: 16px; height: 16px; }

/* ─── COPY TOAST ────────────────────────────────────────────── */
.news-copy-toast {
    position: fixed;
    left: 50%;
    bottom: 26px;
    transform: translateX(-50%) translateY(20px);
    min-height: 44px;
    padding: 0 20px;
    border-radius: 999px;
    background: #111827;
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 900;
    box-shadow: 0 18px 40px rgba(15, 23, 42, .24);
    opacity: 0;
    pointer-events: none;
    z-index: 9999;
    transition: opacity .2s ease, transform .2s ease;
}

.news-copy-toast svg { width: 15px; height: 15px; color: #4ade80; }

.news-copy-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

/* ─── RESPONSIVE ────────────────────────────────────────────── */
@media (max-width: 900px) {
    .news-related-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .news-shell { padding: 0 16px; }
    .news-hero  { padding: 32px 0 92px; }
    .news-title { font-size: 30px; }

    .news-cover img { max-height: 240px; }

    .news-excerpt { font-size: 15px; padding: 16px; }

    .news-body h1 { font-size: 28px; }
    .news-body h2 { font-size: 24px; }
    .news-body h3 { font-size: 20px; }

    .news-gallery-grid         { grid-template-columns: 1fr; }
    .news-gallery-image        { height: 200px; }
    .news-gallery-title,
    .news-related-title        { font-size: 20px; }

    .news-related-grid         { grid-template-columns: 1fr; }

    .news-share-card           { align-items: stretch; }
    .news-share-actions        { width: 100%; justify-content: flex-start; }
}
</style>

<div class="news-page">
    {{-- ─── HERO ─────────────────────────────────────────────── --}}
    <section class="news-hero">
        <div class="news-shell">
            <nav class="news-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('web.home', ['locale' => $locale]) }}">
                    {{ $locale === 'id' ? 'Beranda' : 'Home' }}
                </a>
                <span class="news-breadcrumb-sep">/</span>
                <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}">
                    {{ $locale === 'id' ? 'Media Publikasi' : 'Media Publications' }}
                </a>
                <span class="news-breadcrumb-sep">/</span>
                <span>{{ Str::limit($title, 52) }}</span>
            </nav>

            <header class="news-head">
                @if($news->category?->name)
                    <div class="news-category">{{ $news->category->name }}</div>
                @endif

                <h1 class="news-title">{{ $title }}</h1>

                <div class="news-meta">
                    @if($news->published_at)
                        <time class="news-meta-item" datetime="{{ $news->published_at->toIso8601String() }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $news->published_at->format('d M Y H:i') }}
                        </time>
                    @endif
                </div>
            </header>
        </div>
    </section>

    {{-- ─── CONTENT ──────────────────────────────────────────── --}}
    <section class="news-content-wrap">
        <div class="news-shell">

            {{-- Cover --}}
            <div class="news-cover">
                @if($news->featured_image)
                    <img
                        src="{{ asset($news->featured_image) }}"
                        alt="{{ $title }}"
                        loading="eager"
                        decoding="async"
                        onerror="this.parentElement.style.display='none';"
                    >
                @else
                    <div class="news-cover-empty">
                        {{ $locale === 'id' ? 'Tanpa Gambar Utama' : 'No Cover Image' }}
                    </div>
                @endif
            </div>

            {{-- Share Card --}}
            <section class="news-share-card" aria-label="{{ $locale === 'id' ? 'Bagikan berita' : 'Share news' }}">
                <div>
                    <h2 class="news-share-title">
                        {{ $locale === 'id' ? 'Bagikan Berita Ini' : 'Share This News' }}
                    </h2>
                    <p class="news-share-desc">
                        {{ $locale === 'id'
                            ? 'Kirim berita ini melalui media sosial atau salin tautannya.'
                            : 'Share this article through social media or copy the link.' }}
                    </p>
                </div>

                <div class="news-share-actions">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/?text={{ $encodedText }}%20{{ $encodedUrl }}"
                       target="_blank" rel="noopener noreferrer"
                       class="news-share-btn whatsapp"
                       aria-label="Share to WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
                       target="_blank" rel="noopener noreferrer"
                       class="news-share-btn facebook"
                       aria-label="Share to Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>

                    {{-- X / Twitter --}}
                    <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedText }}"
                       target="_blank" rel="noopener noreferrer"
                       class="news-share-btn twitter"
                       aria-label="Share to X">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>

                    {{-- Telegram --}}
                    <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedText }}"
                       target="_blank" rel="noopener noreferrer"
                       class="news-share-btn telegram"
                       aria-label="Share to Telegram">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </a>

                    {{-- LinkedIn --}}
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
                       target="_blank" rel="noopener noreferrer"
                       class="news-share-btn linkedin"
                       aria-label="Share to LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>

                    {{-- Copy Link --}}
                    <button
                        type="button"
                        class="news-share-btn copy"
                        id="newsCopyLinkBtn"
                        data-url="{{ $currentUrl }}"
                        aria-label="{{ $locale === 'id' ? 'Salin tautan' : 'Copy link' }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        {{ $locale === 'id' ? 'Salin' : 'Copy' }}
                    </button>
                </div>
            </section>

            {{-- Excerpt --}}
            @if($excerpt)
                <p class="news-excerpt">{{ $excerpt }}</p>
            @endif

            {{-- Article Body --}}
            @if($content)
                <article class="news-body" itemscope itemtype="https://schema.org/NewsArticle">
                    {!! $content !!}
                </article>
            @else
                <section class="news-empty">
                    {{ $locale === 'id'
                        ? 'Konten berita belum tersedia.'
                        : 'News content is not available yet.' }}
                </section>
            @endif

            {{-- Photo Gallery --}}
            @if($news->images && $news->images->count())
                <section class="news-gallery-section">
                    <div class="news-gallery-card">
                        <div class="news-gallery-head">
                            <h2 class="news-gallery-title">
                                {{ $locale === 'id' ? 'Galeri Foto' : 'Photo Gallery' }}
                            </h2>
                            <p class="news-gallery-desc">
                                {{ $locale === 'id'
                                    ? 'Dokumentasi tambahan yang terkait dengan berita ini.'
                                    : 'Additional documentation related to this news item.' }}
                            </p>
                        </div>

                        <div class="news-gallery-grid">
                            @foreach($news->images as $image)
                                <figure class="news-gallery-item">
                                    <img
                                        src="{{ asset($image->image_path) }}"
                                        alt="{{ $image->caption ?: $title }}"
                                        class="news-gallery-image"
                                        loading="lazy"
                                        decoding="async"
                                        onerror="this.style.display='none';"
                                    >
                                    @if($image->caption)
                                        <figcaption class="news-gallery-caption">
                                            {{ $image->caption }}
                                        </figcaption>
                                    @endif
                                </figure>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- Related News --}}
            @if($relatedNews->count())
                <section class="news-related-section">
                    <div class="news-related-card">
                        <div class="news-related-head">
                            <div>
                                <h2 class="news-related-title">
                                    {{ $locale === 'id' ? 'Berita Rekomendasi' : 'Recommended News' }}
                                </h2>
                                <p class="news-related-desc">
                                    {{ $locale === 'id'
                                        ? 'Baca juga berita dan publikasi lain dari BSP Zapin.'
                                        : 'Read more news and publications from BSP Zapin.' }}
                                </p>
                            </div>

                            <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="news-related-more">
                                {{ $locale === 'id' ? 'Lihat Semua' : 'View All' }}
                            </a>
                        </div>

                        <div class="news-related-grid">
                            @foreach($relatedNews as $related)
                                @php
                                    $relatedTranslation = $related->getTranslationByLocale($locale);
                                    $relatedTitle = $relatedTranslation?->title ?? ($locale === 'id' ? 'Berita' : 'News');
                                    $relatedSlug  = $relatedTranslation?->slug;
                                    $relatedImage = $related->featured_image
                                        ? asset($related->featured_image)
                                        : asset('images/logo.png');
                                @endphp

                                @if($relatedSlug)
                                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $relatedSlug]) }}"
                                       class="news-related-item">

                                        <div class="news-related-thumb">
                                            <img
                                                src="{{ $relatedImage }}"
                                                alt="{{ $relatedTitle }}"
                                                loading="lazy"
                                                decoding="async"
                                                onerror="this.src='{{ asset('images/logo.png') }}';"
                                            >
                                        </div>

                                        <div class="news-related-body">
                                            @if($related->category?->name)
                                                <div class="news-related-category">
                                                    {{ $related->category->name }}
                                                </div>
                                            @endif

                                            <h3 class="news-related-name">
                                                {{ Str::limit($relatedTitle, 80) }}
                                            </h3>

                                            @if($related->published_at)
                                                <div class="news-related-meta">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                    {{ $related->published_at->format('d M Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- Back Button --}}
            <div class="news-back-section">
                <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="news-back-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    {{ $locale === 'id' ? 'Kembali ke Media Publikasi' : 'Back to Media Publications' }}
                </a>
            </div>

        </div>
    </section>
</div>

{{-- Toast Notification --}}
<div id="newsCopyToast" class="news-copy-toast">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    {{ $locale === 'id' ? 'Link berhasil disalin' : 'Link copied' }}
</div>

<script>
(function () {
    const copyBtn = document.getElementById('newsCopyLinkBtn');
    const toast   = document.getElementById('newsCopyToast');

    if (!copyBtn || !toast) return;

    function showToast() {
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 2200);
    }

    copyBtn.addEventListener('click', function () {
        const url = copyBtn.getAttribute('data-url');
        if (!url) return;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(showToast).catch(function () { fallbackCopy(url); });
        } else {
            fallbackCopy(url);
        }
    });

    function fallbackCopy(text) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try { document.execCommand('copy'); showToast(); }
        catch (e) { window.prompt('{{ $locale === 'id' ? 'Salin link berikut:' : 'Copy this link:' }}', text); }
        document.body.removeChild(ta);
    }
})();
</script>

<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'NewsArticle',
    'headline'        => $title,
    'description'     => $metaDescription ?? ($excerpt ?: Str::limit(strip_tags($content), 160)),
    'image'           => [$coverImage],
    'datePublished'   => $news->published_at?->toIso8601String(),
    'dateModified'    => $news->updated_at?->toIso8601String(),
    'author'          => ['@type' => 'Organization', 'name' => 'PT Bumi Siak Pusako Zapin'],
    'publisher'       => [
        '@type' => 'Organization',
        'name'  => 'PT Bumi Siak Pusako Zapin',
        'logo'  => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
    ],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $currentUrl],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection