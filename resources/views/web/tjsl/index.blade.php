@extends('layouts.app')

@section('title', $locale === 'id' ? 'TJSL' : 'TJSL / CSR')

@section('content')
@php
    $activeYear = request('year') ?: optional($programs->first())->year;
    $activeProgram = $programs->firstWhere('year', $activeYear) ?? $programs->first();
    $activeTranslation = $activeProgram?->getTranslation($locale);
@endphp

<style>
.n-main {
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.tjsl-page {
    display: flex;
    flex-direction: column;
}

.tjsl-band {
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    background: #173f08;
    background-image:
        radial-gradient(ellipse 60% 80% at 15% 50%, rgba(47,125,50,.34) 0%, transparent 65%),
        radial-gradient(ellipse 40% 60% at 85% 30%, rgba(32,71,18,.50) 0%, transparent 60%);
    padding: 44px 0 48px;
}

.tjsl-band-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
}

.tjsl-hero {
    background: #fff;
    border-radius: 24px;
    padding: 52px 48px 48px;
    position: relative;
    overflow: hidden;
    box-shadow:
        0 0 0 1px rgba(32,71,18,.1),
        0 20px 60px rgba(5,18,2,.28),
        0 4px 12px rgba(5,18,2,.12);
}

.tjsl-hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    background: rgba(32,71,18,.06);
}
.tjsl-hero-orb-1 { width: 220px; height: 220px; top: -60px; right: -60px; }
.tjsl-hero-orb-2 { width: 150px; height: 150px; bottom: -40px; left: -30px; }

.tjsl-hero-top {
    position: relative;
    z-index: 2;
}

.tjsl-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}
.tjsl-eyebrow-line {
    width: 34px;
    height: 2px;
    background: #204712;
    border-radius: 999px;
}
.tjsl-eyebrow-text {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: #204712;
}

.tjsl-title {
    margin: 0 0 14px;
    font-size: 34px;
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -.03em;
    color: #111827;
}

.tjsl-desc {
    margin: 0;
    max-width: 760px;
    font-size: 14.5px;
    line-height: 1.8;
    color: #5b6572;
}

.tjsl-content {
    max-width: 1280px;
    margin: 0 auto;
    padding: 44px 28px 72px;
}

.tjsl-year-nav {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 26px;
}

.tjsl-year-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 78px;
    height: 40px;
    padding: 0 16px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid #dfe8db;
    color: #35512e;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: all .18s ease;
    box-shadow: 0 4px 12px rgba(15,23,42,.04);
}

.tjsl-year-chip:hover {
    transform: translateY(-2px);
    border-color: #b9d3b1;
    box-shadow: 0 10px 18px rgba(15,23,42,.08);
}

.tjsl-year-chip.is-active {
    background: #204712;
    color: #fff;
    border-color: #204712;
    box-shadow: 0 10px 20px rgba(32,71,18,.22);
}

.tjsl-feature {
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    gap: 24px;
    align-items: stretch;
    margin-bottom: 28px;
}

.tjsl-feature-media {
    background: #fff;
    border-radius: 22px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 28px rgba(15,23,42,.05);
    min-height: 380px;
}

.tjsl-feature-media img {
    width: 100%;
    height: 100%;
    min-height: 380px;
    object-fit: cover;
    display: block;
}

.tjsl-feature-copy {
    background: #fff;
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 28px rgba(15,23,42,.05);
    padding: 30px 28px;
}

.tjsl-feature-year {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 999px;
    background: rgba(32,71,18,.08);
    color: #204712;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 16px;
}

.tjsl-feature-title {
    margin: 0 0 14px;
    font-size: 28px;
    line-height: 1.25;
    font-weight: 800;
    color: #111827;
}

.tjsl-feature-summary {
    margin: 0 0 18px;
    color: #4b5563;
    line-height: 1.8;
    font-size: 14px;
}

.tjsl-richtext {
    color: #374151;
    line-height: 1.9;
    font-size: 14px;
}
.tjsl-richtext h2,
.tjsl-richtext h3 {
    color: #111827;
    margin: 18px 0 10px;
    line-height: 1.35;
}
.tjsl-richtext p {
    margin: 0 0 14px;
}
.tjsl-richtext ul,
.tjsl-richtext ol {
    padding-left: 20px;
    margin: 0 0 14px;
}

.tjsl-section-head {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 0 0 20px;
}
.tjsl-section-head-text {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #7a9470;
    white-space: nowrap;
}
.tjsl-section-head-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, #dce8d8 0%, transparent 100%);
}

.tjsl-gallery {
    background: #fff;
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 28px rgba(15,23,42,.05);
    padding: 24px;
}

.tjsl-gallery-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 14px;
}

.tjsl-gallery-item {
    grid-column: span 4;
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    background: #eef5eb;
    min-height: 220px;
}

.tjsl-gallery-item:nth-child(5n + 1) {
    grid-column: span 8;
    min-height: 280px;
}

.tjsl-gallery-item img {
    width: 100%;
    height: 100%;
    min-height: 220px;
    object-fit: cover;
    display: block;
    transition: transform .35s ease;
}

.tjsl-gallery-item:hover img {
    transform: scale(1.04);
}

.tjsl-gallery-caption {
    position: absolute;
    inset: auto 0 0 0;
    padding: 16px 16px 14px;
    background: linear-gradient(to top, rgba(0,0,0,.72), rgba(0,0,0,0));
    color: #fff;
    font-size: 12.5px;
    line-height: 1.55;
}

.tjsl-empty {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 48px 22px;
    text-align: center;
    color: #6b7280;
    box-shadow: 0 10px 24px rgba(15,23,42,.04);
}

@media (max-width: 980px) {
    .tjsl-feature {
        grid-template-columns: 1fr;
    }

    .tjsl-gallery-item,
    .tjsl-gallery-item:nth-child(5n + 1) {
        grid-column: span 6;
    }
}

@media (max-width: 680px) {
    .tjsl-band {
        padding: 28px 0 32px;
    }

    .tjsl-band-inner,
    .tjsl-content {
        padding-left: 16px;
        padding-right: 16px;
    }

    .tjsl-hero {
        padding: 34px 22px 30px;
        border-radius: 18px;
    }

    .tjsl-title {
        font-size: 26px;
    }

    .tjsl-feature-media,
    .tjsl-feature-copy,
    .tjsl-gallery {
        border-radius: 18px;
    }

    .tjsl-feature-copy {
        padding: 22px 18px;
    }

    .tjsl-feature-title {
        font-size: 22px;
    }

    .tjsl-gallery-item,
    .tjsl-gallery-item:nth-child(5n + 1) {
        grid-column: span 12;
        min-height: 210px;
    }
}
</style>

<div class="tjsl-page">

    <div class="tjsl-band">
        <div class="tjsl-band-inner">
            <div class="tjsl-hero">
                <div class="tjsl-hero-orb tjsl-hero-orb-1"></div>
                <div class="tjsl-hero-orb tjsl-hero-orb-2"></div>

                <div class="tjsl-hero-top">
                    <div class="tjsl-eyebrow">
                        <span class="tjsl-eyebrow-line"></span>
                        <span class="tjsl-eyebrow-text">
                            {{ $locale === 'id' ? 'Program Sosial & Lingkungan' : 'Social & Environmental Program' }}
                        </span>
                    </div>

                    <h1 class="tjsl-title">TJSL</h1>

                    <p class="tjsl-desc">
                        {{ $locale === 'id'
                            ? 'Program Tanggung Jawab Sosial dan Lingkungan yang mencerminkan kontribusi perusahaan terhadap masyarakat, pendidikan, lingkungan, dan pembangunan berkelanjutan.'
                            : 'Corporate Social and Environmental Responsibility programs reflecting the company’s contribution to communities, education, the environment, and sustainable development.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="tjsl-content">

        @if($programs->count())
            <div class="tjsl-year-nav">
                @foreach($programs as $program)
                    <a href="{{ route('tjsl.index', ['locale' => $locale, 'year' => $program->year]) }}"
                       class="tjsl-year-chip {{ $activeProgram && $activeProgram->id === $program->id ? 'is-active' : '' }}">
                        {{ $program->year }}
                    </a>
                @endforeach
            </div>

            @if($activeProgram && $activeTranslation)
                <section class="tjsl-feature">
                    <div class="tjsl-feature-media">
                        @if($activeProgram->featured_image)
                            <img src="{{ asset($activeProgram->featured_image) }}" alt="{{ $activeTranslation->title }}">
                        @elseif($activeProgram->images->first())
                            <img src="{{ asset($activeProgram->images->first()->image_path) }}" alt="{{ $activeTranslation->title }}">
                        @else
                            <div style="display:flex;align-items:center;justify-content:center;height:100%;min-height:380px;color:#7a9470;font-weight:700;">
                                {{ $locale === 'id' ? 'Belum ada gambar utama' : 'No featured image yet' }}
                            </div>
                        @endif
                    </div>

                    <div class="tjsl-feature-copy">
                        <div class="tjsl-feature-year">{{ $activeProgram->year }}</div>

                        <h2 class="tjsl-feature-title">{{ $activeTranslation->title }}</h2>

                        @if(!empty($activeTranslation->summary))
                            <p class="tjsl-feature-summary">{{ $activeTranslation->summary }}</p>
                        @endif

                        @if(!empty($activeTranslation->content))
                            <div class="tjsl-richtext">{!! $activeTranslation->content !!}</div>
                        @endif
                    </div>
                </section>

                <section class="tjsl-gallery">
                    <div class="tjsl-section-head">
                        <span class="tjsl-section-head-text">
                            {{ $locale === 'id' ? 'Galeri Dokumentasi' : 'Documentation Gallery' }}
                        </span>
                        <span class="tjsl-section-head-line"></span>
                    </div>

                    @if($activeProgram->images->count())
                        <div class="tjsl-gallery-grid">
                            @foreach($activeProgram->images as $image)
                                <div class="tjsl-gallery-item">
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?? $activeTranslation->title }}">
                                    @if(!empty($image->caption))
                                        <div class="tjsl-gallery-caption">{{ $image->caption }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="tjsl-empty">
                            {{ $locale === 'id' ? 'Belum ada galeri foto untuk tahun ini.' : 'No gallery photos for this year yet.' }}
                        </div>
                    @endif
                </section>
            @endif
        @else
            <div class="tjsl-empty">
                {{ $locale === 'id' ? 'Belum ada program TJSL yang tersedia.' : 'No TJSL programs available yet.' }}
            </div>
        @endif

    </div>
</div>

@endsection