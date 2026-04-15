<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <title>Admin — {{ config('app.name', 'BSP Zapin') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --g900: #0f2906;
            --g800: #173f08;
            --g700: #21560e;
            --g500: #2f7d32;
            --g300: #81c784;
            --g100: #e8f5e9;
            --g50:  #f1f8f1;
            --gold: #b8860b;
            --gold-lt: #d4a843;
            --text:  #111827;
            --text2: #374151;
            --text3: #6b7280;
            --line:  #e5e7eb;
            --line2: #f3f4f6;
            --bg:    #f4f6f9;
            --white: #ffffff;
            --sidebar-w: 248px;
            --header-h: 62px;
            --font: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif;
            --ease: cubic-bezier(.4,0,.2,1);
            --r: 10px;
            --r-lg: 14px;
            --shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
            --shadow-md: 0 4px 20px rgba(0,0,0,.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { height: 100%; }

        body {
            min-height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        :focus-visible { outline: 2px solid var(--g500); outline-offset: 2px; }

        /* ═══════════════════════════════
           TOPBAR
        ═══════════════════════════════ */
        .a-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-h);
            background: var(--g800);
            background-image: linear-gradient(90deg, var(--g900) 0%, var(--g800) 60%, var(--g700) 100%);
            z-index: 200;
            display: flex;
            align-items: center;
            padding: 0 20px 0 0;
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }

        .a-topbar-brand {
            width: var(--sidebar-w);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 20px;
            border-right: 1px solid rgba(255,255,255,.08);
            height: 100%;
        }

        .a-topbar-brand img {
            width: 34px;
            height: 34px;
            object-fit: contain;
            filter: brightness(1.2);
            flex-shrink: 0;
        }

        .a-topbar-brand-name {
            font-size: 14px;
            font-weight: 800;
            color: var(--gold-lt);
            line-height: 1.2;
            letter-spacing: -.01em;
        }

        .a-topbar-brand-sub {
            font-size: 10px;
            color: rgba(255,255,255,.45);
            font-style: italic;
        }

        .a-topbar-center {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 8px;
        }

        .a-topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
            font-size: 11.5px;
            font-weight: 600;
            color: rgba(255,255,255,.7);
        }

        .a-topbar-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--g300);
            flex-shrink: 0;
        }

        .a-topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .a-topbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            height: 36px;
            padding: 0 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            transition: background .15s var(--ease), transform .12s;
            white-space: nowrap;
            text-decoration: none;
        }

        .a-topbar-btn:active { transform: scale(.97); }

        .a-topbar-btn--ghost {
            background: rgba(255,255,255,.1);
            color: rgba(255,255,255,.9);
            border: 1px solid rgba(255,255,255,.14);
        }

        .a-topbar-btn--ghost:hover { background: rgba(255,255,255,.16); }

        .a-topbar-btn--white {
            background: var(--white);
            color: var(--g800);
            border: 1px solid rgba(255,255,255,.2);
        }

        .a-topbar-btn--white:hover { background: #f7faf7; }

        /* ═══════════════════════════════
           LAYOUT
        ═══════════════════════════════ */
        .a-layout {
            display: flex;
            padding-top: var(--header-h);
            min-height: 100vh;
        }

        /* ═══════════════════════════════
           SIDEBAR
        ═══════════════════════════════ */
        .a-sidebar {
            position: fixed;
            top: var(--header-h);
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            z-index: 100;
        }

        .a-sidebar-body { flex: 1; padding: 16px 12px; }

        .a-nav-section { margin-bottom: 24px; }

        .a-nav-label {
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text3);
            padding: 0 10px;
            margin-bottom: 6px;
            display: block;
        }

        .a-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            min-height: 42px;
            padding: 0 12px;
            border-radius: var(--r);
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text2);
            text-decoration: none;
            transition: background .14s var(--ease), color .14s var(--ease);
            position: relative;
            border: none;
            background: transparent;
            cursor: pointer;
            font-family: var(--font);
        }

        .a-nav-item:hover {
            background: var(--g50);
            color: var(--g800);
        }

        .a-nav-item.active {
            background: var(--g100);
            color: var(--g800);
            font-weight: 700;
        }

        .a-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--g500);
        }

        .a-nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--line2);
            color: var(--text3);
            transition: background .14s, color .14s;
        }

        .a-nav-item:hover .a-nav-icon,
        .a-nav-item.active .a-nav-icon {
            background: var(--g800);
            color: #fff;
        }

        .a-nav-item-text { flex: 1; line-height: 1.2; }

        .a-nav-item-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: var(--g100);
            color: var(--g700);
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .a-sidebar-foot {
            padding: 12px;
            border-top: 1px solid var(--line2);
            flex-shrink: 0;
        }

        .a-sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--r);
            background: var(--g50);
            border: 1px solid var(--line);
        }

        .a-sidebar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: var(--g800);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .a-sidebar-user-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .a-sidebar-user-role {
            font-size: 11px;
            color: var(--text3);
        }

        /* ═══════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════ */
        .a-main {
            flex: 1;
            margin-left: var(--sidebar-w);
            min-width: 0;
            padding: 28px 28px 48px;
        }

        /* Page header */
        .a-page-head {
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .a-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text3);
            margin-bottom: 6px;
        }

        .a-breadcrumb-sep { opacity: .4; }

        .a-page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            letter-spacing: -.02em;
        }

        .a-page-desc {
            margin-top: 4px;
            color: var(--text3);
            font-size: 13.5px;
        }

        /* Buttons */
        .a-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 40px;
            padding: 0 16px;
            border-radius: var(--r);
            font-size: 13.5px;
            font-weight: 700;
            font-family: var(--font);
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: background .15s var(--ease), transform .12s, box-shadow .15s;
            white-space: nowrap;
        }

        .a-btn:active { transform: scale(.97); }

        .a-btn--primary {
            background: var(--g800);
            color: #fff;
        }

        .a-btn--primary:hover {
            background: var(--g700);
            box-shadow: 0 4px 14px rgba(23,63,8,.25);
        }

        .a-btn--secondary {
            background: var(--white);
            color: var(--text2);
            border: 1px solid var(--line);
        }

        .a-btn--secondary:hover { background: var(--line2); }

        .a-btn--danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .a-btn--danger:hover { background: #fee2e2; }

        .a-btn--sm {
            min-height: 34px;
            padding: 0 12px;
            font-size: 12.5px;
            border-radius: 8px;
        }

        /* Cards */
        .a-card {
            background: var(--white);
            border-radius: var(--r-lg);
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .a-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px 16px;
            border-bottom: 1px solid var(--line2);
            flex-wrap: wrap;
        }

        .a-card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
        }

        .a-card-desc {
            font-size: 12.5px;
            color: var(--text3);
            margin-top: 2px;
        }

        .a-card-body { padding: 22px; }

        /* Stat cards */
        .a-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .a-stat {
            background: var(--white);
            border-radius: var(--r-lg);
            border: 1px solid var(--line);
            padding: 20px 20px 18px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: transform .16s var(--ease), box-shadow .16s;
        }

        .a-stat:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .a-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .a-stat-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--text3);
        }

        .a-stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .a-stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
            letter-spacing: -.03em;
        }

        .a-stat-sub {
            font-size: 12px;
            color: var(--text3);
        }

        /* Table */
        .a-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .a-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .a-table th {
            padding: 11px 16px;
            text-align: left;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--text3);
            background: var(--line2);
            border-bottom: 1px solid var(--line);
            white-space: nowrap;
        }

        .a-table th:first-child { border-radius: var(--r) 0 0 0; }
        .a-table th:last-child  { border-radius: 0 var(--r) 0 0; }

        .a-table td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--line2);
            color: var(--text2);
            vertical-align: middle;
        }

        .a-table tbody tr:last-child td { border-bottom: none; }

        .a-table tbody tr {
            transition: background .12s;
        }

        .a-table tbody tr:hover { background: var(--g50); }

        /* Badges */
        .a-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .a-badge--green  { background: #dcfce7; color: #166534; }
        .a-badge--red    { background: #fee2e2; color: #991b1b; }
        .a-badge--yellow { background: #fef9c3; color: #854d0e; }
        .a-badge--gray   { background: var(--line2); color: var(--text3); }
        .a-badge--blue   { background: #dbeafe; color: #1e40af; }

        /* Form elements */
        .a-form-group { margin-bottom: 18px; }

        .a-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--text2);
            margin-bottom: 6px;
        }

        .a-label-hint {
            font-weight: 400;
            color: var(--text3);
            margin-left: 4px;
        }

        .a-input,
        .a-select,
        .a-textarea {
            display: block;
            width: 100%;
            padding: 9px 13px;
            border-radius: var(--r);
            border: 1px solid var(--line);
            background: var(--white);
            font-size: 14px;
            font-family: var(--font);
            color: var(--text);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .a-input:focus,
        .a-select:focus,
        .a-textarea:focus {
            border-color: var(--g500);
            box-shadow: 0 0 0 3px rgba(47,125,50,.1);
        }

        .a-textarea { min-height: 100px; resize: vertical; line-height: 1.6; }

        /* Alert */
        .a-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: var(--r);
            font-size: 13.5px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .a-alert--success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .a-alert--error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .a-alert--info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
        .a-alert--warn    { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }

        /* Divider */
        .a-divider {
            height: 1px;
            background: var(--line2);
            margin: 20px 0;
        }

        /* Empty state */
        .a-empty {
            text-align: center;
            padding: 48px 24px;
        }

        .a-empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: var(--line2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--text3);
        }

        .a-empty-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }

        .a-empty-desc {
            font-size: 13.5px;
            color: var(--text3);
            max-width: 320px;
            margin: 0 auto 20px;
        }

        /* Scrollbar */
        .a-sidebar::-webkit-scrollbar { width: 4px; }
        .a-sidebar::-webkit-scrollbar-track { background: transparent; }
        .a-sidebar::-webkit-scrollbar-thumb { background: var(--line); border-radius: 4px; }

        /* ═══════════════════════════════
           RESPONSIVE
        ═══════════════════════════════ */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 220px; }
        }

        @media (max-width: 860px) {
            :root { --sidebar-w: 0px; }

            .a-sidebar {
                transform: translateX(-100%);
                width: 260px;
                transition: transform .24s var(--ease), box-shadow .24s;
            }

            .a-sidebar.is-open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,.12);
                width: 260px;
            }

            .a-main { margin-left: 0; padding: 20px 16px 40px; }

            .a-topbar-brand { display: none; }

            .a-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.3);
                z-index: 90;
                opacity: 0;
                transition: opacity .22s;
            }

            .a-overlay.is-open { display: block; opacity: 1; }

            .a-topbar-center { padding: 0 12px; }

            .a-page-title { font-size: 22px; }
        }

        @media (max-width: 540px) {
            .a-topbar { padding: 0 12px 0 0; }
            .a-topbar-btn span { display: none; }
            .a-topbar-btn { padding: 0 10px; }
            .a-stats-grid { grid-template-columns: 1fr 1fr; }
            .a-main { padding: 16px 14px 40px; }
        }

        /* Toast Alerts */
        .a-toast-stack {
            position: fixed;
            top: calc(var(--header-h) + 16px);
            right: 20px;
            z-index: 500;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: min(380px, calc(100vw - 24px));
            pointer-events: none;
        }

        .a-toast {
            --toast-accent: #166534;
            position: relative;
            display: grid;
            grid-template-columns: 40px 1fr auto;
            gap: 12px;
            align-items: flex-start;
            padding: 14px 14px 14px 12px;
            border-radius: 16px;
            background: rgba(255,255,255,.96);
            border: 1px solid rgba(255,255,255,.7);
            box-shadow: 0 18px 40px rgba(15,23,42,.14), 0 4px 12px rgba(15,23,42,.08);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            overflow: hidden;
            pointer-events: auto;
            transform-origin: top right;
            animation: aToastIn .42s cubic-bezier(.22,.61,.36,1) forwards;
        }

        .a-toast.is-hiding {
            animation: aToastOut .28s cubic-bezier(.55,.06,.68,.19) forwards;
        }

        .a-toast::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
            background: var(--toast-accent);
        }

        .a-toast::after {
            content: '';
            position: absolute;
            inset: auto 0 0 0;
            height: 3px;
            background: linear-gradient(90deg, var(--toast-accent), transparent 82%);
            transform-origin: left center;
            animation: aToastTimer linear forwards;
            animation-duration: var(--toast-duration, 5200ms);
        }

        .a-toast:hover::after {
            animation-play-state: paused;
        }

        .a-toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--toast-accent);
            background: color-mix(in srgb, var(--toast-accent) 10%, white);
            flex-shrink: 0;
        }

        .a-toast-body {
            min-width: 0;
        }

        .a-toast-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.35;
            margin-bottom: 2px;
        }

        .a-toast-text {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.55;
            word-break: break-word;
        }

        .a-toast-close {
            appearance: none;
            border: none;
            background: transparent;
            color: var(--text3);
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s var(--ease), color .15s var(--ease), transform .15s var(--ease);
            margin-top: -2px;
        }

        .a-toast-close:hover {
            background: var(--line2);
            color: var(--text);
            transform: scale(1.04);
        }

        .a-toast--success { --toast-accent: #166534; }
        .a-toast--error   { --toast-accent: #b42318; }
        .a-toast--warning { --toast-accent: #b45309; }
        .a-toast--info    { --toast-accent: #1d4ed8; }

        @keyframes aToastIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes aToastOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
                max-height: 220px;
                margin-bottom: 0;
            }
            to {
                opacity: 0;
                transform: translateY(-8px) scale(.96);
                max-height: 0;
                margin-bottom: -4px;
            }
        }

        @keyframes aToastTimer {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

    </style>
</head>
<body>

<div class="a-overlay" id="aOverlay"></div>

<header class="a-topbar">
    <div class="a-topbar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
        <div>
            <div class="a-topbar-brand-name">BSP Zapin</div>
            <div class="a-topbar-brand-sub">Admin Panel</div>
        </div>
    </div>

    <div class="a-topbar-center">
        <button class="a-topbar-btn a-topbar-btn--ghost" id="aSidebarToggle" aria-label="Toggle sidebar" style="display:none; padding:0 10px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <div class="a-topbar-badge">
            <span class="a-topbar-badge-dot"></span>
            BSP Zapin CMS
        </div>
    </div>

    <div class="a-topbar-right">
        <a href="{{ route('web.home', ['locale' => 'id']) }}" target="_blank" class="a-topbar-btn a-topbar-btn--ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
            </svg>
            <span>Lihat Website</span>
        </a>

        @if(session()->has('user_id'))
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="a-topbar-btn a-topbar-btn--white">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        @endif
    </div>
</header>

<div class="a-layout">

    <aside class="a-sidebar" id="aSidebar">
        <div class="a-sidebar-body">

            {{-- ================= UTAMA ================= --}}
            <div class="a-nav-section">
                <span class="a-nav-label">Utama</span>

                <a href="{{ route('admin.dashboard') }}"
                class="a-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="a-nav-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <span class="a-nav-item-text">Dashboard</span>
                </a>
            </div>

            {{-- ================= NEWS (SEMUA ROLE) ================= --}}
            @if(is_admin_panel_user())
            <div class="a-nav-section">
                <span class="a-nav-label">Konten</span>

                <a href="{{ route('admin.news.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">📰</div>
                    <span class="a-nav-item-text">News</span>
                </a>
            </div>
            @endif

            {{-- ================= ADMIN ONLY ================= --}}
            @if(is_admin())

            <div class="a-nav-section">
                <span class="a-nav-label">Konten Lanjutan</span>

                <a href="{{ route('admin.pages.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">📄</div>
                    <span class="a-nav-item-text">Pages</span>
                </a>

                <a href="{{ route('admin.gcg.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.gcg.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">🛡</div>
                    <span class="a-nav-item-text">GCG</span>
                </a>

                <a href="{{ route('admin.gcg-highlight-items.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.gcg-highlight-items.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">⭐</div>
                    <span class="a-nav-item-text">GCG Highlight</span>
                </a>

                <a href="{{ route('admin.investor-relations.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.investor-relations.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">📊</div>
                    <span class="a-nav-item-text">Investor</span>
                </a>

                <a href="{{ route('admin.investor-highlight-items.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.investor-highlight-items.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">📌</div>
                    <span class="a-nav-item-text">Investor Highlight</span>
                </a>

                <a href="{{ route('admin.tjsl.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.tjsl.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">🌱</div>
                    <span class="a-nav-item-text">TJSL</span>
                </a>

                <a href="{{ route('admin.sliders.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">🎞</div>
                    <span class="a-nav-item-text">Sliders</span>
                </a>
            </div>

            <div class="a-nav-section">
                <span class="a-nav-label">Relasi</span>

                <a href="{{ route('admin.partners.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">🤝</div>
                    <span class="a-nav-item-text">Mitra</span>
                </a>
            </div>

            <div class="a-nav-section">
                <span class="a-nav-label">Konfigurasi</span>

                <a href="{{ route('admin.menus.index') }}"
                class="a-nav-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                    <div class="a-nav-icon">⚙</div>
                    <span class="a-nav-item-text">Menu</span>
                </a>
            </div>

            @endif

        </div>


    </aside>

    <main class="a-main">

        <div class="a-toast-stack" id="aToastStack">
            @if(session('success'))
                <div class="a-toast a-toast--success" data-toast data-duration="4200">
                    <div class="a-toast-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                    </div>
                    <div class="a-toast-body">
                        <div class="a-toast-title">Berhasil</div>
                        <div class="a-toast-text">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="a-toast-close" data-toast-close aria-label="Tutup notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="a-toast a-toast--error" data-toast data-duration="5600">
                    <div class="a-toast-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v5"/>
                            <path d="M12 16h.01"/>
                        </svg>
                    </div>
                    <div class="a-toast-body">
                        <div class="a-toast-title">Gagal</div>
                        <div class="a-toast-text">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="a-toast-close" data-toast-close aria-label="Tutup notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="a-toast a-toast--warning" data-toast data-duration="5200">
                    <div class="a-toast-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 2 20h20L12 3z"/>
                            <path d="M12 9v4"/>
                            <path d="M12 17h.01"/>
                        </svg>
                    </div>
                    <div class="a-toast-body">
                        <div class="a-toast-title">Perhatian</div>
                        <div class="a-toast-text">{{ session('warning') }}</div>
                    </div>
                    <button type="button" class="a-toast-close" data-toast-close aria-label="Tutup notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="a-toast a-toast--info" data-toast data-duration="5000">
                    <div class="a-toast-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 10v6"/>
                            <path d="M12 7h.01"/>
                        </svg>
                    </div>
                    <div class="a-toast-body">
                        <div class="a-toast-title">Informasi</div>
                        <div class="a-toast-text">{{ session('info') }}</div>
                    </div>
                    <button type="button" class="a-toast-close" data-toast-close aria-label="Tutup notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="a-toast a-toast--error" data-toast data-duration="6400">
                    <div class="a-toast-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 8v5"/>
                            <path d="M12 16h.01"/>
                        </svg>
                    </div>
                    <div class="a-toast-body">
                        <div class="a-toast-title">Validasi gagal</div>
                        <div class="a-toast-text">{{ $errors->first() }}</div>
                    </div>
                    <button type="button" class="a-toast-close" data-toast-close aria-label="Tutup notifikasi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

</div>

<script>
(function () {
    var sidebar = document.getElementById('aSidebar');
    var toggle  = document.getElementById('aSidebarToggle');
    var overlay = document.getElementById('aOverlay');

    function isMobile() { return window.innerWidth <= 860; }

    function openSidebar() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-open');
    }

    function closeSidebar() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-open');
    }

    function syncBurger() {
        if (toggle) toggle.style.display = isMobile() ? 'inline-flex' : 'none';
    }

    if (toggle) toggle.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
    });

    if (overlay) overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });

    window.addEventListener('resize', syncBurger);
    syncBurger();
})();

(function () {
    function hideToast(toast) {
        if (!toast || toast.classList.contains('is-hiding')) return;
        toast.classList.add('is-hiding');
        window.setTimeout(function () {
            if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
        }, 280);
    }

    document.querySelectorAll('[data-toast]').forEach(function (toast) {
        var duration = Number(toast.getAttribute('data-duration') || 5200);
        toast.style.setProperty('--toast-duration', duration + 'ms');

        var closeBtn = toast.querySelector('[data-toast-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                hideToast(toast);
            });
        }

        var timer = window.setTimeout(function () {
            hideToast(toast);
        }, duration);

        toast.addEventListener('mouseenter', function () {
            window.clearTimeout(timer);
        });

        toast.addEventListener('mouseleave', function () {
            timer = window.setTimeout(function () {
                hideToast(toast);
            }, 1800);
        });
    });
})();
</script>

</body>
</html>