<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'WBS' }}</title>

    <!-- SEO Meta -->
    <meta name="description" content="Whistleblowing System – Laporkan pelanggaran secara aman dan terpercaya.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#2563eb">

    <!-- Professional Fonts: Syne (display) + Plus Jakarta Sans (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ─── Design Tokens ─────────────────────────────────────────── */
        :root {
            /* Sidebar */
            --sidebar-w: 268px;
            --topbar-h: 72px;

            /* Radius */
            --r-xs: 8px;
            --r-sm: 12px;
            --r-md: 16px;
            --r-lg: 20px;
            --r-xl: 24px;

            /* Transition */
            --ease: cubic-bezier(.4,0,.2,1);
            --dur: 200ms;

            /* Shadows */
            --shadow-xs: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --shadow-sm: 0 4px 12px rgba(15,23,42,.07), 0 2px 4px rgba(15,23,42,.04);
            --shadow-md: 0 10px 30px rgba(15,23,42,.10), 0 4px 8px rgba(15,23,42,.05);
            --shadow-lg: 0 20px 60px rgba(15,23,42,.15), 0 8px 24px rgba(15,23,42,.08);

            /* Brand */
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --brand-light: #eff6ff;
            --brand-border: #bfdbfe;
            --brand-glow: rgba(37,99,235,.18);

            /* Danger */
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --danger-glow: rgba(239,68,68,.15);
        }

        /* ─── Light Theme ───────────────────────────────────────────── */
        [data-theme="light"] {
            --bg:             #f0f4fa;
            --surface:        #ffffff;
            --surface-alt:    #f8fafc;
            --surface-hover:  #f1f5f9;
            --border:         #e2e8f0;
            --border-strong:  #cbd5e1;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --input-bg:       #ffffff;
            --sidebar-bg:     #ffffff;
            --topbar-bg:      rgba(255,255,255,.85);
            --notif-bg:       #ffffff;
            --notif-unread:   #eff6ff;
            --notif-shadow:   0 20px 60px rgba(15,23,42,.18);
            --success-bg:     #ecfdf5;
            --success-border: #a7f3d0;
            --success-text:   #065f46;
            --danger-alert-bg: #fef2f2;
            --danger-alert-border: #fecaca;
            --danger-alert-text:   #991b1b;
            --icon-btn-bg:    #f8fafc;
            --logo-filter:    none;
        }

        /* ─── Dark Theme ────────────────────────────────────────────── */
        [data-theme="dark"] {
            --bg:             #0b0f1a;
            --surface:        #111827;
            --surface-alt:    #161d2e;
            --surface-hover:  #1e2a3d;
            --border:         #1e2a3d;
            --border-strong:  #2d3d56;
            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted:     #64748b;
            --input-bg:       #161d2e;
            --sidebar-bg:     #111827;
            --topbar-bg:      rgba(17,24,39,.88);
            --notif-bg:       #111827;
            --notif-unread:   #1e2f4a;
            --notif-shadow:   0 20px 60px rgba(0,0,0,.5);
            --success-bg:     #052e16;
            --success-border: #166534;
            --success-text:   #4ade80;
            --danger-alert-bg: #1f0707;
            --danger-alert-border: #7f1d1d;
            --danger-alert-text:   #fca5a5;
            --icon-btn-bg:    #161d2e;
            --logo-filter:    brightness(0) invert(1);
            --shadow-xs: 0 1px 3px rgba(0,0,0,.25);
            --shadow-sm: 0 4px 12px rgba(0,0,0,.3);
            --shadow-md: 0 10px 30px rgba(0,0,0,.4);
            --shadow-lg: 0 20px 60px rgba(0,0,0,.55);
            --brand-light: #172554;
            --brand-border: #1e40af;
            --brand-glow: rgba(37,99,235,.25);
        }

        /* ─── Reset & Base ──────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            line-height: 1.6;
            transition: background var(--dur) var(--ease), color var(--dur) var(--ease);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        img { max-width: 100%; display: block; }

        /* ─── Layout ────────────────────────────────────────────────── */
        .wbs-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ─── Sidebar ───────────────────────────────────────────────── */
        .wbs-sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 50;
            transition: transform var(--dur) var(--ease), background var(--dur) var(--ease), border-color var(--dur) var(--ease);
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Sidebar overlay (mobile) */
        .wbs-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 45;
            background: rgba(0,0,0,.5);
            backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity var(--dur) var(--ease);
        }

        .wbs-sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Brand area */
        .wbs-sidebar-brand {
            padding: 22px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .wbs-brand-logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: var(--r-sm);
            filter: var(--logo-filter);
            flex-shrink: 0;
        }

        /* Fallback icon if img fails */
        .wbs-brand-icon-fallback {
            width: 40px;
            height: 40px;
            border-radius: var(--r-sm);
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 16px var(--brand-glow);
        }

        .wbs-brand-icon-fallback svg { width: 20px; height: 20px; }

        .wbs-brand-text h1 {
            margin: 0;
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--text-primary);
        }

        .wbs-brand-text p {
            margin: 3px 0 0;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Nav sections */
        .wbs-sidebar-section {
            padding: 20px 12px 0;
            flex: 1;
        }

        .wbs-sidebar-section-title {
            padding: 0 10px 10px;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .wbs-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .wbs-menu-item {
            display: flex;
            align-items: center;
            gap: 11px;
            border-radius: var(--r-md);
            padding: 12px 13px;
            color: var(--text-secondary);
            transition: background var(--dur) var(--ease), color var(--dur) var(--ease), box-shadow var(--dur) var(--ease);
            font-weight: 600;
            font-size: 14px;
            position: relative;
            cursor: pointer;
        }

        .wbs-menu-item:hover {
            background: var(--surface-hover);
            color: var(--text-primary);
        }

        .wbs-menu-item.active {
            background: var(--brand-light);
            color: var(--brand);
            box-shadow: inset 0 0 0 1.5px var(--brand-border);
        }

        .wbs-menu-item svg {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            opacity: .85;
        }

        .wbs-menu-item.active svg { opacity: 1; }

        /* Pulse dot for unread */
        .wbs-menu-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--danger);
            margin-left: auto;
            flex-shrink: 0;
            position: relative;
        }

        .wbs-menu-dot::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: var(--danger);
            opacity: .25;
            animation: pulse-ring 2s ease-out infinite;
        }

        @keyframes pulse-ring {
            0%   { transform: scale(.85); opacity: .25; }
            60%  { transform: scale(1.7); opacity: 0; }
            100% { transform: scale(1.7); opacity: 0; }
        }

        /* User panel */
        .wbs-sidebar-user {
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding: 16px 14px;
            display: flex;
            align-items: center;
            gap: 11px;
            flex-shrink: 0;
        }

        .wbs-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--r-sm);
            background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            flex-shrink: 0;
            letter-spacing: -.01em;
        }

        .wbs-user-meta { min-width: 0; }

        .wbs-user-meta strong {
            display: block;
            font-size: 14px;
            line-height: 1.2;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--text-primary);
        }

        .wbs-user-meta span {
            display: block;
            margin-top: 2px;
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ─── Main ──────────────────────────────────────────────────── */
        .wbs-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            margin-left: var(--sidebar-w);
            transition: margin-left var(--dur) var(--ease);
        }

        /* ─── Topbar ────────────────────────────────────────────────── */
        .wbs-topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            height: var(--topbar-h);
            background: var(--topbar-bg);
            backdrop-filter: blur(14px) saturate(1.5);
            -webkit-backdrop-filter: blur(14px) saturate(1.5);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            gap: 12px;
            transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease);
        }

        .wbs-topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .wbs-topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Hamburger */
        .wbs-menu-toggle {
            width: 40px;
            height: 40px;
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: var(--icon-btn-bg);
            color: var(--text-secondary);
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease);
        }

        .wbs-menu-toggle:hover { background: var(--surface-hover); }
        .wbs-menu-toggle svg { width: 18px; height: 18px; }

        /* Breadcrumb */
        .wbs-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            color: var(--text-muted);
            font-size: 13.5px;
            font-weight: 500;
        }

        .wbs-breadcrumb strong {
            color: var(--text-primary);
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-family: 'Syne', sans-serif;
        }

        .wbs-breadcrumb .sep {
            color: var(--text-muted);
            opacity: .5;
        }

        /* Icon buttons */
        .wbs-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: var(--icon-btn-bg);
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background var(--dur) var(--ease), color var(--dur) var(--ease), border-color var(--dur) var(--ease);
            position: relative;
        }

        .wbs-icon-btn:hover {
            background: var(--surface-hover);
            color: var(--text-primary);
        }

        .wbs-icon-btn svg { width: 17px; height: 17px; }

        /* ─── Notification Badge ─────────────────────────────────────── */
        .wbs-notif-wrap { position: relative; }

        /* Dot on bell (sidebar menu) */
        .wbs-notif-badge {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--danger);
            border: 2px solid var(--surface);
            pointer-events: none;
        }

        .wbs-notif-badge::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--danger);
            opacity: 0;
            animation: notif-pulse 2.4s ease-out infinite;
        }

        @keyframes notif-pulse {
            0%   { transform: scale(.6); opacity: .5; }
            70%  { transform: scale(2); opacity: 0; }
            100% { transform: scale(2); opacity: 0; }
        }

        /* Count pill */
        .wbs-notif-count {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 99px;
            background: var(--danger);
            border: 2px solid var(--surface);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 9.5px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: .01em;
            pointer-events: none;
            transition: transform .15s var(--ease);
        }

        .wbs-icon-btn:hover .wbs-notif-count {
            transform: scale(1.12);
        }

        /* ─── Notification Dropdown ─────────────────────────────────── */
        .wbs-notif-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 380px;
            max-width: calc(100vw - 24px);
            background: var(--notif-bg);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            box-shadow: var(--notif-shadow);
            overflow: hidden;
            z-index: 100;
            /* Animation */
            opacity: 0;
            transform: translateY(-8px) scale(.97);
            transform-origin: top right;
            pointer-events: none;
            transition:
                opacity 200ms var(--ease),
                transform 200ms var(--ease);
        }

        .wbs-notif-dropdown.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .wbs-notif-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            background: var(--notif-bg);
        }

        .wbs-notif-head-left { display: flex; align-items: baseline; gap: 8px; }

        .wbs-notif-head strong {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .wbs-notif-head-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 99px;
            background: var(--danger);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
        }

        .wbs-notif-head form button {
            border: 0;
            background: transparent;
            color: var(--brand);
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
            transition: opacity var(--dur) var(--ease);
        }

        .wbs-notif-head form button:hover { opacity: .7; }

        .wbs-notif-list {
            max-height: 380px;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .wbs-notif-item {
            display: block;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-primary);
            background: var(--notif-bg);
            position: relative;
            transition: background var(--dur) var(--ease);
        }

        .wbs-notif-item:last-child { border-bottom: none; }

        .wbs-notif-item:hover { background: var(--surface-hover); }

        .wbs-notif-item.unread { background: var(--notif-unread); }

        .wbs-notif-item.unread:hover { filter: brightness(.97); }

        /* Unread indicator */
        .wbs-notif-item.unread::after {
            content: '';
            position: absolute;
            top: 18px;
            right: 18px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--danger);
            box-shadow: 0 0 0 3px var(--danger-glow);
        }

        .wbs-notif-title {
            font-size: 13.5px;
            font-weight: 700;
            margin-bottom: 4px;
            padding-right: 20px;
            color: var(--text-primary);
            line-height: 1.35;
        }

        .wbs-notif-message {
            color: var(--text-secondary);
            font-size: 12.5px;
            line-height: 1.6;
            padding-right: 20px;
        }

        .wbs-notif-time {
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 11.5px;
            font-weight: 600;
        }

        .wbs-notif-empty {
            padding: 32px 18px;
            color: var(--text-muted);
            font-size: 13.5px;
            text-align: center;
            line-height: 1.6;
        }

        .wbs-notif-empty svg {
            width: 36px;
            height: 36px;
            margin: 0 auto 10px;
            opacity: .35;
        }

        /* ─── Buttons ───────────────────────────────────────────────── */
        .wbs-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 40px;
            padding: 0 16px;
            border-radius: var(--r-sm);
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 700;
            transition: all var(--dur) var(--ease);
            white-space: nowrap;
            font-family: inherit;
        }

        .wbs-btn svg { width: 15px; height: 15px; }

        .wbs-btn-light {
            background: var(--icon-btn-bg);
            border-color: var(--border);
            color: var(--text-secondary);
        }

        .wbs-btn-light:hover {
            background: var(--surface-hover);
            color: var(--text-primary);
        }

        .wbs-btn-primary {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            box-shadow: 0 4px 14px var(--brand-glow);
        }

        .wbs-btn-primary:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
            box-shadow: 0 6px 20px var(--brand-glow);
        }

        .wbs-btn-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }

        .wbs-btn-danger:hover {
            background: var(--danger-dark);
            border-color: var(--danger-dark);
        }

        /* ─── Theme Toggle ──────────────────────────────────────────── */
        #wbsThemeToggle .icon-moon { display: none; }
        #wbsThemeToggle .icon-sun  { display: block; }

        [data-theme="dark"] #wbsThemeToggle .icon-moon { display: block; }
        [data-theme="dark"] #wbsThemeToggle .icon-sun  { display: none; }

        /* ─── Content Area ──────────────────────────────────────────── */
        .wbs-content {
            padding: 28px 28px 40px;
            flex: 1;
        }

        .wbs-page-title {
            margin: 0 0 20px;
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            line-height: 1.15;
            letter-spacing: -.025em;
            font-weight: 800;
            color: var(--text-primary);
        }

        /* ─── Grid ──────────────────────────────────────────────────── */
        .wbs-grid   { display: grid; gap: 20px; }
        .wbs-grid-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .wbs-grid-4 { grid-template-columns: repeat(4, minmax(0,1fr)); }

        /* ─── Cards & Stats ─────────────────────────────────────────── */
        .wbs-stat,
        .wbs-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-xs);
            transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease), box-shadow var(--dur) var(--ease);
        }

        .wbs-card:hover, .wbs-stat:hover {
            box-shadow: var(--shadow-sm);
        }

        .wbs-stat { padding: 22px; }

        .wbs-stat-label {
            color: var(--text-muted);
            font-size: 12.5px;
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .wbs-stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--text-primary);
        }

        .wbs-card { padding: 24px; }

        .wbs-card-title {
            margin: 0 0 14px;
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -.02em;
        }

        .wbs-card-subtitle {
            margin: 0;
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 14px;
        }

        .wbs-custom-select {
            position: relative;
        }

        .wbs-custom-select select {
            position: absolute;
            inset: 0;
            opacity: 0;
            pointer-events: none;
        }

        .wbs-select-display {
            width: 100%;
            min-height: 44px;
            padding: 11px 42px 11px 14px;
            border: 1.5px solid var(--border-strong);
            border-radius: var(--r-sm);
            background: var(--input-bg);
            color: var(--text-primary);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .wbs-custom-select.open .wbs-select-display {
            border-color: var(--brand);
            box-shadow: 0 0 0 3.5px var(--brand-glow);
        }

        .wbs-select-options {
            position: absolute;
            z-index: 200;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            padding: 6px;
            display: none;
            max-height: 260px;
            overflow-y: auto;
        }

        .wbs-custom-select.open .wbs-select-options {
            display: block;
        }

        .wbs-select-option {
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 14px;
            cursor: pointer;
        }

        .wbs-select-option:hover {
            background: var(--surface-hover);
        }

        .wbs-select-option.selected {
            background: var(--brand);
            color: #ffffff;
        }

        .wbs-custom-select.open .wbs-select-chevron {
            color: var(--brand);
            transform: translateY(-50%) rotate(180deg);
        }

        /* ─── Toolbar ───────────────────────────────────────────────── */
        .wbs-toolbar {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .wbs-toolbar-left,
        .wbs-toolbar-right {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        /* ─── Form Controls ─────────────────────────────────────────── */
        .wbs-field { min-width: 180px; }

        .wbs-field label,
        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-secondary);
            letter-spacing: .01em;
        }

        .wbs-input,
        .wbs-textarea,
        .input,
        .textarea {
            width: 100%;
            border: 1.5px solid var(--border-strong);
            border-radius: var(--r-sm);
            padding: 11px 14px;
            font-size: 14px;
            background: var(--input-bg);
            color: var(--text-primary);
            outline: none;
            transition: border-color var(--dur) var(--ease), box-shadow var(--dur) var(--ease);
            font-family: inherit;
        }

        .wbs-input::placeholder,
        .input::placeholder { color: var(--text-muted); }

        .wbs-input:hover,
        .input:hover {
            border-color: var(--border-strong);
            background: var(--surface-hover);
        }

        .wbs-input:focus,
        .wbs-textarea:focus,
        .input:focus,
        .textarea:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3.5px var(--brand-glow);
        }

        .wbs-textarea, .textarea {
            min-height: 130px;
            resize: vertical;
        }

        .form-group { margin-bottom: 16px; }

        /* ─── Custom Select ─────────────────────────────────────────── */
        .wbs-custom-select {
            position: relative;
            width: 100%;
        }

        .wbs-custom-select select,
        .wbs-select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: var(--input-bg);
            border: 1.5px solid var(--border-strong);
            border-radius: var(--r-sm);
            padding: 11px 42px 11px 14px;
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
            outline: none;
            transition: border-color var(--dur) var(--ease), box-shadow var(--dur) var(--ease), background var(--dur) var(--ease);
            font-family: inherit;
            line-height: 1.5;
        }

        .wbs-custom-select select:hover,
        .wbs-select:hover {
            border-color: var(--brand);
            background: var(--surface-hover);
        }

        .wbs-custom-select select:focus,
        .wbs-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3.5px var(--brand-glow);
        }

        .wbs-custom-select select option,
        .wbs-select option {
            background: var(--surface);
            color: var(--text-primary);
            padding: 8px 12px;
            font-size: 14px;
        }

        /* Chevron icon */
        .wbs-select-chevron {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color var(--dur) var(--ease), transform var(--dur) var(--ease);
        }

        .wbs-custom-select select:focus ~ .wbs-select-chevron,
        .wbs-custom-select:focus-within .wbs-select-chevron {
            color: var(--brand);
            transform: translateY(-50%) rotate(180deg);
        }

        .wbs-select-chevron svg {
            width: 16px;
            height: 16px;
            stroke-width: 2.2;
        }

        /* Standalone .wbs-select (without wrapper) — fallback, keeps original style */
        .wbs-select {
            padding-right: 42px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 13px center;
        }

        /* ─── Table ─────────────────────────────────────────────────── */
        .wbs-table-wrap {
            overflow-x: auto;
            border-radius: var(--r-lg);
        }

        .wbs-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .wbs-table th,
        .wbs-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
            font-size: 13.5px;
            color: var(--text-primary);
        }

        .wbs-table th {
            background: var(--surface-alt);
            color: var(--text-muted);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .wbs-table tbody tr {
            transition: background var(--dur) var(--ease);
        }

        .wbs-table tbody tr:hover { background: var(--surface-hover); }

        /* ─── Badge ─────────────────────────────────────────────────── */
        .wbs-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 26px;
            padding: 0 11px;
            border-radius: 99px;
            background: var(--brand-light);
            color: var(--brand);
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
            border: 1px solid var(--brand-border);
        }

        /* ─── Alerts ────────────────────────────────────────────────── */
        .wbs-alert-success,
        .wbs-alert-danger {
            padding: 14px 18px;
            border-radius: var(--r-md);
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 500;
            line-height: 1.6;
        }

        .wbs-alert-success {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        .wbs-alert-danger {
            background: var(--danger-alert-bg);
            border: 1px solid var(--danger-alert-border);
            color: var(--danger-alert-text);
        }

        /* ─── Meta & Attachment ─────────────────────────────────────── */
        .wbs-meta-grid { display: grid; gap: 12px; }

        .wbs-meta-item {
            padding: 13px 15px;
            border: 1px solid var(--border);
            border-radius: var(--r-md);
            background: var(--surface-alt);
            transition: background var(--dur) var(--ease);
        }

        .wbs-meta-item-label {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-bottom: 5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .wbs-meta-item-value {
            color: var(--text-primary);
            line-height: 1.75;
            word-break: break-word;
            font-size: 14px;
        }

        .wbs-attachment-list { display: grid; gap: 10px; }

        .wbs-attachment-item {
            padding: 13px 15px;
            border: 1px solid var(--border);
            border-radius: var(--r-md);
            background: var(--surface);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            transition: background var(--dur) var(--ease), border-color var(--dur) var(--ease);
        }

        .wbs-attachment-title {
            font-weight: 700;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .wbs-attachment-meta {
            color: var(--text-muted);
            font-size: 12.5px;
        }

        /* ─── Pagination & Empty ────────────────────────────────────── */
        .wbs-pagination { margin-top: 18px; }

        .wbs-empty {
            color: var(--text-muted);
            line-height: 1.8;
            padding: 6px 2px;
            font-size: 14px;
        }

        form { margin: 0; }

        /* ─── Responsive ────────────────────────────────────────────── */
        @media (max-width: 1280px) {
            .wbs-grid-4 { grid-template-columns: repeat(2, minmax(0,1fr)); }
        }

        @media (max-width: 1024px) {
            :root { --sidebar-w: 0px; }

            .wbs-sidebar {
                transform: translateX(-268px);
                width: 268px;
            }

            .wbs-sidebar.open {
                transform: translateX(0);
                box-shadow: var(--shadow-lg);
            }

            .wbs-main { margin-left: 0; }

            .wbs-menu-toggle { display: inline-flex; }
        }

        @media (max-width: 768px) {
            .wbs-content { padding: 18px 16px 32px; }

            .wbs-grid-2,
            .wbs-grid-4 { grid-template-columns: 1fr; }

            .wbs-topbar { padding: 0 16px; }

            .wbs-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .wbs-toolbar-left,
            .wbs-toolbar-right { width: 100%; }

            .wbs-field { min-width: 100%; }

            .wbs-page-title { font-size: 22px; }

            .wbs-btn span { display: none; }
            .wbs-btn { padding: 0 12px; }
            .wbs-btn svg { width: 17px; height: 17px; }

            /* Keep "Website" and "Logout" labels on mobile for clarity */
            .wbs-btn-primary span,
            .wbs-btn-danger span,
            .wbs-btn-home span { display: inline; }

            .wbs-notif-dropdown {
                right: -8px;
                width: calc(100vw - 24px);
                max-width: 380px;
            }
        }

        @media (max-width: 480px) {
            .wbs-topbar-right { gap: 6px; }

            .wbs-btn-home { display: none; }

            .wbs-content { padding: 14px 12px 28px; }
        }

        /* ─── Scrollbar styling (webkit) ────────────────────────────── */
        .wbs-sidebar::-webkit-scrollbar,
        .wbs-notif-list::-webkit-scrollbar { width: 4px; }

        .wbs-sidebar::-webkit-scrollbar-track,
        .wbs-notif-list::-webkit-scrollbar-track { background: transparent; }

        .wbs-sidebar::-webkit-scrollbar-thumb,
        .wbs-notif-list::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        /* ─── Focus-visible ─────────────────────────────────────────── */
        :focus-visible {
            outline: 2.5px solid var(--brand);
            outline-offset: 2px;
            border-radius: var(--r-xs);
        }
    </style>
</head>
<body>
    @php
        $authUser = auth()->user();
        $displayName = $authUser->name ?? session('user_name') ?? '-';
        $displayEmail = $authUser->email ?? '-';
        $role = $authUser->role ?? session('user_role');
        $initials = strtoupper(substr($displayName, 0, 1));
        $currentRoute = request()->route() ? request()->route()->getName() : '';
        $isAdminWbs = in_array($role, ['wbs_admin', 'wbs_officer'], true);
        $isPelapor = $role === 'pelapor';

        $wbsUnreadNotifications = auth()->check()
            ? \App\Models\WbsNotification::query()
                ->where('user_id', auth()->id())
                ->whereNull('read_at')
                ->count()
            : 0;

        $wbsNotifications = auth()->check()
            ? \App\Models\WbsNotification::query()
                ->where('user_id', auth()->id())
                ->latest('id')
                ->take(8)
                ->get()
            : collect();
    @endphp

    <!-- Sidebar overlay (mobile) -->
    <div class="wbs-sidebar-overlay" id="wbsSidebarOverlay"></div>

    <div class="wbs-layout">
        <!-- ── Sidebar ───────────────────────────────────────────── -->
        <aside class="wbs-sidebar" id="wbsSidebar">
            <div class="wbs-sidebar-brand">
                {{-- Logo from public --}}
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="WBS Logo"
                    class="wbs-brand-logo-img"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                >
                {{-- Fallback SVG icon --}}
                <div class="wbs-brand-icon-fallback">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M12 3l7 3v5c0 5-3.5 8.5-7 10-3.5-1.5-7-5-7-10V6l7-3Z"></path>
                    </svg>
                </div>

                <div class="wbs-brand-text">
                    <h1>WBS System</h1>
                    <p>Whistleblowing System</p>
                </div>
            </div>

            <div class="wbs-sidebar-section">
                <div class="wbs-sidebar-section-title">Menu Utama</div>

                <nav class="wbs-menu">
                    @if($isPelapor)
                        <a href="{{ route('wbs.pelapor.dashboard') }}"
                           class="wbs-menu-item {{ $currentRoute === 'wbs.pelapor.dashboard' ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('wbs.pelapor.reports.index') }}"
                           class="wbs-menu-item {{ str_starts_with($currentRoute, 'wbs.pelapor.reports') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                                <path d="M14 3v5h5"></path>
                            </svg>
                            <span>Laporan Saya</span>
                            @if($wbsUnreadNotifications > 0)
                                <span class="wbs-menu-dot" aria-hidden="true"></span>
                            @endif
                        </a>

                        <a href="{{ route('wbs.pelapor.reports.create') }}"
                           class="wbs-menu-item {{ $currentRoute === 'wbs.pelapor.reports.create' ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                            <span>Buat Laporan</span>
                        </a>
                    @endif

                    @if($isAdminWbs)
                        <a href="{{ route('wbs.admin.dashboard') }}"
                           class="wbs-menu-item {{ $currentRoute === 'wbs.admin.dashboard' ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('wbs.admin.reports.index') }}"
                           class="wbs-menu-item {{ str_starts_with($currentRoute, 'wbs.admin.reports') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"></path>
                                <path d="M14 3v5h5"></path>
                            </svg>
                            <span>Monitoring Laporan</span>
                            @if($wbsUnreadNotifications > 0)
                                <span class="wbs-menu-dot" aria-hidden="true"></span>
                            @endif
                        </a>
                    @endif
                </nav>
            </div>

            <div class="wbs-sidebar-user">
                <div class="wbs-user-avatar" aria-hidden="true">{{ $initials }}</div>
                <div class="wbs-user-meta">
                    <strong title="{{ $displayName }}">{{ $displayName }}</strong>
                    <span>{{ $isAdminWbs ? 'Admin WBS' : 'Pelapor' }}</span>
                </div>
            </div>
        </aside>

        <!-- ── Main ─────────────────────────────────────────────── -->
        <main class="wbs-main">
            <header class="wbs-topbar">
                <div class="wbs-topbar-left">
                    <!-- Hamburger (mobile only) -->
                    <button type="button" class="wbs-menu-toggle" id="wbsMenuToggle" aria-label="Buka menu" aria-expanded="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M4 7h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 17h16"></path>
                        </svg>
                    </button>

                    <div class="wbs-breadcrumb" aria-label="Breadcrumb">
                        <span>WBS</span>
                        <span class="sep" aria-hidden="true">›</span>
                        <strong>{{ $pageTitle ?? 'Dashboard' }}</strong>
                    </div>
                </div>

                <div class="wbs-topbar-right">
                    <!-- Notification Bell -->
                    <div class="wbs-notif-wrap">
                        <button type="button" class="wbs-icon-btn" id="wbsNotifButton"
                                aria-label="Notifikasi{{ $wbsUnreadNotifications > 0 ? ' (' . $wbsUnreadNotifications . ' belum dibaca)' : '' }}"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                                <path d="M10 17a2 2 0 0 0 4 0"></path>
                            </svg>

                            @if($wbsUnreadNotifications > 0)
                                <span class="wbs-notif-badge" aria-hidden="true"></span>
                                <span class="wbs-notif-count" aria-hidden="true">
                                    {{ $wbsUnreadNotifications > 9 ? '9+' : $wbsUnreadNotifications }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div class="wbs-notif-dropdown" id="wbsNotifDropdown" role="dialog" aria-label="Panel Notifikasi">
                            <div class="wbs-notif-head">
                                <div class="wbs-notif-head-left">
                                    <strong>Notifikasi</strong>
                                    @if($wbsUnreadNotifications > 0)
                                        <span class="wbs-notif-head-count" aria-label="{{ $wbsUnreadNotifications }} belum dibaca">
                                            {{ $wbsUnreadNotifications }}
                                        </span>
                                    @endif
                                </div>

                                @if($wbsUnreadNotifications > 0)
                                    <form action="{{ route('wbs.notifications.mark-all-read') }}" method="POST">
                                        @csrf
                                        <button type="submit">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>

                            <div class="wbs-notif-list">
                                @forelse($wbsNotifications as $notification)
                                    <a href="{{ route('wbs.notifications.open', $notification->id) }}"
                                       class="wbs-notif-item {{ $notification->read_at ? '' : 'unread' }}"
                                       aria-label="{{ $notification->title }}{{ $notification->read_at ? '' : ' (belum dibaca)' }}">
                                        <div class="wbs-notif-title">{{ $notification->title }}</div>
                                        <div class="wbs-notif-message">{{ $notification->message }}</div>
                                        <div class="wbs-notif-time">{{ $notification->created_at->diffForHumans() }}</div>
                                    </a>
                                @empty
                                    <div class="wbs-notif-empty">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"></path>
                                            <path d="M10 17a2 2 0 0 0 4 0"></path>
                                        </svg>
                                        Belum ada notifikasi
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button type="button" class="wbs-icon-btn" id="wbsThemeToggle" aria-label="Ganti tema">
                        <!-- Sun (shown in dark mode) -->
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <circle cx="12" cy="12" r="4.5"></circle>
                            <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                        </svg>
                        <!-- Moon (shown in light mode) -->
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>
                        </svg>
                    </button>

                    <!-- Website Link -->
                    <a href="{{ route('web.home', ['locale' => 'id']) }}" class="wbs-btn wbs-btn-light wbs-btn-home" aria-label="Kembali ke Website">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path d="M3 10.5 12 3l9 7.5"></path>
                            <path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"></path>
                        </svg>
                        <span>Website</span>
                    </a>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="wbs-btn wbs-btn-danger" aria-label="Logout">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <path d="M10 17l5-5-5-5"></path>
                                <path d="M15 12H3"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <div class="wbs-content">
                @if(session('success'))
                    <div class="wbs-alert-success" role="alert">{{ session('success') }}</div>
                @endif

                @if(session('pdf_url'))
                    <div class="wbs-alert-success" role="alert">
                        PDF berhasil dibuat.
                        <a href="{{ session('pdf_url') }}" target="_blank" rel="noopener noreferrer" style="font-weight: 800; margin-left: 6px;">Buka PDF ↗</a>
                    </div>
                @endif

                @if($errors->any())
                    <div class="wbs-alert-danger" role="alert">
                        <div style="font-weight: 800; margin-bottom: 6px;">Terjadi kesalahan:</div>
                        <ul style="margin: 0; padding-left: 18px;">
                            @foreach($errors->all() as $error)
                                <li style="margin-bottom: 4px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
    (function () {
        'use strict';

        /* ── Theme ─────────────────────────────────────────────── */
        const html = document.documentElement;
        const THEME_KEY = 'wbs-theme';

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            localStorage.setItem(THEME_KEY, theme);
        }

        // Restore saved theme before paint
        const savedTheme = localStorage.getItem(THEME_KEY);
        if (savedTheme === 'dark' || savedTheme === 'light') {
            applyTheme(savedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            applyTheme('dark');
        }

        document.addEventListener('DOMContentLoaded', function () {

            /* ── Theme toggle ──────────────────────────────────── */
            const themeBtn = document.getElementById('wbsThemeToggle');
            if (themeBtn) {
                themeBtn.addEventListener('click', function () {
                    const current = html.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            }

            /* ── Sidebar (mobile) ──────────────────────────────── */
            const sidebar  = document.getElementById('wbsSidebar');
            const overlay  = document.getElementById('wbsSidebarOverlay');
            const menuBtn  = document.getElementById('wbsMenuToggle');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('show');
                menuBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                menuBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }

            if (menuBtn) {
                menuBtn.addEventListener('click', function () {
                    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
                });
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                    closeNotif();
                }
            });

            /* ── Notification dropdown ─────────────────────────── */
            const notifBtn      = document.getElementById('wbsNotifButton');
            const notifDropdown = document.getElementById('wbsNotifDropdown');

            function openNotif() {
                notifDropdown.classList.add('show');
                notifBtn.setAttribute('aria-expanded', 'true');
            }

            function closeNotif() {
                notifDropdown.classList.remove('show');
                notifBtn.setAttribute('aria-expanded', 'false');
            }

            function toggleNotif() {
                notifDropdown.classList.contains('show') ? closeNotif() : openNotif();
            }

            if (notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    toggleNotif();
                });

                notifDropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });

                document.addEventListener('click', closeNotif);
            }

        });
    })();

        document.querySelectorAll('.wbs-custom-select').forEach(function (wrapper) {
        const select = wrapper.querySelector('select');
        const chevron = wrapper.querySelector('.wbs-select-chevron');

        if (!select || wrapper.querySelector('.wbs-select-display')) return;

        const display = document.createElement('div');
        display.className = 'wbs-select-display';
        display.textContent = select.options[select.selectedIndex]?.text || '';

        const optionsBox = document.createElement('div');
        optionsBox.className = 'wbs-select-options';

        Array.from(select.options).forEach(function (option) {
            const item = document.createElement('div');
            item.className = 'wbs-select-option';
            item.textContent = option.textContent;
            item.dataset.value = option.value;

            if (option.selected) {
                item.classList.add('selected');
            }

            item.addEventListener('click', function () {
                select.value = option.value;
                display.textContent = option.textContent;

                optionsBox.querySelectorAll('.wbs-select-option').forEach(function (el) {
                    el.classList.remove('selected');
                });

                item.classList.add('selected');
                wrapper.classList.remove('open');

                select.dispatchEvent(new Event('change', { bubbles: true }));
            });

            optionsBox.appendChild(item);
        });

        wrapper.insertBefore(display, select);

        if (chevron) {
            wrapper.appendChild(chevron);
        }

        wrapper.appendChild(optionsBox);

        display.addEventListener('click', function (e) {
            e.stopPropagation();

            document.querySelectorAll('.wbs-custom-select.open').forEach(function (el) {
                if (el !== wrapper) el.classList.remove('open');
            });

            wrapper.classList.toggle('open');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.wbs-custom-select.open').forEach(function (wrapper) {
            wrapper.classList.remove('open');
        });
    });
    </script>
</body>
</html>