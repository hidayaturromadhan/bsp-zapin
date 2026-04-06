@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id');

    $youtubeEmbedUrl = 'https://www.youtube-nocookie.com/embed/7-TghrJvi9c';

    $latestNews = $latestNews ?? collect();
    $featuredNews = $featuredNews ?? collect();
    $customerPartners = $customerPartners ?? collect();
    $businessPartners = $businessPartners ?? collect();

    $hasCustomers = $customerPartners->count() > 0;
    $hasBusinessPartners = $businessPartners->count() > 0;

    $aboutUrl = route('profil.index', ['locale' => $locale]);
@endphp

<style>
    .home-wrap {
        display: flex;
        flex-direction: column;
        gap: 64px;
    }

    /* ── SECTION ── */
    .home-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
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

    /* ── SLIDER (full-width, no margin) ── */
    .bspz-slider-wrap {
        margin: 0 -28px;
        position: relative;
    }

    .bspz-slider {
        position: relative;
    }

    .bspz-slider__viewport {
        overflow: hidden;
        background: #dfe7db;
    }

    .bspz-slider__track {
        display: flex;
        transition: transform 500ms cubic-bezier(.4,0,.2,1);
        will-change: transform;
    }

    .bspz-slider__slide {
        min-width: 100%;
        position: relative;
        overflow: hidden;
    }

    .bspz-slider__img {
        display: block;
        width: 100%;
        height: clamp(260px, 52vw, 640px);
        object-fit: cover;
        object-position: center;
    }

    .bspz-slider__nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        cursor: pointer;
        background: rgba(0,0,0,.32);
        backdrop-filter: blur(6px);
        color: #fff;
        font-size: 26px;
        line-height: 48px;
        text-align: center;
        user-select: none;
        z-index: 3;
        transition: background .14s ease;
    }

    .bspz-slider__nav--prev { left: 20px; }
    .bspz-slider__nav--next { right: 20px; }

    .bspz-slider__nav:hover {
        background: rgba(0,0,0,.52);
    }

    .bspz-slider__dots {
        position: absolute;
        bottom: 18px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 3;
    }

    .bspz-slider__dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        border: 0;
        cursor: pointer;
        background: rgba(255,255,255,.45);
        transition: width .2s ease, background .2s ease;
    }

    .bspz-slider__dot.is-active {
        background: #fff;
        width: 24px;
    }

    /* ── OVERVIEW ── */
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

    /* ── VIDEO ── */
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
        padding-top: 56.25%;
    }

    .home-video-embed iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* ── NEWS GRID (auto-center) ── */
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

    /* ── PARTNERS ── */
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

    /* ── PARTNER MARQUEE ── */
    .home-partner-marquee-outer {
        overflow: hidden;
        position: relative;
    }

    .home-partner-marquee-outer::before,
    .home-partner-marquee-outer::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 60px;
        z-index: 2;
        pointer-events: none;
    }

    .home-partner-marquee-outer::before { left: 0; background: linear-gradient(to right, #fff, transparent); }
    .home-partner-marquee-outer::after  { right: 0; background: linear-gradient(to left, #fff, transparent); }

    .home-relations-card--dark .home-partner-marquee-outer::before { background: linear-gradient(to right, #0d2905, transparent); }
    .home-relations-card--dark .home-partner-marquee-outer::after  { background: linear-gradient(to left, #0d2905, transparent); }

    .home-partner-marquee {
        display: flex;
        gap: 14px;
        width: max-content;
        animation: marquee-scroll 28s linear infinite;
    }

    .home-partner-marquee:hover {
        animation-play-state: paused;
    }

    @keyframes marquee-scroll {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .home-partner-item {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 160px;
        height: 100px;
        padding: 14px 20px;
        border-radius: 14px;
        text-decoration: none;
        flex-shrink: 0;
        background: #f8fbf7;
        border: 1px solid #e7eee3;
        transition: border-color .18s ease, transform .18s ease, box-shadow .18s ease;
    }

    .home-partner-item:hover {
        border-color: #b8d6b0;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(15,23,42,.08);
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
    }

    .home-partner-logo--boxed {
        background: #fff;
        border-radius: 8px;
        padding: 6px 8px;
    }

    /* static grid fallback for few items */
    .home-partner-grid-static {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 14px;
    }

    /* ── EMPTY STATE ── */
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
        margin: 0;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.75;
        max-width: 400px;
        margin: 0 auto;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
        .home-card-grid:not(.cols-1):not(.cols-2) {
            --cols: 2;
        }
    }

    @media (max-width: 860px) {
        .home-overview {
            grid-template-columns: 1fr;
        }

        .home-overview-media-card {
            aspect-ratio: 16/9;
            max-height: 360px;
        }

        .bspz-slider-wrap {
            margin: 0 -18px;
        }
    }

    @media (max-width: 680px) {
        .home-wrap { gap: 48px; }

        .home-card-grid,
        .home-card-grid.cols-2 {
            --cols: 1;
            max-width: 100%;
        }

        .home-relations-card {
            padding: 22px 18px;
        }

        .home-partner-item {
            min-width: 130px;
            height: 88px;
        }
    }

    @media (max-width: 480px) {
        .bspz-slider-wrap {
            margin: 0 -14px;
        }

        .bspz-slider__nav {
            width: 38px;
            height: 38px;
            line-height: 38px;
            font-size: 22px;
        }

        .bspz-slider__nav--prev { left: 10px; }
        .bspz-slider__nav--next { right: 10px; }

        .home-overview-card {
            padding: 22px 18px;
        }
    }
</style>

<div class="home-wrap">

    {{-- SLIDER (full width) --}}
    @if($sliders->count())
        <div class="bspz-slider-wrap">
            <section class="bspz-slider" data-autoplay="true" data-interval="5000">
                <div class="bspz-slider__viewport">
                    <div class="bspz-slider__track">
                        @foreach($sliders as $index => $s)
                            <article class="bspz-slider__slide" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                                @if($s->link_url)
                                    <a href="{{ $s->link_url }}">
                                        <img src="{{ asset($s->image_path) }}" alt="Slider {{ $index + 1 }}" class="bspz-slider__img" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                    </a>
                                @else
                                    <img src="{{ asset($s->image_path) }}" alt="Slider {{ $index + 1 }}" class="bspz-slider__img" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>

                @if($sliders->count() > 1)
                    <button type="button" class="bspz-slider__nav bspz-slider__nav--prev" aria-label="Sebelumnya">‹</button>
                    <button type="button" class="bspz-slider__nav bspz-slider__nav--next" aria-label="Berikutnya">›</button>
                    <div class="bspz-slider__dots" role="tablist" aria-label="Pagination Slider">
                        @foreach($sliders as $index => $s)
                            <button type="button"
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

        <script>
        (function () {
            function initBspzSlider(slider) {
                var track = slider.querySelector('.bspz-slider__track');
                var slides = Array.from(slider.querySelectorAll('.bspz-slider__slide'));
                var dots = Array.from(slider.querySelectorAll('.bspz-slider__dot'));
                var prevBtn = slider.querySelector('.bspz-slider__nav--prev');
                var nextBtn = slider.querySelector('.bspz-slider__nav--next');
                if (!track || !slides.length) return;

                var index = 0, timer = null;
                var autoplay = slider.getAttribute('data-autoplay') === 'true';
                var interval = Number(slider.getAttribute('data-interval') || 5000);

                function goTo(i) {
                    index = (i + slides.length) % slides.length;
                    track.style.transform = 'translateX(-' + (index * 100) + '%)';
                    slides.forEach(function(s, idx) { s.setAttribute('aria-hidden', idx === index ? 'false' : 'true'); });
                    dots.forEach(function(d, idx) {
                        var a = idx === index;
                        d.classList.toggle('is-active', a);
                        d.setAttribute('aria-current', a ? 'true' : 'false');
                    });
                }

                function next() { goTo(index + 1); }
                function prev() { goTo(index - 1); }

                function start() {
                    if (!autoplay || slides.length <= 1) return;
                    stop();
                    timer = setInterval(next, interval);
                }

                function stop() { if (timer) { clearInterval(timer); timer = null; } }

                if (nextBtn) nextBtn.addEventListener('click', function() { next(); start(); });
                if (prevBtn) prevBtn.addEventListener('click', function() { prev(); start(); });

                dots.forEach(function(dot) {
                    dot.addEventListener('click', function() {
                        goTo(Number(dot.getAttribute('data-slide') || 0));
                        start();
                    });
                });

                var vp = slider.querySelector('.bspz-slider__viewport');
                if (vp) {
                    vp.addEventListener('mouseenter', stop);
                    vp.addEventListener('mouseleave', start);
                }

                var startX = 0;
                vp.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; }, { passive: true });
                vp.addEventListener('touchend', function(e) {
                    var diff = startX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 40) { diff > 0 ? next() : prev(); start(); }
                }, { passive: true });

                goTo(0);
                start();
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.bspz-slider').forEach(initBspzSlider);
            });
        })();
        </script>
    @else
        <section class="home-empty">
            <div class="home-empty-icon">🖼️</div>
            <h2 class="home-empty-title">
                {{ $locale === 'id' ? 'Belum ada slider' : 'No slider available yet' }}
            </h2>
            <p class="home-empty-text">
                {{ $locale === 'id'
                    ? 'Silakan tambahkan slider dari panel admin.'
                    : 'Please add sliders from the admin panel.' }}
            </p>
        </section>
    @endif

    {{-- COMPANY OVERVIEW --}}
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

    {{-- VIDEO --}}
    <section class="home-section">
        <div class="home-section-copy">
            <div class="home-section-eyebrow">
                {{ $locale === 'id' ? 'Video Perusahaan' : 'Company Video' }}
            </div>
            <h2 class="home-section-title">
                {{ $locale === 'id' ? 'Lihat Sekilas Profil BSP Zapin' : 'Take a Quick Look at BSP Zapin Profile' }}
            </h2>
        </div>
        <div class="home-video-box">
            <div class="home-video-embed">
                <iframe
                    src="{{ $youtubeEmbedUrl }}"
                    title="BSP Zapin YouTube Video"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>

    {{-- LATEST NEWS --}}
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
            @php
                $latestColClass = $latestCount === 1 ? 'cols-1' : ($latestCount === 2 ? 'cols-2' : '');
            @endphp
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

    {{-- FEATURED NEWS --}}
    <section class="home-section">
        <div class="home-section-head">
            <div class="home-section-copy">
                <div class="home-section-eyebrow">
                    {{ $locale === 'id' ? 'Unggulan' : 'Featured' }}
                </div>
                <h2 class="home-section-title">
                    {{ $locale === 'id' ? 'Berita Unggulan Pilihan' : 'Selected Featured News' }}
                </h2>
                <p class="home-section-desc">
                    {{ $locale === 'id'
                        ? 'Sorotan informasi penting dan publikasi unggulan yang perlu mendapatkan perhatian lebih dari pengunjung.'
                        : 'Highlights of important information and featured publications that deserve more visitor attention.' }}
                </p>
            </div>
        </div>

        @php $featuredCount = $featuredNews->count(); @endphp
        @if($featuredCount)
            @php
                $featuredColClass = $featuredCount === 1 ? 'cols-1' : ($featuredCount === 2 ? 'cols-2' : '');
            @endphp
            <div class="home-card-grid {{ $featuredColClass }}">
                @foreach($featuredNews as $news)
                    @php
                        $translation = method_exists($news, 'getTranslationByLocale')
                            ? $news->getTranslationByLocale($locale)
                            : ($news->translations->firstWhere('locale', $locale) ?? $news->translations->firstWhere('locale', 'id'));
                    @endphp
                    <article class="home-news-card">
                        @if($news->featured_image)
                            <a href="{{ $translation?->slug ? route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) : '#' }}" class="home-news-thumb-wrap">
                                <img src="{{ asset($news->featured_image) }}" alt="{{ $translation?->title ?? 'Featured image' }}" class="home-news-thumb" loading="lazy">
                            </a>
                        @endif
                        <div class="home-news-body">
                            <div class="home-news-meta">
                                <span class="home-news-category">{{ $locale === 'id' ? 'Unggulan' : 'Featured' }}</span>
                                @if($news->published_at)
                                    <span>{{ $news->published_at->format('d M Y') }}</span>
                                @endif
                            </div>
                            <h3 class="home-news-title">
                                @if($translation?->slug)
                                    <a href="{{ route('news.show', ['locale' => $locale, 'slug' => $translation->slug]) }}">{{ $translation->title }}</a>
                                @else
                                    {{ $translation?->title ?? ($locale === 'id' ? 'Berita Unggulan' : 'Featured News') }}
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
                <h3 class="home-empty-title">{{ $locale === 'id' ? 'Belum ada berita unggulan' : 'No featured news yet' }}</h3>
                <p class="home-empty-text">
                    {{ $locale === 'id'
                        ? 'Tandai berita sebagai unggulan dari panel admin.'
                        : 'Mark news as featured from the admin panel.' }}
                </p>
            </div>
        @endif
    </section>

    {{-- PARTNERS --}}
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
                @php $useMarqueeBusiness = $businessPartners->count() > 4; @endphp
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

                    @if($useMarqueeBusiness)
                        @php $doubledBusiness = $businessPartners->merge($businessPartners); @endphp
                        <div class="home-partner-marquee-outer">
                            <div class="home-partner-marquee" style="animation-direction: reverse; animation-duration: 32s;">
                                @foreach($doubledBusiness as $partner)
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
                    @else
                        <div class="home-partner-grid-static">
                            @foreach($businessPartners as $partner)
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
                    @endif
                </div>
                @endif

                @if($hasCustomers)
                @php $useMarqueeCustomer = $customerPartners->count() > 4; @endphp
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

                    @if($useMarqueeCustomer)
                        @php $doubledCustomers = $customerPartners->merge($customerPartners); @endphp
                        <div class="home-partner-marquee-outer">
                            <div class="home-partner-marquee" style="--count: {{ $customerPartners->count() }}">
                                @foreach($doubledCustomers as $partner)
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
                    @else
                        <div class="home-partner-grid-static">
                            @foreach($customerPartners as $partner)
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
                    @endif
                </div>
                @endif

            </div>
        @endif
    </section>

</div>
@endsection