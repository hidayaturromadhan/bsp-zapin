@extends('layouts.app')

@section('body_class', 'page-wbs')

@section('content')
@php
    $locale = $locale ?? app()->getLocale();
    $isId = $locale === 'id';

    $subtitle = $isId
        ? 'Sarana pelaporan dugaan pelanggaran yang aman, tertib, dan dapat ditindaklanjuti.'
        : 'A secure reporting channel for alleged violations that can be properly followed up.';

    $reportUrl = auth()->check()
        ? route('wbs.pelapor.dashboard')
        : route('login');

    $principles = [
        [
            'title_id' => 'Kerahasiaan',
            'title_en' => 'Confidentiality',
            'desc_id' => 'Identitas dan data pelapor dikelola secara terbatas sesuai kewenangan.',
            'desc_en' => 'Reporter identity and data are handled with limited authorized access.',
            'icon' => 'lock',
        ],
        [
            'title_id' => 'Perlindungan Pelapor',
            'title_en' => 'Reporter Protection',
            'desc_id' => 'Pelapor berhak mendapat perlindungan dari tekanan atau tindakan merugikan.',
            'desc_en' => 'Reporters are entitled to protection from pressure or harmful actions.',
            'icon' => 'shield',
        ],
        [
            'title_id' => 'Anonimitas',
            'title_en' => 'Anonymity',
            'desc_id' => 'Pelapor dapat menyampaikan informasi dengan tetap menjaga kerahasiaan identitas.',
            'desc_en' => 'Reports can be submitted while maintaining identity confidentiality.',
            'icon' => 'user',
        ],
        [
            'title_id' => 'Independensi',
            'title_en' => 'Independence',
            'desc_id' => 'Penanganan laporan dilakukan secara objektif dan bebas dari konflik kepentingan.',
            'desc_en' => 'Reports are handled objectively and free from conflicts of interest.',
            'icon' => 'scale',
        ],
        [
            'title_id' => 'Keadilan',
            'title_en' => 'Fairness',
            'desc_id' => 'Setiap laporan ditangani secara proporsional, adil, dan tidak memihak.',
            'desc_en' => 'Every report is handled proportionally, fairly, and impartially.',
            'icon' => 'check',
        ],
        [
            'title_id' => 'Aksesibilitas',
            'title_en' => 'Accessibility',
            'desc_id' => 'Sistem pelaporan dibuat mudah digunakan dan dapat diakses oleh pelapor.',
            'desc_en' => 'The reporting system is made easy to use and accessible to reporters.',
            'icon' => 'access',
        ],
        [
            'title_id' => 'Akuntabilitas',
            'title_en' => 'Accountability',
            'desc_id' => 'Setiap laporan dicatat, dipantau, dan dikelola secara bertanggung jawab.',
            'desc_en' => 'Each report is recorded, monitored, and managed responsibly.',
            'icon' => 'file',
        ],
        [
            'title_id' => 'Tindak Lanjut',
            'title_en' => 'Follow-Up',
            'desc_id' => 'Laporan yang memenuhi kriteria akan ditindaklanjuti sesuai prosedur.',
            'desc_en' => 'Reports that meet the criteria will be followed up according to procedures.',
            'icon' => 'clock',
        ],
    ];
@endphp

<style>
    body.page-wbs .n-main {
        max-width: none;
        width: 100%;
        margin: 0;
        padding: 0;
        overflow: visible;
    }

    .wbs-public {
        --wbs-green-900: #123806;
        --wbs-green-800: #173f08;
        --wbs-green-700: #21560e;
        --wbs-green-600: #2f7d32;
        --wbs-green-100: #eef7ed;
        --wbs-gold: #d4a843;
        --wbs-gold-dark: #9a6f0a;
        --wbs-text: #102033;
        --wbs-muted: #64748b;
        --wbs-line: #e6edf3;
        --wbs-white: #ffffff;
        --wbs-bg: #f7faf6;

        width: 100%;
        background: var(--wbs-bg);
        overflow: hidden;
    }

    .wbs-skeleton-wrap {
        width: 100%;
        background: var(--wbs-bg);
        overflow: hidden;
    }

    .wbs-skeleton-wrap.is-hidden {
        display: none;
    }

    .wbs-real-content {
        display: none;
    }

    .wbs-real-content.is-loaded {
        display: block;
    }

    .wbs-sk-line,
    .wbs-sk-box,
    .wbs-sk-circle {
        position: relative;
        overflow: hidden;
        background: rgba(226,232,240,.88);
    }

    .wbs-sk-line::after,
    .wbs-sk-box::after,
    .wbs-sk-circle::after {
        content: '';
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.68), transparent);
        animation: wbsSkeletonShimmer 1.35s infinite;
    }

    @keyframes wbsSkeletonShimmer {
        100% { transform: translateX(100%); }
    }

    .wbs-skeleton-hero {
        position: relative;
        min-height: 620px;
        padding: 96px 0 86px;
        display: flex;
        align-items: center;
        background:
            radial-gradient(circle at 12% 16%, rgba(212,168,67,.20) 0, transparent 26%),
            radial-gradient(circle at 86% 28%, rgba(47,125,50,.25) 0, transparent 24%),
            linear-gradient(135deg, #0f2d05 0%, #173f08 48%, #21560e 100%);
        isolation: isolate;
    }

    .wbs-skeleton-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: -2;
        background-image:
            linear-gradient(135deg, rgba(255,255,255,.06) 0 1px, transparent 1px),
            linear-gradient(45deg, rgba(255,255,255,.04) 0 1px, transparent 1px);
        background-size: 72px 72px, 96px 96px;
        opacity: .65;
    }

    .wbs-skeleton-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.08fr) minmax(340px, .92fr);
        gap: 58px;
        align-items: center;
    }

    .wbs-sk-pill {
        width: 230px;
        height: 38px;
        border-radius: 999px;
        background: rgba(255,255,255,.18);
        margin-bottom: 22px;
    }

    .wbs-sk-title {
        width: min(620px, 100%);
        height: 76px;
        border-radius: 24px;
        background: rgba(255,255,255,.20);
        margin-bottom: 14px;
    }

    .wbs-sk-title.second {
        width: min(430px, 78%);
        margin-bottom: 26px;
    }

    .wbs-sk-subtitle {
        width: min(690px, 100%);
        height: 18px;
        border-radius: 999px;
        background: rgba(255,255,255,.20);
        margin-bottom: 12px;
    }

    .wbs-sk-subtitle.short {
        width: min(540px, 82%);
    }

    .wbs-sk-actions {
        display: flex;
        gap: 14px;
        margin-top: 34px;
        flex-wrap: wrap;
    }

    .wbs-sk-btn {
        width: 155px;
        height: 52px;
        border-radius: 999px;
        background: rgba(255,255,255,.26);
    }

    .wbs-sk-btn.alt {
        width: 130px;
        background: rgba(255,255,255,.16);
    }

    .wbs-sk-panel {
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 34px;
        padding: 26px;
        box-shadow: 0 30px 80px rgba(0,0,0,.22);
        backdrop-filter: blur(18px);
    }

    .wbs-sk-card {
        background: rgba(255,255,255,.94);
        border-radius: 28px;
        padding: 28px;
    }

    .wbs-sk-icon {
        width: 72px;
        height: 72px;
        border-radius: 24px;
        margin-bottom: 20px;
    }

    .wbs-sk-card-title {
        width: 75%;
        height: 30px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .wbs-sk-card-text {
        width: 100%;
        height: 14px;
        border-radius: 999px;
        margin-bottom: 10px;
    }

    .wbs-sk-card-text.short {
        width: 80%;
        margin-bottom: 24px;
    }

    .wbs-sk-mini {
        display: flex;
        gap: 12px;
        padding: 14px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #e6edf3;
        margin-top: 12px;
    }

    .wbs-sk-mini-icon {
        width: 20px;
        height: 20px;
        border-radius: 999px;
        flex: 0 0 20px;
        margin-top: 3px;
    }

    .wbs-sk-mini-content {
        flex: 1;
    }

    .wbs-sk-mini-title {
        width: 45%;
        height: 14px;
        border-radius: 999px;
        margin-bottom: 9px;
    }

    .wbs-sk-mini-text {
        width: 88%;
        height: 12px;
        border-radius: 999px;
    }

    .wbs-skeleton-section {
        padding: 86px 0;
        background: #ffffff;
    }

    .wbs-skeleton-section.soft {
        background:
            radial-gradient(circle at 18% 10%, rgba(47,125,50,.08) 0, transparent 28%),
            radial-gradient(circle at 88% 60%, rgba(212,168,67,.10) 0, transparent 24%),
            #f7faf6;
    }

    .wbs-sk-kicker {
        width: 160px;
        height: 18px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .wbs-sk-heading {
        width: min(620px, 100%);
        height: 44px;
        border-radius: 16px;
        margin-bottom: 16px;
    }

    .wbs-sk-heading-text {
        width: min(760px, 100%);
        height: 15px;
        border-radius: 999px;
        margin-bottom: 10px;
    }

    .wbs-sk-heading-text.short {
        width: min(580px, 76%);
        margin-bottom: 34px;
    }

    .wbs-sk-card-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    .wbs-sk-principle-card {
        background: #ffffff;
        border: 1px solid var(--wbs-line);
        border-radius: 28px;
        padding: 26px;
        box-shadow: 0 18px 44px rgba(16,32,51,.06);
    }

    .wbs-sk-principle-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        margin-bottom: 18px;
    }

    .wbs-sk-principle-title {
        width: 52%;
        height: 20px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .wbs-sk-principle-text {
        width: 100%;
        height: 13px;
        border-radius: 999px;
        margin-bottom: 9px;
    }

    .wbs-sk-principle-text.short {
        width: 78%;
    }

    .wbs-sk-flow-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .wbs-sk-flow-card {
        padding: 24px;
        border-radius: 28px;
        background: #ffffff;
        border: 1px solid var(--wbs-line);
        box-shadow: 0 18px 44px rgba(16,32,51,.05);
    }

    .wbs-sk-number {
        width: 42px;
        height: 42px;
        border-radius: 16px;
        margin-bottom: 18px;
    }

    .wbs-sk-flow-title {
        width: 72%;
        height: 18px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .wbs-sk-flow-text {
        width: 100%;
        height: 13px;
        border-radius: 999px;
        margin-bottom: 9px;
    }

    .wbs-sk-flow-text.short {
        width: 72%;
    }

    .wbs-sk-cta {
        border-radius: 36px;
        padding: 46px;
        background:
            radial-gradient(circle at 20% 20%, rgba(212,168,67,.18) 0, transparent 28%),
            radial-gradient(circle at 92% 30%, rgba(255,255,255,.10) 0, transparent 22%),
            linear-gradient(135deg, var(--wbs-green-900), var(--wbs-green-700));
        box-shadow: 0 28px 80px rgba(23,63,8,.18);
    }

    .wbs-sk-cta-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 28px;
        align-items: center;
    }

    .wbs-sk-cta-title {
        width: min(460px, 100%);
        height: 42px;
        border-radius: 16px;
        background: rgba(255,255,255,.22);
        margin-bottom: 18px;
    }

    .wbs-sk-cta-text {
        width: min(680px, 100%);
        height: 15px;
        border-radius: 999px;
        background: rgba(255,255,255,.20);
    }

    .wbs-sk-cta-btn {
        width: 165px;
        height: 52px;
        border-radius: 999px;
        background: rgba(255,255,255,.28);
    }

    .wbs-hero {
        position: relative;
        min-height: 620px;
        display: flex;
        align-items: center;
        padding: 96px 0 86px;
        background:
            radial-gradient(circle at 12% 16%, rgba(212,168,67,.20) 0, transparent 26%),
            radial-gradient(circle at 86% 28%, rgba(47,125,50,.25) 0, transparent 24%),
            linear-gradient(135deg, #0f2d05 0%, #173f08 48%, #21560e 100%);
        color: #ffffff;
        isolation: isolate;
    }

    .wbs-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        z-index: -2;
        background-image:
            linear-gradient(135deg, rgba(255,255,255,.06) 0 1px, transparent 1px),
            linear-gradient(45deg, rgba(255,255,255,.04) 0 1px, transparent 1px);
        background-size: 72px 72px, 96px 96px;
        opacity: .65;
    }

    .wbs-wave {
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        width: 100%;
        height: 120px;
        z-index: 1;
    }

    .wbs-shape {
        position: absolute;
        border: 1px solid rgba(255,255,255,.12);
        background: rgba(255,255,255,.045);
        backdrop-filter: blur(2px);
        border-radius: 26px;
        transform: rotate(12deg);
        z-index: -1;
    }

    .wbs-shape.one {
        width: 180px;
        height: 180px;
        top: 92px;
        right: 9%;
    }

    .wbs-shape.two {
        width: 120px;
        height: 120px;
        bottom: 150px;
        left: 6%;
        transform: rotate(-16deg);
    }

    .wbs-shape.three {
        width: 86px;
        height: 86px;
        top: 240px;
        right: 24%;
        border-radius: 22px;
        transform: rotate(28deg);
    }

    .wbs-container {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .wbs-hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.08fr) minmax(340px, .92fr);
        gap: 58px;
        align-items: center;
    }

    .wbs-pill {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 9px 14px;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 999px;
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.88);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 22px;
    }

    .wbs-pill svg {
        width: 16px;
        height: 16px;
    }

    .wbs-hero-title {
        margin: 0;
        max-width: 760px;
        font-size: clamp(42px, 5vw, 72px);
        line-height: .98;
        letter-spacing: -.055em;
        font-weight: 900;
    }

    .wbs-hero-title span {
        color: var(--wbs-gold);
    }

    .wbs-hero-subtitle {
        margin: 24px 0 0;
        max-width: 690px;
        color: rgba(255,255,255,.78);
        font-size: 18px;
        line-height: 1.85;
    }

    .wbs-hero-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 34px;
    }

    .wbs-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 52px;
        padding: 0 22px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease;
    }

    .wbs-btn svg {
        width: 18px;
        height: 18px;
    }

    .wbs-btn-primary {
        color: #173f08;
        background: #ffffff;
        box-shadow: 0 18px 44px rgba(0,0,0,.22);
    }

    .wbs-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 54px rgba(0,0,0,.28);
    }

    .wbs-btn-outline {
        color: #ffffff;
        border: 1px solid rgba(255,255,255,.28);
        background: rgba(255,255,255,.07);
    }

    .wbs-btn-outline:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,.12);
    }

    .wbs-hero-panel {
        position: relative;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 34px;
        padding: 26px;
        box-shadow: 0 30px 80px rgba(0,0,0,.22);
        backdrop-filter: blur(18px);
        overflow: hidden;
    }

    .wbs-hero-panel::before {
        content: '';
        position: absolute;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        right: -90px;
        top: -90px;
        background: rgba(212,168,67,.18);
    }

    .wbs-hero-card {
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,.94);
        border-radius: 28px;
        padding: 28px;
        color: var(--wbs-text);
    }

    .wbs-icon-large {
        width: 72px;
        height: 72px;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eef7ed, #ffffff);
        border: 1px solid #dcebdd;
        color: var(--wbs-green-700);
        margin-bottom: 20px;
    }

    .wbs-icon-large svg {
        width: 38px;
        height: 38px;
    }

    .wbs-hero-card h3 {
        margin: 0 0 10px;
        font-size: 26px;
        line-height: 1.2;
        letter-spacing: -.03em;
        font-weight: 900;
        color: var(--wbs-green-900);
    }

    .wbs-hero-card p {
        margin: 0;
        color: var(--wbs-muted);
        line-height: 1.8;
        font-size: 15px;
    }

    .wbs-mini-list {
        display: grid;
        gap: 12px;
        margin-top: 24px;
    }

    .wbs-mini-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #e6edf3;
    }

    .wbs-mini-item svg {
        width: 20px;
        height: 20px;
        flex: 0 0 20px;
        color: var(--wbs-green-700);
        margin-top: 2px;
    }

    .wbs-mini-item strong {
        display: block;
        color: var(--wbs-text);
        font-size: 14px;
        margin-bottom: 2px;
    }

    .wbs-mini-item span {
        color: var(--wbs-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .wbs-section {
        padding: 86px 0;
        position: relative;
    }

    .wbs-section.light {
        background: #ffffff;
    }

    .wbs-section.soft {
        background:
            radial-gradient(circle at 18% 10%, rgba(47,125,50,.08) 0, transparent 28%),
            radial-gradient(circle at 88% 60%, rgba(212,168,67,.10) 0, transparent 24%),
            #f7faf6;
    }

    .wbs-heading {
        max-width: 760px;
        margin-bottom: 34px;
    }

    .wbs-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--wbs-green-700);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .wbs-kicker svg {
        width: 16px;
        height: 16px;
    }

    .wbs-heading h2 {
        margin: 0;
        color: var(--wbs-text);
        font-size: clamp(30px, 3.2vw, 46px);
        line-height: 1.08;
        letter-spacing: -.045em;
        font-weight: 900;
    }

    .wbs-heading p {
        margin: 16px 0 0;
        color: var(--wbs-muted);
        font-size: 16px;
        line-height: 1.85;
    }

    .wbs-card-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    .wbs-card {
        position: relative;
        background: #ffffff;
        border: 1px solid var(--wbs-line);
        border-radius: 28px;
        padding: 26px;
        box-shadow: 0 18px 44px rgba(16,32,51,.06);
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        min-height: 245px;
    }

    .wbs-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 26px 60px rgba(16,32,51,.10);
        border-color: rgba(47,125,50,.22);
    }

    .wbs-card::after {
        content: '';
        position: absolute;
        width: 96px;
        height: 96px;
        right: -38px;
        top: -38px;
        border-radius: 24px;
        background: rgba(47,125,50,.06);
        transform: rotate(18deg);
    }

    .wbs-card-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--wbs-green-100);
        color: var(--wbs-green-700);
        margin-bottom: 18px;
    }

    .wbs-card-icon svg {
        width: 27px;
        height: 27px;
    }

    .wbs-card h3 {
        position: relative;
        margin: 0 0 10px;
        color: var(--wbs-text);
        font-size: 19px;
        line-height: 1.35;
        font-weight: 900;
        letter-spacing: -.02em;
        z-index: 1;
    }

    .wbs-card p {
        position: relative;
        margin: 0;
        color: var(--wbs-muted);
        line-height: 1.78;
        font-size: 14.5px;
        z-index: 1;
    }

    .wbs-flow {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .wbs-flow-item {
        position: relative;
        padding: 24px;
        border-radius: 28px;
        background: #ffffff;
        border: 1px solid var(--wbs-line);
        box-shadow: 0 18px 44px rgba(16,32,51,.05);
    }

    .wbs-flow-number {
        width: 42px;
        height: 42px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        background: var(--wbs-green-700);
        font-weight: 900;
        margin-bottom: 18px;
        box-shadow: 0 12px 24px rgba(33,86,14,.20);
    }

    .wbs-flow-item h3 {
        margin: 0 0 10px;
        font-size: 17px;
        color: var(--wbs-text);
        font-weight: 900;
    }

    .wbs-flow-item p {
        margin: 0;
        color: var(--wbs-muted);
        line-height: 1.75;
        font-size: 14px;
    }

    .wbs-cta {
        position: relative;
        border-radius: 36px;
        padding: 46px;
        background:
            radial-gradient(circle at 20% 20%, rgba(212,168,67,.18) 0, transparent 28%),
            radial-gradient(circle at 92% 30%, rgba(255,255,255,.10) 0, transparent 22%),
            linear-gradient(135deg, var(--wbs-green-900), var(--wbs-green-700));
        color: #ffffff;
        overflow: hidden;
        box-shadow: 0 28px 80px rgba(23,63,8,.18);
    }

    .wbs-cta::before,
    .wbs-cta::after {
        content: '';
        position: absolute;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.05);
        border-radius: 28px;
        transform: rotate(18deg);
    }

    .wbs-cta::before {
        width: 190px;
        height: 190px;
        right: 70px;
        top: -90px;
    }

    .wbs-cta::after {
        width: 110px;
        height: 110px;
        right: -28px;
        bottom: 24px;
        transform: rotate(-22deg);
    }

    .wbs-cta-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 28px;
    }

    .wbs-cta h2 {
        margin: 0;
        font-size: clamp(28px, 3vw, 42px);
        line-height: 1.12;
        letter-spacing: -.04em;
        font-weight: 900;
    }

    .wbs-cta p {
        margin: 14px 0 0;
        max-width: 720px;
        color: rgba(255,255,255,.76);
        line-height: 1.8;
        font-size: 15.5px;
    }

    @media (max-width: 1180px) {
        .wbs-card-grid,
        .wbs-sk-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 980px) {
        .wbs-hero-grid,
        .wbs-cta-inner,
        .wbs-skeleton-grid,
        .wbs-sk-cta-inner {
            grid-template-columns: 1fr;
        }

        .wbs-flow,
        .wbs-sk-flow-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wbs-hero,
        .wbs-skeleton-hero {
            min-height: auto;
            padding: 78px 0 110px;
        }
    }

    @media (max-width: 640px) {
        .wbs-container {
            width: min(100% - 30px, 1180px);
        }

        .wbs-hero-title {
            font-size: 42px;
        }

        .wbs-hero-subtitle {
            font-size: 16px;
        }

        .wbs-hero-panel,
        .wbs-hero-card,
        .wbs-card,
        .wbs-flow-item,
        .wbs-cta,
        .wbs-sk-panel,
        .wbs-sk-card,
        .wbs-sk-principle-card,
        .wbs-sk-flow-card,
        .wbs-sk-cta {
            border-radius: 24px;
        }

        .wbs-card-grid,
        .wbs-flow,
        .wbs-sk-card-grid,
        .wbs-sk-flow-grid {
            grid-template-columns: 1fr;
        }

        .wbs-section,
        .wbs-skeleton-section {
            padding: 64px 0;
        }

        .wbs-cta,
        .wbs-sk-cta {
            padding: 30px;
        }

        .wbs-sk-title {
            height: 48px;
        }

        .wbs-sk-title.second {
            width: 82%;
        }
    }
</style>

<div class="wbs-public">
    <div id="wbsSkeleton" class="wbs-skeleton-wrap" aria-hidden="true">
        <section class="wbs-skeleton-hero">
            <div class="wbs-shape one"></div>
            <div class="wbs-shape two"></div>
            <div class="wbs-shape three"></div>

            <div class="wbs-container">
                <div class="wbs-skeleton-grid">
                    <div>
                        <div class="wbs-sk-line wbs-sk-pill"></div>
                        <div class="wbs-sk-line wbs-sk-title"></div>
                        <div class="wbs-sk-line wbs-sk-title second"></div>
                        <div class="wbs-sk-line wbs-sk-subtitle"></div>
                        <div class="wbs-sk-line wbs-sk-subtitle short"></div>

                        <div class="wbs-sk-actions">
                            <div class="wbs-sk-line wbs-sk-btn"></div>
                            <div class="wbs-sk-line wbs-sk-btn alt"></div>
                        </div>
                    </div>

                    <div class="wbs-sk-panel">
                        <div class="wbs-sk-card">
                            <div class="wbs-sk-box wbs-sk-icon"></div>
                            <div class="wbs-sk-line wbs-sk-card-title"></div>
                            <div class="wbs-sk-line wbs-sk-card-text"></div>
                            <div class="wbs-sk-line wbs-sk-card-text short"></div>

                            @for ($i = 0; $i < 3; $i++)
                                <div class="wbs-sk-mini">
                                    <div class="wbs-sk-circle wbs-sk-mini-icon"></div>
                                    <div class="wbs-sk-mini-content">
                                        <div class="wbs-sk-line wbs-sk-mini-title"></div>
                                        <div class="wbs-sk-line wbs-sk-mini-text"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <svg class="wbs-wave" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,64 C220,120 390,18 620,58 C850,98 980,118 1200,62 C1320,32 1390,38 1440,48 L1440,120 L0,120 Z" fill="#ffffff"></path>
            </svg>
        </section>

        <section class="wbs-skeleton-section">
            <div class="wbs-container">
                <div class="wbs-sk-line wbs-sk-kicker"></div>
                <div class="wbs-sk-line wbs-sk-heading"></div>
                <div class="wbs-sk-line wbs-sk-heading-text"></div>
                <div class="wbs-sk-line wbs-sk-heading-text short"></div>

                <div class="wbs-sk-card-grid">
                    @for ($i = 0; $i < 8; $i++)
                        <div class="wbs-sk-principle-card">
                            <div class="wbs-sk-box wbs-sk-principle-icon"></div>
                            <div class="wbs-sk-line wbs-sk-principle-title"></div>
                            <div class="wbs-sk-line wbs-sk-principle-text"></div>
                            <div class="wbs-sk-line wbs-sk-principle-text short"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        <section class="wbs-skeleton-section soft">
            <div class="wbs-container">
                <div class="wbs-sk-line wbs-sk-kicker"></div>
                <div class="wbs-sk-line wbs-sk-heading"></div>
                <div class="wbs-sk-line wbs-sk-heading-text"></div>
                <div class="wbs-sk-line wbs-sk-heading-text short"></div>

                <div class="wbs-sk-flow-grid">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="wbs-sk-flow-card">
                            <div class="wbs-sk-box wbs-sk-number"></div>
                            <div class="wbs-sk-line wbs-sk-flow-title"></div>
                            <div class="wbs-sk-line wbs-sk-flow-text"></div>
                            <div class="wbs-sk-line wbs-sk-flow-text short"></div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        <section class="wbs-skeleton-section">
            <div class="wbs-container">
                <div class="wbs-sk-cta">
                    <div class="wbs-sk-cta-inner">
                        <div>
                            <div class="wbs-sk-line wbs-sk-cta-title"></div>
                            <div class="wbs-sk-line wbs-sk-cta-text"></div>
                        </div>
                        <div class="wbs-sk-line wbs-sk-cta-btn"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="wbsRealContent" class="wbs-real-content">
        <section class="wbs-hero">
            <div class="wbs-shape one"></div>
            <div class="wbs-shape two"></div>
            <div class="wbs-shape three"></div>

            <div class="wbs-container">
                <div class="wbs-hero-grid">
                    <div>
                        <div class="wbs-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z"></path>
                                <path d="M9 12l2 2 4-5"></path>
                            </svg>
                            {{ $isId ? 'Saluran Pelaporan Resmi' : 'Official Reporting Channel' }}
                        </div>

                        <h1 class="wbs-hero-title">
                            Whistleblowing System
                            <span>BSP Zapin</span>
                        </h1>

                        <p class="wbs-hero-subtitle">{{ $subtitle }}</p>

                        <div class="wbs-hero-actions">
                            <a href="{{ $reportUrl }}" class="wbs-btn wbs-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M12 5v14"></path>
                                    <path d="M5 12h14"></path>
                                </svg>
                                {{ $isId ? 'Lapor Sekarang' : 'Report Now' }}
                            </a>

                            <a href="#alur" class="wbs-btn wbs-btn-outline">
                                {{ $isId ? 'Lihat Alur' : 'View Flow' }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="wbs-hero-panel">
                        <div class="wbs-hero-card">
                            <div class="wbs-icon-large">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z"></path>
                                    <path d="M9 12l2 2 4-5"></path>
                                </svg>
                            </div>

                            <h3>{{ $isId ? 'Aman, tertib, dan tercatat.' : 'Secure, structured, and recorded.' }}</h3>
                            <p>
                                {{ $isId
                                    ? 'Setiap laporan disampaikan melalui sistem agar dapat diterima, dicatat, dan ditindaklanjuti secara lebih terarah.'
                                    : 'Each report is submitted through the system so it can be received, recorded, and followed up properly.' }}
                            </p>

                            <div class="wbs-mini-list">
                                <div class="wbs-mini-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M20 7L10 17l-5-5"></path>
                                    </svg>
                                    <div>
                                        <strong>{{ $isId ? 'Kerahasiaan' : 'Confidentiality' }}</strong>
                                        <span>{{ $isId ? 'Data pelapor dikelola secara terbatas.' : 'Reporter data is handled with limited access.' }}</span>
                                    </div>
                                </div>

                                <div class="wbs-mini-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M4 4h16v16H4z"></path>
                                        <path d="M8 9h8"></path>
                                        <path d="M8 13h6"></path>
                                    </svg>
                                    <div>
                                        <strong>{{ $isId ? 'Tercatat' : 'Recorded' }}</strong>
                                        <span>{{ $isId ? 'Laporan memiliki nomor pelacakan.' : 'Reports have a tracking number.' }}</span>
                                    </div>
                                </div>

                                <div class="wbs-mini-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                        <path d="M12 8v5l3 2"></path>
                                        <circle cx="12" cy="12" r="9"></circle>
                                    </svg>
                                    <div>
                                        <strong>{{ $isId ? 'Ditindaklanjuti' : 'Followed Up' }}</strong>
                                        <span>{{ $isId ? 'Status laporan dapat dipantau.' : 'Report status can be monitored.' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <svg class="wbs-wave" viewBox="0 0 1440 120" preserveAspectRatio="none">
                <path d="M0,64 C220,120 390,18 620,58 C850,98 980,118 1200,62 C1320,32 1390,38 1440,48 L1440,120 L0,120 Z" fill="#ffffff"></path>
            </svg>
        </section>

        <section class="wbs-section light">
            <div class="wbs-container">
                <div class="wbs-heading">
                    <div class="wbs-kicker">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z"></path>
                        </svg>
                        {{ $isId ? 'Prinsip WBS' : 'WBS Principles' }}
                    </div>
                    <h2>{{ $isId ? 'Prinsip utama dalam pengelolaan pelaporan WBS.' : 'Key principles in managing WBS reports.' }}</h2>
                    <p>
                        {{ $isId
                            ? 'Setiap laporan dikelola berdasarkan prinsip yang menjaga keamanan pelapor, kejelasan proses, dan tindak lanjut yang bertanggung jawab.'
                            : 'Each report is managed based on principles that protect reporters, ensure clear processes, and support responsible follow-up.' }}
                    </p>
                </div>

                <div class="wbs-card-grid">
                    @foreach($principles as $principle)
                        <div class="wbs-card">
                            <div class="wbs-card-icon">
                                @switch($principle['icon'])
                                    @case('lock')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                            <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                        </svg>
                                        @break

                                    @case('shield')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z"></path>
                                            <path d="M9 12l2 2 4-5"></path>
                                        </svg>
                                        @break

                                    @case('user')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <circle cx="12" cy="8" r="4"></circle>
                                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                                        </svg>
                                        @break

                                    @case('scale')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <path d="M12 3v18"></path>
                                            <path d="M5 7h14"></path>
                                            <path d="M6 7l-3 6h6L6 7Z"></path>
                                            <path d="M18 7l-3 6h6l-3-6Z"></path>
                                        </svg>
                                        @break

                                    @case('check')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <path d="M20 7L10 17l-5-5"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                        </svg>
                                        @break

                                    @case('access')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <path d="M4 12h16"></path>
                                            <path d="M12 4v16"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                        </svg>
                                        @break

                                    @case('file')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                                            <path d="M14 3v5h5"></path>
                                            <path d="M9 13h6"></path>
                                            <path d="M9 17h4"></path>
                                        </svg>
                                        @break

                                    @default
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                            <path d="M12 8v5l3 2"></path>
                                            <circle cx="12" cy="12" r="9"></circle>
                                        </svg>
                                @endswitch
                            </div>
                            <h3>{{ $isId ? $principle['title_id'] : $principle['title_en'] }}</h3>
                            <p>{{ $isId ? $principle['desc_id'] : $principle['desc_en'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="wbs-section soft" id="alur">
            <div class="wbs-container">
                <div class="wbs-heading">
                    <div class="wbs-kicker">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M4 6h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 18h10"></path>
                        </svg>
                        {{ $isId ? 'Alur Pelaporan' : 'Reporting Flow' }}
                    </div>
                    <h2>{{ $isId ? 'Langkah pelaporan WBS.' : 'WBS reporting steps.' }}</h2>
                    <p>
                        {{ $isId
                            ? 'Pelaporan dilakukan melalui akun pelapor agar laporan dapat dipantau kembali.'
                            : 'Reports are submitted through a reporter account so they can be monitored later.' }}
                    </p>
                </div>

                <div class="wbs-flow">
                    <div class="wbs-flow-item">
                        <div class="wbs-flow-number">1</div>
                        <h3>{{ $isId ? 'Login / Daftar' : 'Login / Register' }}</h3>
                        <p>{{ $isId ? 'Pelapor masuk ke sistem atau membuat akun terlebih dahulu.' : 'The reporter signs in or creates an account first.' }}</p>
                    </div>

                    <div class="wbs-flow-item">
                        <div class="wbs-flow-number">2</div>
                        <h3>{{ $isId ? 'Isi Laporan' : 'Fill Report' }}</h3>
                        <p>{{ $isId ? 'Lengkapi kategori, uraian, kronologi, lokasi, dan lampiran bila ada.' : 'Complete category, description, chronology, location, and attachments if any.' }}</p>
                    </div>

                    <div class="wbs-flow-item">
                        <div class="wbs-flow-number">3</div>
                        <h3>{{ $isId ? 'Diterima Admin' : 'Received by Admin' }}</h3>
                        <p>{{ $isId ? 'Admin WBS memantau dan memperbarui status laporan.' : 'WBS admin monitors and updates the report status.' }}</p>
                    </div>

                    <div class="wbs-flow-item">
                        <div class="wbs-flow-number">4</div>
                        <h3>{{ $isId ? 'Pantau Status' : 'Track Status' }}</h3>
                        <p>{{ $isId ? 'Pelapor dapat melihat status dan catatan tindak lanjut pada dashboard.' : 'The reporter can view status and follow-up notes on the dashboard.' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="wbs-section light">
            <div class="wbs-container">
                <div class="wbs-cta">
                    <div class="wbs-cta-inner">
                        <div>
                            <h2>{{ $isId ? 'Siap membuat laporan?' : 'Ready to submit a report?' }}</h2>
                            <p>
                                {{ $isId
                                    ? 'Gunakan sistem WBS untuk menyampaikan laporan secara tertib dan tercatat.'
                                    : 'Use the WBS system to submit reports in a structured and recorded way.' }}
                            </p>
                        </div>

                        <a href="{{ $reportUrl }}" class="wbs-btn wbs-btn-primary">
                            {{ $isId ? 'Lapor Sekarang' : 'Report Now' }}
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M5 12h14"></path>
                                <path d="M13 6l6 6-6 6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const skeleton = document.getElementById('wbsSkeleton');
    const content = document.getElementById('wbsRealContent');

    function showWbsContent() {
        if (skeleton) {
            skeleton.classList.add('is-hidden');
        }

        if (content) {
            content.classList.add('is-loaded');
        }
    }

    if (document.readyState === 'complete') {
        setTimeout(showWbsContent, 450);
    } else {
        window.addEventListener('load', function () {
            setTimeout(showWbsContent, 450);
        });
    }

    setTimeout(showWbsContent, 1800);
});
</script>

<script type="text/javascript">
var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
Tawk_API.onLoad = function () {
    Tawk_API.setAttributes({ page: window.location.pathname }, function () {});
};
(function () {
    var s1 = document.createElement('script');
    var s0 = document.getElementsByTagName('script')[0];
    s1.async = true;
    s1.src = 'https://embed.tawk.to/69eb1d2b83d73f1c32c7f822/1jmv6jt3a';
    s1.charset = 'UTF-8';
    s1.setAttribute('crossorigin', '*');
    s0.parentNode.insertBefore(s1, s0);
})();
</script>
@endsection