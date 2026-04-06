@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id','en']) ? request()->segment(1) : 'id');
@endphp

<style>
    .profil-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    .profil-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 18px;
        font-size: 13px;
        color: #6b7280;
    }

    .profil-breadcrumb a {
        color: #2f7d32;
        text-decoration: none;
        transition: color .14s ease;
    }

    .profil-breadcrumb a:hover {
        color: #173f08;
    }

    .profil-breadcrumb-sep {
        color: #9ca3af;
    }

    .profil-wrap {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 28px;
        align-items: start;
    }

    .profil-content {
        min-width: 0;
    }

    .profil-hero {
        margin-bottom: 24px;
    }

    .profil-title {
        margin: 0 0 18px;
        font-size: clamp(30px, 4vw, 42px);
        line-height: 1.15;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.03em;
    }

    .profil-cover {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
        display: block;
        border-radius: 20px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, .10);
        border: 1px solid rgba(229, 231, 235, .9);
    }

    .profil-body {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 34px 34px 38px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }

    .profil-page-content {
        color: #374151;
        font-size: 15px;
        line-height: 1.9;
        word-wrap: break-word;
    }

    .profil-page-content > *:first-child {
        margin-top: 0 !important;
    }

    .profil-page-content > *:last-child {
        margin-bottom: 0 !important;
    }

    .profil-page-content h1,
    .profil-page-content h2,
    .profil-page-content h3,
    .profil-page-content h4,
    .profil-page-content h5,
    .profil-page-content h6 {
        color: #111827;
        line-height: 1.3;
        margin-top: 1.8em;
        margin-bottom: .7em;
        font-weight: 700;
    }

    .profil-page-content h1 { font-size: 30px; }
    .profil-page-content h2 { font-size: 26px; }
    .profil-page-content h3 { font-size: 22px; }
    .profil-page-content h4 { font-size: 18px; }

    .profil-page-content p {
        margin: 0 0 1.15em;
    }

    .profil-page-content ul,
    .profil-page-content ol {
        margin: 0 0 1.2em 1.3em;
        padding: 0;
    }

    .profil-page-content li {
        margin-bottom: .45em;
    }

    .profil-page-content a {
        color: #21560e;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .profil-page-content a:hover {
        color: #173f08;
    }

    .profil-page-content blockquote {
        margin: 1.4em 0;
        padding: 14px 18px;
        border-left: 4px solid #2f7d32;
        background: #f6faf4;
        color: #374151;
        border-radius: 0 12px 12px 0;
    }

    .profil-page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 14px;
    }

    .profil-page-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.4em 0;
        overflow: hidden;
        border-radius: 12px;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .profil-page-content table th,
    .profil-page-content table td {
        border: 1px solid #e5e7eb;
        padding: 12px 14px;
        text-align: left;
        vertical-align: top;
    }

    .profil-page-content table th {
        background: #f8fafc;
        color: #111827;
        font-weight: 700;
    }

    .profil-page-content hr {
        border: 0;
        border-top: 1px solid #e5e7eb;
        margin: 2em 0;
    }

    .profil-sidebar {
        position: sticky;
        top: 92px;
    }

    .profil-panel {
        background: #173f08;
        border-radius: 20px;
        padding: 18px 0;
        box-shadow: 0 14px 28px rgba(15, 23, 42, .10);
        overflow: hidden;
    }

    .profil-panel-title {
        padding: 0 22px 14px;
        margin: 0 0 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: rgba(255,255,255,.62);
    }

    .profil-panel a {
        display: block;
        padding: 14px 22px;
        color: rgba(255,255,255,.92);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
        transition: background .14s ease, color .14s ease;
        position: relative;
    }

    .profil-panel a:hover {
        background: rgba(255,255,255,.08);
        color: #ffffff;
    }

    .profil-panel a.active {
        background: rgba(255,255,255,.10);
        color: #ffffff;
    }

    .profil-panel a.active::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #d4a843;
    }

    @media (max-width: 980px) {
        .profil-wrap {
            grid-template-columns: 1fr;
        }

        .profil-sidebar {
            position: static;
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .profil-cover {
            max-height: 280px;
            border-radius: 16px;
        }

        .profil-body {
            padding: 22px 18px 24px;
            border-radius: 16px;
        }

        .profil-page-content {
            font-size: 14px;
            line-height: 1.8;
        }

        .profil-page-content h1 { font-size: 24px; }
        .profil-page-content h2 { font-size: 21px; }
        .profil-page-content h3 { font-size: 18px; }

        .profil-panel {
            border-radius: 16px;
        }
    }
</style>

<div class="profil-shell">
    <nav class="profil-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('web.home', ['locale' => $locale]) }}">
            {{ $locale === 'id' ? 'Beranda' : 'Home' }}
        </a>

        <span class="profil-breadcrumb-sep">/</span>

        <a href="{{ route('profil.index', ['locale' => $locale]) }}">
            {{ $locale === 'id' ? 'Profil' : 'Profile' }}
        </a>

        @if(!empty($page))
            <span class="profil-breadcrumb-sep">/</span>
            <span>{{ $page->title }}</span>
        @endif
    </nav>

    <div class="profil-wrap">
        <div class="profil-content">
            @if(!empty($page))
                <header class="profil-hero">
                    <h1 class="profil-title">{{ $page->title }}</h1>

                    @if($page->page?->cover_image)
                        <img
                            src="{{ asset($page->page->cover_image) }}"
                            alt="{{ $page->title }}"
                            class="profil-cover"
                        >
                    @endif
                </header>

                <section class="profil-body">
                    <div class="profil-page-content">
                        {!! $page->content !!}
                    </div>
                </section>
            @else
                <section class="profil-body">
                    <div class="profil-page-content">
                        <p>{{ $locale === 'id' ? 'Konten profil belum tersedia.' : 'Profile content is not available yet.' }}</p>
                    </div>
                </section>
            @endif
        </div>

        <aside class="profil-sidebar">
            <div class="profil-panel">
                <div class="profil-panel-title">
                    {{ $locale === 'id' ? 'Menu Profil' : 'Profile Menu' }}
                </div>

                @foreach($menu as $m)
                    <a
                        href="{{ route('profil.show', ['locale' => $locale, 'slug' => $m->slug]) }}"
                        class="{{ !empty($page) && $m->page_id === $page->page_id ? 'active' : '' }}"
                    >
                        {{ $m->title }}
                    </a>
                @endforeach
            </div>
        </aside>
    </div>
</div>
@endsection