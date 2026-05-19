@extends('layouts.app')

@section('title', $metaTitle ?? ($translation?->title ?? 'News Detail'))
@section('body_class', 'page-news-detail')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');

    $title       = $translation?->title ?? ($locale === 'id' ? 'Detail Berita' : 'News Detail');
    $excerpt     = $translation?->excerpt ?? '';
    $content     = $contentHtml ?? ($translation?->content ?? '');
    $coverImage  = $coverImage ?? ($metaImage ?? asset('images/logo.png'));

    $currentUrl  = request()->fullUrl();
    $shareText   = $title . ' - BSP Zapin';
    $encodedUrl  = urlencode($currentUrl);
    $encodedText = urlencode($shareText);

    $relatedNews = $relatedNews ?? collect();
@endphp

@push('meta')
    <link rel="canonical" href="{{ $currentUrl }}">
    <meta name="description" content="{{ $metaDescription ?? ($excerpt ?: \Illuminate\Support\Str::limit(strip_tags($content), 160)) }}">
    <meta property="og:title" content="{{ $metaTitle ?? $title . ' - BSP Zapin' }}">
    <meta property="og:description" content="{{ $metaDescription ?? ($excerpt ?: \Illuminate\Support\Str::limit(strip_tags($content), 160)) }}">
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
/* FIX: halaman detail berita harus keluar dari wrapper default layout.app
   Penyebab jarak kiri/kanan: .n-main di layout punya max-width + padding.
   Override ini hanya berlaku di halaman detail berita, tidak mengganggu halaman lain. */
body.page-news-detail .n-main {
    max-width: none !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: visible !important;
}

/* ─────────────────────────────────────────────────────────────────────────────
   RESET & SCOPE
   ───────────────────────────────────────────────────────────────────────────── */
.nd * { box-sizing: border-box; }

/* ─────────────────────────────────────────────────────────────────────────────
   LAYOUT SHELL
   Semua section memakai --nd-px sebagai padding horizontal,
   dan --nd-max sebagai max-width wrapper, agar breadcrumb / hero /
   body / footer strip selalu lurus satu garis vertikal dengan navbar.
   ───────────────────────────────────────────────────────────────────────────── */
:root {
    --nd-max : 1280px;   /* sama dengan navbar max-width         */
    --nd-px  : 28px;     /* sama dengan navbar horizontal padding */
    --nd-gap : clamp(22px, 3vw, 40px);
    --nd-col : 304px;    /* lebar sidebar                        */
}

/* ─────────────────────────────────────────────────────────────────────────────
   READING PROGRESS
   ───────────────────────────────────────────────────────────────────────────── */
.nd-progress {
    position: fixed;
    top: 0; left: 0;
    height: 3px;
    width: 0%;
    background: linear-gradient(90deg, var(--g500), var(--gold-lt));
    z-index: 950;
    border-radius: 0 2px 2px 0;
    pointer-events: none;
    transition: width .1s linear;
}

/* ─────────────────────────────────────────────────────────────────────────────
   BREADCRUMB BAR
   ───────────────────────────────────────────────────────────────────────────── */
.nd-bc-bar {
    background: var(--white);
    border-bottom: 1px solid var(--line);
    /* Nol padding — inner wrapper yang mengatur alignment */
    padding: 0;
}

.nd-bc-inner {
    width: 100%;
    max-width: var(--nd-max);
    margin: 0 auto;
    padding: 0 var(--nd-px);   /* ← diselaraskan dengan navbar */
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.nd-bc {
    list-style: none;
    margin: 0; padding: 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    font-size: 12px;
    font-weight: 500;
    color: var(--text3);
}

.nd-bc li { display: flex; align-items: center; }

.nd-bc a {
    color: var(--text2);
    text-decoration: none;
    transition: color .14s;
}

.nd-bc a:hover { color: var(--g900); }

.nd-bc-sep {
    margin: 0 8px;
    color: var(--line);
    font-weight: 300;
}

/* Quick share icons */
.nd-bc-share {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}

.nd-bc-icon {
    width: 30px; height: 30px;
    border-radius: 7px;
    border: 1px solid var(--line);
    background: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: var(--text3);
    cursor: pointer;
    padding: 0;
    font: inherit;
    transition: background .15s, border-color .15s, color .15s, transform .15s;
}

.nd-bc-icon:hover { transform: translateY(-2px); }
.nd-bc-icon.wa:hover { background: #25D366; border-color: #25D366; color: #fff; }
.nd-bc-icon.fb:hover { background: #1877F2; border-color: #1877F2; color: #fff; }
.nd-bc-icon.tg:hover { background: #229ED9; border-color: #229ED9; color: #fff; }
.nd-bc-icon.x:hover  { background: var(--text); border-color: var(--text); color: #fff; }
.nd-bc-icon svg { width: 14px; height: 14px; display: block; }

/* ─────────────────────────────────────────────────────────────────────────────
   HERO
   ───────────────────────────────────────────────────────────────────────────── */
.nd-hero {
    background: var(--white);
    border-bottom: 1px solid var(--line);
    padding: 0;           /* padding dikelola oleh inner */
}

.nd-hero-inner {
    width: 100%;
    max-width: var(--nd-max);
    margin: 0 auto;
    padding: clamp(28px, 5vw, 52px) var(--nd-px) clamp(22px, 4vw, 40px);
    /* Batasi lebar konten hero agar nyaman dibaca,
       tapi tetap rata kiri dengan navbar (bukan di-center sendiri) */
    display: grid;
    grid-template-columns: minmax(0, 860px) 1fr;
}

/* Kolom kiri (konten) mengisi maks 860px */
.nd-hero-content {
    min-width: 0;
}

/* Category badge */
.nd-cat {
    display: inline-flex;
    align-items: center;
    height: 26px;
    padding: 0 10px;
    border-radius: 6px;
    background: var(--g100);
    color: var(--g900);
    border: 1px solid var(--g200);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 14px;
}

/* Headline */
.nd-headline {
    font-family: var(--font);
    font-weight: 800;
    font-size: clamp(24px, 4vw, 46px);
    line-height: 1.15;
    letter-spacing: -.03em;
    color: var(--text);
    margin: 0 0 16px;
}

/* Excerpt deck */
.nd-deck {
    font-family: var(--font);
    font-size: clamp(14px, 1.7vw, 16.5px);
    font-weight: 400;
    font-style: italic;
    line-height: 1.78;
    color: var(--text2);
    margin: 0 0 22px;
    padding-left: 14px;
    border-left: 3px solid var(--gold-lt);
}

/* Byline */
.nd-byline {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-top: 18px;
    border-top: 1px solid var(--line2);
}

.nd-byline-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.nd-byline-avatar {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: var(--g100);
    border: 1px solid var(--g200);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}

.nd-byline-avatar img {
    width: 100%; height: 100%;
    object-fit: contain;
    padding: 5px;
}

.nd-byline-name {
    font-family: var(--font);
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    line-height: 1.3;
}

.nd-byline-date {
    font-family: var(--font);
    font-size: 12px;
    color: var(--text3);
    line-height: 1;
    margin-top: 2px;
    display: block;
}

/* ─────────────────────────────────────────────────────────────────────────────
   PAGE BODY GRID
   Wrapper sama persis dengan navbar & breadcrumb agar garis vertikal lurus.
   ───────────────────────────────────────────────────────────────────────────── */
.nd-body {
    width: 100%;
    max-width: var(--nd-max);
    margin: 0 auto;
    padding: clamp(24px, 4vw, 48px) var(--nd-px) clamp(18px, 3vw, 36px);
    display: grid;
    grid-template-columns: minmax(0, 1fr) var(--nd-col);
    gap: var(--nd-gap);
    align-items: start;
}

/* ─────────────────────────────────────────────────────────────────────────────
   MAIN COLUMN
   ───────────────────────────────────────────────────────────────────────────── */
.nd-main { min-width: 0; }

/* Cover */
.nd-cover {
    margin: 0 0 28px;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid var(--line);
    background: var(--line2);
    box-shadow: 0 8px 28px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.04);
}

.nd-cover img {
    width: 100%;
    max-height: 500px;
    aspect-ratio: 16/9;
    object-fit: cover;
    display: block;
}

.nd-cover-empty {
    width: 100%;
    min-height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font);
    font-size: 14px;
    font-style: italic;
    color: var(--text3);
}

/* Prose body */
.nd-prose {
    max-width: none;
    overflow-wrap: anywhere;
    font-family: var(--font);
    font-size: clamp(15px, 1.5vw, 16.5px);
    line-height: 1.85;
    color: var(--text2);
}

.nd-prose > *:first-child { margin-top: 0 !important; }
.nd-prose > *:last-child  { margin-bottom: 0 !important; }

.nd-prose p {
    margin: 0 0 1.4em;
    text-align: justify;
    text-justify: inter-word;
    -webkit-hyphens: auto;
    hyphens: auto;
}

.nd-prose h2,
.nd-prose h3,
.nd-prose h4 {
    font-family: var(--font);
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.025em;
    line-height: 1.25;
    margin: 2em 0 .75em;
}

.nd-prose h2 { font-size: clamp(20px, 2.4vw, 26px); }
.nd-prose h3 { font-size: clamp(17px, 1.9vw, 21px); }
.nd-prose h4 { font-size: clamp(15px, 1.5vw, 17px); font-weight: 700; }

.nd-prose ul,
.nd-prose ol {
    margin: 0 0 1.4em 1.6em;
    padding: 0;
}

.nd-prose li { margin-bottom: .6em; }

.nd-prose blockquote {
    margin: 1.8em 0;
    padding: 16px 20px;
    border-left: 3px solid var(--gold-lt);
    background: var(--g50);
    border-radius: 0 10px 10px 0;
    color: var(--text2);
    font-style: italic;
}

.nd-prose blockquote p:last-child { margin-bottom: 0; }

.nd-prose a {
    color: var(--g700);
    text-decoration: underline;
    text-underline-offset: 3px;
    text-decoration-color: var(--g200);
    transition: color .14s, text-decoration-color .14s;
}

.nd-prose a:hover {
    color: var(--g900);
    text-decoration-color: var(--g500);
}

.nd-prose img {
    width: 100%; height: auto;
    border-radius: 10px;
    display: block;
    margin: 1.8em auto;
    border: 1px solid var(--line);
}

.nd-prose figure { margin: 2em 0; }

.nd-prose figcaption {
    text-align: center;
    font-size: 12.5px;
    color: var(--text3);
    margin-top: 9px;
    line-height: 1.6;
}

/* Section label */
.nd-section-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font);
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text3);
    margin: 0 0 18px;
}

.nd-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--line);
}

/* Gallery */
.nd-gallery {
    margin-top: 36px;
    padding-top: 32px;
    border-top: 1px solid var(--line);
}

.nd-gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.nd-gallery-item {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--line);
    background: var(--white);
    transition: box-shadow .2s;
}

.nd-gallery-item:hover { box-shadow: 0 8px 20px rgba(0,0,0,.07); }

.nd-gallery-item img {
    width: 100%; height: 180px;
    object-fit: cover;
    display: block;
    transition: transform .35s;
}

.nd-gallery-item:hover img { transform: scale(1.03); }

.nd-gallery-caption {
    padding: 9px 12px;
    font-family: var(--font);
    font-size: 12px;
    color: var(--text3);
    line-height: 1.6;
}

/* ─────────────────────────────────────────────────────────────────────────────
   SIDEBAR
   ───────────────────────────────────────────────────────────────────────────── */
.nd-sidebar {
    position: sticky;
    top: calc(var(--nav-h, 66px) + 20px);
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Card */
.nd-card {
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 18px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}

.nd-card-title {
    font-family: var(--font);
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text3);
    margin: 0 0 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.nd-card-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--line2);
}

/* Share list */
.nd-share-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.nd-share-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 8px;
    border: 1px solid var(--line);
    background: var(--bg);
    text-decoration: none;
    font-family: var(--font);
    font-size: 13px;
    font-weight: 500;
    color: var(--text2);
    cursor: pointer;
    width: 100%;
    text-align: left;
    transition: background .15s, border-color .15s, color .15s, transform .15s;
}

.nd-share-row:hover {
    background: var(--g50);
    border-color: var(--g200);
    color: var(--g900);
    transform: translateX(3px);
}

.nd-share-row.wa:hover { background: #ecfdf5; border-color: #bbf7d0; color: #166534; }
.nd-share-row.fb:hover { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
.nd-share-row.tg:hover { background: #f0f9ff; border-color: #bae6fd; color: #0369a1; }
.nd-share-row.x:hover  { background: var(--line2); border-color: var(--line); color: var(--text); }
.nd-share-row.li:hover { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
.nd-share-row.cp:hover { background: var(--g100); border-color: var(--g200); color: var(--g900); }

.nd-share-row svg {
    width: 15px; height: 15px;
    flex-shrink: 0;
    opacity: .7;
}

/* Related list */
.nd-related-list {
    display: flex;
    flex-direction: column;
}

.nd-related-item {
    display: flex;
    gap: 11px;
    padding: 12px 0;
    border-bottom: 1px solid var(--line2);
    text-decoration: none;
    color: inherit;
    align-items: flex-start;
    transition: opacity .15s;
}

.nd-related-item:last-child { border-bottom: none; padding-bottom: 0; }
.nd-related-item:first-child { padding-top: 0; }
.nd-related-item:hover { opacity: .68; }

.nd-related-thumb {
    width: 68px; height: 50px;
    border-radius: 7px;
    overflow: hidden;
    flex-shrink: 0;
    background: var(--line2);
    border: 1px solid var(--line);
}

.nd-related-thumb img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}

.nd-related-text { flex: 1; min-width: 0; }

.nd-related-cat {
    display: inline-flex;
    align-items: center;
    height: 18px;
    padding: 0 7px;
    border-radius: 5px;
    background: var(--g100);
    color: var(--g700);
    border: 1px solid var(--g200);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.nd-related-title {
    font-family: var(--font);
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    line-height: 1.42;
    margin: 0 0 5px;
    letter-spacing: -.01em;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.nd-related-meta {
    font-family: var(--font);
    font-size: 11px;
    color: var(--text3);
}

.nd-related-all {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 13px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid var(--line);
    background: var(--bg);
    font-family: var(--font);
    font-size: 12.5px;
    font-weight: 700;
    color: var(--g900);
    text-decoration: none;
    transition: background .14s, border-color .14s;
}

.nd-related-all:hover {
    background: var(--g50);
    border-color: var(--g200);
}

/* ─────────────────────────────────────────────────────────────────────────────
   FOOTER STRIP
   ───────────────────────────────────────────────────────────────────────────── */
.nd-footer {
    border-top: 1px solid var(--line);
    background: var(--white);
    padding: 0;      /* dikelola oleh inner */
}

.nd-footer-inner {
    width: 100%;
    max-width: var(--nd-max);
    margin: 0 auto;
    padding: 16px var(--nd-px);   /* ← selaras dengan navbar, tanpa jarak berlebih */
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.nd-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font);
    font-size: 13px;
    font-weight: 700;
    color: var(--g900);
    text-decoration: none;
    transition: gap .15s;
}

.nd-back:hover { gap: 12px; }
.nd-back svg { width: 15px; height: 15px; }

.nd-footer-date {
    font-family: var(--font);
    font-size: 12px;
    color: var(--text3);
}

/* ─────────────────────────────────────────────────────────────────────────────
   TOAST
   ───────────────────────────────────────────────────────────────────────────── */
.nd-toast {
    position: fixed;
    bottom: 26px; left: 50%;
    transform: translateX(-50%) translateY(14px);
    background: var(--text);
    color: var(--white);
    font-family: var(--font);
    font-size: 13px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 999px;
    pointer-events: none;
    opacity: 0;
    z-index: 9999;
    box-shadow: 0 8px 28px rgba(0,0,0,.22);
    transition: opacity .2s, transform .2s;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}

.nd-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.nd-toast-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--g200);
    flex-shrink: 0;
}

/* ─────────────────────────────────────────────────────────────────────────────
   RESPONSIVE
   ───────────────────────────────────────────────────────────────────────────── */

/* Tablet — sidebar turun ke bawah */
@media (max-width: 1060px) {
    .nd-body {
        grid-template-columns: 1fr;
    }

    .nd-hero-inner {
        grid-template-columns: 1fr;
    }

    .nd-sidebar {
        position: static;
    }

    .nd-share-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .nd-related-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0 20px;
    }
}

/* Mobile */
@media (max-width: 680px) {
    :root {
        --nd-px: 16px;   /* kurangi padding di mobile, selaras navbar mobile */
    }

    .nd-bc-share       { display: none; }
    .nd-gallery-grid   { grid-template-columns: 1fr; }
    .nd-gallery-item img { height: 190px; }
    .nd-share-list     { grid-template-columns: 1fr; }
    .nd-related-list   { grid-template-columns: 1fr; }
}
</style>

{{-- Progress bar --}}
<div class="nd-progress" id="ndProgress" role="progressbar" aria-label="Reading progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>

{{-- ── Breadcrumb bar ─────────────────────────────────────────────────────────── --}}
<div class="nd-bc-bar">
    <div class="nd-bc-inner">
        <nav aria-label="Breadcrumb">
            <ol class="nd-bc">
                <li>
                    <a href="{{ route('web.home', ['locale' => $locale]) }}">
                        {{ $locale === 'id' ? 'Beranda' : 'Home' }}
                    </a>
                </li>
                <li>
                    <span class="nd-bc-sep" aria-hidden="true">/</span>
                    <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}">
                        {{ $locale === 'id' ? 'Media Publikasi' : 'Publications' }}
                    </a>
                </li>
                <li>
                    <span class="nd-bc-sep" aria-hidden="true">/</span>
                    <span aria-current="page">{{ \Illuminate\Support\Str::limit($title, 50) }}</span>
                </li>
            </ol>
        </nav>

        <div class="nd-bc-share" aria-label="{{ $locale === 'id' ? 'Bagikan' : 'Share' }}">
            <a href="https://wa.me/?text={{ $encodedText }}%20{{ $encodedUrl }}" target="_blank" rel="noopener noreferrer" class="nd-bc-icon wa" aria-label="WhatsApp">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.854L.057 23.776a.5.5 0 0 0 .612.612l5.922-1.476A11.953 11.953 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22a9.952 9.952 0 0 1-5.147-1.432l-.37-.22-3.815.951.968-3.74-.241-.383A9.944 9.944 0 0 1 2 12c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank" rel="noopener noreferrer" class="nd-bc-icon fb" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedText }}" target="_blank" rel="noopener noreferrer" class="nd-bc-icon x" aria-label="X / Twitter">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedText }}" target="_blank" rel="noopener noreferrer" class="nd-bc-icon tg" aria-label="Telegram">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- ── Hero ───────────────────────────────────────────────────────────────────── --}}
<header class="nd-hero">
    <div class="nd-hero-inner">
        <div class="nd-hero-content">

            @if($news->category?->name)
                <div class="nd-cat">{{ $news->category->name }}</div>
            @endif

            <h1 class="nd-headline">{{ $title }}</h1>

            @if($excerpt)
                <p class="nd-deck">{{ $excerpt }}</p>
            @endif

            <div class="nd-byline">
                <div class="nd-byline-left">
                    <div class="nd-byline-avatar">
                        <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
                    </div>
                    <div>
                        <div class="nd-byline-name">BSP Zapin</div>
                        @if($news->published_at)
                            <time class="nd-byline-date" datetime="{{ $news->published_at->toIso8601String() }}">
                                {{ $news->published_at->translatedFormat('d F Y, H:i') }}
                            </time>
                        @endif
                    </div>
                </div>

                @if($news->category?->name)
                    <div class="nd-cat" style="margin-bottom:0;">{{ $news->category->name }}</div>
                @endif
            </div>

        </div>
        {{-- Kolom kanan kosong — berfungsi sebagai spacer agar hero konten
             tidak melampaui lebar main column di bawahnya --}}
        <div aria-hidden="true"></div>
    </div>
</header>

{{-- ── Body ───────────────────────────────────────────────────────────────────── --}}
<div class="nd-body">

    {{-- Artikel utama --}}
    <main class="nd-main" id="ndArticle">

        {{-- Cover image --}}
        <div class="nd-cover">
            @if($news->featured_image)
                <img
                    src="{{ $coverImage }}"
                    alt="{{ $title }}"
                    loading="eager"
                    decoding="async"
                    width="860"
                    height="484"
                    onerror="this.parentElement.style.display='none';"
                >
            @else
                <div class="nd-cover-empty">
                    {{ $locale === 'id' ? 'Tidak ada gambar utama' : 'No cover image' }}
                </div>
            @endif
        </div>

        {{-- Prose --}}
        @if($content)
            <article class="nd-prose" itemscope itemtype="https://schema.org/NewsArticle">
                {!! $content !!}
            </article>
        @else
            <p style="font-family:var(--font);font-style:italic;color:var(--text3);font-size:15px;">
                {{ $locale === 'id' ? 'Konten belum tersedia.' : 'Content not available yet.' }}
            </p>
        @endif

        {{-- Photo gallery --}}
        @if($news->images && $news->images->count())
            <section class="nd-gallery" aria-label="{{ $locale === 'id' ? 'Galeri Foto' : 'Photo Gallery' }}">
                <p class="nd-section-label">{{ $locale === 'id' ? 'Galeri Foto' : 'Photo Gallery' }}</p>
                <div class="nd-gallery-grid">
                    @foreach($news->images as $image)
                        <figure class="nd-gallery-item">
                            <img
                                src="{{ $image->resolved_image_url ?? asset('images/logo.png') }}"
                                alt="{{ $image->caption ?: $title }}"
                                loading="lazy"
                                decoding="async"
                                onerror="this.style.display='none';"
                            >
                            @if($image->caption)
                                <figcaption class="nd-gallery-caption">{{ $image->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    {{-- Sidebar --}}
    <aside class="nd-sidebar">

        {{-- Share card --}}
        <div class="nd-card">
            <p class="nd-card-title">{{ $locale === 'id' ? 'Bagikan' : 'Share' }}</p>
            <div class="nd-share-list">

                <a href="https://wa.me/?text={{ $encodedText }}%20{{ $encodedUrl }}"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-row wa">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.854L.057 23.776a.5.5 0 0 0 .612.612l5.922-1.476A11.953 11.953 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22a9.952 9.952 0 0 1-5.147-1.432l-.37-.22-3.815.951.968-3.74-.241-.383A9.944 9.944 0 0 1 2 12c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10z"/></svg>
                    WhatsApp
                </a>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-row fb">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Facebook
                </a>

                <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedText }}"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-row x">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    X / Twitter
                </a>

                <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedText }}"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-row tg">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                    Telegram
                </a>

                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
                   target="_blank" rel="noopener noreferrer"
                   class="nd-share-row li">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </a>

                <button
                    type="button"
                    id="ndCopyBtn"
                    class="nd-share-row cp"
                    data-url="{{ $currentUrl }}"
                    aria-label="{{ $locale === 'id' ? 'Salin tautan' : 'Copy link' }}"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    {{ $locale === 'id' ? 'Salin Tautan' : 'Copy Link' }}
                </button>

            </div>
        </div>

        {{-- Related --}}
        @if($relatedNews->count())
            <div class="nd-card">
                <p class="nd-card-title">{{ $locale === 'id' ? 'Baca Juga' : 'Related' }}</p>
                <div class="nd-related-list">
                    @foreach($relatedNews->take(5) as $related)
                        <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $related->display_slug]) }}"
                           class="nd-related-item">
                            <div class="nd-related-thumb">
                                <img
                                    src="{{ $related->display_image_url }}"
                                    alt="{{ $related->display_title }}"
                                    loading="lazy"
                                    decoding="async"
                                    width="68"
                                    height="50"
                                    onerror="this.src='{{ asset('images/logo.png') }}';"
                                >
                            </div>
                            <div class="nd-related-text">
                                @if($related->category?->name)
                                    <div class="nd-related-cat">{{ $related->category->name }}</div>
                                @endif
                                <p class="nd-related-title">{{ $related->display_title }}</p>
                                @if($related->published_at)
                                    <time class="nd-related-meta" datetime="{{ $related->published_at->toIso8601String() }}">
                                        {{ $related->published_at->format('d M Y') }}
                                    </time>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="nd-related-all">
                    {{ $locale === 'id' ? 'Lihat semua berita →' : 'View all news →' }}
                </a>
            </div>
        @endif

    </aside>
</div>

{{-- ── Footer strip ───────────────────────────────────────────────────────────── --}}
<div class="nd-footer">
    <div class="nd-footer-inner">
        <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="nd-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            {{ $locale === 'id' ? 'Kembali ke Media Publikasi' : 'Back to Publications' }}
        </a>
        @if($news->published_at)
            <span class="nd-footer-date">
                {{ $locale === 'id' ? 'Diterbitkan' : 'Published' }}
                {{ $news->published_at->format('d M Y') }}
            </span>
        @endif
    </div>
</div>

{{-- Toast --}}
<div class="nd-toast" id="ndToast" role="status" aria-live="polite">
    <span class="nd-toast-dot"></span>
    {{ $locale === 'id' ? 'Tautan berhasil disalin' : 'Link copied!' }}
</div>

<script>
(function () {
    'use strict';

    /* ── Reading progress ── */
    var bar     = document.getElementById('ndProgress');
    var article = document.getElementById('ndArticle');

    function updateProgress() {
        if (!bar || !article) return;
        var rect     = article.getBoundingClientRect();
        var total    = article.offsetHeight;
        var scrolled = Math.max(-rect.top, 0);
        var pct      = Math.min(scrolled / total, 1);
        bar.style.width = (pct * 100) + '%';
        bar.setAttribute('aria-valuenow', Math.round(pct * 100));
    }

    window.addEventListener('scroll', updateProgress, { passive: true });

    /* ── Copy link ── */
    var copyBtn   = document.getElementById('ndCopyBtn');
    var toast     = document.getElementById('ndToast');
    var toastTimer;

    function showToast() {
        if (!toast) return;
        toast.classList.add('show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { toast.classList.remove('show'); }, 2500);
    }

    function fallbackCopy(url) {
        var ta = document.createElement('textarea');
        ta.value = url;
        ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;opacity:0;';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); showToast(); } catch (e) {}
        document.body.removeChild(ta);
    }

    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            var url = copyBtn.getAttribute('data-url') || window.location.href;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(showToast).catch(function () { fallbackCopy(url); });
            } else {
                fallbackCopy(url);
            }
        });
    }

})();
</script>

@endsection