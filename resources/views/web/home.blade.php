@extends('layouts.app')

@section('body_class', 'page-home')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');

    $companyVideoUrl = asset('videos/company-profile.mp4');
    $companyVideoPoster = asset('images/video-poster.jpg');

    $latestNews = $latestNews ?? collect();
    $customerPartners = $customerPartners ?? collect();
    $businessPartners = $businessPartners ?? collect();

    $hasCustomers = $customerPartners->count() > 0;
    $hasBusinessPartners = $businessPartners->count() > 0;

    $aboutUrl = route('profil.index', ['locale' => $locale]);
@endphp

<style>
    html, body {
        overflow-x: hidden;
        max-width: 100%;
    }

    .n-main {
        overflow-x: visible !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: none !important;
    }

    .home-wrap {
        --home-content-max: 1280px;
        --home-section-gutter: clamp(16px, 2.4vw, 28px);
        display: flex;
        flex-direction: column;
        gap: 64px;
        padding-bottom: 64px;
    }

    .home-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: min(calc(100% - (var(--home-section-gutter) * 2)), var(--home-content-max));
        margin-inline: auto;
        padding: 0;
    }

    .home-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .home-section-copy {
        max-width: 760px;
    }

    .home-section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #2f7d32;
        margin-bottom: 10px;
    }

    .home-section-eyebrow::before {
        content: '';
        width: 28px;
        height: 2px;
        border-radius: 999px;
        background: #2f7d32;
        flex-shrink: 0;
    }

    .home-section-title {
        margin: 0 0 8px;
        font-size: clamp(24px, 2.8vw, 36px);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #111827;
    }

    .home-section-desc {
        margin: 0;
        font-size: 15px;
        line-height: 1.85;
        color: #6b7280;
    }

    .home-link-more {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        color: #173f08;
        background: #eef5eb;
        border: 1px solid #dbe8d5;
        transition: background .15s ease, transform .15s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .home-link-more:hover {
        background: #e0eddb;
        transform: translateY(-1px);
    }

    .home-btn-primary,
    .home-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 20px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: all .15s ease;
    }

    .home-btn-primary {
        background: #173f08;
        color: #fff;
        border: 1px solid #173f08;
    }

    .home-btn-primary:hover {
        background: #21560e;
        border-color: #21560e;
        transform: translateY(-1px);
    }

    .home-btn-secondary {
        background: #fff;
        color: #173f08;
        border: 1px solid #d8e1d4;
    }

    .home-btn-secondary:hover {
        background: #f8fbf7;
        transform: translateY(-1px);
    }

    .home-slider-section {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        padding: 0 !important;
        overflow: hidden;
        opacity: 1 !important;
        transform: none !important;
        margin-top: 0;
    }

    .bspz-slider-wrap {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .bspz-slider {
        position: relative;
        width: 100%;
        background: #dfe7db;
        overflow: hidden;
    }

    .bspz-slider__viewport {
        width: 100%;
        overflow: hidden;
        background: #dfe7db;
        cursor: grab;
        user-select: none;
        -webkit-user-select: none;
    }

    .bspz-slider__viewport:active {
        cursor: grabbing;
    }

    .bspz-slider__track {
        display: flex;
        transition: transform 520ms cubic-bezier(.4,0,.2,1);
        will-change: transform;
    }

    .bspz-slider__track.is-dragging {
        transition: none;
    }

    .bspz-slider__slide {
        min-width: 100%;
        width: 100%;
        position: relative;
        overflow: hidden;
        isolation: isolate;
    }

    .bspz-slider__img {
        display: block;
        width: 100%;
        height: clamp(420px, calc(100vh - 66px), 860px);
        object-fit: cover;
        object-position: center center;
        pointer-events: none;
        -webkit-user-drag: none;
    }

    .bspz-slider__overlay {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(8,18,5,.78) 0%, rgba(8,18,5,.45) 32%, rgba(8,18,5,.12) 58%, rgba(8,18,5,.10) 100%),
            linear-gradient(0deg, rgba(8,18,5,.30) 0%, rgba(8,18,5,0) 42%);
        z-index: 1;
        opacity: 0;
        transition: opacity .3s ease;
    }

    .bspz-slider__slide--has-content .bspz-slider__overlay {
        opacity: 1;
    }

    .bspz-slider__content {
        position: absolute;
        inset: 0;
        z-index: 2;
        max-width: 1280px;
        margin: 0 auto;
        width: 100%;
        padding: 0 56px;
        display: flex;
        align-items: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s ease;
    }

    .bspz-slider__slide--has-content .bspz-slider__content {
        opacity: 1;
        pointer-events: auto;
    }

    .bspz-slider__content-inner {
        max-width: min(680px, 90%);
        color: #fff;
    }

    .bspz-slider__title {
        margin: 0;
        font-size: clamp(30px, 5vw, 56px);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -.04em;
        text-shadow: 0 10px 30px rgba(0,0,0,.35);
    }

    .bspz-slider__dots {
        position: absolute;
        left: 50%;
        bottom: 24px;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 5;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.10);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .bspz-slider__dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        border: 0;
        cursor: pointer;
        background: rgba(255,255,255,.42);
        transition: width .22s ease, background .22s ease, transform .22s ease;
    }

    .bspz-slider__dot.is-active {
        width: 28px;
        background: #fff;
        transform: translateY(-1px);
    }

    .home-overview {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        align-items: center;
    }

    .home-overview-media-card {
        overflow: hidden;
        border-radius: 20px;
        aspect-ratio: 4/3;
        background: #eef5eb;
    }

    .home-overview-media-card img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }

    .home-overview-media-card:hover img {
        transform: scale(1.03);
    }

    .home-overview-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 36px 32px 32px;
        box-shadow: 0 8px 24px rgba(15,23,42,.05);
    }

    .home-overview-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: fit-content;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #173f08;
        margin-bottom: 14px;
    }

    .home-overview-kicker::before {
        content: '';
        width: 24px;
        height: 2px;
        border-radius: 999px;
        background: #d4a843;
    }

    .home-overview-title {
        margin: 0 0 12px;
        font-size: clamp(28px, 3.2vw, 42px);
        line-height: 1.1;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.04em;
    }

    .home-overview-divider {
        width: 60px;
        height: 3px;
        border-radius: 999px;
        background: #d4a843;
        margin-bottom: 18px;
    }

    .home-overview-text {
        margin: 0;
        font-size: 15px;
        line-height: 1.95;
        color: #4b5563;
    }

    .home-overview-text p { margin: 0 0 1em; }
    .home-overview-text p:last-child { margin-bottom: 0; }

    .home-overview-actions {
        margin-top: 24px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* VIDEO */
    .home-video-shell {
        width: min(100%, 920px);
        margin: 0 auto;
    }

    .home-video-box {
        overflow: hidden;
        border-radius: 20px;
        background: #111827;
        border: 1px solid #e5e7eb;
        box-shadow: 0 8px 24px rgba(15,23,42,.06);
    }

    .home-video-embed {
        position: relative;
        width: 100%;
        padding-top: 48%;
        background: #000;
    }

    .home-video-embed video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .home-card-grid {
        --cols: 3;
        display: grid;
        grid-template-columns: repeat(var(--cols), minmax(0, 1fr));
        gap: 20px;
        justify-content: center;
    }

    .home-card-grid.cols-1 { --cols: 1; max-width: 420px; margin: 0 auto; }
    .home-card-grid.cols-2 { --cols: 2; max-width: 860px; margin: 0 auto; }

    .home-news-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(15,23,42,.05);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        display: flex;
        flex-direction: column;
    }

    .home-news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(15,23,42,.09);
        border-color: #d7e6d2;
    }

    .home-news-thumb-wrap {
        width: 100%;
        aspect-ratio: 16/9;
        overflow: hidden;
        background: #eef5eb;
        flex-shrink: 0;
    }

    .home-news-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .35s ease;
    }

    .home-news-card:hover .home-news-thumb {
        transform: scale(1.04);
    }

    .home-news-body {
        padding: 18px 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    .home-news-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .home-news-category {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 999px;
        background: #eef5eb;
        color: #21560e;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: .02em;
    }

    .home-news-title {
        margin: 0;
        font-size: 17px;
        line-height: 1.4;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.02em;
    }

    .home-news-title a {
        text-decoration: none;
        color: inherit;
    }

    .home-news-title a:hover { color: #173f08; }

    .home-news-excerpt {
        margin: 0;
        font-size: 13.5px;
        line-height: 1.75;
        color: #6b7280;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .home-relations {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .home-relations-stack {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .home-relations-card {
        border-radius: 20px;
        padding: 32px;
        border: 1px solid #e5e7eb;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15,23,42,.05);
        overflow: hidden;
    }

    .home-relations-card--dark {
        background: linear-gradient(135deg, #173f08 0%, #0d2905 100%);
        border-color: rgba(255,255,255,.08);
        color: #fff;
    }

    .home-relations-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .home-relations-copy h3 {
        margin: 0 0 5px;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.2;
    }

    .home-relations-copy p {
        margin: 0;
        font-size: 13.5px;
        line-height: 1.75;
        color: #6b7280;
    }

    .home-relations-card--dark .home-relations-copy p {
        color: rgba(255,255,255,.65);
    }

    .home-relations-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 16px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .home-relations-badge--customer {
        background: #ecfdf3;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .home-relations-badge--partner {
        background: rgba(255,255,255,.1);
        color: rgba(255,255,255,.9);
        border: 1px solid rgba(255,255,255,.18);
    }

    .home-partner-preview-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
    }

    .home-partner-preview {
        display: flex;
        align-items: stretch;
        justify-content: center;
        flex-wrap: wrap;
        gap: 18px;
        width: 100%;
        max-width: 1000px;
        padding: 8px 0 4px;
        margin: 0 auto;
        overflow: visible;
    }

    .home-partner-preview.is-centered,
    .home-partner-preview.has-more {
        justify-content: center;
    }

    .home-partner-more-wrap {
        display: flex;
        justify-content: center;
    }

    .home-partner-more {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 44px;
        padding: 0 18px;
        border-radius: 999px;
        border: 1px solid #d7e6d2;
        background: #f8fbf7;
        color: #173f08;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .01em;
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
    }

    .home-partner-more:hover {
        background: #eef5eb;
        border-color: #bfd7b8;
        transform: translateY(-1px);
        box-shadow: 0 10px 18px rgba(15,23,42,.08);
    }

    .home-partner-more__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: 999px;
        background: rgba(23,63,8,.08);
        font-size: 14px;
        line-height: 1;
        transition: transform .22s ease;
    }

    .home-relations-card--dark .home-partner-more {
        background: rgba(255,255,255,.08);
        border-color: rgba(255,255,255,.14);
        color: #fff;
    }

    .home-relations-card--dark .home-partner-more:hover {
        background: rgba(255,255,255,.14);
        border-color: rgba(255,255,255,.22);
        box-shadow: 0 12px 24px rgba(0,0,0,.18);
    }

    .home-relations-card--dark .home-partner-more__icon {
        background: rgba(255,255,255,.12);
    }

    .home-partner-expand {
        display: grid;
        grid-template-rows: 0fr;
        opacity: 0;
        transform: translateY(-6px);
        transition: grid-template-rows .34s ease, opacity .26s ease, transform .26s ease, margin-top .26s ease;
        margin-top: 0;
    }

    .home-partner-expand.is-open {
        grid-template-rows: 1fr;
        opacity: 1;
        transform: translateY(0);
        margin-top: 2px;
    }

    .home-partner-expand-inner {
        min-height: 0;
        overflow: hidden;
        padding-top: 2px;
    }

    .home-partner-item {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 160px;
        min-width: 160px;
        height: 100px;
        padding: 14px 20px;
        border-radius: 14px;
        text-decoration: none;
        flex-shrink: 0;
        position: relative;
        overflow: visible;
        background: #f8fbf7;
        border: 1px solid #e7eee3;
        box-shadow: 0 1px 0 rgba(255,255,255,.7) inset;
        transition: border-color .18s ease, transform .18s ease, box-shadow .18s ease, background .18s ease;
        cursor: default;
    }

    a.home-partner-item { cursor: pointer; }

    .home-partner-item:hover {
        border-color: #b8d6b0;
        background: #fbfdfb;
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(15,23,42,.10);
    }

    .home-relations-card--dark .home-partner-item {
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.12);
    }

    .home-relations-card--dark .home-partner-item:hover {
        background: rgba(255,255,255,.12);
        border-color: rgba(255,255,255,.25);
        box-shadow: 0 10px 20px rgba(0,0,0,.2);
    }

    .home-partner-logo {
        max-width: 120px;
        max-height: 54px;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .home-partner-logo--boxed {
        background: #fff;
        border-radius: 8px;
        padding: 6px 8px;
    }

    .home-partner-grid-static {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 18px;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        padding: 8px 0 4px;
    }

    .home-empty {
        background: #fff;
        border: 1px dashed #d1d5db;
        border-radius: 18px;
        padding: 48px 28px;
        text-align: center;
    }

    .home-empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 16px;
        border-radius: 18px;
        background: #eef5eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .home-empty-title {
        margin: 0 0 8px;
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }

    .home-empty-text {
        margin: 0 auto;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.75;
        max-width: 400px;
    }

    .home-section {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity .55s cubic-bezier(.4,0,.2,1), transform .55s cubic-bezier(.4,0,.2,1);
    }

    .home-section.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .bspz-slider__badge {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(6px);
        color: rgba(255,255,255,.9);
    }

    .bspz-slider__line {
        width: 60px;
        height: 3px;
        margin-top: 18px;
        border-radius: 999px;
        background: linear-gradient(90deg, #d4a843, #f2c94c);
    }

    @media (max-width: 1100px) {
        .home-card-grid:not(.cols-1):not(.cols-2) { --cols: 2; }
    }

    @media (max-width: 860px) {
        .home-overview {
            grid-template-columns: 1fr;
        }

        .home-overview-media-card {
            aspect-ratio: 16/9;
            max-height: 360px;
        }

        .bspz-slider__content {
            padding: 0 28px;
        }

        .bspz-slider__img {
            height: clamp(360px, calc(100vh - 66px), 720px);
        }

        .home-video-shell {
            width: 100%;
        }

        .home-video-embed {
            padding-top: 54%;
        }
    }

    @media (max-width: 680px) {
        .home-wrap {
            gap: 48px;
            --home-section-gutter: 16px;
        }

        .home-slider-section {
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
        }

        .home-card-grid,
        .home-card-grid.cols-2 {
            --cols: 1;
            max-width: 100%;
        }

        .home-relations-card {
            padding: 22px 18px;
        }

        .home-partner-item {
            width: 130px;
            min-width: 130px;
            height: 88px;
        }

        .home-partner-preview {
            gap: 14px;
            padding: 6px 0 2px;
        }

        .home-partner-grid-static {
            gap: 14px;
        }

        .bspz-slider__img {
            height: clamp(300px, calc(100vh - 58px), 560px);
        }

        .bspz-slider__content {
            padding: 0 20px;
            align-items: flex-end;
        }

        .bspz-slider__content-inner {
            max-width: 100%;
            padding-bottom: 62px;
        }

        .bspz-slider__title {
            font-size: 26px;
            line-height: 1.14;
        }

        .bspz-slider__dots {
            bottom: 14px;
            padding: 7px 10px;
        }

        .bspz-slider__dot {
            width: 8px;
            height: 8px;
        }

        .bspz-slider__dot.is-active {
            width: 22px;
        }

        .home-video-embed {
            padding-top: 58%;
        }
    }

    @media (max-width: 480px) {
        .home-overview-card {
            padding: 22px 18px;
        }
    }
</style>

<div class="home-wrap">

    @if($sliders->count())
        <div class="home-slider-section">
            <div class="bspz-slider-wrap">
                <section class="bspz-slider" data-autoplay="true" data-interval="5000">
                    <div class="bspz-slider__viewport">
                        <div class="bspz-slider__track">
                            @foreach($sliders as $index => $s)
                                @php
                                    $hasContent = filled($s->title);
                                @endphp
                                <article
                                    class="bspz-slider__slide {{ $hasContent ? 'bspz-slider__slide--has-content' : '' }}"
                                    aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                                >
                                    <img
                                        src="{{ asset($s->image_path) }}"
                                        alt="{{ $s->title ?: 'Slider ' . ($index + 1) }}"
                                        class="bspz-slider__img"
                                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                        draggable="false"
                                    >

                                    @if($hasContent)
                                        <div class="bspz-slider__overlay"></div>
                                    @endif

                                    @if($hasContent)
                                        <div class="bspz-slider__content">
                                            <div class="bspz-slider__content-inner">
                                                <div class="bspz-slider__badge">
                                                    {{ $locale === 'id' ? 'BSP Zapin' : 'BSP Zapin' }}
                                                </div>

                                                <h2 class="bspz-slider__title">
                                                    {{ $s->title }}
                                                </h2>

                                                <div class="bspz-slider__line"></div>
                                            </div>
                                        </div>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>

                    @if($sliders->count() > 1)
                        <div class="bspz-slider__dots" role="tablist" aria-label="Pagination Slider">
                            @foreach($sliders as $index => $s)
                                <button
                                    type="button"
                                    class="bspz-slider__dot {{ $index === 0 ? 'is-active' : '' }}"
                                    aria-label="Slide {{ $index + 1 }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    data-slide="{{ $index }}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </div>

        <script>
        (function () {
            function initBspzSlider(slider) {
                var track    = slider.querySelector('.bspz-slider__track');
                var slides   = Array.from(slider.querySelectorAll('.bspz-slider__slide'));
                var dots     = Array.from(slider.querySelectorAll('.bspz-slider__dot'));
                var viewport = slider.querySelector('.bspz-slider__viewport');
                if (!track || !slides.length) return;

                var index    = 0;
                var timer    = null;
                var autoplay = slider.getAttribute('data-autoplay') === 'true';
                var interval = Number(slider.getAttribute('data-interval') || 5000);

                function goTo(i) {
                    index = (i + slides.length) % slides.length;
                    track.style.transform = 'translateX(-' + (index * 100) + '%)';

                    slides.forEach(function (s, idx) {
                        s.setAttribute('aria-hidden', idx === index ? 'false' : 'true');
                    });

                    dots.forEach(function (d, idx) {
                        var active = idx === index;
                        d.classList.toggle('is-active', active);
                        d.setAttribute('aria-current', active ? 'true' : 'false');
                    });
                }

                function next() { goTo(index + 1); }
                function prev() { goTo(index - 1); }

                function start() {
                    if (!autoplay || slides.length <= 1) return;
                    stop();
                    timer = setInterval(next, interval);
                }

                function stop() {
                    if (timer) { clearInterval(timer); timer = null; }
                }

                dots.forEach(function (dot) {
                    dot.addEventListener('click', function () {
                        goTo(Number(dot.getAttribute('data-slide') || 0));
                        start();
                    });
                });

                if (viewport) {
                    viewport.addEventListener('mouseenter', stop);
                    viewport.addEventListener('mouseleave', start);
                }

                var touchStartX = 0;
                var touchStartY = 0;

                if (viewport) {
                    viewport.addEventListener('touchstart', function (e) {
                        touchStartX = e.touches[0].clientX;
                        touchStartY = e.touches[0].clientY;
                    }, { passive: true });

                    viewport.addEventListener('touchend', function (e) {
                        var dx = touchStartX - e.changedTouches[0].clientX;
                        var dy = Math.abs(touchStartY - e.changedTouches[0].clientY);
                        if (Math.abs(dx) > 40 && Math.abs(dx) > dy) {
                            dx > 0 ? next() : prev();
                            start();
                        }
                    }, { passive: true });
                }

                var dragStartX   = 0;
                var isDragging   = false;
                var dragMoved    = false;
                var dragThreshold = 8;

                if (viewport) {
                    viewport.addEventListener('mousedown', function (e) {
                        if (e.button !== 0) return;
                        dragStartX  = e.clientX;
                        isDragging  = true;
                        dragMoved   = false;
                        stop();
                        track.classList.add('is-dragging');
                    });

                    window.addEventListener('mousemove', function (e) {
                        if (!isDragging) return;
                        var dx = e.clientX - dragStartX;
                        if (Math.abs(dx) > dragThreshold) dragMoved = true;

                        if (dragMoved) {
                            var baseOffset = -(index * 100);
                            var percentDelta = (dx / viewport.offsetWidth) * 100;
                            track.style.transform = 'translateX(' + (baseOffset + percentDelta) + '%)';
                        }
                    });

                    window.addEventListener('mouseup', function (e) {
                        if (!isDragging) return;
                        isDragging = false;
                        track.classList.remove('is-dragging');

                        var dx = e.clientX - dragStartX;
                        if (dragMoved && Math.abs(dx) > 60) {
                            dx < 0 ? next() : prev();
                        } else {
                            goTo(index);
                        }
                        start();
                    });

                    viewport.addEventListener('click', function (e) {
                        if (dragMoved) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }, true);
                }

                goTo(0);
                start();
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.bspz-slider').forEach(initBspzSlider);
            });
        })();
        </script>
    @else
        <section class="home-section">
            <div class="home-empty">
                <div class="home-empty-icon">🖼️</div>
                <h2 class="home-empty-title">
                    {{ $locale === 'id' ? 'Belum ada slider' : 'No slider available yet' }}
                </h2>
                <p class="home-empty-text">
                    {{ $locale === 'id'
                        ? 'Silakan tambahkan slider dari panel admin.'
                        : 'Please add sliders from the admin panel.' }}
                </p>
            </div>
        </section>
    @endif

    <section class="home-section">
        <div class="home-overview">
            <div class="home-overview-media-card">
                <img src="{{ asset('images/profile/company-overview.jpeg') }}" alt="Company Overview" loading="lazy">
            </div>

            <div class="home-overview-card">
                <div class="home-overview-kicker">Company Overview</div>
                <h2 class="home-overview-title">
                    {{ $locale === 'id' ? 'Mengenal BSP Zapin Lebih Dekat' : 'Getting to Know BSP Zapin Better' }}
                </h2>
                <div class="home-overview-divider"></div>
                <div class="home-overview-text">
                    @if($locale === 'id')
                        <p>PT Bumi Siak Pusako Zapin adalah perusahaan yang bergerak di bidang hilir minyak dan gas bumi, serta menjadi bagian dari pengembangan usaha strategis yang berorientasi pada pertumbuhan, profesionalisme, dan keberlanjutan.</p>
                        <p>Dalam perjalanannya, BSP Zapin terus membangun kepercayaan melalui semangat kerja, transparansi, integritas, dan komitmen untuk menciptakan nilai tambah bagi pemegang saham, mitra usaha, dan seluruh pemangku kepentingan.</p>
                    @else
                        <p>PT Bumi Siak Pusako Zapin is a company operating in the downstream oil and gas sector and serves as part of a strategic business development effort focused on growth, professionalism, and sustainability.</p>
                        <p>Throughout its journey, BSP Zapin continues to build trust through work spirit, transparency, integrity, and a strong commitment to creating added value for shareholders, business partners, and stakeholders.</p>
                    @endif
                </div>
                <div class="home-overview-actions">
                    <a href="{{ $aboutUrl }}" class="home-btn-primary">
                        {{ $locale === 'id' ? 'Selengkapnya' : 'Read More' }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="home-section-copy">
            <div class="home-section-eyebrow">
                {{ $locale === 'id' ? 'Video Perusahaan' : 'Company Video' }}
            </div>
            <h2 class="home-section-title">
                {{ $locale === 'id' ? 'Lihat Sekilas Profil BSP Zapin' : 'Take a Quick Look at BSP Zapin Profile' }}
            </h2>
        </div>

        <div class="home-video-shell">
            <div class="home-video-box">
                <div class="home-video-embed">
                    <video
                        controls
                        preload="metadata"
                        playsinline
                        poster="{{ $companyVideoPoster }}"
                    >
                        <source src="{{ $companyVideoUrl }}" type="video/mp4">
                        Browser Anda tidak mendukung pemutaran video.
                    </video>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="home-section-head">
            <div class="home-section-copy">
                <div class="home-section-eyebrow">
                    {{ $locale === 'id' ? 'Berita Terkini' : 'Latest News' }}
                </div>
                <h2 class="home-section-title">
                    {{ $locale === 'id' ? 'Informasi dan Pembaruan Terbaru' : 'Latest Updates and Information' }}
                </h2>
                <p class="home-section-desc">
                    {{ $locale === 'id'
                        ? 'Ikuti perkembangan terbaru perusahaan, publikasi resmi, serta informasi yang relevan bagi masyarakat dan para pemangku kepentingan.'
                        : 'Follow the latest company developments, official publications, and information relevant to the public and stakeholders.' }}
                </p>
            </div>
            <a href="{{ route('media_publikasi.index', ['locale' => $locale]) }}" class="home-link-more">
                {{ $locale === 'id' ? 'Lihat Semua' : 'See All' }}
            </a>
        </div>

        @php $latestCount = $latestNews->count(); @endphp
        @if($latestCount)
            @php $latestColClass = $latestCount === 1 ? 'cols-1' : ($latestCount === 2 ? 'cols-2' : ''); @endphp
            <div class="home-card-grid {{ $latestColClass }}">
                @foreach($latestNews as $news)
                    @php
                        $translation = method_exists($news, 'getTranslationByLocale')
                            ? $news->getTranslationByLocale($locale)
                            : ($news->translations->firstWhere('locale', $locale) ?? $news->translations->firstWhere('locale', 'id'));
                    @endphp
                    <article class="home-news-card">
                        @if($news->featured_image)
                            <a href="{{ $translation?->slug ? route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) : '#' }}" class="home-news-thumb-wrap">
                                <img src="{{ asset($news->featured_image) }}" alt="{{ $translation?->title ?? 'News image' }}" class="home-news-thumb" loading="lazy">
                            </a>
                        @endif
                        <div class="home-news-body">
                            <div class="home-news-meta">
                                @if($news->category?->name)
                                    <span class="home-news-category">{{ $news->category->name }}</span>
                                @endif
                                @if($news->published_at)
                                    <span>{{ $news->published_at->format('d M Y') }}</span>
                                @endif
                            </div>
                            <h3 class="home-news-title">
                                @if($translation?->slug)
                                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) }}">{{ $translation->title }}</a>
                                @else
                                    {{ $translation?->title ?? ($locale === 'id' ? 'Berita' : 'News') }}
                                @endif
                            </h3>
                            @if(!empty($translation?->excerpt))
                                <p class="home-news-excerpt">{{ $translation->excerpt }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="home-empty">
                <h3 class="home-empty-title">{{ $locale === 'id' ? 'Belum ada berita terkini' : 'No latest news yet' }}</h3>
                <p class="home-empty-text">
                    {{ $locale === 'id'
                        ? 'Silakan tambahkan berita dari panel admin agar bagian ini tampil.'
                        : 'Please add news from the admin panel so this section can appear.' }}
                </p>
            </div>
        @endif
    </section>

    <section class="home-section home-relations">
        <div class="home-section-copy">
            <div class="home-section-eyebrow">
                {{ $locale === 'id' ? 'Relasi Perusahaan' : 'Company Relations' }}
            </div>
            <h2 class="home-section-title">
                {{ $locale === 'id' ? 'Pelanggan & Mitra Bisnis' : 'Customers & Business Partners' }}
            </h2>
            <p class="home-section-desc">
                {{ $locale === 'id'
                    ? 'Kolaborasi kami dibangun melalui kepercayaan, profesionalisme, dan komitmen untuk menghadirkan nilai terbaik bagi seluruh pihak yang bekerja sama dengan perusahaan.'
                    : 'Our collaborations are built on trust, professionalism, and a commitment to delivering the best value to every party working with the company.' }}
            </p>
        </div>

        @if(!$hasCustomers && !$hasBusinessPartners)
            <div class="home-empty">
                <h3 class="home-empty-title">
                    {{ $locale === 'id' ? 'Belum ada pelanggan atau mitra bisnis' : 'No customers or business partners yet' }}
                </h3>
                <p class="home-empty-text">
                    {{ $locale === 'id'
                        ? 'Silakan tambahkan data pelanggan atau mitra bisnis dari panel admin.'
                        : 'Please add customer or business partner data from the admin panel.' }}
                </p>
            </div>
        @else
            <div class="home-relations-stack">

                @if($hasBusinessPartners)
                    @php
                        $businessPreviewCount = 4;
                        $businessPreviewItems = $businessPartners->take($businessPreviewCount);
                        $businessRemainingItems = $businessPartners->slice($businessPreviewCount)->values();
                        $businessHasMore = $businessRemainingItems->count() > 0;
                    @endphp
                    <div class="home-relations-card home-relations-card--dark">
                        <div class="home-relations-head">
                            <div class="home-relations-copy">
                                <h3>{{ $locale === 'id' ? 'Mitra Bisnis' : 'Business Partners' }}</h3>
                                <p>{{ $locale === 'id' ? 'Jaringan kemitraan strategis yang mendukung penguatan operasional dan pengembangan bisnis perusahaan.' : 'Strategic partnership networks that support operational strength and business growth.' }}</p>
                            </div>
                            <div class="home-relations-badge home-relations-badge--partner">
                                {{ $businessPartners->count() }} {{ $locale === 'id' ? 'Mitra' : 'Partners' }}
                            </div>
                        </div>

                        <div class="home-partner-preview-wrap">
                            <div class="home-partner-preview {{ $businessHasMore ? 'has-more' : 'is-centered' }}">
                                @foreach($businessPreviewItems as $partner)
                                    @if($partner->website_url)
                                        <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="home-partner-item" title="{{ $partner->name }}">
                                            <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo home-partner-logo--boxed" loading="lazy">
                                        </a>
                                    @else
                                        <div class="home-partner-item" title="{{ $partner->name }}">
                                            <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo home-partner-logo--boxed" loading="lazy">
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @if($businessHasMore)
                                <div class="home-partner-more-wrap">
                                    <button
                                        type="button"
                                        class="home-partner-more"
                                        data-toggle-expand
                                        data-open-text="{{ $locale === 'id' ? 'Tampilkan Lebih Sedikit' : 'Show Less' }}"
                                        data-close-text="{{ $locale === 'id' ? 'Lihat Semua Mitra' : 'View All Partners' }}"
                                        aria-expanded="false"
                                    >
                                        <span class="home-partner-more__label">{{ $locale === 'id' ? 'Lihat Semua Mitra' : 'View All Partners' }}</span>
                                        <span class="home-partner-more__icon">+</span>
                                    </button>
                                </div>

                                <div class="home-partner-expand" data-expand-panel>
                                    <div class="home-partner-expand-inner">
                                        <div class="home-partner-grid-static">
                                            @foreach($businessRemainingItems as $partner)
                                                @if($partner->website_url)
                                                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="home-partner-item" title="{{ $partner->name }}">
                                                        <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo home-partner-logo--boxed" loading="lazy">
                                                    </a>
                                                @else
                                                    <div class="home-partner-item" title="{{ $partner->name }}">
                                                        <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo home-partner-logo--boxed" loading="lazy">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($hasCustomers)
                    @php
                        $customerPreviewCount = 5;
                        $customerPreviewItems = $customerPartners->take($customerPreviewCount);
                        $customerRemainingItems = $customerPartners->slice($customerPreviewCount)->values();
                        $customerHasMore = $customerRemainingItems->count() > 0;
                    @endphp
                    <div class="home-relations-card">
                        <div class="home-relations-head">
                            <div class="home-relations-copy">
                                <h3>{{ $locale === 'id' ? 'Pelanggan' : 'Customers' }}</h3>
                                <p>{{ $locale === 'id' ? 'Perusahaan dan institusi yang telah mempercayakan kerja sama kepada BSP Zapin.' : 'Companies and institutions that have entrusted their collaboration with BSP Zapin.' }}</p>
                            </div>
                            <div class="home-relations-badge home-relations-badge--customer">
                                {{ $customerPartners->count() }} {{ $locale === 'id' ? 'Pelanggan' : 'Customers' }}
                            </div>
                        </div>

                        <div class="home-partner-preview-wrap">
                            <div class="home-partner-preview {{ $customerHasMore ? 'has-more' : 'is-centered' }}">
                                @foreach($customerPreviewItems as $partner)
                                    @if($partner->website_url)
                                        <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="home-partner-item" title="{{ $partner->name }}">
                                            <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo" loading="lazy">
                                        </a>
                                    @else
                                        <div class="home-partner-item" title="{{ $partner->name }}">
                                            <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo" loading="lazy">
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @if($customerHasMore)
                                <div class="home-partner-more-wrap">
                                    <button
                                        type="button"
                                        class="home-partner-more"
                                        data-toggle-expand
                                        data-open-text="{{ $locale === 'id' ? 'Tampilkan Lebih Sedikit' : 'Show Less' }}"
                                        data-close-text="{{ $locale === 'id' ? 'Lihat Semua Pelanggan' : 'View All Customers' }}"
                                        aria-expanded="false"
                                    >
                                        <span class="home-partner-more__label">{{ $locale === 'id' ? 'Lihat Semua Pelanggan' : 'View All Customers' }}</span>
                                        <span class="home-partner-more__icon">+</span>
                                    </button>
                                </div>

                                <div class="home-partner-expand" data-expand-panel>
                                    <div class="home-partner-expand-inner">
                                        <div class="home-partner-grid-static">
                                            @foreach($customerRemainingItems as $partner)
                                                @if($partner->website_url)
                                                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="home-partner-item" title="{{ $partner->name }}">
                                                        <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo" loading="lazy">
                                                    </a>
                                                @else
                                                    <div class="home-partner-item" title="{{ $partner->name }}">
                                                        <img src="{{ asset($partner->logo_path) }}" alt="{{ $partner->name }}" class="home-partner-logo" loading="lazy">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </section>

</div>

<script>
(function () {

    function initPartnerExpand() {
        document.querySelectorAll('[data-toggle-expand]').forEach(function (button) {
            var panel = button.closest('.home-partner-preview-wrap')?.querySelector('[data-expand-panel]');
            if (!panel) return;

            var label = button.querySelector('.home-partner-more__label');
            var icon = button.querySelector('.home-partner-more__icon');

            button.addEventListener('click', function () {
                var isOpen = panel.classList.toggle('is-open');
                button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                if (label) {
                    label.textContent = isOpen
                        ? (button.getAttribute('data-open-text') || 'Show Less')
                        : (button.getAttribute('data-close-text') || 'View All');
                }

                if (icon) {
                    icon.textContent = isOpen ? '−' : '+';
                    icon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
                }
            });
        });
    }

    function initReveal() {
        var sections = document.querySelectorAll('.home-section');
        if (!('IntersectionObserver' in window)) {
            sections.forEach(function (s) { s.classList.add('is-visible'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px'
        });

        sections.forEach(function (s) { observer.observe(s); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initPartnerExpand();
        initReveal();
    });

})();
</script>
@endsection