@extends('layouts.app')

@section('title', $metaTitle ?? ($locale === 'id' ? 'Media & Publikasi' : 'Media & Publications'))

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');

    $pageTitle = $locale === 'id' ? 'Media & Publikasi' : 'Media & Publications';
    $pageDesc = $locale === 'id'
        ? 'Temukan berita terbaru, publikasi resmi, dan berbagai informasi perusahaan yang telah dipublikasikan untuk masyarakat dan para pemangku kepentingan.'
        : 'Discover the latest news, official publications, and company updates published for the public and stakeholders.';

    $currentUrl = request()->fullUrl();

    $sortOptions = [
        'latest' => $locale === 'id' ? 'Terbaru' : 'Latest',
        'oldest' => $locale === 'id' ? 'Terlama' : 'Oldest',
    ];

    $selectedSortLabel = $sortOptions[$sort] ?? $sortOptions['latest'];

    $selectedYearLabel = $year !== ''
        ? (string) $year
        : ($locale === 'id' ? 'Semua Tahun' : 'All Years');
@endphp

@push('meta')
    <link rel="canonical" href="{{ $currentUrl }}">
    <meta name="description" content="{{ $metaDescription ?? $pageDesc }}">
    <meta property="og:title" content="{{ $metaTitle ?? $pageTitle . ' - BSP Zapin' }}">
    <meta property="og:description" content="{{ $metaDescription ?? $pageDesc }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:image" content="{{ $metaImage ?? asset('images/logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
@endpush

<style>
.n-main {
    max-width: none !important;
    width: 100% !important;
    padding: 0 !important;
}

.media-page {
    width: 100%;
    background: #f8fafc;
    color: #111827;
}

.media-hero {
    background:
        radial-gradient(circle at 8% 18%, rgba(255,255,255,.16), transparent 28%),
        radial-gradient(circle at 88% 20%, rgba(255,214,130,.18), transparent 28%),
        linear-gradient(135deg, #102d06 0%, #173f08 52%, #21560e 100%);
    padding: 58px 0 64px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.media-hero::after {
    content: "";
    position: absolute;
    inset: auto -80px -160px auto;
    width: 360px;
    height: 360px;
    border-radius: 999px;
    background: rgba(255,255,255,.06);
}

.media-shell {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 2;
}

.media-breadcrumb {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
    margin-bottom: 18px;
    font-size: 13px;
    color: rgba(255,255,255,.65);
}

.media-breadcrumb a {
    color: rgba(255,255,255,.78);
    text-decoration: none;
    font-weight: 700;
}

.media-breadcrumb a:hover {
    color: #fff;
}

.media-breadcrumb-sep {
    color: rgba(255,255,255,.4);
}

.media-kicker {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 34px;
    padding: 0 13px;
    border-radius: 999px;
    background: rgba(255,255,255,.09);
    border: 1px solid rgba(255,255,255,.12);
    color: #f6d28b;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.media-kicker-dot {
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: #f6d28b;
    box-shadow: 0 0 0 4px rgba(246,210,139,.13);
}

.media-title-main {
    font-size: clamp(34px, 5vw, 58px);
    line-height: 1.04;
    font-weight: 900;
    letter-spacing: -.055em;
    margin: 0 0 16px;
    max-width: 780px;
}

.media-title-main span {
    color: #f6d28b;
}

.media-desc-main {
    font-size: 15px;
    line-height: 1.9;
    color: rgba(255,255,255,.72);
    margin: 0;
    max-width: 760px;
}

.media-content {
    padding: 34px 0 70px;
}

.media-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 360px;
    gap: 28px;
    align-items: start;
}

.media-main {
    min-width: 0;
}

.media-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.media-result {
    font-size: 14px;
    color: #64748b;
    line-height: 1.7;
}

.media-result strong {
    color: #111827;
    font-weight: 900;
}

.media-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
}

.media-card {
    background: #fff;
    border-radius: 22px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}

.media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 18px 38px rgba(15, 23, 42, .10);
    border-color: #cddfc9;
}

.media-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.media-thumb {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: #edf2f7;
}

.media-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .42s ease;
}

.media-card:hover .media-thumb img {
    transform: scale(1.045);
}

.media-thumb-empty {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(circle at 20% 20%, rgba(23,63,8,.12), transparent 35%),
        #eef5eb;
    color: #173f08;
    font-size: 13px;
    font-weight: 900;
}

.media-card-body {
    padding: 18px;
}

.media-meta {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
    margin-bottom: 10px;
    font-size: 12px;
    color: #64748b;
}

.media-category {
    display: inline-flex;
    align-items: center;
    min-height: 26px;
    padding: 0 10px;
    border-radius: 999px;
    background: #eef5eb;
    color: #21560e;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .03em;
}

.media-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.media-title {
    font-size: 19px;
    font-weight: 900;
    line-height: 1.45;
    color: #111827;
    margin: 0 0 9px;
    letter-spacing: -.025em;
    transition: color .18s ease;
}

.media-card-link:hover .media-title {
    color: #173f08;
}

.media-excerpt {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.8;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Sidebar */
.media-sidebar {
    position: sticky;
    top: calc(var(--nav-h, 78px) + 18px);
}

.sidebar-card {
    background: #fff;
    border-radius: 24px;
    border: 1px solid #e5e7eb;
    padding: 22px;
    margin-bottom: 18px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
}

.sidebar-title {
    font-size: 20px;
    font-weight: 900;
    color: #111827;
    margin: 0 0 16px;
    letter-spacing: -.035em;
}

.sidebar-form {
    display: grid;
    gap: 12px;
}

.sidebar-field {
    position: relative;
}

.sidebar-input {
    width: 100%;
    height: 54px;
    border-radius: 16px;
    border: 1px solid #d7dee8;
    padding: 0 16px;
    box-sizing: border-box;
    font: inherit;
    font-size: 15px;
    color: #111827;
    background: #fff;
    outline: none;
    transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
}

.sidebar-input::placeholder {
    color: #8b95a7;
}

.sidebar-input:focus {
    border-color: #173f08;
    background: #fbfdfb;
    box-shadow: 0 0 0 5px rgba(23,63,8,.09);
}

/* Custom Select */
.media-select {
    position: relative;
    width: 100%;
}

.media-select-button {
    width: 100%;
    height: 54px;
    border-radius: 16px;
    border: 1px solid #d7dee8;
    background: #fff;
    color: #111827;
    padding: 0 48px 0 16px;
    text-align: left;
    font: inherit;
    font-size: 15px;
    font-weight: 650;
    cursor: pointer;
    outline: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
}

.media-select-button:hover {
    border-color: #b9c6d8;
    background: #fbfdfb;
}

.media-select-button:focus,
.media-select.is-open .media-select-button {
    border-color: #173f08;
    background: #fbfdfb;
    box-shadow: 0 0 0 5px rgba(23,63,8,.09);
}

.media-select-value {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.media-select-icon {
    position: absolute;
    right: 16px;
    top: 50%;
    width: 18px;
    height: 18px;
    transform: translateY(-50%);
    color: #334155;
    pointer-events: none;
    transition: transform .18s ease, color .18s ease;
}

.media-select.is-open .media-select-icon {
    transform: translateY(-50%) rotate(180deg);
    color: #173f08;
}

.media-select-menu {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 8px);
    z-index: 80;
    background: #fff;
    border: 1px solid #dce4ee;
    border-radius: 18px;
    padding: 7px;
    box-shadow: 0 18px 44px rgba(15, 23, 42, .16);
    display: none;
    max-height: 240px;
    overflow-y: auto;
}

.media-select.is-open .media-select-menu {
    display: block;
    animation: mediaSelectIn .16s ease both;
}

@keyframes mediaSelectIn {
    from {
        opacity: 0;
        transform: translateY(-4px) scale(.985);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.media-select-option {
    width: 100%;
    min-height: 42px;
    border: none;
    border-radius: 12px;
    background: transparent;
    color: #111827;
    padding: 0 12px;
    text-align: left;
    font: inherit;
    font-size: 14px;
    font-weight: 750;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    transition: background .14s ease, color .14s ease;
}

.media-select-option:hover {
    background: #eef6eb;
    color: #173f08;
}

.media-select-option.is-active {
    background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
    color: #fff;
}

.media-select-check {
    width: 15px;
    height: 15px;
    opacity: 0;
    flex-shrink: 0;
}

.media-select-option.is-active .media-select-check {
    opacity: 1;
}

.media-select-menu::-webkit-scrollbar {
    width: 8px;
}

.media-select-menu::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 999px;
}

.sidebar-btn,
.sidebar-reset {
    width: 100%;
    height: 50px;
    border-radius: 16px;
    font-weight: 900;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    transition: transform .16s ease, background .16s ease, border-color .16s ease, box-shadow .16s ease;
}

.sidebar-btn {
    background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
    color: #fff;
    box-shadow: 0 12px 22px rgba(23, 63, 8, .18);
}

.sidebar-btn:hover {
    transform: translateY(-1px);
    background: linear-gradient(135deg, #102d06 0%, #173f08 100%);
    box-shadow: 0 16px 26px rgba(23, 63, 8, .24);
}

.sidebar-reset {
    background: #fff;
    color: #111827;
    border: 1px solid #d7dee8;
}

.sidebar-reset:hover {
    transform: translateY(-1px);
    border-color: #173f08;
    color: #173f08;
    background: #fbfdfb;
}

.recent-list {
    display: grid;
    gap: 14px;
}

.recent-item {
    display: grid;
    grid-template-columns: 64px minmax(0, 1fr);
    gap: 12px;
    text-decoration: none;
    color: inherit;
}

.recent-thumb,
.recent-thumb-empty {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    object-fit: cover;
    flex-shrink: 0;
    display: block;
    background: #eef2f7;
}

.recent-thumb-empty {
    border: 1px solid #e5e7eb;
}

.recent-title {
    font-size: 14px;
    font-weight: 900;
    line-height: 1.45;
    color: #111827;
    margin-bottom: 5px;
    transition: color .18s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.recent-item:hover .recent-title {
    color: #173f08;
}

.recent-date {
    font-size: 12px;
    color: #64748b;
}

.media-empty {
    padding: 54px 24px;
    text-align: center;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
}

.media-empty-icon {
    width: 74px;
    height: 74px;
    margin: 0 auto 16px;
    border-radius: 22px;
    background: #eef5eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #173f08;
}

.media-empty-title {
    margin: 0 0 8px;
    font-size: 22px;
    font-weight: 900;
    color: #111827;
}

.media-empty-text {
    margin: 0;
    font-size: 14px;
    color: #64748b;
    line-height: 1.7;
}

/* Pagination */
.media-pagination {
    margin-top: 38px;
    margin-bottom: 10px;
    width: 100%;
}

.media-pagination-nav {
    width: 100%;
    display: flex;
    justify-content: center;
}

.media-pagination-card {
    width: fit-content;
    max-width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    padding: 12px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, .96);
    border: 1px solid #e5e7eb;
    box-shadow: 0 14px 34px rgba(15, 23, 42, .08);
}

.media-pagination-info {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 4px 0 8px;
    white-space: nowrap;
    font-size: 13px;
    line-height: 1.4;
}

.media-pagination-info-main {
    color: #111827;
    font-weight: 900;
}

.media-pagination-info-sub {
    color: #64748b;
    font-weight: 700;
}

.media-pagination-list {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    flex-wrap: nowrap;
}

.media-page-btn,
.media-page-dots {
    width: 42px;
    height: 42px;
    min-width: 42px;
    padding: 0;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    font-size: 14px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    line-height: 1;
    transition:
        transform .16s ease,
        background .16s ease,
        border-color .16s ease,
        color .16s ease,
        box-shadow .16s ease;
}

.media-page-btn:hover {
    transform: translateY(-2px);
    border-color: #173f08;
    background: #eef6eb;
    color: #173f08;
    box-shadow: 0 10px 20px rgba(23, 63, 8, .12);
}

.media-page-btn--active {
    background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
    border-color: #173f08;
    color: #ffffff;
    box-shadow: 0 10px 22px rgba(23, 63, 8, .24);
    cursor: default;
}

.media-page-btn--active:hover {
    transform: none;
    background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
    color: #ffffff;
    box-shadow: 0 10px 22px rgba(23, 63, 8, .24);
}

.media-page-btn--disabled {
    opacity: .45;
    cursor: not-allowed;
    background: #f8fafc;
    color: #94a3b8;
    box-shadow: none;
}

.media-page-btn--disabled:hover {
    transform: none;
    border-color: #e2e8f0;
    background: #f8fafc;
    color: #94a3b8;
    box-shadow: none;
}

.media-page-dots {
    border-color: transparent;
    background: transparent;
    color: #94a3b8;
    box-shadow: none;
}

/* Responsive */
@media (max-width: 1080px) {
    .media-layout {
        grid-template-columns: 1fr;
    }

    .media-sidebar {
        position: static;
    }

    .media-sidebar {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .sidebar-card {
        margin-bottom: 0;
    }
}

@media (max-width: 820px) {
    .media-sidebar {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 760px) {
    .media-pagination-card {
        width: 100%;
        border-radius: 22px;
        flex-direction: column;
        gap: 12px;
        padding: 14px;
    }

    .media-pagination-info {
        padding: 0;
        white-space: normal;
        justify-content: center;
        text-align: center;
        flex-wrap: wrap;
    }

    .media-pagination-list {
        flex-wrap: wrap;
        gap: 7px;
    }

    .media-page-btn,
    .media-page-dots {
        width: 39px;
        height: 39px;
        min-width: 39px;
        font-size: 13px;
    }
}

@media (max-width: 720px) {
    .media-shell {
        padding: 0 16px;
    }

    .media-hero {
        padding: 42px 0 48px;
    }

    .media-content {
        padding: 24px 0 54px;
    }

    .media-grid {
        grid-template-columns: 1fr;
    }

    .media-card {
        border-radius: 20px;
    }

    .media-card-body {
        padding: 16px;
    }

    .media-title {
        font-size: 17px;
    }

    .sidebar-card {
        border-radius: 22px;
        padding: 18px;
    }

    .sidebar-title {
        font-size: 19px;
    }

    .sidebar-input,
    .media-select-button {
        height: 52px;
        border-radius: 15px;
        font-size: 14px;
    }

    .media-select-menu {
        position: fixed;
        left: 16px;
        right: 16px;
        top: auto;
        bottom: 16px;
        max-height: 45vh;
        border-radius: 22px;
        padding: 10px;
        box-shadow: 0 -14px 45px rgba(15, 23, 42, .22);
    }

    .media-select.is-open::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: 70;
        background: rgba(15, 23, 42, .35);
    }

    .media-select.is-open .media-select-menu {
        z-index: 90;
    }

    .media-select-option {
        min-height: 46px;
        border-radius: 14px;
    }

    .recent-item {
        grid-template-columns: 58px minmax(0, 1fr);
    }

    .recent-thumb,
    .recent-thumb-empty {
        width: 58px;
        height: 58px;
        border-radius: 15px;
    }
}

@media (max-width: 420px) {
    .media-page-btn,
    .media-page-dots {
        width: 36px;
        height: 36px;
        min-width: 36px;
        font-size: 12px;
    }

    .media-title-main {
        font-size: 32px;
    }
}
</style>

<div class="media-page">
    <section class="media-hero">
        <div class="media-shell">
            <nav class="media-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('web.home', ['locale' => $locale]) }}">
                    {{ $locale === 'id' ? 'Beranda' : 'Home' }}
                </a>
                <span class="media-breadcrumb-sep">/</span>
                <span>{{ $pageTitle }}</span>
            </nav>

            <div class="media-kicker">
                <span class="media-kicker-dot"></span>
                {{ $locale === 'id' ? 'Informasi Perusahaan' : 'Company Updates' }}
            </div>

            <h1 class="media-title-main">
                {{ $locale === 'id' ? 'Media & ' : 'Media & ' }}<span>{{ $locale === 'id' ? 'Publikasi' : 'Publications' }}</span>
            </h1>

            <p class="media-desc-main">{{ $pageDesc }}</p>
        </div>
    </section>

    <section class="media-content">
        <div class="media-shell">
            <div class="media-layout">
                <main class="media-main">
                    <div class="media-toolbar">
                        <div class="media-result">
                            @if($news->total() > 0)
                                {!! $locale === 'id'
                                    ? 'Menampilkan <strong>' . $news->firstItem() . '</strong> - <strong>' . $news->lastItem() . '</strong> dari <strong>' . $news->total() . '</strong> publikasi.'
                                    : 'Showing <strong>' . $news->firstItem() . '</strong> - <strong>' . $news->lastItem() . '</strong> of <strong>' . $news->total() . '</strong> publications.' !!}
                            @else
                                {{ $locale === 'id' ? 'Belum ada publikasi yang tersedia.' : 'No publications are available yet.' }}
                            @endif
                        </div>
                    </div>

                    @if($news->count())
                        <div class="media-grid">
                            @foreach($news as $n)
                                @php
                                    $t = method_exists($n, 'getTranslationByLocale')
                                        ? $n->getTranslationByLocale($locale)
                                        : ($n->translations->firstWhere('locale', $locale) ?? $n->translations->firstWhere('locale', 'id'));

                                    $title = $t?->title ?? ($locale === 'id' ? 'Berita' : 'News');
                                    $excerpt = $t?->excerpt ?? '';
                                    $slug = $t?->slug ?? null;
                                @endphp

                                @if($slug)
                                    <article class="media-card" itemscope itemtype="https://schema.org/NewsArticle">
                                        <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $slug]) }}" class="media-card-link" itemprop="url">
                                            <div class="media-thumb">
                                                @if($n->featured_image)
                                                    <img
                                                        src="{{ asset($n->featured_image) }}"
                                                        alt="{{ $title }}"
                                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                                        decoding="async"
                                                        itemprop="image"
                                                    >
                                                @else
                                                    <div class="media-thumb-empty">
                                                        {{ $locale === 'id' ? 'Tanpa Gambar' : 'No Image' }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="media-card-body">
                                                <div class="media-meta">
                                                    @if($n->category?->name)
                                                        <span class="media-category">{{ $n->category->name }}</span>
                                                    @endif

                                                    @if($n->published_at)
                                                        <time class="media-date" datetime="{{ $n->published_at->toIso8601String() }}" itemprop="datePublished">
                                                            {{ $n->published_at->format('d M Y') }}
                                                        </time>
                                                    @endif
                                                </div>

                                                <h2 class="media-title" itemprop="headline">{{ $title }}</h2>

                                                @if($excerpt)
                                                    <p class="media-excerpt" itemprop="description">{{ $excerpt }}</p>
                                                @endif
                                            </div>
                                        </a>
                                    </article>
                                @endif
                            @endforeach
                        </div>

                        <div class="media-pagination">
                            {{ $news->links('vendor.pagination.media') }}
                        </div>
                    @else
                        <section class="media-empty">
                            <div class="media-empty-icon">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                    <path d="M8 7h8"/>
                                    <path d="M8 11h8"/>
                                    <path d="M8 15h5"/>
                                </svg>
                            </div>

                            <h2 class="media-empty-title">
                                {{ $locale === 'id' ? 'Belum ada berita' : 'No news yet' }}
                            </h2>

                            <p class="media-empty-text">
                                {{ $locale === 'id'
                                    ? 'Belum ada berita atau hasil filter belum menemukan data.'
                                    : 'There are no news items available or your filters returned no data.' }}
                            </p>
                        </section>
                    @endif
                </main>

                <aside class="media-sidebar">
                    <div class="sidebar-card">
                        <h2 class="sidebar-title">
                            {{ $locale === 'id' ? 'Pencarian & Filter' : 'Search & Filter' }}
                        </h2>

                        <form method="GET" action="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="sidebar-form" id="mediaFilterForm">
                            <div class="sidebar-field">
                                <input
                                    name="q"
                                    value="{{ $q }}"
                                    class="sidebar-input"
                                    placeholder="{{ $locale === 'id' ? 'Cari berita...' : 'Search news...' }}"
                                    aria-label="{{ $locale === 'id' ? 'Cari berita' : 'Search news' }}"
                                >
                            </div>

                            <div class="sidebar-field">
                                <input type="hidden" name="year" value="{{ $year }}" data-custom-select-input="year">

                                <div class="media-select" data-custom-select="year">
                                    <button type="button" class="media-select-button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="media-select-value">{{ $selectedYearLabel }}</span>
                                        <svg class="media-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>

                                    <div class="media-select-menu" role="listbox">
                                        <button type="button"
                                                class="media-select-option {{ $year === '' ? 'is-active' : '' }}"
                                                data-value=""
                                                data-label="{{ $locale === 'id' ? 'Semua Tahun' : 'All Years' }}">
                                            <span>{{ $locale === 'id' ? 'Semua Tahun' : 'All Years' }}</span>
                                            <svg class="media-select-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </button>

                                        @foreach($years as $y)
                                            <button type="button"
                                                    class="media-select-option {{ (string) $year === (string) $y ? 'is-active' : '' }}"
                                                    data-value="{{ $y }}"
                                                    data-label="{{ $y }}">
                                                <span>{{ $y }}</span>
                                                <svg class="media-select-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="sidebar-field">
                                <input type="hidden" name="sort" value="{{ $sort }}" data-custom-select-input="sort">

                                <div class="media-select" data-custom-select="sort">
                                    <button type="button" class="media-select-button" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="media-select-value">{{ $selectedSortLabel }}</span>
                                        <svg class="media-select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </button>

                                    <div class="media-select-menu" role="listbox">
                                        @foreach($sortOptions as $value => $label)
                                            <button type="button"
                                                    class="media-select-option {{ $sort === $value ? 'is-active' : '' }}"
                                                    data-value="{{ $value }}"
                                                    data-label="{{ $label }}">
                                                <span>{{ $label }}</span>
                                                <svg class="media-select-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="sidebar-btn">
                                {{ $locale === 'id' ? 'Terapkan Filter' : 'Apply Filter' }}
                            </button>

                            <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="sidebar-reset">
                                {{ $locale === 'id' ? 'Reset Filter' : 'Reset Filter' }}
                            </a>
                        </form>
                    </div>

                    <div class="sidebar-card">
                        <h2 class="sidebar-title">
                            {{ $locale === 'id' ? 'Postingan Terbaru' : 'Recent Posts' }}
                        </h2>

                        @if($recentPosts->count())
                            <div class="recent-list">
                                @foreach($recentPosts as $p)
                                    @php
                                        $t = method_exists($p, 'getTranslationByLocale')
                                            ? $p->getTranslationByLocale($locale)
                                            : ($p->translations->firstWhere('locale', $locale) ?? $p->translations->firstWhere('locale', 'id'));

                                        $title = $t?->title ?? ($locale === 'id' ? 'Berita' : 'News');
                                        $slug = $t?->slug ?? null;
                                    @endphp

                                    @if($slug)
                                        <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $slug]) }}" class="recent-item">
                                            @if($p->featured_image)
                                                <img
                                                    src="{{ asset($p->featured_image) }}"
                                                    alt="{{ $title }}"
                                                    class="recent-thumb"
                                                    loading="lazy"
                                                    decoding="async"
                                                >
                                            @else
                                                <div class="recent-thumb-empty"></div>
                                            @endif

                                            <div>
                                                <div class="recent-title">{{ $title }}</div>

                                                @if($p->published_at)
                                                    <time class="recent-date" datetime="{{ $p->published_at->toIso8601String() }}">
                                                        {{ $p->published_at->format('d M Y') }}
                                                    </time>
                                                @endif
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="media-empty-text">
                                {{ $locale === 'id' ? 'Belum ada postingan terbaru.' : 'No recent posts yet.' }}
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selects = Array.from(document.querySelectorAll('[data-custom-select]'));

    function closeAll(except = null) {
        selects.forEach(function (select) {
            if (select === except) return;

            select.classList.remove('is-open');

            const button = select.querySelector('.media-select-button');
            if (button) {
                button.setAttribute('aria-expanded', 'false');
            }
        });
    }

    selects.forEach(function (select) {
        const name = select.getAttribute('data-custom-select');
        const button = select.querySelector('.media-select-button');
        const valueText = select.querySelector('.media-select-value');
        const options = Array.from(select.querySelectorAll('.media-select-option'));
        const input = document.querySelector('[data-custom-select-input="' + name + '"]');

        if (!button || !valueText || !input) return;

        button.addEventListener('click', function (event) {
            event.stopPropagation();

            const willOpen = !select.classList.contains('is-open');

            closeAll(select);

            select.classList.toggle('is-open', willOpen);
            button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        });

        options.forEach(function (option) {
            option.addEventListener('click', function (event) {
                event.stopPropagation();

                const value = option.getAttribute('data-value') || '';
                const label = option.getAttribute('data-label') || option.textContent.trim();

                input.value = value;
                valueText.textContent = label;

                options.forEach(function (item) {
                    item.classList.remove('is-active');
                });

                option.classList.add('is-active');
                select.classList.remove('is-open');
                button.setAttribute('aria-expanded', 'false');
            });
        });
    });

    document.addEventListener('click', function () {
        closeAll();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAll();
        }
    });
});
</script>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $metaTitle ?? $pageTitle . ' - BSP Zapin',
    'description' => $metaDescription ?? $pageDesc,
    'url' => $currentUrl,
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'PT Bumi Siak Pusako Zapin',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png'),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection