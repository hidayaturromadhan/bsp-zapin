@extends('layouts.app')

@section('content')
@php
    $locale = $locale ?? (in_array(request()->segment(1), ['id','en']) ? request()->segment(1) : 'id');

    $structuredContent = $structuredContent ?? [];

    $isAboutPage = $isAboutPage ?? (($structuredContent['template'] ?? null) === 'about_us');
    $isVisionMissionPage = $isVisionMissionPage ?? (($structuredContent['template'] ?? null) === 'vision_mission');
    $isHistoryPage = $isHistoryPage ?? (($structuredContent['template'] ?? null) === 'history');
    $isShareholderPage = $isShareholderPage ?? (($structuredContent['template'] ?? null) === 'shareholder');
    $isOrganizationStructurePage = $isOrganizationStructurePage ?? (($structuredContent['template'] ?? null) === 'organization_structure');
    $isHsePage = $isHsePage ?? (($structuredContent['template'] ?? null) === 'hse');

    $historyIntro = $structuredContent['intro'] ?? [];
    $historySections = collect($structuredContent['sections'] ?? [])
        ->filter(function ($item) {
            return !empty($item['title']) || !empty($item['content']);
        })
        ->values();

    $historyTimeline = collect($structuredContent['timeline'] ?? [])
        ->filter(function ($item) {
            return !empty($item['label']) || !empty($item['date']) || !empty($item['title']) || !empty($item['content']);
        })
        ->values();

    $missionItems = collect($structuredContent['mission_items'] ?? [])
        ->filter()
        ->values();

    $shareholderIntro = $structuredContent['intro'] ?? [];
    $shareholderItems = collect($structuredContent['items'] ?? [])
        ->filter(function ($item) {
            return !empty($item['percentage']) || !empty($item['name']) || !empty($item['desc']) || !empty($item['logo']);
        })
        ->values();

    $organizationIntro = $structuredContent['intro'] ?? [];

    $hseIntro = $structuredContent['intro'] ?? [];
    $hsePolicyTitle = $structuredContent['policy_title'] ?? ($locale === 'id' ? 'Kebijakan K3LL' : 'HSE Policy');
    $hsePolicyImage = $structuredContent['policy_image'] ?? null;
    $hseCertification = $structuredContent['certification'] ?? [];
    $hseCertificationItems = collect($hseCertification['items'] ?? [])
        ->filter(function ($item) {
            return !empty($item['code']) || !empty($item['title']);
        })
        ->values();

    $director = $structuredContent['director'] ?? null;
    if ((!is_array($director) || empty(array_filter($director))) && !empty($structuredContent['directors']) && is_array($structuredContent['directors'])) {
        $director = collect($structuredContent['directors'])
            ->first(function ($item) {
                return !empty($item['name']) || !empty($item['position']) || !empty($item['photo']);
            });
    }

    $commissioner = $structuredContent['commissioner'] ?? null;
    if ((!is_array($commissioner) || empty(array_filter($commissioner))) && !empty($structuredContent['commissioners']) && is_array($structuredContent['commissioners'])) {
        $commissioner = collect($structuredContent['commissioners'])
            ->first(function ($item) {
                return !empty($item['name']) || !empty($item['position']) || !empty($item['photo']);
            });
    }
@endphp

<style>
/* ───────────────────────────────────────────────
   BASE SHELL & LAYOUT
─────────────────────────────────────────────── */
.profil-shell {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 16px;
}

.profil-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 20px;
    font-size: 12.5px;
    color: #6b7280;
}
.profil-breadcrumb a {
    color: #2f7d32;
    text-decoration: none;
    transition: color .14s;
}
.profil-breadcrumb a:hover { color: #173f08; }
.profil-breadcrumb-sep { color: #d1d5db; }

.profil-wrap {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 248px;
    gap: 24px;
    align-items: start;
}
.profil-content { min-width: 0; }

/* ───────────────────────────────────────────────
   SIDEBAR
─────────────────────────────────────────────── */
.profil-sidebar {
    position: sticky;
    top: 88px;
}

.profil-panel {
    background: #173f08;
    border-radius: 16px;
    padding: 14px 0;
    overflow: hidden;
}

.profil-panel-title {
    padding: 0 16px 10px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .13em;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
    margin: 0 0 4px;
}

.profil-panel a {
    display: block;
    padding: 11px 16px;
    color: rgba(255,255,255,.85);
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.4;
    transition: background .14s, color .14s;
    position: relative;
}
.profil-panel a:hover {
    background: rgba(255,255,255,.07);
    color: #fff;
}
.profil-panel a.active {
    background: rgba(255,255,255,.10);
    color: #fff;
}
.profil-panel a.active::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 3px; height: 100%;
    background: #d4a843;
    border-radius: 0 2px 2px 0;
}

/* ───────────────────────────────────────────────
   GENERIC PAGE
─────────────────────────────────────────────── */
.profil-title {
    margin: 0 0 16px;
    font-size: clamp(26px, 3.6vw, 38px);
    line-height: 1.15;
    font-weight: 800;
    color: #111827;
    letter-spacing: -.03em;
}

.profil-cover {
    width: 100%;
    max-height: 340px;
    object-fit: cover;
    display: block;
    border-radius: 18px;
    border: 1px solid rgba(229,231,235,.9);
}

.profil-body {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 28px 30px;
}

.profil-page-content {
    color: #374151;
    font-size: 15px;
    line-height: 1.9;
}
.profil-page-content > *:first-child { margin-top: 0 !important; }
.profil-page-content > *:last-child  { margin-bottom: 0 !important; }
.profil-page-content h1,.profil-page-content h2,.profil-page-content h3,
.profil-page-content h4,.profil-page-content h5,.profil-page-content h6 {
    color: #111827; line-height: 1.3; margin-top: 1.8em; margin-bottom: .7em; font-weight: 700;
}
.profil-page-content h1{font-size:28px;} .profil-page-content h2{font-size:24px;}
.profil-page-content h3{font-size:20px;} .profil-page-content h4{font-size:17px;}
.profil-page-content p  { margin: 0 0 1.1em; }
.profil-page-content ul,.profil-page-content ol { margin: 0 0 1.2em 1.3em; }
.profil-page-content li { margin-bottom: .4em; }
.profil-page-content a  { color: #21560e; text-decoration: underline; text-underline-offset: 3px; }
.profil-page-content a:hover { color: #173f08; }
.profil-page-content blockquote {
    margin: 1.4em 0; padding: 12px 16px;
    border-left: 4px solid #2f7d32;
    background: #f6faf4; border-radius: 0 10px 10px 0;
}
.profil-page-content img { max-width: 100%; border-radius: 12px; }

/* ───────────────────────────────────────────────
   REVEAL ANIMATIONS
─────────────────────────────────────────────── */
.reveal-up {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity .55s ease, transform .55s ease;
}
.reveal-up.is-visible {
    opacity: 1;
    transform: translateY(0);
}
.reveal-left {
    opacity: 0;
    transform: translateX(-20px);
    transition: opacity .55s ease, transform .55s ease;
}
.reveal-left.is-visible {
    opacity: 1;
    transform: translateX(0);
}
.reveal-right {
    opacity: 0;
    transform: translateX(20px);
    transition: opacity .55s ease, transform .55s ease;
}
.reveal-right.is-visible {
    opacity: 1;
    transform: translateX(0);
}
.reveal-scale {
    opacity: 0;
    transform: scale(.94);
    transition: opacity .55s ease, transform .55s ease;
}
.reveal-scale.is-visible {
    opacity: 1;
    transform: scale(1);
}

/* stagger helpers */
.delay-1 { transition-delay: .08s; }
.delay-2 { transition-delay: .16s; }
.delay-3 { transition-delay: .24s; }
.delay-4 { transition-delay: .32s; }
.delay-5 { transition-delay: .40s; }

/* ───────────────────────────────────────────────
   ABOUT US
─────────────────────────────────────────────── */
.about-stack { display: grid; gap: 20px; }

.about-intro {
    background: linear-gradient(135deg, #173f08 0%, #204d0d 100%);
    border-radius: 18px;
    padding: 26px 28px;
}
.about-intro p {
    margin: 0;
    font-size: 15px;
    line-height: 2;
    color: rgba(255,255,255,.88);
    white-space: pre-line;
}

.about-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    align-items: stretch;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    overflow: hidden;
}
.about-section.reverse .about-section-media { order: 2; }
.about-section.reverse .about-section-text  { order: 1; }

.about-section-media {
    position: relative;
    overflow: hidden;
    min-height: 260px;
}
.about-section-media::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, rgba(23,63,8,.12) 0%, transparent 60%);
    pointer-events: none;
}
.about-section-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .7s ease;
}
.about-section:hover .about-section-media img {
    transform: scale(1.04);
}
.about-section-text {
    padding: 28px 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.about-section-title {
    margin: 0 0 12px;
    font-size: 24px;
    line-height: 1.2;
    font-weight: 800;
    color: #111827;
    letter-spacing: -.02em;
}
.about-section-title span {
    display: block;
    width: 36px; height: 3px;
    background: #2f7d32;
    border-radius: 99px;
    margin-bottom: 10px;
}
.about-section-body {
    margin: 0;
    font-size: 14.5px;
    line-height: 1.9;
    color: #4b5563;
    white-space: pre-line;
}

/* ───────────────────────────────────────────────
   VISION MISSION
─────────────────────────────────────────────── */
.vm-wrap { display: grid; gap: 16px; }

.vm-hero {
    background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
    border-radius: 18px;
    padding: 22px 26px;
    position: relative;
    overflow: hidden;
}
.vm-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 140px; height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    pointer-events: none;
}
.vm-hero::after {
    content: '';
    position: absolute;
    bottom: -50px; left: 20%;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
    pointer-events: none;
}

.vm-hero-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.7);
}
.vm-hero-kicker::before {
    content: '';
    width: 20px; height: 2px;
    border-radius: 99px;
    background: #d4a843;
}

.vm-grid {
    display: grid;
    grid-template-columns: .9fr 1.1fr;
    gap: 16px;
    align-items: start;
}

.vm-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 22px 24px;
}

.vm-title {
    margin: 0 0 14px;
    font-size: 22px;
    line-height: 1.1;
    font-weight: 800;
    color: #111827;
    letter-spacing: -.02em;
    display: flex;
    align-items: center;
    gap: 10px;
}
.vm-title::before {
    content: '';
    display: block;
    width: 4px; height: 22px;
    border-radius: 99px;
    background: #2f7d32;
    flex-shrink: 0;
}
.vm-title--green { color: #173f08; }

.vm-vision {
    margin: 0;
    font-size: 14.5px;
    line-height: 1.9;
    color: #374151;
    white-space: pre-line;
}

.vm-mission-list { display: grid; gap: 9px; }

.vm-mission-item {
    display: grid;
    grid-template-columns: 38px 1fr;
    gap: 10px;
    align-items: start;
    padding: 11px 13px;
    border-radius: 12px;
    background: #f8fbf7;
    border: 1px solid #e1ecdc;
    transition: background .18s, border-color .18s, transform .18s;
}
.vm-mission-item:hover {
    background: #f0f8ec;
    border-color: #c5e0ba;
    transform: translateX(3px);
}

.vm-mission-no {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: #173f08;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 800;
    flex-shrink: 0;
}

.vm-mission-text {
    margin: 0;
    font-size: 13.5px;
    line-height: 1.75;
    color: #4b5563;
    white-space: pre-line;
    padding-top: 9px;
}

/* ───────────────────────────────────────────────
   HISTORY
─────────────────────────────────────────────── */
.hs-wrap { display: grid; gap: 18px; }

.hs-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #173f08 0%, #21560e 100%);
    border-radius: 20px;
    padding: 24px 28px;
}
.hs-hero::after {
    content: '';
    position: absolute;
    inset: auto -50px -50px auto;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.hs-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.7);
}
.hs-eyebrow::before {
    content: '';
    width: 20px; height: 2px;
    border-radius: 99px;
    background: #d4a843;
}

.hs-hero-title {
    margin: 0 0 8px;
    font-size: clamp(22px, 3vw, 30px);
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -.03em;
    color: #fff;
}

.hs-hero-text {
    margin: 0;
    font-size: 14px;
    line-height: 1.85;
    color: rgba(255,255,255,.85);
    max-width: 680px;
    white-space: pre-line;
}

.hs-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.hs-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 20px 22px;
    transition: border-color .2s, transform .2s;
    position: relative;
    overflow: hidden;
}
.hs-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 3px;
    background: linear-gradient(90deg, #173f08, #2f7d32);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s ease;
}
.hs-card:hover {
    border-color: #c5e0ba;
    transform: translateY(-3px);
}
.hs-card:hover::before {
    transform: scaleX(1);
}

.hs-card-title {
    margin: 0 0 10px;
    font-size: 19px;
    line-height: 1.2;
    font-weight: 800;
    color: #173f08;
    letter-spacing: -.02em;
}

.hs-card-text {
    margin: 0;
    font-size: 14px;
    line-height: 1.85;
    color: #4b5563;
    white-space: pre-line;
}

/* TIMELINE FLIP CARDS */
.hs-timeline {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.hs-flip { min-height: 280px; perspective: 1200px; }

.hs-flip-inner {
    position: relative;
    width: 100%; height: 100%;
    min-height: 280px;
    transition: transform .7s cubic-bezier(.2,.7,.2,1);
    transform-style: preserve-3d;
}

.hs-flip:hover .hs-flip-inner,
.hs-flip.is-flipped .hs-flip-inner {
    transform: rotateY(180deg);
}

.hs-flip-face {
    position: absolute;
    inset: 0;
    border-radius: 18px;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    overflow: hidden;
}

.hs-flip-front {
    background: linear-gradient(145deg, #173f08 0%, #285b13 100%);
    color: #fff;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.hs-flip-front--light {
    background: linear-gradient(145deg, #f4faf0 0%, #e8f5e2 100%);
    color: #173f08;
    border: 1px solid #d4e9cb;
}

.hs-flip-back {
    background: #fff;
    color: #111827;
    border: 1px solid #e5e7eb;
    padding: 20px;
    transform: rotateY(180deg);
    overflow-y: auto;
}

.hs-front-kicker {
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .13em;
    text-transform: uppercase;
    opacity: .75;
}
.hs-front-date {
    margin: 14px 0 8px;
    font-size: 26px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -.04em;
}
.hs-front-title {
    margin: 0;
    font-size: 16px;
    line-height: 1.35;
    font-weight: 700;
}
.hs-front-hint {
    margin-top: 14px;
    font-size: 11px;
    line-height: 1.5;
    opacity: .65;
    display: flex;
    align-items: center;
    gap: 5px;
}
.hs-front-hint::before {
    content: '↻';
    font-size: 13px;
}

.hs-back-title {
    margin: 0 0 8px;
    font-size: 16px;
    line-height: 1.3;
    font-weight: 800;
    color: #173f08;
}
.hs-back-text {
    margin: 0;
    font-size: 13.5px;
    line-height: 1.8;
    color: #4b5563;
    white-space: pre-line;
}

/* ───────────────────────────────────────────────
   SHAREHOLDER
─────────────────────────────────────────────── */
.sh-wrap { display: grid; gap: 20px; }

.sh-hero {
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(rgba(255,255,255,.88), rgba(255,255,255,.88)),
        var(--shareholder-bg, none);
    background-size: cover;
    background-position: center;
    border-radius: 22px;
    padding: 32px 34px 36px;
    border: 1px solid #e5e7eb;
}

.sh-tag {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 12px;
    background: #f0f8ec;
    border: 1px solid #d4e9cb;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #173f08;
    margin-bottom: 14px;
}
.sh-tag::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #2f7d32;
}

.sh-title {
    margin: 0 0 10px;
    font-size: clamp(28px, 4vw, 52px);
    line-height: 1.04;
    font-weight: 800;
    color: #173f08;
    letter-spacing: -.05em;
}

.sh-desc {
    margin: 0;
    max-width: 680px;
    font-size: 14.5px;
    line-height: 1.85;
    color: #4b5563;
}

.sh-divider {
    width: 100%;
    height: 1px;
    background: #e5e7eb;
    margin: 24px 0;
}

.sh-layout {
    display: grid;
    grid-template-columns: 1fr minmax(240px, 480px) 1fr;
    gap: 16px;
    align-items: center;
}

.sh-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.sh-item--left  { align-items: flex-start; text-align: left; }
.sh-item--right { align-items: flex-end;   text-align: right; }

.sh-logo-box {
    width: 80px; height: 80px;
    border-radius: 16px;
    background: #f8fbf7;
    border: 1px solid #e1ecdc;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 4px;
}
.sh-logo-box img {
    max-width: 80%;
    max-height: 80%;
    object-fit: contain;
}

.sh-percentage {
    font-size: 44px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -.05em;
    color: #1f2937;
}
.sh-item--left  .sh-percentage { color: #c62828; }

.sh-name {
    font-size: 16px;
    line-height: 1.3;
    font-weight: 800;
    color: #1f2937;
    text-transform: uppercase;
    letter-spacing: .02em;
}
.sh-item--left .sh-name { color: #c62828; }

.sh-small {
    font-size: 13px;
    line-height: 1.6;
    color: #6b7280;
}

.sh-chart {
    display: flex;
    align-items: center;
    justify-content: center;
}
.sh-chart-box {
    width: 100%;
    max-width: 480px;
}
.sh-chart-box img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: contain;
}


/* ───────────────────────────────────────────────
   HSE
─────────────────────────────────────────────── */
.hse-wrap { display: grid; gap: 22px; }

.hse-hero {
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(135deg, rgba(23,63,8,.96), rgba(30,84,12,.88)),
        var(--hse-bg, none);
    background-size: cover;
    background-position: center;
    border-radius: 22px;
    padding: 30px 32px;
    color: #fff;
    box-shadow: 0 14px 34px rgba(15,23,42,.08);
}
.hse-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at top right, rgba(212,168,67,.18), transparent 34%),
        radial-gradient(circle at bottom left, rgba(255,255,255,.08), transparent 30%);
    pointer-events: none;
}
.hse-hero > * { position: relative; z-index: 1; }

.hse-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 30px;
    padding: 0 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.12);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.hse-title {
    margin: 0 0 8px;
    font-size: clamp(28px, 4vw, 42px);
    line-height: 1.08;
    font-weight: 800;
    letter-spacing: -.04em;
}
.hse-desc {
    margin: 0;
    max-width: 720px;
    color: rgba(255,255,255,.86);
    font-size: 14px;
    line-height: 1.8;
}

.hse-policy-card,
.hse-cert-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(15,23,42,.05);
}

.hse-section-head {
    padding: 18px 22px 0;
}
.hse-section-kicker {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 5px 12px;
    background: #f0f8ec;
    border: 1px solid #d4e9cb;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #173f08;
    margin-bottom: 12px;
}
.hse-section-kicker::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #2f7d32;
}
.hse-section-title {
    margin: 0;
    font-size: 24px;
    line-height: 1.2;
    font-weight: 800;
    color: #111827;
    letter-spacing: -.03em;
}

.hse-policy-media {
    padding: 22px;
}
.hse-policy-frame {
    background: linear-gradient(180deg, #f8faf7 0%, #f3f7f1 100%);
    border: 1px solid #e0eadb;
    border-radius: 20px;
    padding: 18px;
}
.hse-policy-frame img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: contain;
    border-radius: 14px;
}

.hse-cert-card {
    padding: 24px;
}
.hse-cert-header {
    max-width: 930px;
    margin: 0 auto 28px;
    padding: 18px 24px;
    border: 3px solid #224f0d;
    border-radius: 16px;
    text-align: center;
}
.hse-cert-main-title {
    margin: 0 0 4px;
    color: #173f08;
    font-size: clamp(22px, 3.2vw, 34px);
    line-height: 1.15;
    font-weight: 900;
    letter-spacing: -.03em;
    text-transform: uppercase;
}
.hse-cert-subtitle {
    margin: 0;
    color: #224f0d;
    font-size: clamp(15px, 2vw, 20px);
    line-height: 1.5;
    font-weight: 600;
}

.hse-cert-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
}
.hse-cert-item {
    border-radius: 18px;
    padding: 26px 18px;
    background: linear-gradient(180deg, #ffffff 0%, #f8faf7 100%);
    border: 1px solid #e5ece0;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}
.hse-cert-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 28px rgba(15,23,42,.08);
    border-color: #d7e6cf;
}
.hse-cert-code {
    margin: 0 0 14px;
    color: #173f08;
    font-size: clamp(24px, 3vw, 38px);
    line-height: 1.05;
    font-weight: 900;
    letter-spacing: -.04em;
}
.hse-cert-text {
    margin: 0;
    color: #1f2937;
    font-size: 15px;
    line-height: 1.65;
    font-weight: 500;
}

/* ───────────────────────────────────────────────
   ORGANIZATION STRUCTURE  ← FIXED
─────────────────────────────────────────────── */
.org-wrap { display: grid; gap: 20px; }

.org-hero {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    padding: clamp(22px, 3vw, 32px);
    background:
        linear-gradient(135deg, rgba(23,63,8,.96), rgba(30,84,12,.88)),
        var(--org-bg, none);
    background-size: cover;
    background-position: center;
    color: #fff;
    box-shadow: 0 12px 28px rgba(15,23,42,.10);
}

.org-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at top right, rgba(212,168,67,.20), transparent 34%),
        radial-gradient(circle at bottom left, rgba(255,255,255,.08), transparent 28%);
    pointer-events: none;
}

.org-hero > * { position: relative; z-index: 1; }

.org-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 30px;
    padding: 0 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.10);
    border: 1px solid rgba(255,255,255,.12);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.org-title {
    margin: 0 0 8px;
    font-size: clamp(24px, 3vw, 36px);
    line-height: 1.1;
    font-weight: 800;
    letter-spacing: -.03em;
}

.org-desc {
    margin: 0;
    max-width: 680px;
    color: rgba(255,255,255,.84);
    font-size: 14px;
    line-height: 1.75;
}

/* ── Cards layout: centered, max-width constrained ── */
.org-layout {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 340px));
    gap: 18px;
    justify-content: center;   /* center the pair on wide screens */
    align-items: stretch;
}

.org-column {
    display: grid;
}

.org-card {
    position: relative;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(15,23,42,.06);
    transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
    height: 100%;
}

.org-card::before {
    content: "";
    position: absolute;
    inset: 0 auto auto 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #173f08, #d4a843);
    opacity: .95;
    z-index: 2;
}

.org-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 34px rgba(15,23,42,.11);
    border-color: rgba(23,63,8,.16);
}

/* ── Photo area: fixed height, not aspect-ratio ── */
.org-card-media {
    position: relative;
    width: 100%;
    height: 300px;          /* fixed, predictable height */
    overflow: hidden;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
}

.org-card-media img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    object-position: center top;   /* keep face visible */
    transition: transform .45s ease;
}

.org-card:hover .org-card-media img {
    transform: scale(1.04);
}

.org-card-body {
    padding: 16px 18px 20px;
    text-align: center;
}

.org-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 26px;
    padding: 0 10px;
    border-radius: 999px;
    background: #eef5eb;
    color: #173f08;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.org-name {
    margin: 0 0 5px;
    font-size: 18px;
    line-height: 1.3;
    font-weight: 800;
    color: #111827;
    letter-spacing: -.02em;
}

.org-position {
    margin: 0;
    color: #6b7280;
    font-size: 13px;
    line-height: 1.6;
    font-weight: 600;
}

.org-empty {
    padding: 18px;
    border-radius: 18px;
    border: 1px dashed #d1d5db;
    color: #6b7280;
    background: #fff;
    font-size: 14px;
    text-align: center;
}

/* ───────────────────────────────────────────────
   RESPONSIVE
─────────────────────────────────────────────── */
@media (max-width: 1000px) {
    .profil-wrap {
        grid-template-columns: 1fr;
    }
    .profil-sidebar {
        position: static;
        order: -1;
    }
    .profil-panel {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        padding: 10px;
        gap: 4px;
    }
    .profil-panel-title { display: none; }
    .profil-panel a {
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 12.5px;
    }
    .profil-panel a.active::before { display: none; }
    .profil-panel a.active {
        background: rgba(255,255,255,.15);
        border-radius: 10px;
    }
    .sh-layout {
        grid-template-columns: 1fr;
        gap: 18px;
    }
    .hse-cert-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .sh-item--left, .sh-item--right {
        align-items: center;
        text-align: center;
    }
    .org-layout {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        justify-content: stretch;
    }
}

@media (max-width: 760px) {
    .about-section, .vm-grid, .hs-grid, .hs-timeline {
        grid-template-columns: 1fr;
    }
    .about-section.reverse .about-section-media,
    .about-section.reverse .about-section-text { order: initial; }
    .about-section-media { min-height: 200px; }
    .hs-flip, .hs-flip-inner { min-height: 220px; }
    .sh-hero { padding: 22px 20px 28px; }
    .profil-cover { max-height: 220px; }
    .org-layout {
        grid-template-columns: 1fr;
        max-width: 340px;
        margin: 0 auto;
    }
    .hse-hero { padding: 22px 20px 26px; }
    .hse-cert-card { padding: 18px; }
    .hse-cert-header { padding: 16px 14px; margin-bottom: 20px; }
    .org-card-media { height: 240px; }
}

@media (max-width: 520px) {
    .sh-percentage { font-size: 36px; }
    .sh-name { font-size: 14px; }
    .sh-logo-box { width: 64px; height: 64px; }
    .about-section-title, .vm-title, .hs-card-title { font-size: 20px; }
    .org-card-media { height: 200px; }
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
        {{-- ── MAIN CONTENT ── --}}
        <div class="profil-content">
            @if(!empty($page))

                {{-- ════════════════════════════════════
                     ABOUT US
                ════════════════════════════════════ --}}
                @if($isAboutPage)
                    <header class="reveal-up" style="margin-bottom:20px;">
                        <h1 class="profil-title">{{ $page->title }}</h1>
                        @if($page->page?->cover_image)
                            <img
                                src="{{ asset($page->page->cover_image) }}"
                                alt="{{ $page->title }}"
                                class="profil-cover"
                            >
                        @endif
                    </header>

                    <div class="about-stack">
                        @if(!empty($structuredContent['hero_text']))
                            <section class="about-intro reveal-up delay-1">
                                <p>{{ $structuredContent['hero_text'] }}</p>
                            </section>
                        @endif

                        @if(
                            !empty($structuredContent['section_1_title']) ||
                            !empty($structuredContent['section_1_text'])  ||
                            !empty($structuredContent['section_1_image'])
                        )
                            <section class="about-section reveal-up delay-2">
                                <div class="about-section-media">
                                    @if(!empty($structuredContent['section_1_image']))
                                        <img src="{{ asset($structuredContent['section_1_image']) }}" alt="{{ $structuredContent['section_1_title'] ?? '' }}">
                                    @endif
                                </div>
                                <div class="about-section-text">
                                    @if(!empty($structuredContent['section_1_title']))
                                        <h2 class="about-section-title">
                                            <span></span>{{ $structuredContent['section_1_title'] }}
                                        </h2>
                                    @endif
                                    @if(!empty($structuredContent['section_1_text']))
                                        <p class="about-section-body">{{ $structuredContent['section_1_text'] }}</p>
                                    @endif
                                </div>
                            </section>
                        @endif

                        @if(
                            !empty($structuredContent['section_2_title']) ||
                            !empty($structuredContent['section_2_text'])  ||
                            !empty($structuredContent['section_2_image'])
                        )
                            <section class="about-section reverse reveal-up delay-3">
                                <div class="about-section-media">
                                    @if(!empty($structuredContent['section_2_image']))
                                        <img src="{{ asset($structuredContent['section_2_image']) }}" alt="{{ $structuredContent['section_2_title'] ?? '' }}">
                                    @endif
                                </div>
                                <div class="about-section-text">
                                    @if(!empty($structuredContent['section_2_title']))
                                        <h2 class="about-section-title">
                                            <span></span>{{ $structuredContent['section_2_title'] }}
                                        </h2>
                                    @endif
                                    @if(!empty($structuredContent['section_2_text']))
                                        <p class="about-section-body">{{ $structuredContent['section_2_text'] }}</p>
                                    @endif
                                </div>
                            </section>
                        @endif
                    </div>

                {{-- ════════════════════════════════════
                     VISION MISSION
                ════════════════════════════════════ --}}
                @elseif($isVisionMissionPage)
                    <header class="reveal-up" style="margin-bottom:20px;">
                        <h1 class="profil-title">{{ $page->title }}</h1>
                        @if($page->page?->cover_image)
                            <img
                                src="{{ asset($page->page->cover_image) }}"
                                alt="{{ $page->title }}"
                                class="profil-cover"
                            >
                        @endif
                    </header>

                    <div class="vm-wrap">
                        <section class="vm-hero reveal-up delay-1">
                            <div class="vm-hero-kicker">
                                {{ $locale === 'id' ? 'Arah Strategis Perusahaan' : 'Company Strategic Direction' }}
                            </div>
                            <h2 style="margin:0; font-size:clamp(20px,2.8vw,28px); font-weight:800; color:#fff; letter-spacing:-.03em;">
                                {{ $page->title }}
                            </h2>
                        </section>

                        <div class="vm-grid">
                            <section class="vm-card reveal-left delay-2">
                                <h2 class="vm-title vm-title--green">
                                    {{ $structuredContent['vision_title'] ?? ($locale === 'id' ? 'VISI' : 'VISION') }}
                                </h2>
                                <p class="vm-vision">{{ $structuredContent['vision_text'] ?? '' }}</p>
                            </section>

                            <section class="vm-card reveal-right delay-2">
                                <h2 class="vm-title vm-title--green">
                                    {{ $structuredContent['mission_title'] ?? ($locale === 'id' ? 'MISI' : 'MISSION') }}
                                </h2>
                                <div class="vm-mission-list">
                                    @foreach($missionItems as $index => $item)
                                        <div class="vm-mission-item reveal-up" style="transition-delay:{{ ($index * 0.07 + 0.28) }}s">
                                            <div class="vm-mission-no">{{ $index + 1 }}</div>
                                            <p class="vm-mission-text">{{ $item }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    </div>

                {{-- ════════════════════════════════════
                     HISTORY
                ════════════════════════════════════ --}}
                @elseif($isHistoryPage)
                    <header class="reveal-up" style="margin-bottom:20px;">
                        <h1 class="profil-title">{{ $page->title }}</h1>
                        @if($page->page?->cover_image)
                            <img
                                src="{{ asset($page->page->cover_image) }}"
                                alt="{{ $page->title }}"
                                class="profil-cover"
                            >
                        @endif
                    </header>

                    <div class="hs-wrap">
                        <section class="hs-hero reveal-up delay-1">
                            <div class="hs-eyebrow">
                                {{ $locale === 'id' ? 'Perjalanan Perusahaan' : 'Company Journey' }}
                            </div>
                            <h2 class="hs-hero-title">
                                {{ $historyIntro['title'] ?? ($locale === 'id' ? 'Sejarah' : 'History') }}
                            </h2>
                            <p class="hs-hero-text">{{ $historyIntro['desc'] ?? '' }}</p>
                        </section>

                        @if($historySections->count())
                            <div class="hs-grid">
                                @foreach($historySections as $i => $section)
                                    <section class="hs-card reveal-up" style="transition-delay:{{ ($i * 0.1 + 0.15) }}s">
                                        <h3 class="hs-card-title">{{ $section['title'] ?? '' }}</h3>
                                        <p class="hs-card-text">{{ $section['content'] ?? '' }}</p>
                                    </section>
                                @endforeach
                            </div>
                        @endif

                        @if($historyTimeline->count())
                            <div class="hs-timeline">
                                @foreach($historyTimeline as $index => $item)
                                    <div class="hs-flip reveal-scale" style="transition-delay:{{ ($index * 0.1 + 0.2) }}s">
                                        <div class="hs-flip-inner">
                                            <div class="hs-flip-face hs-flip-front {{ $index === 1 ? 'hs-flip-front--light' : '' }}">
                                                <div class="hs-front-kicker">{{ $item['label'] ?? '' }}</div>
                                                <div>
                                                    <div class="hs-front-date">{{ $item['date'] ?? '' }}</div>
                                                    <h3 class="hs-front-title">{{ $item['title'] ?? '' }}</h3>
                                                </div>
                                                <div class="hs-front-hint">
                                                    {{ $locale === 'id' ? 'Hover atau tap untuk detail' : 'Hover or tap for details' }}
                                                </div>
                                            </div>
                                            <div class="hs-flip-face hs-flip-back">
                                                <h3 class="hs-back-title">{{ $item['title'] ?? '' }}</h3>
                                                <p class="hs-back-text">{{ $item['content'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                {{-- ════════════════════════════════════
                     SHAREHOLDER
                ════════════════════════════════════ --}}
                @elseif($isShareholderPage)
                    <div class="sh-wrap">
                        <section
                            class="sh-hero reveal-up"
                            style="{{ $page->page?->cover_image ? '--shareholder-bg:url(' . asset($page->page->cover_image) . ');' : '' }}"
                        >
                            <div class="sh-tag">
                                {{ $locale === 'id' ? 'Komposisi Kepemilikan' : 'Ownership Composition' }}
                            </div>

                            <h1 class="sh-title">
                                {{ $shareholderIntro['title'] ?? $page->title }}
                            </h1>

                            @if(!empty($shareholderIntro['desc']))
                                <p class="sh-desc">{{ $shareholderIntro['desc'] }}</p>
                            @endif

                            <div class="sh-divider"></div>

                            <div class="sh-layout">
                                @php
                                    $left  = $shareholderItems->get(1);
                                    $right = $shareholderItems->get(0);
                                @endphp

                                <div class="sh-item sh-item--left reveal-left delay-2">
                                    @if($left)
                                        @if(!empty($left['logo']))
                                            <div class="sh-logo-box">
                                                <img src="{{ asset($left['logo']) }}" alt="{{ $left['name'] ?? 'Logo' }}">
                                            </div>
                                        @endif
                                        <div class="sh-percentage">{{ $left['percentage'] ?? '' }}</div>
                                        <div class="sh-name">{{ $left['name'] ?? '' }}</div>
                                        @if(!empty($left['desc']))
                                            <div class="sh-small">{{ $left['desc'] }}</div>
                                        @endif
                                    @endif
                                </div>

                                <div class="sh-chart reveal-scale delay-3">
                                    @if(!empty($structuredContent['chart_image']))
                                        <div class="sh-chart-box">
                                            <img src="{{ asset($structuredContent['chart_image']) }}" alt="{{ $page->title }}">
                                        </div>
                                    @endif
                                </div>

                                <div class="sh-item sh-item--right reveal-right delay-2">
                                    @if($right)
                                        @if(!empty($right['logo']))
                                            <div class="sh-logo-box">
                                                <img src="{{ asset($right['logo']) }}" alt="{{ $right['name'] ?? 'Logo' }}">
                                            </div>
                                        @endif
                                        <div class="sh-percentage">{{ $right['percentage'] ?? '' }}</div>
                                        <div class="sh-name">{{ $right['name'] ?? '' }}</div>
                                        @if(!empty($right['desc']))
                                            <div class="sh-small">{{ $right['desc'] }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </section>
                    </div>


                {{-- ════════════════════════════════════
                     HSE
                ════════════════════════════════════ --}}
                @elseif($isHsePage)
                    <div class="hse-wrap">
                        <section
                            class="hse-hero reveal-up"
                            style="{{ $page->page?->cover_image ? '--hse-bg:url(' . asset($page->page->cover_image) . ');' : '' }}"
                        >
                            <div class="hse-kicker">
                                {{ $locale === 'id' ? 'Health, Safety & Environment' : 'Health, Safety & Environment' }}
                            </div>

                            <h1 class="hse-title">
                                {{ $hseIntro['title'] ?? $page->title }}
                            </h1>

                            @if(!empty($hseIntro['desc']))
                                <p class="hse-desc">{{ $hseIntro['desc'] }}</p>
                            @endif
                        </section>

                        <section class="hse-policy-card reveal-up delay-1">
                            <div class="hse-section-head">
                                <div class="hse-section-kicker">{{ $locale === 'id' ? 'Kebijakan' : 'Policy' }}</div>
                                <h2 class="hse-section-title">{{ $hsePolicyTitle }}</h2>
                            </div>

                            @if(!empty($hsePolicyImage))
                                <div class="hse-policy-media">
                                    <div class="hse-policy-frame">
                                        <img src="{{ asset($hsePolicyImage) }}" alt="{{ $hsePolicyTitle }}">
                                    </div>
                                </div>
                            @else
                                <div class="profil-body" style="margin:18px;">
                                    <div class="profil-page-content">
                                        <p>{{ $locale === 'id' ? 'Gambar kebijakan HSE belum tersedia.' : 'HSE policy image is not available yet.' }}</p>
                                    </div>
                                </div>
                            @endif
                        </section>

                        <section class="hse-cert-card reveal-up delay-2">
                            <div class="hse-cert-header">
                                <h2 class="hse-cert-main-title">{{ $hseCertification['title'] ?? '' }}</h2>
                                @if(!empty($hseCertification['subtitle']))
                                    <p class="hse-cert-subtitle">{{ $hseCertification['subtitle'] }}</p>
                                @endif
                            </div>

                            @if($hseCertificationItems->isNotEmpty())
                                <div class="hse-cert-grid">
                                    @foreach($hseCertificationItems as $item)
                                        <article class="hse-cert-item reveal-scale" style="transition-delay:{{ ($loop->index * 0.08) + 0.1 }}s">
                                            <h3 class="hse-cert-code">{{ $item['code'] ?? '' }}</h3>
                                            <p class="hse-cert-text">{{ $item['title'] ?? '' }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                {{-- ════════════════════════════════════
                     ORGANIZATION STRUCTURE
                ════════════════════════════════════ --}}
                @elseif($isOrganizationStructurePage)
                    <div class="org-wrap">
                        <section
                            class="org-hero reveal-up"
                            style="{{ $page->page?->cover_image ? '--org-bg:url(' . asset($page->page->cover_image) . ');' : '' }}"
                        >
                            <div class="org-kicker">
                                {{ $locale === 'id' ? 'Struktur Organisasi' : 'Organization Structure' }}
                            </div>

                            <h1 class="org-title">
                                {{ $organizationIntro['title'] ?? $page->title }}
                            </h1>

                            @if(!empty($organizationIntro['desc']))
                                <p class="org-desc">{{ $organizationIntro['desc'] }}</p>
                            @endif
                        </section>

                        <section class="org-layout">
                            <div class="org-column reveal-up delay-1">
                                @if(!empty($director))
                                    <article class="org-card">
                                        <div class="org-card-media">
                                            @if(!empty($director['photo']))
                                                <img src="{{ asset($director['photo']) }}" alt="{{ $director['name'] ?? 'Direktur' }}">
                                            @endif
                                        </div>
                                        <div class="org-card-body">
                                            <div class="org-badge">{{ $locale === 'id' ? 'Direktur' : 'Director' }}</div>
                                            <h3 class="org-name">{{ $director['name'] ?? '-' }}</h3>
                                            <p class="org-position">{{ $director['position'] ?? ($locale === 'id' ? 'Direktur Utama' : 'President Director') }}</p>
                                        </div>
                                    </article>
                                @else
                                    <div class="org-empty">
                                        {{ $locale === 'id' ? 'Data direktur belum tersedia.' : 'Director data is not available yet.' }}
                                    </div>
                                @endif
                            </div>

                            <div class="org-column reveal-up delay-2">
                                @if(!empty($commissioner))
                                    <article class="org-card">
                                        <div class="org-card-media">
                                            @if(!empty($commissioner['photo']))
                                                <img src="{{ asset($commissioner['photo']) }}" alt="{{ $commissioner['name'] ?? 'Komisaris' }}">
                                            @endif
                                        </div>
                                        <div class="org-card-body">
                                            <div class="org-badge">{{ $locale === 'id' ? 'Komisaris' : 'Commissioner' }}</div>
                                            <h3 class="org-name">{{ $commissioner['name'] ?? '-' }}</h3>
                                            <p class="org-position">{{ $commissioner['position'] ?? ($locale === 'id' ? 'Komisaris Utama' : 'President Commissioner') }}</p>
                                        </div>
                                    </article>
                                @else
                                    <div class="org-empty">
                                        {{ $locale === 'id' ? 'Data komisaris belum tersedia.' : 'Commissioner data is not available yet.' }}
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                {{-- ════════════════════════════════════
                     GENERIC CONTENT
                ════════════════════════════════════ --}}
                @else
                    <header class="reveal-up" style="margin-bottom:20px;">
                        <h1 class="profil-title">{{ $page->title }}</h1>
                        @if($page->page?->cover_image)
                            <img
                                src="{{ asset($page->page->cover_image) }}"
                                alt="{{ $page->title }}"
                                class="profil-cover"
                            >
                        @endif
                    </header>
                    <section class="profil-body reveal-up delay-1">
                        <div class="profil-page-content">
                            {!! $page->content !!}
                        </div>
                    </section>
                @endif

            @else
                <section class="profil-body reveal-up">
                    <div class="profil-page-content">
                        <p>{{ $locale === 'id' ? 'Konten profil belum tersedia.' : 'Profile content is not available yet.' }}</p>
                    </div>
                </section>
            @endif
        </div>

        {{-- ── SIDEBAR ── --}}
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

<script>
(function () {
    var revealClasses = ['.reveal-up', '.reveal-left', '.reveal-right', '.reveal-scale'];
    var items = document.querySelectorAll(revealClasses.join(','));

    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.10 });

        items.forEach(function(item) { observer.observe(item); });
    } else {
        items.forEach(function(item) { item.classList.add('is-visible'); });
    }

    /* tap-to-flip on mobile for history timeline */
    if (window.innerWidth <= 980) {
        document.querySelectorAll('.hs-flip').forEach(function(card) {
            card.addEventListener('click', function() {
                card.classList.toggle('is-flipped');
            });
        });
    }
})();
</script>
@endsection