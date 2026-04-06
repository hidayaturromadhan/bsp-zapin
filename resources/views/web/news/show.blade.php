@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');
@endphp

<style>
    .news-shell {
        max-width: 980px;
        margin: 0 auto;
    }

    .news-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
        font-size: 13px;
        color: #6b7280;
    }

    .news-breadcrumb a {
        color: #2f7d32;
        text-decoration: none;
    }

    .news-breadcrumb a:hover {
        color: #173f08;
    }

    .news-breadcrumb-sep {
        color: #9ca3af;
    }

    .news-head {
        margin-bottom: 24px;
    }

    .news-title {
        margin: 0 0 12px;
        font-size: clamp(32px, 4vw, 44px);
        line-height: 1.14;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #111827;
    }

    .news-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 18px;
    }

    .news-category {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        background: #eef5eb;
        color: #21560e;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: .02em;
    }

    .news-cover {
        margin-bottom: 24px;
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #fff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, .08);
    }

    .news-cover img {
        width: 100%;
        max-height: 460px;
        object-fit: cover;
        display: block;
    }

    .news-excerpt {
        margin: 0 0 22px;
        font-size: 17px;
        line-height: 1.85;
        color: #4b5563;
        padding: 18px 20px;
        border-radius: 18px;
        background: #f8fbf7;
        border: 1px solid #e5eee2;
    }

    .news-body {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 32px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
        color: #374151;
        font-size: 15px;
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
        line-height: 1.3;
        margin-top: 1.8em;
        margin-bottom: .7em;
        font-weight: 700;
    }

    .news-body p {
        margin: 0 0 1.15em;
    }

    .news-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }

    .news-body figure {
        margin: 1.5em 0;
    }

    .news-body figcaption {
        margin-top: 8px;
        font-size: 13px;
        color: #6b7280;
        text-align: center;
    }

    .news-empty {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 24px;
        color: #6b7280;
    }

    @media (max-width: 640px) {
        .news-title {
            font-size: 28px;
        }

        .news-body {
            padding: 22px;
            border-radius: 16px;
        }

        .news-excerpt {
            font-size: 15px;
            padding: 16px;
        }
    }
</style>

<div class="news-shell">
    <nav class="news-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('web.home', ['locale' => $locale]) }}">
            {{ $locale === 'id' ? 'Beranda' : 'Home' }}
        </a>

        <span class="news-breadcrumb-sep">/</span>

        <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}">
            {{ $locale === 'id' ? 'Media Publikasi' : 'Media Publications' }}
        </a>

        @if(!empty($translation?->title))
            <span class="news-breadcrumb-sep">/</span>
            <span>{{ $translation->title }}</span>
        @endif
    </nav>

    <header class="news-head">
        <h1 class="news-title">
            {{ $translation?->title ?? ($locale === 'id' ? 'Detail Berita' : 'News Detail') }}
        </h1>

        <div class="news-meta">
            @if($news->category?->name)
                <span class="news-category">{{ $news->category->name }}</span>
            @endif

            @if($news->published_at)
                <span>{{ $news->published_at->format('d M Y') }}</span>
            @endif
        </div>
    </header>

    @if($news->featured_image)
        <div class="news-cover">
            <img
                src="{{ asset($news->featured_image) }}"
                alt="{{ $translation?->title ?? 'News image' }}"
                onerror="this.style.display='none';"
            >
        </div>
    @endif

    @if(!empty($translation?->excerpt))
        <p class="news-excerpt">{{ $translation->excerpt }}</p>
    @endif

    @if($translation && !empty($translation->content))
        <article class="news-body">
            {!! $translation->content !!}
        </article>
    @else
        <section class="news-empty">
            {{ $locale === 'id'
                ? 'Konten berita belum tersedia.'
                : 'News content is not available yet.' }}
        </section>
    @endif
</div>
@endsection