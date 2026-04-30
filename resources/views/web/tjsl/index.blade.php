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
    padding: 0 !important;
    max-width: none !important;
    width: 100% !important;
}

.tj-page {
    width: 100%;
    background: var(--bg);
    font-family: var(--font);
    color: var(--text);
}

.tj-hero {
    background: var(--g900);
    background-image:
        radial-gradient(ellipse 65% 90% at 8% 55%, rgba(47,125,50,.3) 0%, transparent 60%),
        radial-gradient(ellipse 45% 65% at 92% 20%, rgba(32,71,18,.5) 0%, transparent 55%);
    padding: 52px 0 56px;
}

.tj-hero-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
}

.tj-kicker {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.tj-kicker-line {
    width: 28px;
    height: 2px;
    border-radius: 2px;
    background: var(--gold-lt);
}

.tj-kicker-text {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.45);
}

.tj-hero-title {
    font-size: clamp(30px, 4.5vw, 52px);
    font-weight: 800;
    letter-spacing: -.035em;
    line-height: 1.05;
    color: #fff;
    margin: 0 0 16px;
}

.tj-hero-title span {
    color: var(--gold-lt);
}

.tj-hero-desc {
    font-size: 14px;
    line-height: 1.75;
    color: rgba(255,255,255,.45);
    max-width: 560px;
    margin: 0;
}

.tj-nav-strip {
    background: var(--white);
    border-bottom: 1px solid var(--line);
    position: sticky;
    top: var(--nav-h);
    z-index: 40;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
}

.tj-nav-scroll {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 28px;
    display: flex;
    align-items: center;
    gap: 2px;
    overflow-x: auto;
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.tj-nav-scroll::-webkit-scrollbar {
    display: none;
}

.tj-year-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 13px 16px;
    font-family: var(--font);
    font-size: 13px;
    font-weight: 600;
    color: var(--text3);
    text-decoration: none;
    white-space: nowrap;
    border-bottom: 2px solid transparent;
    transition: color .15s, border-color .15s;
    flex-shrink: 0;
}

.tj-year-btn:hover {
    color: var(--g900);
}

.tj-year-btn.is-active {
    color: var(--g900);
    font-weight: 700;
    border-bottom-color: var(--g500);
}

.tj-year-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--line);
    transition: background .15s;
    flex-shrink: 0;
}

.tj-year-btn.is-active .tj-year-dot,
.tj-year-btn:hover .tj-year-dot {
    background: var(--g500);
}

.tj-content {
    max-width: 1280px;
    margin: 0 auto;
    padding: 40px 28px 64px;
}

.tj-skeleton {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--line);
    margin-bottom: 24px;
    background: var(--white);
    min-height: 430px;
}

.tj-skel-media {
    background: var(--line2);
    position: relative;
    overflow: hidden;
}

.tj-skel-copy {
    padding: 36px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    border-left: 1px solid var(--line);
}

.tj-skel-gallery {
    background: var(--white);
    border-radius: 20px;
    border: 1px solid var(--line);
    overflow: hidden;
    margin-bottom: 0;
}

.tj-skel-gallery-head {
    padding: 20px 24px;
    border-bottom: 1px solid var(--line);
}

.tj-skel-ggrid {
    display: grid;
    grid-template-columns: repeat(12,1fr);
    gap: 8px;
    padding: 8px;
}

.tj-sgitem {
    position: relative;
    min-height: 180px;
    border-radius: 14px;
    overflow: hidden;
}

.tj-sgitem:nth-child(1) {
    grid-column: span 6;
    min-height: 360px;
}

.tj-sgitem:nth-child(2),
.tj-sgitem:nth-child(3),
.tj-sgitem:nth-child(4),
.tj-sgitem:nth-child(5) {
    grid-column: span 3;
}

.skel {
    border-radius: 7px;
    background: linear-gradient(90deg, var(--line2) 25%, var(--line) 50%, var(--line2) 75%);
    background-size: 200% 100%;
    animation: skelwave 1.5s ease-in-out infinite;
}

@keyframes skelwave {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.skel-fill {
    position:absolute;
    inset:0;
    border-radius:0;
}

.skel-pill {
    height:24px;
    width:68px;
    border-radius:999px;
}

.skel-h1 {
    height:34px;
    width:72%;
}

.skel-h2 {
    height:34px;
    width:48%;
}

.skel-ln {
    height:12px;
}

.skel-ln-s {
    height:12px;
    width:62%;
}

.tj-feature {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--line);
    box-shadow: 0 6px 28px rgba(15,23,42,.06), 0 2px 6px rgba(15,23,42,.04);
    margin-bottom: 24px;
    background: var(--white);
    align-items: stretch;
}

.tj-slider {
    position: relative;
    overflow: hidden;
    background: var(--g100);
    height: 430px;
    min-height: 430px;
}

.tj-slides {
    display: flex;
    height: 100%;
    will-change: transform;
    transition: transform .55s cubic-bezier(.77,0,.175,1);
}

.tj-slide {
    flex: 0 0 100%;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.tj-slide img {
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
    transition: transform 7s ease;
}

.tj-slide.is-active img {
    transform: scale(1.05);
}

.tj-slide-overlay {
    position:absolute;
    inset:0;
    background: linear-gradient(160deg, rgba(23,63,8,.18) 0%, transparent 55%, rgba(0,0,0,.12) 100%);
}

.tj-arrow {
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:36px;
    height:36px;
    border-radius:50%;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(6px);
    border:1px solid rgba(255,255,255,.2);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    z-index:5;
    transition:background .14s;
}

.tj-arrow:hover {
    background:rgba(255,255,255,.26);
}

.tj-arrow.prev {
    left:12px;
}

.tj-arrow.next {
    right:12px;
}

.tj-slider-dots {
    position:absolute;
    bottom:14px;
    left:50%;
    transform:translateX(-50%);
    display:flex;
    align-items:center;
    gap:7px;
    z-index:5;
}

.tj-sdot {
    width:6px;
    height:6px;
    border-radius:999px;
    background:rgba(255,255,255,.32);
    border:none;
    cursor:pointer;
    padding:0;
    transition:all .25s;
}

.tj-sdot.is-active {
    background:#fff;
    width:20px;
}

.tj-slider-count {
    position:absolute;
    top:12px;
    right:12px;
    background:rgba(0,0,0,.32);
    backdrop-filter:blur(6px);
    color:rgba(255,255,255,.72);
    font-size:11px;
    font-weight:600;
    letter-spacing:.05em;
    padding:3px 10px;
    border-radius:999px;
    z-index:5;
}

.tj-no-img {
    width:100%;
    height:100%;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    color:var(--g500);
    gap:10px;
    background:var(--g100);
}

.tj-no-img svg {
    opacity:.28;
}

.tj-no-img span {
    font-size:13px;
    color:var(--text3);
}

.tj-copy {
    padding: 28px 32px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    border-left:1px solid var(--line);
    background:var(--white);
    min-height: 430px;
    height: 430px;
    overflow: hidden;
    transition: height .48s ease, min-height .48s ease;
}

.tj-copy.is-expanded {
    height: auto;
    min-height: 430px;
    overflow: visible;
    justify-content:flex-start;
}

.tj-copy-tag {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:4px 12px;
    background:var(--g100);
    border:1px solid var(--g200);
    border-radius:999px;
    font-size:11.5px;
    font-weight:700;
    color:var(--g800);
    letter-spacing:.04em;
    margin-bottom:18px;
    width:fit-content;
    flex-shrink:0;
}

.tj-copy-tag-dot {
    width:5px;
    height:5px;
    border-radius:50%;
    background:var(--g500);
}

.tj-copy-title {
    font-size: clamp(20px,2.2vw,24px);
    font-weight:800;
    letter-spacing:-.025em;
    line-height:1.2;
    color:var(--text);
    margin:0 0 14px;
    flex-shrink:0;
}

.tj-copy-summary {
    font-size:13.5px;
    line-height:1.7;
    color:var(--text3);
    margin:0 0 14px;
    padding-bottom:14px;
    border-bottom:1px solid var(--line2);
    text-align:justify;
    text-justify:inter-word;
    flex-shrink:0;
}

.tj-richtext-wrap {
    position: relative;
    overflow: hidden;
    max-height: 104px;
    transition: max-height .55s ease;
}

.tj-richtext-wrap.is-collapsed {
    max-height: 104px;
}

.tj-richtext-wrap.is-expanded {
    max-height: var(--expanded-height, 1400px);
}

.tj-richtext-wrap.is-collapsed::after {
    content:"";
    position:absolute;
    left:0;
    right:0;
    bottom:0;
    height:52px;
    background:linear-gradient(to bottom, rgba(255,255,255,0), #fff 88%);
    pointer-events:none;
}

.tj-richtext-wrap.is-expanded::after {
    display:none;
}

.tj-richtext {
    font-size:13.5px;
    line-height:1.75;
    color:var(--text2);
    text-align:justify;
    text-justify:inter-word;
}

.tj-richtext h2,
.tj-richtext h3 {
    color:var(--text);
    margin:14px 0 7px;
    font-size:15px;
}

.tj-richtext p {
    margin:0 0 10px;
}

.tj-richtext ul,
.tj-richtext ol {
    padding-left:16px;
    margin:0 0 10px;
}

.tj-more-btn {
    margin-top: 12px;
    width: fit-content;
    display: inline-flex;
    align-items:center;
    gap:8px;
    border:1px solid var(--g200);
    background:var(--g100);
    color:var(--g800);
    border-radius:999px;
    padding:8px 13px;
    font-size:12px;
    font-weight:800;
    cursor:pointer;
    transition:background .18s ease, transform .18s ease;
    flex-shrink:0;
}

.tj-more-btn:hover {
    background:var(--g200);
    transform:translateY(-1px);
}

.tj-more-icon {
    transition: transform .35s ease;
}

.tj-more-btn.is-open .tj-more-icon {
    transform:rotate(180deg);
}

.tj-gallery {
    background:var(--white);
    border-radius:20px;
    border:1px solid var(--line);
    box-shadow:0 4px 14px rgba(15,23,42,.04);
    overflow:hidden;
}

.tj-gallery-head {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:18px 24px;
    border-bottom:1px solid var(--line2);
}

.tj-gallery-head-l {
    display:flex;
    align-items:center;
    gap:9px;
}

.tj-gallery-icon {
    width:28px;
    height:28px;
    border-radius:8px;
    background:var(--g100);
    border:1px solid var(--g200);
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--g500);
    flex-shrink:0;
}

.tj-gallery-ttl {
    font-size:11.5px;
    font-weight:700;
    letter-spacing:.1em;
    text-transform:uppercase;
    color:var(--text3);
}

.tj-gallery-badge {
    font-size:11.5px;
    font-weight:600;
    color:var(--text3);
    padding:3px 10px;
    background:var(--line2);
    border-radius:999px;
}

.tj-gallery-grid {
    display:grid;
    grid-template-columns:repeat(12,1fr);
    grid-auto-rows:170px;
    gap:8px;
    padding:8px;
    grid-auto-flow:dense;
}

.tj-gitem {
    position:relative;
    overflow:hidden;
    background:var(--g100);
    cursor:zoom-in;
    border-radius:14px;
    min-height:170px;
}

.tj-gallery-grid.count-1 {
    grid-auto-rows:430px;
}

.tj-gallery-grid.count-1 .tj-gitem {
    grid-column:span 12;
    grid-row:span 1;
}

.tj-gallery-grid.count-2 {
    grid-auto-rows:330px;
}

.tj-gallery-grid.count-2 .tj-gitem {
    grid-column:span 6;
    grid-row:span 1;
}

.tj-gallery-grid.count-3 .tj-gitem:nth-child(1) {
    grid-column:span 7;
    grid-row:span 2;
}

.tj-gallery-grid.count-3 .tj-gitem:nth-child(2),
.tj-gallery-grid.count-3 .tj-gitem:nth-child(3) {
    grid-column:span 5;
    grid-row:span 1;
}

.tj-gallery-grid.count-4 .tj-gitem:nth-child(1) {
    grid-column:span 7;
    grid-row:span 2;
}

.tj-gallery-grid.count-4 .tj-gitem:nth-child(2),
.tj-gallery-grid.count-4 .tj-gitem:nth-child(3) {
    grid-column:span 5;
    grid-row:span 1;
}

.tj-gallery-grid.count-4 .tj-gitem:nth-child(4) {
    grid-column:span 12;
    grid-row:span 1;
}

.tj-gallery-grid.count-5 .tj-gitem:nth-child(1) {
    grid-column:span 6;
    grid-row:span 2;
}

.tj-gallery-grid.count-5 .tj-gitem:nth-child(2) {
    grid-column:span 6;
    grid-row:span 1;
}

.tj-gallery-grid.count-5 .tj-gitem:nth-child(3),
.tj-gallery-grid.count-5 .tj-gitem:nth-child(4) {
    grid-column:span 3;
    grid-row:span 1;
}

.tj-gallery-grid.count-5 .tj-gitem:nth-child(5) {
    grid-column:span 12;
    grid-row:span 1;
}

.tj-gitem-inner {
    position:absolute;
    inset:0;
    overflow:hidden;
}

.tj-gitem img {
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
    transition:transform .42s var(--ease);
}

.tj-gitem:hover img {
    transform:scale(1.05);
}

.tj-gitem-caption {
    position:absolute;
    inset:auto 0 0 0;
    padding:26px 14px 12px;
    background:linear-gradient(to top, rgba(23,63,8,.7), transparent);
    color:rgba(255,255,255,.88);
    font-size:12px;
    line-height:1.4;
    transform:translateY(100%);
    transition:transform .26s var(--ease);
}

.tj-gitem:hover .tj-gitem-caption {
    transform:translateY(0);
}

.tj-gallery-empty {
    padding:48px 24px;
    text-align:center;
    color:var(--text3);
    font-size:13.5px;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:10px;
}

.tj-gallery-empty svg {
    opacity:.22;
}

.tj-lb {
    position:fixed;
    inset:0;
    background:rgba(5,12,2,.9);
    z-index:9999;
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    pointer-events:none;
    transition:opacity .22s var(--ease);
    backdrop-filter:blur(5px);
}

.tj-lb.is-open {
    opacity:1;
    pointer-events:auto;
}

.tj-lb-img {
    max-width:88vw;
    max-height:86vh;
    object-fit:contain;
    border-radius:8px;
    box-shadow:0 32px 80px rgba(0,0,0,.6);
    transform:scale(.94);
    opacity:0;
    transition:transform .24s var(--ease), opacity .16s var(--ease);
}

.tj-lb.is-open .tj-lb-img {
    transform:scale(1);
    opacity:1;
}

.tj-lb-close {
    position:absolute;
    top:18px;
    right:20px;
    width:36px;
    height:36px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.13);
    color:rgba(255,255,255,.62);
    font-size:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:background .13s;
}

.tj-lb-close:hover {
    background:rgba(255,255,255,.15);
}

.tj-lb-nav {
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:40px;
    height:40px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    color:rgba(255,255,255,.62);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:background .13s;
}

.tj-lb-nav:hover {
    background:rgba(255,255,255,.16);
}

.tj-lb-nav.prev {
    left:18px;
}

.tj-lb-nav.next {
    right:18px;
}

.tj-lb-cap {
    position:absolute;
    bottom:18px;
    left:50%;
    transform:translateX(-50%);
    background:rgba(0,0,0,.42);
    color:rgba(255,255,255,.62);
    font-size:12px;
    padding:5px 14px;
    border-radius:999px;
    white-space:nowrap;
    max-width:80vw;
    overflow:hidden;
    text-overflow:ellipsis;
}

.tj-empty {
    background:var(--white);
    border:1px solid var(--line);
    border-radius:20px;
    padding:64px 24px;
    text-align:center;
}

.tj-empty-icon {
    width:48px;
    height:48px;
    border-radius:14px;
    background:var(--g100);
    border:1px solid var(--g200);
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 14px;
    color:var(--g500);
}

.tj-empty-title {
    font-size:16px;
    font-weight:700;
    color:var(--text);
    margin:0 0 5px;
}

.tj-empty-text {
    font-size:13.5px;
    color:var(--text3);
}

.tj-fade {
    opacity:0;
    transform:translateY(18px);
    transition:opacity .45s var(--ease), transform .45s var(--ease);
}

.tj-fade.in {
    opacity:1;
    transform:translateY(0);
}

.tj-fade.d1 {
    transition-delay:.08s;
}

@media (max-width: 900px) {
    .tj-feature,
    .tj-skeleton {
        grid-template-columns:1fr;
    }

    .tj-slider {
        height:280px;
        min-height:280px;
    }

    .tj-copy {
        height:auto;
        min-height:auto;
        overflow:visible;
        border-left:none;
        border-top:1px solid var(--line2);
        justify-content:flex-start;
    }

    .tj-copy.is-expanded {
        min-height:auto;
    }

    .tj-richtext-wrap.is-collapsed {
        max-height: 150px;
    }

    .tj-gallery-grid,
    .tj-gallery-grid.count-1,
    .tj-gallery-grid.count-2,
    .tj-gallery-grid.count-3,
    .tj-gallery-grid.count-4,
    .tj-gallery-grid.count-5 {
        grid-template-columns:repeat(2,minmax(0,1fr));
        grid-auto-rows:220px;
    }

    .tj-gallery-grid.count-1 .tj-gitem,
    .tj-gallery-grid.count-2 .tj-gitem,
    .tj-gallery-grid.count-3 .tj-gitem,
    .tj-gallery-grid.count-4 .tj-gitem,
    .tj-gallery-grid.count-5 .tj-gitem {
        grid-column:auto !important;
        grid-row:auto !important;
        min-height:220px;
    }

    .tj-gallery-grid.count-1 .tj-gitem,
    .tj-gallery-grid.count-3 .tj-gitem:nth-child(1),
    .tj-gallery-grid.count-4 .tj-gitem:nth-child(1),
    .tj-gallery-grid.count-5 .tj-gitem:nth-child(1) {
        grid-column:span 2 !important;
        min-height:300px;
    }
}

@media (max-width: 680px) {
    .tj-hero-inner,
    .tj-content,
    .tj-nav-scroll {
        padding-left:16px;
        padding-right:16px;
    }

    .tj-hero {
        padding:36px 0 40px;
    }

    .tj-gallery-grid,
    .tj-gallery-grid.count-1,
    .tj-gallery-grid.count-2,
    .tj-gallery-grid.count-3,
    .tj-gallery-grid.count-4,
    .tj-gallery-grid.count-5 {
        grid-template-columns:1fr;
        grid-auto-rows:230px;
        gap:8px;
        padding:8px;
    }

    .tj-gallery-grid.count-1 .tj-gitem,
    .tj-gallery-grid.count-2 .tj-gitem,
    .tj-gallery-grid.count-3 .tj-gitem,
    .tj-gallery-grid.count-4 .tj-gitem,
    .tj-gallery-grid.count-5 .tj-gitem {
        grid-column:auto !important;
        grid-row:auto !important;
        min-height:230px;
    }

    .tj-gallery-grid.count-3 .tj-gitem:nth-child(1),
    .tj-gallery-grid.count-4 .tj-gitem:nth-child(1),
    .tj-gallery-grid.count-5 .tj-gitem:nth-child(1) {
        grid-column:auto !important;
        min-height:250px;
    }

    .tj-gitem-caption {
        transform:translateY(0);
        background:linear-gradient(to top, rgba(23,63,8,.65), transparent);
    }
}
</style>

<div class="tj-lb" id="tjLb" role="dialog" aria-modal="true" aria-label="Photo viewer">
    <button class="tj-lb-close" id="tjLbClose" aria-label="Close">&#x2715;</button>
    <button class="tj-lb-nav prev" id="tjLbPrev" aria-label="Previous">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <img class="tj-lb-img" id="tjLbImg" src="" alt="">
    <button class="tj-lb-nav next" id="tjLbNext" aria-label="Next">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="tj-lb-cap" id="tjLbCap"></div>
</div>

<div class="tj-page">
    <div class="tj-hero">
        <div class="tj-hero-inner">
            <div class="tj-hero-left">
                <div class="tj-kicker">
                    <span class="tj-kicker-line"></span>
                    <span class="tj-kicker-text">
                        {{ $locale === 'id' ? 'Program Sosial & Lingkungan' : 'Social & Environmental Program' }}
                    </span>
                </div>
                <h1 class="tj-hero-title">
                    TJSL <span>/ CSR</span>
                </h1>
                <p class="tj-hero-desc">
                    {{ $locale === 'id'
                        ? 'Program Tanggung Jawab Sosial dan Lingkungan yang mencerminkan kontribusi nyata perusahaan terhadap masyarakat, pendidikan, lingkungan, dan pembangunan berkelanjutan.'
                        : 'Corporate Social and Environmental Responsibility programs reflecting the company\'s real contribution to communities, education, the environment, and sustainable development.' }}
                </p>
            </div>
        </div>
    </div>

    @if($programs->count())
        <div class="tj-nav-strip">
            <div class="tj-nav-scroll">
                @foreach($programs as $program)
                    <a href="{{ route('tjsl.index', ['locale' => $locale, 'year' => $program->year]) }}"
                       class="tj-year-btn {{ $activeProgram && $activeProgram->id === $program->id ? 'is-active' : '' }}">
                        <span class="tj-year-dot"></span>
                        {{ $program->year }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="tj-content">
        @if($programs->count())
            <div class="tj-skeleton" id="tjSkel" aria-hidden="true">
                <div class="tj-skel-media"><div class="skel skel-fill"></div></div>
                <div class="tj-skel-copy">
                    <div class="skel skel-pill"></div>
                    <div class="skel skel-h1"></div>
                    <div class="skel skel-h2"></div>
                    <div style="height:6px"></div>
                    <div class="skel skel-ln"></div>
                    <div class="skel skel-ln"></div>
                    <div class="skel skel-ln-s"></div>
                    <div style="height:6px"></div>
                    <div class="skel skel-ln"></div>
                    <div class="skel skel-ln"></div>
                    <div class="skel skel-ln" style="width:86%"></div>
                    <div class="skel skel-ln-s"></div>
                </div>
            </div>

            <div class="tj-skel-gallery" id="tjSkelG" aria-hidden="true" style="margin-bottom:0">
                <div class="tj-skel-gallery-head">
                    <div class="skel skel-pill" style="width:130px;height:20px"></div>
                </div>
                <div class="tj-skel-ggrid">
                    @for($s = 1; $s <= 5; $s++)
                        <div class="tj-sgitem">
                            <div class="skel skel-fill"></div>
                        </div>
                    @endfor
                </div>
            </div>

            @if($activeProgram && $activeTranslation)
                <div class="tj-feature tj-fade" id="tjFeature" style="display:none">
                    <div class="tj-slider" id="tjSlider">
                        @php
                            $allSlides = collect();
                            if ($activeProgram->featured_image) {
                                $allSlides->push(['src' => asset($activeProgram->featured_image), 'cap' => $activeTranslation->title]);
                            }
                            foreach ($activeProgram->images as $img) {
                                $allSlides->push(['src' => asset($img->image_path), 'cap' => $img->caption ?? '']);
                            }
                        @endphp

                        @if($allSlides->count())
                            <div class="tj-slides" id="tjSlides">
                                @foreach($allSlides as $i => $sl)
                                    <div class="tj-slide {{ $i === 0 ? 'is-active' : '' }}">
                                        <img src="{{ $sl['src'] }}" alt="{{ $sl['cap'] }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                        <div class="tj-slide-overlay"></div>
                                    </div>
                                @endforeach
                            </div>

                            @if($allSlides->count() > 1)
                                <button class="tj-arrow prev" id="tjPrev" aria-label="Previous">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                                </button>
                                <button class="tj-arrow next" id="tjNext" aria-label="Next">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </button>
                                <div class="tj-slider-dots" id="tjDots">
                                    @foreach($allSlides as $i => $sl)
                                        <button class="tj-sdot {{ $i === 0 ? 'is-active' : '' }}" data-i="{{ $i }}" aria-label="Slide {{ $i+1 }}"></button>
                                    @endforeach
                                </div>
                                <div class="tj-slider-count" id="tjCount">1 / {{ $allSlides->count() }}</div>
                            @endif
                        @else
                            <div class="tj-no-img">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                <span>{{ $locale === 'id' ? 'Belum ada gambar' : 'No images yet' }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="tj-copy" data-copy-box>
                        <div class="tj-copy-tag">
                            <span class="tj-copy-tag-dot"></span>
                            {{ $locale === 'id' ? 'Program' : 'Program' }} {{ $activeProgram->year }}
                        </div>

                        <h2 class="tj-copy-title">{{ $activeTranslation->title }}</h2>

                        @if(!empty($activeTranslation->summary))
                            <p class="tj-copy-summary">{{ $activeTranslation->summary }}</p>
                        @endif

                        @if(!empty($activeTranslation->content))
                            <div class="tj-richtext-wrap is-collapsed" data-read-wrap>
                                <div class="tj-richtext">{!! $activeTranslation->content !!}</div>
                            </div>

                            <button type="button" class="tj-more-btn" data-read-more>
                                <span data-more-text>{{ $locale === 'id' ? 'Selengkapnya' : 'Read more' }}</span>
                                <svg class="tj-more-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="tj-gallery tj-fade d1" id="tjGallery" style="display:none">
                    <div class="tj-gallery-head">
                        <div class="tj-gallery-head-l">
                            <div class="tj-gallery-icon">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                            <span class="tj-gallery-ttl">
                                {{ $locale === 'id' ? 'Galeri Dokumentasi' : 'Documentation Gallery' }}
                            </span>
                        </div>

                        @if($activeProgram->images->count())
                            <span class="tj-gallery-badge">
                                {{ $activeProgram->images->count() }} {{ $locale === 'id' ? 'foto' : 'photos' }}
                            </span>
                        @endif
                    </div>

                    @if($activeProgram->images->count())
                        <div class="tj-gallery-grid count-{{ min($activeProgram->images->count(), 5) }}">
                            @foreach($activeProgram->images as $idx => $image)
                                <div class="tj-gitem"
                                     data-src="{{ asset($image->image_path) }}"
                                     data-cap="{{ $image->caption ?? $activeTranslation->title }}"
                                     data-idx="{{ $idx }}">
                                    <div class="tj-gitem-inner">
                                        <img src="{{ asset($image->image_path) }}" alt="{{ $image->caption ?? $activeTranslation->title }}" loading="lazy">

                                        @if(!empty($image->caption))
                                            <div class="tj-gitem-caption">{{ $image->caption }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="tj-gallery-empty">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="18" height="18" rx="3"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            {{ $locale === 'id' ? 'Belum ada galeri foto untuk tahun ini.' : 'No gallery photos for this year yet.' }}
                        </div>
                    @endif
                </div>
            @endif
        @else
            <div class="tj-empty">
                <div class="tj-empty-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
                <div class="tj-empty-title">{{ $locale === 'id' ? 'Belum ada program' : 'No programs yet' }}</div>
                <div class="tj-empty-text">{{ $locale === 'id' ? 'Belum ada program TJSL yang tersedia.' : 'No TJSL programs available yet.' }}</div>
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    'use strict';

    var skel  = document.getElementById('tjSkel');
    var skelG = document.getElementById('tjSkelG');
    var feat  = document.getElementById('tjFeature');
    var gal   = document.getElementById('tjGallery');

    function reveal() {
        [skel, skelG].forEach(function (el) {
            if (!el) return;
            el.style.transition = 'opacity .32s ease';
            el.style.opacity = '0';
            setTimeout(function () { if (el) el.style.display = 'none'; }, 340);
        });

        if (feat) {
            feat.style.display = 'grid';
            setTimeout(function () { feat.classList.add('in'); }, 40);
        }

        if (gal) {
            gal.style.display = 'block';
            setTimeout(function () { gal.classList.add('in'); }, 130);
        }
    }

    if (document.readyState === 'complete') {
        setTimeout(reveal, 220);
    } else {
        window.addEventListener('load', function () { setTimeout(reveal, 220); });
    }

    setTimeout(reveal, 2600);

    document.querySelectorAll('[data-read-more]').forEach(function (button) {
        var copyBox = document.querySelector('[data-copy-box]');
        var wrap = document.querySelector('[data-read-wrap]');
        var text = button.querySelector('[data-more-text]');

        if (!wrap || !copyBox) {
            button.style.display = 'none';
            return;
        }

        var rich = wrap.querySelector('.tj-richtext');

        function getFullHeight() {
            return rich ? rich.scrollHeight + 24 : 1400;
        }

        setTimeout(function () {
            var fullHeight = getFullHeight();
            wrap.style.setProperty('--expanded-height', fullHeight + 'px');

            if (!rich || fullHeight <= 120) {
                button.style.display = 'none';
                wrap.classList.remove('is-collapsed');
                wrap.classList.add('is-expanded');
                copyBox.classList.add('is-expanded');
            }
        }, 420);

        button.addEventListener('click', function () {
            var isOpen = wrap.classList.contains('is-expanded');

            if (isOpen) {
                wrap.style.setProperty('--expanded-height', getFullHeight() + 'px');

                wrap.classList.remove('is-expanded');
                wrap.classList.add('is-collapsed');

                copyBox.classList.remove('is-expanded');

                button.classList.remove('is-open');
                text.textContent = '{{ $locale === 'id' ? 'Selengkapnya' : 'Read more' }}';
            } else {
                wrap.style.setProperty('--expanded-height', getFullHeight() + 'px');

                wrap.classList.remove('is-collapsed');
                wrap.classList.add('is-expanded');

                copyBox.classList.add('is-expanded');

                button.classList.add('is-open');
                text.textContent = '{{ $locale === 'id' ? 'Tampilkan lebih sedikit' : 'Show less' }}';
            }
        });
    });

    var slides   = Array.from(document.querySelectorAll('.tj-slide'));
    var dots     = Array.from(document.querySelectorAll('.tj-sdot'));
    var slidesEl = document.getElementById('tjSlides');
    var countEl  = document.getElementById('tjCount');
    var cur = 0, total = slides.length, timer = null;

    function goTo(n) {
        if (total < 2) return;

        slides[cur].classList.remove('is-active');
        if (dots[cur]) dots[cur].classList.remove('is-active');

        cur = (n + total) % total;

        slides[cur].classList.add('is-active');
        if (dots[cur]) dots[cur].classList.add('is-active');
        if (slidesEl) slidesEl.style.transform = 'translateX(-' + (cur * 100) + '%)';
        if (countEl) countEl.textContent = (cur + 1) + ' / ' + total;
    }

    function startAuto() {
        clearInterval(timer);
        timer = setInterval(function () { goTo(cur + 1); }, 4800);
    }

    var prevEl = document.getElementById('tjPrev');
    var nextEl = document.getElementById('tjNext');

    if (prevEl) prevEl.addEventListener('click', function () { goTo(cur - 1); startAuto(); });
    if (nextEl) nextEl.addEventListener('click', function () { goTo(cur + 1); startAuto(); });

    dots.forEach(function (d) {
        d.addEventListener('click', function () { goTo(+d.getAttribute('data-i')); startAuto(); });
    });

    var sliderEl = document.getElementById('tjSlider');

    if (sliderEl) {
        var tx0 = 0;

        sliderEl.addEventListener('touchstart', function (e) {
            tx0 = e.changedTouches[0].clientX;
        }, { passive: true });

        sliderEl.addEventListener('touchend', function (e) {
            var dx = e.changedTouches[0].clientX - tx0;
            if (Math.abs(dx) > 36) {
                goTo(cur + (dx < 0 ? 1 : -1));
                startAuto();
            }
        }, { passive: true });
    }

    if (total > 1) startAuto();

    var items   = Array.from(document.querySelectorAll('.tj-gitem'));
    var lb      = document.getElementById('tjLb');
    var lbImg   = document.getElementById('tjLbImg');
    var lbCap   = document.getElementById('tjLbCap');
    var lbClose = document.getElementById('tjLbClose');
    var lbPrev  = document.getElementById('tjLbPrev');
    var lbNext  = document.getElementById('tjLbNext');
    var lbCur   = 0;

    if (lbImg) {
        lbImg.style.transition = 'opacity .15s ease, transform .24s var(--ease)';
    }

    function setLb(item) {
        if (!lbImg || !item) return;

        lbImg.style.opacity = '0';

        setTimeout(function () {
            lbImg.src = item.getAttribute('data-src') || '';
            lbImg.alt = item.getAttribute('data-cap') || '';

            if (lbCap) {
                lbCap.textContent = item.getAttribute('data-cap') || '';
            }

            lbImg.style.opacity = '1';
        }, 150);
    }

    function openLb(idx) {
        if (!lb || !items.length) return;
        lbCur = idx;
        setLb(items[lbCur]);
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLb() {
        if (!lb) return;
        lb.classList.remove('is-open');
        document.body.style.overflow = '';
        setTimeout(function () { if (lbImg) lbImg.src = ''; }, 240);
    }

    function navLb(dir) {
        lbCur = (lbCur + dir + items.length) % items.length;
        setLb(items[lbCur]);
    }

    items.forEach(function (item, idx) {
        item.addEventListener('click', function () { openLb(idx); });
    });

    if (lbClose) lbClose.addEventListener('click', closeLb);
    if (lb) lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
    if (lbPrev) lbPrev.addEventListener('click', function () { navLb(-1); });
    if (lbNext) lbNext.addEventListener('click', function () { navLb(1); });

    document.addEventListener('keydown', function (e) {
        if (!lb || !lb.classList.contains('is-open')) return;
        if (e.key === 'Escape') closeLb();
        if (e.key === 'ArrowLeft') navLb(-1);
        if (e.key === 'ArrowRight') navLb(1);
    });

    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.06 });

        document.querySelectorAll('.tj-fade').forEach(function (el) { io.observe(el); });
    } else {
        document.querySelectorAll('.tj-fade').forEach(function (el) { el.classList.add('in'); });
    }
})();
</script>

@endsection