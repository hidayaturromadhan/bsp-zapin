@extends('layouts.app')

@section('title', $locale === 'id' ? 'Good Corporate Governance' : 'Good Corporate Governance')

@section('content')

<style>
.n-main {
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    max-width: none !important;
    width: 100% !important;
}

.gcg-page {
    width: 100%;
    min-height: 100vh;
    background:
        radial-gradient(circle at 8% 42%, rgba(23,63,8,.06), transparent 26%),
        radial-gradient(circle at 92% 70%, rgba(154,111,10,.08), transparent 24%),
        linear-gradient(180deg, #f8faf7 0%, #ffffff 48%, #f7faf6 100%);
    color: #10220c;
    overflow: hidden;
}

/* =========================
   HERO
========================= */
.gcg-band {
    width: 100%;
    background:
        radial-gradient(circle at 10% 22%, rgba(255,255,255,.16), transparent 27%),
        radial-gradient(circle at 86% 24%, rgba(246,210,139,.18), transparent 30%),
        radial-gradient(ellipse 70% 90% at 50% 105%, rgba(47,125,50,.22), transparent 65%),
        linear-gradient(135deg, #102d06 0%, #173f08 48%, #21560e 100%);
    padding: 56px 0 70px;
    position: relative;
    overflow: hidden;
}

.gcg-band::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
    background-size: 56px 56px;
    mask-image: linear-gradient(to bottom, rgba(0,0,0,.72), transparent 76%);
    pointer-events: none;
}

.gcg-band::after {
    content: "";
    position: absolute;
    width: 440px;
    height: 440px;
    right: -180px;
    bottom: -230px;
    border-radius: 999px;
    background: rgba(255,255,255,.075);
    pointer-events: none;
}

.gcg-band-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
    position: relative;
    z-index: 2;
}

.gcg-hero {
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(circle at 12% 22%, rgba(238,246,235,.9), transparent 26%),
        radial-gradient(circle at 86% 18%, rgba(246,210,139,.18), transparent 24%),
        linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
    border: 1px solid rgba(255,255,255,.55);
    border-radius: 30px;
    padding: 58px 48px 52px;
    text-align: center;
    box-shadow:
        0 30px 80px rgba(5,18,2,.34),
        0 8px 22px rgba(5,18,2,.16),
        inset 0 1px 0 rgba(255,255,255,.9);
}

.gcg-hero::before {
    content: "";
    position: absolute;
    inset: 16px;
    border: 1px solid rgba(23,63,8,.07);
    border-radius: 24px;
    pointer-events: none;
}

.gcg-hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    filter: blur(.2px);
}

.gcg-hero-orb-1 {
    width: 280px;
    height: 280px;
    top: -100px;
    right: -80px;
    background: rgba(32,71,18,.07);
}

.gcg-hero-orb-2 {
    width: 180px;
    height: 180px;
    bottom: -70px;
    left: -52px;
    background: rgba(32,71,18,.08);
}

.gcg-hero-orb-3 {
    width: 86px;
    height: 86px;
    top: 38px;
    left: 48px;
    background: rgba(154,111,10,.09);
}

.gcg-hero-accent {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 34px;
    padding: 0 14px;
    margin-bottom: 20px;
    border-radius: 999px;
    background: rgba(23,63,8,.055);
    border: 1px solid rgba(23,63,8,.10);
    position: relative;
    z-index: 2;
}

.gcg-hero-line {
    width: 28px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #204712);
    border-radius: 999px;
    flex-shrink: 0;
}

.gcg-hero-line:last-child {
    background: linear-gradient(90deg, #204712, transparent);
}

.gcg-hero-tag {
    font-size: 10.5px;
    font-weight: 900;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: #204712;
    white-space: nowrap;
}

.gcg-hero-title {
    position: relative;
    z-index: 2;
    font-size: clamp(32px, 4.6vw, 56px);
    font-weight: 900;
    color: #0f1f0a;
    margin: 0 0 16px;
    line-height: 1.05;
    letter-spacing: -.055em;
}

.gcg-hero-title span {
    color: #9a6f0a;
}

.gcg-hero-desc {
    position: relative;
    z-index: 2;
    font-size: 15.5px;
    color: #5a6b55;
    line-height: 1.85;
    max-width: 720px;
    margin: 0 auto 30px;
}

.gcg-hero-desc.no-pills {
    margin-bottom: 0;
}

.gcg-hero-pills {
    position: relative;
    z-index: 2;
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.gcg-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 38px;
    padding: 0 16px;
    border-radius: 999px;
    background: rgba(32,71,18,.065);
    border: 1px solid rgba(32,71,18,.14);
    font-size: 12.5px;
    font-weight: 800;
    color: #204712;
    box-shadow: 0 8px 18px rgba(23,63,8,.045);
}

.gcg-hero-pill-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #204712;
    box-shadow: 0 0 0 4px rgba(32,71,18,.12);
    flex-shrink: 0;
}

/* =========================
   DOCUMENT SECTION
========================= */
.gcg-docs {
    width: 100%;
    padding: 56px 28px 84px;
    position: relative;
}

.gcg-docs-inner {
    max-width: 1280px;
    margin: 0 auto;
}

.gcg-section-label {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 34px;
}

.gcg-section-label-text {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 34px;
    padding: 0 14px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid #e2eadf;
    box-shadow: 0 8px 20px rgba(15,23,42,.035);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #476b3e;
    white-space: nowrap;
}

.gcg-section-label-text::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #204712;
    box-shadow: 0 0 0 4px rgba(32,71,18,.10);
}

.gcg-section-label-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, #dce8d8 0%, transparent 100%);
}

/* =========================
   GRID & CARD
========================= */
.gcg-grid {
    --gcg-card-min: 230px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(var(--gcg-card-min), 1fr));
    gap: 38px 28px;
    justify-content: center;
    align-items: start;
}

.gcg-grid > * {
    min-width: 0;
}

.gcg-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: min(100%, 260px);
    margin: 0 auto;
    opacity: 0;
    animation: gcg-card-in .65s cubic-bezier(.22,.61,.36,1) forwards;
}

.gcg-card:nth-child(2n) { animation-delay: .05s; }
.gcg-card:nth-child(3n) { animation-delay: .1s; }
.gcg-card:nth-child(4n) { animation-delay: .15s; }
.gcg-card:nth-child(5n) { animation-delay: .2s; }
.gcg-card:nth-child(6n) { animation-delay: .25s; }

@keyframes gcg-card-in {
    from {
        opacity: 0;
        transform: translateY(18px) scale(.985);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.gcg-book-wrap {
    position: relative;
    width: 100%;
    padding: 10px;
    border-radius: 24px;
    background:
        linear-gradient(180deg, rgba(255,255,255,.96), rgba(247,250,246,.88));
    border: 1px solid rgba(226,234,223,.95);
    box-shadow:
        0 16px 34px rgba(15,23,42,.07),
        inset 0 1px 0 rgba(255,255,255,.9);
    transition: transform .28s cubic-bezier(.22,.61,.36,1), box-shadow .28s ease, border-color .28s ease;
}

.gcg-card:hover .gcg-book-wrap {
    transform: translateY(-8px);
    border-color: rgba(32,71,18,.20);
    box-shadow:
        0 28px 54px rgba(15,23,42,.13),
        inset 0 1px 0 rgba(255,255,255,.9);
}

.gcg-book-cover {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 4;
    border-radius: 8px 18px 18px 8px;
    overflow: hidden;
    background: #edf4eb;
    border-left: 7px solid rgba(11,26,6,.22);
    box-shadow:
        inset -2px 0 8px rgba(0,0,0,.06),
        inset 2px 0 4px rgba(255,255,255,.65),
        0 1px 0 rgba(255,255,255,.55);
    transition: transform .32s cubic-bezier(.22,.61,.36,1), box-shadow .32s ease;
}

.gcg-card:hover .gcg-book-cover {
    transform: perspective(900px) rotateY(-4deg) rotateX(.6deg);
    box-shadow:
        inset -2px 0 8px rgba(0,0,0,.06),
        inset 2px 0 4px rgba(255,255,255,.65),
        0 18px 30px rgba(5,18,2,.13);
}

.gcg-book-cover::before {
    content: "";
    position: absolute;
    top: 8px;
    bottom: 8px;
    right: -4px;
    width: 4px;
    background: repeating-linear-gradient(
        to bottom,
        #dedede 0px,
        #dedede 1px,
        #f8f8f8 1px,
        #f8f8f8 3px
    );
    border-radius: 0 2px 2px 0;
    z-index: 1;
}

.gcg-book-cover::after {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg, rgba(255,255,255,.20) 0%, rgba(255,255,255,0) 18%),
        linear-gradient(180deg, rgba(255,255,255,.12) 0%, rgba(255,255,255,0) 34%, rgba(0,0,0,.06) 100%);
    pointer-events: none;
    z-index: 2;
}

.gcg-book-cover img {
    position: relative;
    z-index: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .38s cubic-bezier(.22,.61,.36,1), filter .38s ease;
}

.gcg-card:hover .gcg-book-cover img {
    transform: scale(1.045);
    filter: saturate(1.04) contrast(1.02);
}

/* =========================
   PLACEHOLDER
========================= */
.gcg-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 18px;
    background:
        radial-gradient(circle at 30% 18%, rgba(255,255,255,.7), transparent 24%),
        linear-gradient(160deg, #f2f8f0 0%, #dcebd7 100%);
    padding: 34px;
    text-align: center;
    position: relative;
    z-index: 1;
}

.gcg-placeholder-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, #173f08 0%, #28561a 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
        0 12px 26px rgba(32,71,18,.32),
        0 2px 6px rgba(32,71,18,.18);
}

.gcg-placeholder-text {
    font-size: 12.5px;
    color: #6b8065;
    line-height: 1.65;
    max-width: 150px;
    font-weight: 650;
}

/* =========================
   OVERLAY
========================= */
.gcg-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(to top, rgba(4,12,2,.96) 0%, rgba(4,12,2,.74) 46%, rgba(4,12,2,.12) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    gap: 11px;
    padding: 20px 14px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: opacity .24s ease, visibility .24s ease, transform .28s cubic-bezier(.22,.61,.36,1);
    z-index: 10;
}

.gcg-card:hover .gcg-overlay,
.gcg-card:focus-within .gcg-overlay {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.gcg-overlay-title {
    font-size: 12px;
    font-weight: 750;
    color: rgba(255,255,255,.84);
    text-align: center;
    line-height: 1.45;
    margin-bottom: 2px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    width: 100%;
}

.gcg-overlay-actions {
    display: flex;
    gap: 8px;
    width: 100%;
}

.gcg-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 42px;
    border-radius: 13px;
    font-size: 12.5px;
    font-weight: 900;
    text-decoration: none;
    transition: transform .16s ease, box-shadow .16s ease, background .16s ease, border-color .16s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.gcg-btn:hover {
    transform: translateY(-2px);
}

.gcg-btn--view {
    background: rgba(255,255,255,.96);
    color: #204712;
    box-shadow: 0 8px 18px rgba(0,0,0,.20);
}

.gcg-btn--view:hover {
    background: #fff;
}

.gcg-btn--dl {
    background: linear-gradient(180deg, #28561a 0%, #204712 100%);
    color: #fff;
    border: 1px solid rgba(255,255,255,.14);
    box-shadow: 0 10px 22px rgba(32,71,18,.36);
}

.gcg-btn--dl:hover {
    background: linear-gradient(180deg, #2f651e 0%, #204712 100%);
}

/* =========================
   CARD META
========================= */
.gcg-card-title {
    width: 100%;
    margin-top: 16px;
    font-size: 14.5px;
    font-weight: 850;
    color: #1a2e16;
    line-height: 1.55;
    text-align: center;
    letter-spacing: -.01em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* =========================
   EMPTY
========================= */
.gcg-empty {
    grid-column: 1 / -1;
    width: 100%;
    text-align: center;
    padding: 68px 28px;
    color: #70846b;
    font-size: 14px;
    border: 1.5px dashed rgba(32,71,18,.18);
    border-radius: 24px;
    background:
        radial-gradient(circle at 50% 0%, rgba(32,71,18,.06), transparent 34%),
        rgba(255,255,255,.68);
    box-shadow: 0 10px 26px rgba(15,23,42,.035);
}

.gcg-empty-icon {
    width: 62px;
    height: 62px;
    border-radius: 20px;
    background: #eef6eb;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #204712;
    box-shadow: 0 10px 24px rgba(15,23,42,.045);
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 1060px) {
    .gcg-band-inner {
        padding: 0 20px;
    }

    .gcg-docs {
        padding: 46px 20px 66px;
    }

    .gcg-grid {
        --gcg-card-min: 205px;
        gap: 30px 20px;
    }

    .gcg-card {
        width: min(100%, 238px);
    }
}

@media (max-width: 760px) {
    .gcg-band {
        padding: 34px 0 42px;
    }

    .gcg-band-inner {
        padding: 0 16px;
    }

    .gcg-hero {
        padding: 38px 22px 34px;
        border-radius: 24px;
    }

    .gcg-hero::before {
        inset: 10px;
        border-radius: 19px;
    }

    .gcg-hero-accent {
        gap: 8px;
        padding: 0 12px;
    }

    .gcg-hero-line {
        width: 18px;
    }

    .gcg-hero-tag {
        font-size: 9.5px;
        letter-spacing: .12em;
    }

    .gcg-hero-title {
        font-size: 30px;
        letter-spacing: -.04em;
    }

    .gcg-hero-desc {
        font-size: 13.8px;
        line-height: 1.75;
    }

    .gcg-hero-pill {
        min-height: 34px;
        padding: 0 13px;
        font-size: 11.5px;
    }

    .gcg-docs {
        padding: 38px 16px 58px;
    }

    .gcg-section-label {
        margin-bottom: 26px;
    }

    .gcg-section-label-text {
        font-size: 10px;
        letter-spacing: .10em;
    }

    .gcg-grid {
        --gcg-card-min: 155px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 26px 14px;
    }

    .gcg-card {
        width: 100%;
        max-width: none;
    }

    .gcg-book-wrap {
        padding: 8px;
        border-radius: 20px;
    }

    .gcg-book-cover {
        border-radius: 7px 15px 15px 7px;
        border-left-width: 6px;
    }

    .gcg-overlay {
        opacity: 1;
        visibility: visible;
        transform: none;
        padding: 10px;
        gap: 7px;
        background:
            linear-gradient(to top, rgba(4,12,2,.86) 0%, rgba(4,12,2,.44) 54%, rgba(4,12,2,.04) 100%);
    }

    .gcg-overlay-title {
        display: none;
    }

    .gcg-overlay-actions {
        gap: 6px;
    }

    .gcg-btn {
        height: 34px;
        font-size: 10.5px;
        border-radius: 10px;
        gap: 4px;
    }

    .gcg-btn svg {
        width: 12px;
        height: 12px;
    }

    .gcg-card-title {
        font-size: 12.5px;
        margin-top: 10px;
        line-height: 1.45;
    }
}

@media (max-width: 430px) {
    .gcg-hero-title {
        font-size: 27px;
    }

    .gcg-hero-accent {
        max-width: 100%;
    }

    .gcg-hero-tag {
        white-space: normal;
        line-height: 1.4;
    }

    .gcg-grid {
        grid-template-columns: 1fr;
    }

    .gcg-card {
        max-width: 280px;
    }

    .gcg-overlay {
        padding: 14px;
    }

    .gcg-btn {
        height: 38px;
        font-size: 11.5px;
    }

    .gcg-card-title {
        font-size: 13.5px;
    }
}
</style>

<div class="gcg-page">

    <div class="gcg-band">
        <div class="gcg-band-inner">
            <div class="gcg-hero">
                <div class="gcg-hero-orb gcg-hero-orb-1"></div>
                <div class="gcg-hero-orb gcg-hero-orb-2"></div>
                <div class="gcg-hero-orb gcg-hero-orb-3"></div>

                <div class="gcg-hero-accent">
                    <div class="gcg-hero-line"></div>
                    <span class="gcg-hero-tag">PT BUMI SIAK PUSAKO ZAPIN</span>
                    <div class="gcg-hero-line"></div>
                </div>

                <h1 class="gcg-hero-title">
                    Good Corporate <span>Governance</span>
                </h1>

                <div class="gcg-hero-desc {{ isset($highlightItems) && $highlightItems->count() ? '' : 'no-pills' }}">
                    {{ $locale === 'id'
                        ? 'Pedoman tata kelola, dokumen kebijakan, dan regulasi perusahaan yang transparan, akuntabel, serta mudah diakses.'
                        : 'Corporate governance guidelines, policy documents, and regulations presented transparently, accountably, and with easy access.' }}
                </div>

                @if(isset($highlightItems) && $highlightItems->count())
                    <div class="gcg-hero-pills">
                        @foreach($highlightItems as $item)
                            @php
                                $hl = $locale === 'en'
                                    ? (!empty($item->label_en) ? $item->label_en : $item->label_id)
                                    : $item->label_id;
                            @endphp

                            <span class="gcg-hero-pill">
                                <span class="gcg-hero-pill-dot"></span>
                                {{ $hl }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="gcg-docs">
        <div class="gcg-docs-inner">

            <div class="gcg-section-label">
                <span class="gcg-section-label-text">
                    {{ $locale === 'id' ? 'Dokumen GCG' : 'GCG Documents' }}
                </span>
                <div class="gcg-section-label-line"></div>
            </div>

            <div class="gcg-grid">
                @forelse($documents as $doc)
                    @php
                        $tr = $doc->translations->firstWhere('locale', $locale) ?? $doc->translations->first();
                        $docTitle = $tr->title ?? $doc->file_name ?? 'Document';
                    @endphp

                    <div class="gcg-card">
                        <div class="gcg-book-wrap">
                            <div class="gcg-book-cover">

                                @if(!empty($doc->cover))
                                    <img src="{{ asset('images/gcg/' . $doc->cover) }}" alt="{{ $docTitle }}">
                                @else
                                    <div class="gcg-placeholder">
                                        <div class="gcg-placeholder-icon">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/>
                                                <polyline points="14 2 14 9 21 9"/>
                                                <line x1="9" y1="13" x2="15" y2="13"/>
                                                <line x1="9" y1="17" x2="13" y2="17"/>
                                            </svg>
                                        </div>
                                        <div class="gcg-placeholder-text">
                                            {{ $locale === 'id' ? 'Cover belum tersedia.' : 'Cover not available.' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="gcg-overlay">
                                    <div class="gcg-overlay-title">{{ $docTitle }}</div>

                                    <div class="gcg-overlay-actions">
                                        <a href="{{ asset('documents/gcg/' . $doc->file_path) }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="gcg-btn gcg-btn--view">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                            {{ $locale === 'id' ? 'Lihat' : 'View' }}
                                        </a>

                                        <a href="{{ route('gcg.download', $doc) }}"
                                           class="gcg-btn gcg-btn--dl">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2.2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            {{ $locale === 'id' ? 'Unduh' : 'Download' }}
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="gcg-card-title">{{ $docTitle }}</div>
                    </div>
                @empty
                    <div class="gcg-empty">
                        <div class="gcg-empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.6"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/>
                                <polyline points="14 2 14 9 21 9"/>
                            </svg>
                        </div>

                        {{ $locale === 'id' ? 'Belum ada dokumen GCG.' : 'No GCG documents yet.' }}
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</div>

@endsection