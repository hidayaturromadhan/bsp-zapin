@extends('layouts.app')

@section('title', $translation?->title ?? 'Preview News')

@section('content')
@php
    $locale = $locale ?? app()->getLocale();
    $translation = $translation ?? $news->getTranslationByLocale($locale);
    $categoryName = $news->category?->name ?? 'News';

    $publishedDate = $news->published_at
        ? $news->published_at->format('d M Y')
        : $news->created_at?->format('d M Y');

    $allImages = collect();

    if ($news->featured_image) {
        $allImages->push([
            'src' => asset($news->featured_image),
            'caption' => $translation?->title ?? 'Featured Image',
        ]);
    }

    foreach ($news->images as $image) {
        $allImages->push([
            'src' => asset($image->image_path),
            'caption' => $image->caption ?: ($translation?->title ?? 'News Image'),
        ]);
    }
@endphp

<style>
    .n-main {
        padding: 0 !important;
        max-width: none !important;
        width: 100% !important;
    }

    .np-page {
        width: 100%;
        background: var(--bg);
        font-family: var(--font);
        color: var(--text);
    }

    .np-preview-bar {
        position: sticky;
        top: var(--nav-h, 0px);
        z-index: 60;
        background: linear-gradient(90deg, #173f08 0%, #21560e 100%);
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.12);
        box-shadow: 0 8px 24px rgba(15,23,42,.16);
    }

    .np-preview-bar-inner {
        max-width: 1180px;
        margin: 0 auto;
        padding: 12px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .np-preview-label {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: .03em;
    }

    .np-preview-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        background: #bbf7d0;
        box-shadow: 0 0 0 5px rgba(187,247,208,.14);
    }

    .np-preview-meta {
        font-size: 12px;
        color: rgba(255,255,255,.72);
        font-weight: 700;
    }

    .np-preview-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .np-preview-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 12px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.10);
        color: #fff;
        font-size: 12px;
        font-weight: 900;
        text-decoration: none;
        transition: background .18s ease, transform .18s ease;
    }

    .np-preview-btn:hover {
        background: rgba(255,255,255,.18);
        transform: translateY(-1px);
        color: #fff;
    }

    .np-hero {
        background:
            radial-gradient(ellipse 70% 90% at 8% 60%, rgba(47,125,50,.32), transparent 60%),
            radial-gradient(ellipse 44% 62% at 92% 18%, rgba(32,71,18,.52), transparent 58%),
            linear-gradient(135deg, #173f08 0%, #102d06 100%);
        padding: 58px 0 76px;
        color: #fff;
        overflow: hidden;
    }

    .np-hero-inner {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 22px;
    }

    .np-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .np-kicker-line {
        width: 28px;
        height: 2px;
        border-radius: 999px;
        background: var(--gold-lt, #d9b45b);
    }

    .np-kicker-text {
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: rgba(255,255,255,.58);
    }

    .np-hero-title {
        max-width: 860px;
        margin: 0;
        color: #fff;
        font-size: clamp(32px, 5vw, 58px);
        line-height: 1.06;
        font-weight: 900;
        letter-spacing: -.05em;
    }

    .np-hero-title span {
        color: var(--gold-lt, #d9b45b);
    }

    .np-hero-excerpt {
        max-width: 760px;
        margin: 18px 0 0;
        color: rgba(255,255,255,.66);
        font-size: 15px;
        line-height: 1.8;
    }

    .np-meta-row {
        margin-top: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .np-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 34px;
        padding: 0 13px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.13);
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.78);
        font-size: 12px;
        font-weight: 800;
    }

    .np-pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: var(--gold-lt, #d9b45b);
    }

    .np-wrap {
        max-width: 1180px;
        margin: -42px auto 0;
        padding: 0 22px 72px;
        position: relative;
        z-index: 2;
    }

    .np-main-card {
        background: #fff;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15,23,42,.12);
    }

    .np-featured {
        position: relative;
        width: 100%;
        height: min(56vw, 520px);
        min-height: 320px;
        background: #f1f5f9;
        overflow: hidden;
    }

    .np-featured img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .np-featured::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 42%;
        background: linear-gradient(to top, rgba(15,23,42,.34), transparent);
        pointer-events: none;
    }

    .np-no-image {
        width: 100%;
        height: 100%;
        min-height: 360px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        background:
            radial-gradient(circle at 20% 20%, rgba(47,125,50,.10), transparent 34%),
            linear-gradient(135deg, #f8fafc, #eef6eb);
        font-size: 14px;
        font-weight: 900;
    }

    .np-content {
        padding: clamp(24px, 5vw, 54px);
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 42px;
        align-items: start;
    }

    .np-article {
        min-width: 0;
    }

    .np-article h2 {
        margin: 32px 0 12px;
        color: #0f172a;
        font-size: clamp(22px, 3vw, 30px);
        line-height: 1.22;
        font-weight: 900;
        letter-spacing: -.035em;
    }

    .np-article h2:first-child {
        margin-top: 0;
    }

    .np-article p {
        margin: 0 0 18px;
        color: #334155;
        font-size: 15px;
        line-height: 1.9;
        text-align: justify;
        text-justify: inter-word;
    }

    .np-article figure {
        margin: 28px 0;
    }

    .np-article figure img {
        width: 100%;
        max-height: 520px;
        object-fit: cover;
        border-radius: 18px;
        border: 1px solid #e5e7eb;
        display: block;
        box-shadow: 0 14px 34px rgba(15,23,42,.08);
    }

    .np-article figcaption {
        margin-top: 10px;
        text-align: center;
        font-size: 12px;
        line-height: 1.6;
        color: #64748b;
    }

    .np-sidebar {
        position: sticky;
        top: calc(var(--nav-h, 0px) + 74px);
        display: grid;
        gap: 14px;
    }

    .np-side-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
    }

    .np-side-title {
        margin: 0 0 12px;
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: -.01em;
    }

    .np-side-row {
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .np-side-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .np-side-label {
        font-size: 11px;
        font-weight: 900;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 4px;
    }

    .np-side-value {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.6;
    }

    .np-status {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
    }

    .np-status.draft {
        background: #f1f5f9;
        color: #334155;
    }

    .np-status.published {
        background: #dcfce7;
        color: #166534;
    }

    .np-status.archived {
        background: #fee2e2;
        color: #991b1b;
    }

    .np-gallery {
        margin-top: 28px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15,23,42,.06);
    }

    .np-gallery-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
    }

    .np-gallery-title {
        margin: 0;
        color: #0f172a;
        font-size: 15px;
        font-weight: 900;
    }

    .np-gallery-count {
        display: inline-flex;
        min-height: 28px;
        padding: 0 10px;
        align-items: center;
        border-radius: 999px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
    }

    .np-gallery-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 8px;
        padding: 8px;
        grid-auto-rows: 170px;
        grid-auto-flow: dense;
    }

    .np-gitem {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        background: #f1f5f9;
        cursor: zoom-in;
    }

    .np-gallery-grid.count-1 {
        grid-auto-rows: 430px;
    }

    .np-gallery-grid.count-1 .np-gitem {
        grid-column: span 12;
    }

    .np-gallery-grid.count-2 {
        grid-auto-rows: 320px;
    }

    .np-gallery-grid.count-2 .np-gitem {
        grid-column: span 6;
    }

    .np-gallery-grid.count-3 .np-gitem:nth-child(1) {
        grid-column: span 7;
        grid-row: span 2;
    }

    .np-gallery-grid.count-3 .np-gitem:nth-child(2),
    .np-gallery-grid.count-3 .np-gitem:nth-child(3) {
        grid-column: span 5;
    }

    .np-gallery-grid.count-4 .np-gitem:nth-child(1) {
        grid-column: span 7;
        grid-row: span 2;
    }

    .np-gallery-grid.count-4 .np-gitem:nth-child(2),
    .np-gallery-grid.count-4 .np-gitem:nth-child(3) {
        grid-column: span 5;
    }

    .np-gallery-grid.count-4 .np-gitem:nth-child(4) {
        grid-column: span 12;
    }

    .np-gallery-grid.count-5 .np-gitem:nth-child(1) {
        grid-column: span 6;
        grid-row: span 2;
    }

    .np-gallery-grid.count-5 .np-gitem:nth-child(2) {
        grid-column: span 6;
    }

    .np-gallery-grid.count-5 .np-gitem:nth-child(3),
    .np-gallery-grid.count-5 .np-gitem:nth-child(4) {
        grid-column: span 3;
    }

    .np-gallery-grid.count-5 .np-gitem:nth-child(5) {
        grid-column: span 12;
    }

    .np-gitem img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .42s ease;
    }

    .np-gitem:hover img {
        transform: scale(1.055);
    }

    .np-gcap {
        position: absolute;
        inset: auto 0 0;
        padding: 34px 14px 13px;
        background: linear-gradient(to top, rgba(15,23,42,.72), transparent);
        color: rgba(255,255,255,.88);
        font-size: 12px;
        line-height: 1.5;
        transform: translateY(100%);
        transition: transform .24s ease;
    }

    .np-gitem:hover .np-gcap {
        transform: translateY(0);
    }

    .np-lb {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(5,12,2,.90);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .22s ease;
    }

    .np-lb.is-open {
        opacity: 1;
        pointer-events: auto;
    }

    .np-lb-img {
        max-width: 88vw;
        max-height: 84vh;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 32px 90px rgba(0,0,0,.60);
        transform: scale(.94);
        opacity: 0;
        transition: transform .24s ease, opacity .18s ease;
    }

    .np-lb.is-open .np-lb-img {
        opacity: 1;
        transform: scale(1);
    }

    .np-lb-close,
    .np-lb-nav {
        position: absolute;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.72);
        cursor: pointer;
        transition: background .16s ease;
    }

    .np-lb-close:hover,
    .np-lb-nav:hover {
        background: rgba(255,255,255,.16);
    }

    .np-lb-close {
        top: 18px;
        right: 20px;
        width: 38px;
        height: 38px;
        border-radius: 999px;
        font-size: 16px;
    }

    .np-lb-nav {
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .np-lb-nav.prev {
        left: 18px;
    }

    .np-lb-nav.next {
        right: 18px;
    }

    .np-lb-cap {
        position: absolute;
        left: 50%;
        bottom: 18px;
        transform: translateX(-50%);
        max-width: 80vw;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        background: rgba(0,0,0,.42);
        color: rgba(255,255,255,.72);
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 12px;
    }

    @media (max-width: 980px) {
        .np-content {
            grid-template-columns: 1fr;
            gap: 26px;
        }

        .np-sidebar {
            position: static;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .np-gallery-grid,
        .np-gallery-grid.count-1,
        .np-gallery-grid.count-2,
        .np-gallery-grid.count-3,
        .np-gallery-grid.count-4,
        .np-gallery-grid.count-5 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-auto-rows: 220px;
        }

        .np-gallery-grid.count-1 .np-gitem,
        .np-gallery-grid.count-2 .np-gitem,
        .np-gallery-grid.count-3 .np-gitem,
        .np-gallery-grid.count-4 .np-gitem,
        .np-gallery-grid.count-5 .np-gitem {
            grid-column: auto !important;
            grid-row: auto !important;
        }

        .np-gallery-grid.count-1 .np-gitem,
        .np-gallery-grid.count-3 .np-gitem:nth-child(1),
        .np-gallery-grid.count-4 .np-gitem:nth-child(1),
        .np-gallery-grid.count-5 .np-gitem:nth-child(1) {
            grid-column: span 2 !important;
            min-height: 300px;
        }
    }

    @media (max-width: 680px) {
        .np-hero {
            padding: 42px 0 60px;
        }

        .np-wrap {
            padding-left: 16px;
            padding-right: 16px;
        }

        .np-hero-inner {
            padding-left: 16px;
            padding-right: 16px;
        }

        .np-featured {
            min-height: 250px;
            height: 270px;
        }

        .np-content {
            padding: 22px;
        }

        .np-sidebar {
            grid-template-columns: 1fr;
        }

        .np-gallery-grid,
        .np-gallery-grid.count-1,
        .np-gallery-grid.count-2,
        .np-gallery-grid.count-3,
        .np-gallery-grid.count-4,
        .np-gallery-grid.count-5 {
            grid-template-columns: 1fr;
            grid-auto-rows: 230px;
        }

        .np-gallery-grid.count-1 .np-gitem,
        .np-gallery-grid.count-2 .np-gitem,
        .np-gallery-grid.count-3 .np-gitem,
        .np-gallery-grid.count-4 .np-gitem,
        .np-gallery-grid.count-5 .np-gitem {
            grid-column: auto !important;
            grid-row: auto !important;
            min-height: 230px;
        }

        .np-gcap {
            transform: translateY(0);
        }

        .np-lb-nav {
            display: none;
        }
    }
</style>

<div class="np-lb" id="npLb" role="dialog" aria-modal="true" aria-label="Photo viewer">
    <button type="button" class="np-lb-close" id="npLbClose" aria-label="Close">&#x2715;</button>

    <button type="button" class="np-lb-nav prev" id="npLbPrev" aria-label="Previous">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </button>

    <img class="np-lb-img" id="npLbImg" src="" alt="">

    <button type="button" class="np-lb-nav next" id="npLbNext" aria-label="Next">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </button>

    <div class="np-lb-cap" id="npLbCap"></div>
</div>

<div class="np-page">
    <div class="np-preview-bar">
        <div class="np-preview-bar-inner">
            <div>
                <div class="np-preview-label">
                    <span class="np-preview-dot"></span>
                    <span>MODE PREVIEW NEWS</span>
                </div>
                <div class="np-preview-meta">
                    Halaman ini adalah preview internal dan belum tentu tampil untuk publik.
                </div>
            </div>

            <div class="np-preview-actions">
                @auth
                    @if(auth()->user()?->role === 'writer')
                        <a href="{{ route('writer.news.show', $news) }}" class="np-preview-btn">Kembali ke Detail Writer</a>
                        <a href="{{ route('writer.news.edit', $news) }}" class="np-preview-btn">Edit News</a>
                    @elseif(auth()->user()?->role === 'reviewer')
                        <a href="{{ route('reviewer.news.show', $news) }}" class="np-preview-btn">Kembali ke Detail Reviewer</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <section class="np-hero">
        <div class="np-hero-inner">
            <div class="np-kicker">
                <span class="np-kicker-line"></span>
                <span class="np-kicker-text">{{ $categoryName }}</span>
            </div>

            <h1 class="np-hero-title">
                {{ $translation?->title ?? 'Untitled News' }}
            </h1>

            @if($translation?->excerpt)
                <p class="np-hero-excerpt">{{ $translation->excerpt }}</p>
            @endif

            <div class="np-meta-row">
                <span class="np-pill">
                    <span class="np-pill-dot"></span>
                    {{ $publishedDate ?: '-' }}
                </span>

                <span class="np-pill">
                    {{ $news->author?->name ?? 'BSP Zapin' }}
                </span>

                <span class="np-pill">
                    {{ ucfirst(str_replace('_', ' ', $news->status)) }}
                </span>
            </div>
        </div>
    </section>

    <main class="np-wrap">
        <article class="np-main-card">
            <div class="np-featured">
                @if($news->featured_image)
                    <img src="{{ asset($news->featured_image) }}" alt="{{ $translation?->title ?? 'Featured Image' }}">
                @else
                    <div class="np-no-image">Belum ada featured image</div>
                @endif
            </div>

            <div class="np-content">
                <div class="np-article">
                    {!! $translation?->content ?: '<p>Konten belum tersedia.</p>' !!}
                </div>

                <aside class="np-sidebar">
                    <div class="np-side-card">
                        <h3 class="np-side-title">Informasi Berita</h3>

                        <div class="np-side-row">
                            <div class="np-side-label">Kategori</div>
                            <div class="np-side-value">{{ $categoryName }}</div>
                        </div>

                        <div class="np-side-row">
                            <div class="np-side-label">Tanggal</div>
                            <div class="np-side-value">{{ $publishedDate ?: '-' }}</div>
                        </div>

                        <div class="np-side-row">
                            <div class="np-side-label">Penulis</div>
                            <div class="np-side-value">{{ $news->author?->name ?? '-' }}</div>
                        </div>

                        <div class="np-side-row">
                            <div class="np-side-label">Status</div>
                            <div class="np-side-value">
                                <span class="np-status {{ $news->status }}">
                                    {{ $news->status_label ?? ucfirst(str_replace('_', ' ', $news->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="np-side-card">
                        <h3 class="np-side-title">Catatan Preview</h3>

                        <div style="font-size:13px;color:#64748b;line-height:1.8">
                            Tampilan ini mengikuti format halaman publik. Reviewer dapat mengecek susunan teks, gambar, kategori, dan informasi publikasi.
                        </div>
                    </div>
                </aside>
            </div>
        </article>

        @if($news->images->count())
            <section class="np-gallery">
                <div class="np-gallery-head">
                    <h2 class="np-gallery-title">Galeri Dokumentasi</h2>
                    <span class="np-gallery-count">{{ $news->images->count() }} gambar</span>
                </div>

                <div class="np-gallery-grid count-{{ min($news->images->count(), 5) }}">
                    @foreach($news->images as $index => $image)
                        <div
                            class="np-gitem"
                            data-src="{{ asset($image->image_path) }}"
                            data-cap="{{ $image->caption ?: ($translation?->title ?? 'News Image') }}"
                            data-idx="{{ $index }}"
                        >
                            <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?: ($translation?->title ?? 'News Image') }}" loading="lazy">

                            <div class="np-gcap">
                                {{ $image->caption ?: ($translation?->title ?? 'News Image') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</div>

<script>
(function () {
    'use strict';

    var items = Array.from(document.querySelectorAll('.np-gitem'));
    var lb = document.getElementById('npLb');
    var lbImg = document.getElementById('npLbImg');
    var lbCap = document.getElementById('npLbCap');
    var lbClose = document.getElementById('npLbClose');
    var lbPrev = document.getElementById('npLbPrev');
    var lbNext = document.getElementById('npLbNext');
    var lbCur = 0;

    function setLb(item) {
        if (!item || !lbImg) return;

        lbImg.style.opacity = '0';

        setTimeout(function () {
            lbImg.src = item.getAttribute('data-src') || '';
            lbImg.alt = item.getAttribute('data-cap') || '';

            if (lbCap) {
                lbCap.textContent = item.getAttribute('data-cap') || '';
            }

            lbImg.style.opacity = '1';
        }, 130);
    }

    function openLb(index) {
        if (!lb || !items.length) return;

        lbCur = index;
        setLb(items[lbCur]);
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLb() {
        if (!lb) return;

        lb.classList.remove('is-open');
        document.body.style.overflow = '';

        setTimeout(function () {
            if (lbImg) lbImg.src = '';
        }, 240);
    }

    function navLb(direction) {
        if (!items.length) return;

        lbCur = (lbCur + direction + items.length) % items.length;
        setLb(items[lbCur]);
    }

    items.forEach(function (item, index) {
        item.addEventListener('click', function () {
            openLb(index);
        });
    });

    if (lbClose) {
        lbClose.addEventListener('click', closeLb);
    }

    if (lb) {
        lb.addEventListener('click', function (event) {
            if (event.target === lb) {
                closeLb();
            }
        });
    }

    if (lbPrev) {
        lbPrev.addEventListener('click', function () {
            navLb(-1);
        });
    }

    if (lbNext) {
        lbNext.addEventListener('click', function () {
            navLb(1);
        });
    }

    document.addEventListener('keydown', function (event) {
        if (!lb || !lb.classList.contains('is-open')) return;

        if (event.key === 'Escape') closeLb();
        if (event.key === 'ArrowLeft') navLb(-1);
        if (event.key === 'ArrowRight') navLb(1);
    });
})();
</script>
@endsection