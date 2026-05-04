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

.news-breadcrumb a:hover {
    color: #fff;
}

.news-breadcrumb-sep {
    color: rgba(255,255,255,.4);
}

.news-head {
    max-width: 900px;
}

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

.news-content-wrap {
    margin-top: -74px;
    padding-bottom: 74px;
    position: relative;
    z-index: 5;
}

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
    max-height: 560px;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    display: block;
}

.news-cover-empty {
    width: 100%;
    min-height: 360px;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(circle at 20% 20%, rgba(23,63,8,.12), transparent 35%),
        #eef5eb;
    color: #173f08;
    font-weight: 900;
}

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

.news-body > *:first-child {
    margin-top: 0 !important;
}

.news-body > *:last-child {
    margin-bottom: 0 !important;
}

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

.news-body li {
    margin-bottom: .65em;
}

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

.news-body figure {
    margin: 2em 0;
}

.news-body figure img {
    width: 100%;
    max-height: 520px;
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

.news-gallery-section {
    margin-top: 28px;
}

.news-gallery-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 26px;
    padding: clamp(20px, 3vw, 30px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
}

.news-gallery-head {
    margin-bottom: 18px;
}

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
    height: 270px;
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
    min-height: 46px;
    padding: 0 18px;
    border-radius: 999px;
    background: #173f08;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 900;
}

.news-back-btn:hover {
    background: #21560e;
    color: #fff;
}

@media (max-width: 768px) {
    .news-gallery-grid {
        grid-template-columns: 1fr;
    }

    .news-gallery-image {
        height: 220px;
    }

    .news-cover img {
        max-height: 380px;
    }
}

@media (max-width: 640px) {
    .news-shell {
        padding: 0 16px;
    }

    .news-hero {
        padding: 32px 0 92px;
    }

    .news-title {
        font-size: 30px;
    }

    .news-excerpt {
        font-size: 15px;
        padding: 16px;
    }

    .news-body h1 { font-size: 28px; }
    .news-body h2 { font-size: 24px; }
    .news-body h3 { font-size: 20px; }

    .news-gallery-title {
        font-size: 20px;
    }
}
</style>

<div class="news-page">
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
                            {{ $news->published_at->format('d M Y H:i') }}
                        </time>
                    @endif

                    @if($news->updated_at)
                        <span class="news-meta-item">
                            {{ $locale === 'id' ? 'Diperbarui' : 'Updated' }}:
                            {{ $news->updated_at->format('d M Y H:i') }}
                        </span>
                    @endif
                </div>
            </header>
        </div>
    </section>

    <section class="news-content-wrap">
        <div class="news-shell">
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

            @if($excerpt)
                <p class="news-excerpt">{{ $excerpt }}</p>
            @endif

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

            <div class="news-back-section">
                <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="news-back-btn">
                    {{ $locale === 'id' ? 'Kembali ke Media Publikasi' : 'Back to Media Publications' }}
                </a>
            </div>
        </div>
    </section>
</div>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $title,
    'description' => $metaDescription ?? ($excerpt ?: Str::limit(strip_tags($content), 160)),
    'image' => [$coverImage],
    'datePublished' => $news->published_at?->toIso8601String(),
    'dateModified' => $news->updated_at?->toIso8601String(),
    'author' => [
        '@type' => 'Organization',
        'name' => 'PT Bumi Siak Pusako Zapin',
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'PT Bumi Siak Pusako Zapin',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png'),
        ],
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => $currentUrl,
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endsection