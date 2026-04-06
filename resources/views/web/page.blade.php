@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');
@endphp

<style>
    .page-shell {
        max-width: 980px;
        margin: 0 auto;
    }

    .page-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
        font-size: 13px;
        color: #6b7280;
    }

    .page-breadcrumb a {
        color: #2f7d32;
        text-decoration: none;
    }

    .page-breadcrumb a:hover {
        color: #173f08;
    }

    .page-breadcrumb-sep {
        color: #9ca3af;
    }

    .page-cover {
        margin-bottom: 24px;
        overflow: hidden;
        border-radius: 22px;
        border: 1px solid #e5e7eb;
        background: #fff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, .08);
    }

    .page-cover img {
        width: 100%;
        max-height: 430px;
        object-fit: cover;
        display: block;
    }

    .page-head {
        margin-bottom: 22px;
    }

    .page-title {
        margin: 0 0 10px;
        font-size: clamp(32px, 4vw, 44px);
        line-height: 1.12;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #111827;
    }

    .page-body {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        padding: 32px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, .05);
        color: #374151;
        line-height: 1.9;
        font-size: 15px;
    }

    .page-body > *:first-child {
        margin-top: 0 !important;
    }

    .page-body > *:last-child {
        margin-bottom: 0 !important;
    }

    .page-body h1,
    .page-body h2,
    .page-body h3,
    .page-body h4,
    .page-body h5,
    .page-body h6 {
        color: #111827;
        line-height: 1.3;
        margin-top: 1.5em;
        margin-bottom: .6em;
        font-weight: 700;
    }

    .page-body p {
        margin: 0 0 1em;
    }

    .page-body ul,
    .page-body ol {
        margin: 0 0 1.2em 1.3em;
        padding: 0;
    }

    .page-body li {
        margin-bottom: .45em;
    }

    .page-body a {
        color: #21560e;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .page-body a:hover {
        color: #173f08;
    }

    .page-body blockquote {
        margin: 1.4em 0;
        padding: 14px 18px;
        border-left: 4px solid #2f7d32;
        background: #f6faf4;
        color: #374151;
        border-radius: 0 12px 12px 0;
    }

    .page-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }

    .page-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.4em 0;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .page-body table th,
    .page-body table td {
        border: 1px solid #e5e7eb;
        padding: 12px 14px;
        text-align: left;
        vertical-align: top;
    }

    .page-body table th {
        background: #f8fafc;
        color: #111827;
        font-weight: 700;
    }

    .page-empty {
        padding: 30px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        color: #6b7280;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .04);
    }

    @media (max-width: 640px) {
        .page-body {
            padding: 22px;
            border-radius: 16px;
        }

        .page-title {
            font-size: 28px;
        }
    }
</style>

<div class="page-shell">
    <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('web.home', ['locale' => $locale]) }}">
            {{ $locale === 'id' ? 'Beranda' : 'Home' }}
        </a>

        <span class="page-breadcrumb-sep">/</span>

        <span>{{ $translation?->title ?? ($locale === 'id' ? 'Halaman' : 'Page') }}</span>
    </nav>

    @if($page->cover_image)
        <div class="page-cover">
            <img
                src="{{ asset($page->cover_image) }}"
                alt="{{ $translation?->title ?? 'Page cover' }}"
                onerror="this.style.display='none';"
            >
        </div>
    @endif

    <header class="page-head">
        <h1 class="page-title">
            {{ $translation?->title ?? ($locale === 'id' ? 'Halaman' : 'Page') }}
        </h1>
    </header>

    @if($translation && !empty($translation->content))
        <article class="page-body">
            {!! $translation->content !!}
        </article>
    @else
        <section class="page-empty">
            {{ $locale === 'id'
                ? 'Konten halaman belum tersedia.'
                : 'Page content is not available yet.' }}
        </section>
    @endif
</div>
@endsection