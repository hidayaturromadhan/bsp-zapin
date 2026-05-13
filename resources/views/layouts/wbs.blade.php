<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'WBS' }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <meta name="description" content="Whistleblowing System – Laporkan pelanggaran secara aman dan terpercaya.">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#2563eb">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 268px;
            --topbar-h: 72px;

            --r-xs: 8px;
            --r-sm: 12px;
            --r-md: 16px;
            --r-lg: 20px;
            --r-xl: 24px;

            --ease: cubic-bezier(.4,0,.2,1);
            --dur: 200ms;

            --shadow-xs: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --shadow-sm: 0 4px 12px rgba(15,23,42,.07), 0 2px 4px rgba(15,23,42,.04);
            --shadow-md: 0 10px 30px rgba(15,23,42,.10), 0 4px 8px rgba(15,23,42,.05);
            --shadow-lg: 0 20px 60px rgba(15,23,42,.15), 0 8px 24px rgba(15,23,42,.08);

            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --brand-light: #eff6ff;
            --brand-border: #bfdbfe;
            --brand-glow: rgba(37,99,235,.18);

            --danger: #ef4444;
            --danger-dark: #dc2626;
            --danger-glow: rgba(239,68,68,.15);
        }

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

            /* FIX: logo PNG jangan di-invert */
            --logo-filter:    none;

            --shadow-xs: 0 1px 3px rgba(0,0,0,.25);
            --shadow-sm: 0 4px 12px rgba(0,0,0,.3);
            --shadow-md: 0 10px 30px rgba(0,0,0,.4);
            --shadow-lg: 0 20px 60px rgba(0,0,0,.55);
            --brand-light: #172554;
            --brand-border: #1e40af;
            --brand-glow: rgba(37,99,235,.25);
        }

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

        .wbs-layout {
            display: flex;
            min-height: 100vh;
        }

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

        .wbs-sidebar-brand {
            padding: 22px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        [data-theme="dark"] .wbs-sidebar-brand {
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .12), transparent 34%),
                var(--sidebar-bg);
        }

        .wbs-brand-logo-img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 14px;
            filter: var(--logo-filter);
            flex-shrink: 0;
            padding: 5px;
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, .22);
            box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
        }

        [data-theme="dark"] .wbs-brand-logo-img {
            background: #ffffff;
            border-color: rgba(255, 255, 255, .12);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .22);
        }

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

        .wbs-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            margin-left: var(--sidebar-w);
            transition: margin-left var(--dur) var(--ease);
        }

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

        .wbs-notif-wrap { position: relative; }

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

        #wbsThemeToggle .icon-moon { display: none; }
        #wbsThemeToggle .icon-sun  { display: block; }

        [data-theme="dark"] #wbsThemeToggle .icon-moon { display: block; }
        [data-theme="dark"] #wbsThemeToggle .icon-sun  { display: none; }

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

        .wbs-grid   { display: grid; gap: 20px; }
        .wbs-grid-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .wbs-grid-4 { grid-template-columns: repeat(4, minmax(0,1fr)); }

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
            width: 100%;
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

        .wbs-select {
            padding-right: 42px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 13px center;
        }

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

        .wbs-alert-success,
        .wbs-alert-danger {
            display: none;
        }

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

        .wbs-pagination { margin-top: 18px; }

        .wbs-empty {
            color: var(--text-muted);
            line-height: 1.8;
            padding: 6px 2px;
            font-size: 14px;
        }

        form { margin: 0; }

        .swal2-popup.swal2-wbs-popup {
            width: min(92vw, 520px) !important;
            border-radius: 24px !important;
            padding: 30px 30px 28px !important;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif !important;
            box-shadow: 0 30px 90px rgba(15, 23, 42, .26) !important;
        }

        .swal2-title.swal2-wbs-title {
            font-size: 24px !important;
            font-weight: 900 !important;
            color: #0f172a !important;
            letter-spacing: -.04em !important;
            line-height: 1.2 !important;
            padding: 0 !important;
        }

        .swal2-html-container.swal2-wbs-html {
            margin: 12px 0 0 !important;
            font-size: 15px !important;
            color: #64748b !important;
            line-height: 1.7 !important;
        }

        .swal2-actions {
            gap: 10px !important;
            margin-top: 26px !important;
        }

        .swal2-styled {
            box-shadow: none !important;
        }

        .swal2-confirm.swal2-wbs-confirm,
        .swal2-cancel.swal2-wbs-cancel {
            min-height: 44px !important;
            padding: 0 20px !important;
            border-radius: 14px !important;
            font-size: 14px !important;
            font-weight: 900 !important;
            line-height: 1 !important;
            border: 1px solid transparent !important;
            transition: transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease, box-shadow .16s ease !important;
        }

        .swal2-confirm.swal2-wbs-confirm:hover,
        .swal2-cancel.swal2-wbs-cancel:hover {
            transform: translateY(-1px) !important;
        }

        .swal2-confirm.swal2-wbs-confirm--primary {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            box-shadow: 0 12px 24px rgba(37, 99, 235, .22) !important;
        }

        .swal2-confirm.swal2-wbs-confirm--primary:hover {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }

        .swal2-confirm.swal2-wbs-confirm--success {
            background: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
            box-shadow: 0 12px 24px rgba(22, 163, 74, .22) !important;
        }

        .swal2-confirm.swal2-wbs-confirm--success:hover {
            background: #15803d !important;
            border-color: #15803d !important;
        }

        .swal2-confirm.swal2-wbs-confirm--danger {
            background: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #ffffff !important;
            box-shadow: 0 12px 24px rgba(220, 38, 38, .22) !important;
        }

        .swal2-confirm.swal2-wbs-confirm--danger:hover {
            background: #b91c1c !important;
            border-color: #b91c1c !important;
        }

        .swal2-confirm.swal2-wbs-confirm--warning {
            background: #d97706 !important;
            border-color: #d97706 !important;
            color: #ffffff !important;
            box-shadow: 0 12px 24px rgba(217, 119, 6, .22) !important;
        }

        .swal2-confirm.swal2-wbs-confirm--warning:hover {
            background: #b45309 !important;
            border-color: #b45309 !important;
        }

        .swal2-cancel.swal2-wbs-cancel {
            background: #ffffff !important;
            color: #334155 !important;
            border-color: #dbe3ea !important;
        }

        .swal2-cancel.swal2-wbs-cancel:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }

        .swal2-icon.swal2-error {
            border-color: #ef4444 !important;
            color: #ef4444 !important;
        }

        .swal2-icon.swal2-success {
            border-color: #22c55e !important;
            color: #22c55e !important;
        }

        .swal2-icon.swal2-question {
            border-color: #2563eb !important;
            color: #2563eb !important;
        }

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

            .wbs-btn-primary span,
            .wbs-btn-danger span,
            .wbs-btn-home span { display: inline; }

            .wbs-notif-dropdown {
                right: -8px;
                width: calc(100vw - 24px);
                max-width: 380px;
            }

            .swal2-popup.swal2-wbs-popup {
                padding: 26px 20px 24px !important;
                border-radius: 20px !important;
            }

            .swal2-title.swal2-wbs-title {
                font-size: 21px !important;
            }

            .swal2-html-container.swal2-wbs-html {
                font-size: 14px !important;
            }

            .swal2-actions {
                width: 100% !important;
            }

            .swal2-confirm.swal2-wbs-confirm,
            .swal2-cancel.swal2-wbs-cancel {
                flex: 1 1 auto !important;
                min-width: 120px !important;
            }
        }

        @media (max-width: 480px) {
            .wbs-topbar-right { gap: 6px; }

            .wbs-btn-home { display: none; }

            .wbs-content { padding: 14px 12px 28px; }
        }

        .wbs-sidebar::-webkit-scrollbar,
        .wbs-notif-list::-webkit-scrollbar { width: 4px; }

        .wbs-sidebar::-webkit-scrollbar-track,
        .wbs-notif-list::-webkit-scrollbar-track { background: transparent; }

        .wbs-sidebar::-webkit-scrollbar-thumb,
        .wbs-notif-list::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        :focus-visible {
            outline: 2.5px solid var(--brand);
            outline-offset: 2px;
            border-radius: var(--r-xs);
        }

        @media (max-width: 1180px) {
        .wbs-content {
            padding: 24px 22px 36px;
        }

        .wbs-grid-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wbs-grid-2 {
            grid-template-columns: 1fr;
        }

        .wbs-toolbar {
            align-items: stretch;
        }

        .wbs-toolbar-left,
        .wbs-toolbar-right {
            width: 100%;
        }

        .wbs-toolbar-right {
            justify-content: flex-start;
        }
    }

    @media (max-width: 1024px) {
        :root {
            --sidebar-w: 0px;
        }

        .wbs-sidebar {
            width: 286px;
            transform: translateX(-286px);
            box-shadow: none;
        }

        .wbs-sidebar.open {
            transform: translateX(0);
            box-shadow: var(--shadow-lg);
        }

        .wbs-main {
            margin-left: 0;
            width: 100%;
        }

        .wbs-menu-toggle {
            display: inline-flex;
        }

        .wbs-topbar {
            padding: 0 18px;
        }

        .wbs-content {
            padding: 22px 18px 34px;
        }
    }

    @media (max-width: 768px) {
        body {
            overflow-x: hidden;
        }

        .wbs-layout {
            width: 100%;
            min-width: 0;
        }

        .wbs-main {
            min-width: 0;
            width: 100%;
        }

        .wbs-topbar {
            height: 64px;
            padding: 0 14px;
            gap: 10px;
        }

        .wbs-topbar-left {
            min-width: 0;
            flex: 1;
        }

        .wbs-topbar-right {
            gap: 6px;
            flex-shrink: 0;
        }

        .wbs-breadcrumb {
            min-width: 0;
            gap: 6px;
            font-size: 12.5px;
        }

        .wbs-breadcrumb span:first-child,
        .wbs-breadcrumb .sep {
            display: none;
        }

        .wbs-breadcrumb strong {
            max-width: 150px;
            font-size: 13px;
        }

        .wbs-menu-toggle,
        .wbs-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 12px;
        }

        .wbs-btn {
            min-height: 38px;
            padding: 0 12px;
            border-radius: 12px;
            font-size: 13px;
        }

        .wbs-btn svg {
            width: 16px;
            height: 16px;
        }

        .wbs-btn-home {
            display: none;
        }

        .wbs-topbar-right > form .wbs-btn {
            width: 38px;
            height: 38px;
            min-height: 38px;
            padding: 0;
        }

        .wbs-topbar-right > form .wbs-btn span {
            display: none;
        }

        .wbs-content {
            padding: 18px 14px 30px;
        }

        .wbs-page-title {
            font-size: 22px;
            line-height: 1.25;
            margin-bottom: 16px;
        }

        .wbs-card,
        .wbs-stat {
            border-radius: 20px;
            padding: 18px;
        }

        .wbs-card-title {
            font-size: 18px;
            line-height: 1.3;
        }

        .wbs-card-subtitle {
            font-size: 13.5px;
        }

        .wbs-grid,
        .wbs-grid-2,
        .wbs-grid-4 {
            grid-template-columns: 1fr !important;
            gap: 16px;
        }

        .wbs-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .wbs-toolbar-left,
        .wbs-toolbar-right {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .wbs-toolbar .wbs-btn,
        .wbs-toolbar-left .wbs-btn,
        .wbs-toolbar-right .wbs-btn {
            width: 100%;
        }

        .wbs-field {
            min-width: 100%;
            width: 100%;
        }

        .wbs-input,
        .wbs-textarea,
        .input,
        .textarea,
        .wbs-select,
        .wbs-select-display {
            min-height: 46px;
            font-size: 14px;
            border-radius: 14px;
        }

        .wbs-textarea,
        .textarea {
            min-height: 120px;
        }

        .wbs-custom-select {
            width: 100%;
        }

        .wbs-select-options {
            max-height: 230px;
            border-radius: 16px;
            z-index: 999;
        }

        .wbs-select-option {
            min-height: 40px;
            display: flex;
            align-items: center;
            font-size: 13.5px;
        }

        .wbs-table-wrap {
            border-radius: 18px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .wbs-table {
            min-width: 760px;
        }

        .wbs-table th,
        .wbs-table td {
            padding: 12px 14px;
            font-size: 12.8px;
        }

        .wbs-table th {
            font-size: 10.8px;
            white-space: nowrap;
        }

        .wbs-badge {
            min-height: 24px;
            padding: 0 9px;
            font-size: 10.8px;
        }

        .wbs-meta-grid {
            grid-template-columns: 1fr;
        }

        .wbs-meta-item {
            padding: 12px;
            border-radius: 14px;
        }

        .wbs-attachment-item {
            align-items: stretch;
            flex-direction: column;
        }

        .wbs-attachment-item .wbs-btn {
            width: 100%;
        }

        .wbs-pagination {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            text-align: center;
        }

        .wbs-pagination-links {
            justify-content: center;
        }

        .wbs-notif-dropdown {
            position: fixed;
            top: 72px;
            left: 12px;
            right: 12px;
            width: auto;
            max-width: none;
            border-radius: 20px;
        }

        .wbs-notif-list {
            max-height: 360px;
        }

        .wbs-notif-head {
            padding: 14px 16px;
        }

        .wbs-notif-item {
            padding: 13px 16px;
        }

        .wbs-notif-title {
            font-size: 13px;
        }

        .wbs-notif-message {
            font-size: 12.3px;
        }

        .wbs-sidebar {
            width: 286px;
            transform: translateX(-286px);
        }

        .wbs-sidebar-brand {
            padding: 18px 16px;
        }

        .wbs-brand-logo-img {
            width: 42px;
            height: 42px;
            border-radius: 13px;
        }

        .wbs-brand-text h1 {
            font-size: 15px;
        }

        .wbs-brand-text p {
            font-size: 11.5px;
        }

        .wbs-sidebar-section {
            padding: 16px 10px 0;
        }

        .wbs-menu-item {
            padding: 12px;
            border-radius: 15px;
            font-size: 13.5px;
        }

        .wbs-sidebar-user {
            padding: 14px 12px;
        }

        .swal2-popup.swal2-wbs-popup {
            width: calc(100vw - 28px) !important;
            padding: 24px 18px 22px !important;
            border-radius: 20px !important;
        }

        .swal2-title.swal2-wbs-title {
            font-size: 20px !important;
            line-height: 1.25 !important;
        }

        .swal2-html-container.swal2-wbs-html {
            font-size: 14px !important;
            line-height: 1.65 !important;
        }

        .swal2-actions {
            width: 100% !important;
            gap: 8px !important;
        }

        .swal2-confirm.swal2-wbs-confirm,
        .swal2-cancel.swal2-wbs-cancel {
            min-height: 42px !important;
            flex: 1 1 0 !important;
            padding: 0 14px !important;
            font-size: 13px !important;
            border-radius: 13px !important;
        }
    }

    @media (max-width: 560px) {
        .wbs-topbar {
            height: 60px;
            padding: 0 10px;
        }

        .wbs-content {
            padding: 14px 10px 26px;
        }

        .wbs-breadcrumb strong {
            max-width: 118px;
        }

        .wbs-menu-toggle,
        .wbs-icon-btn {
            width: 36px;
            height: 36px;
        }

        .wbs-topbar-right > form .wbs-btn {
            width: 36px;
            height: 36px;
            min-height: 36px;
        }

        .wbs-notif-count {
            top: -5px;
            right: -5px;
            min-width: 17px;
            height: 17px;
            font-size: 9px;
        }

        .wbs-page-title {
            font-size: 20px;
        }

        .wbs-card,
        .wbs-stat {
            padding: 16px;
            border-radius: 18px;
        }

        .wbs-stat-value {
            font-size: 28px;
        }

        .wbs-card-title {
            font-size: 17px;
        }

        .wbs-input,
        .wbs-textarea,
        .input,
        .textarea,
        .wbs-select,
        .wbs-select-display {
            min-height: 44px;
            padding: 10px 12px;
            font-size: 13.5px;
        }

        .wbs-select-display {
            padding-right: 40px;
        }

        .wbs-table {
            min-width: 720px;
        }

        .wbs-table th,
        .wbs-table td {
            padding: 11px 12px;
        }

        .wbs-btn {
            width: auto;
            min-height: 38px;
            font-size: 12.8px;
        }

        .wbs-notif-dropdown {
            top: 66px;
            left: 8px;
            right: 8px;
            border-radius: 18px;
        }

        .wbs-notif-list {
            max-height: 330px;
        }

        .wbs-sidebar {
            width: 280px;
            transform: translateX(-280px);
        }

        .wbs-sidebar.open {
            transform: translateX(0);
        }
    }

    @media (max-width: 420px) {
        .wbs-content {
            padding-left: 8px;
            padding-right: 8px;
        }

        .wbs-breadcrumb strong {
            max-width: 96px;
        }

        .wbs-card,
        .wbs-stat {
            padding: 14px;
        }

        .wbs-table {
            min-width: 680px;
        }

        .wbs-notif-dropdown {
            left: 6px;
            right: 6px;
        }

        .swal2-confirm.swal2-wbs-confirm,
        .swal2-cancel.swal2-wbs-cancel {
            flex: 1 1 100% !important;
            width: 100% !important;
        }
    }

    @media (max-width: 360px) {
        .wbs-breadcrumb strong {
            max-width: 78px;
        }

        .wbs-menu-toggle,
        .wbs-icon-btn {
            width: 34px;
            height: 34px;
        }

        .wbs-topbar-right > form .wbs-btn {
            width: 34px;
            height: 34px;
            min-height: 34px;
        }

        .wbs-card,
        .wbs-stat {
            padding: 12px;
            border-radius: 16px;
        }

        .wbs-sidebar {
            width: 264px;
            transform: translateX(-264px);
        }

        .wbs-sidebar.open {
            transform: translateX(0);
        }
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

    <div class="wbs-sidebar-overlay" id="wbsSidebarOverlay"></div>

    <div class="wbs-layout">
        <aside class="wbs-sidebar" id="wbsSidebar">
            <div class="wbs-sidebar-brand">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="WBS Logo"
                    class="wbs-brand-logo-img"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                >

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

        <main class="wbs-main">
            <header class="wbs-topbar">
                <div class="wbs-topbar-left">
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

                    <button type="button" class="wbs-icon-btn" id="wbsThemeToggle" aria-label="Ganti tema">
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <circle cx="12" cy="12" r="4.5"></circle>
                            <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"></path>
                        </svg>

                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>
                        </svg>
                    </button>

                    <a href="{{ route('web.home', ['locale' => 'id']) }}" class="wbs-btn wbs-btn-light wbs-btn-home" aria-label="Kembali ke Website">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path d="M3 10.5 12 3l9 7.5"></path>
                            <path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"></path>
                        </svg>
                        <span>Website</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="js-wbs-logout-form">
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
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    (function () {
        'use strict';

        const html = document.documentElement;
        const THEME_KEY = 'wbs-theme';

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            localStorage.setItem(THEME_KEY, theme);
        }

        const savedTheme = localStorage.getItem(THEME_KEY);
        if (savedTheme === 'dark' || savedTheme === 'light') {
            applyTheme(savedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            applyTheme('dark');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const WbsSwal = Swal.mixin({
                customClass: {
                    popup: 'swal2-wbs-popup',
                    title: 'swal2-wbs-title',
                    htmlContainer: 'swal2-wbs-html',
                    confirmButton: 'swal2-wbs-confirm swal2-wbs-confirm--primary',
                    cancelButton: 'swal2-wbs-cancel'
                },
                buttonsStyling: false,
                confirmButtonText: 'OK',
                cancelButtonText: 'Batal'
            });

            window.WbsSwal = WbsSwal;

            function submitFormDirectly(form) {
                form.dataset.confirmed = '1';
                HTMLFormElement.prototype.submit.call(form);
            }

            @if(session('success'))
                WbsSwal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    timer: 2400,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'swal2-wbs-popup',
                        title: 'swal2-wbs-title',
                        htmlContainer: 'swal2-wbs-html',
                        confirmButton: 'swal2-wbs-confirm swal2-wbs-confirm--success',
                        cancelButton: 'swal2-wbs-cancel'
                    }
                });
            @endif

            @if(session('pdf_url'))
                WbsSwal.fire({
                    icon: 'success',
                    title: 'PDF berhasil dibuat',
                    html: 'Dokumen PDF berhasil dibuat.<br><br><a href="' + @json(session('pdf_url')) + '" target="_blank" rel="noopener noreferrer" style="font-weight:900;color:#2563eb;text-decoration:none;">Buka PDF ↗</a>',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'swal2-wbs-popup',
                        title: 'swal2-wbs-title',
                        htmlContainer: 'swal2-wbs-html',
                        confirmButton: 'swal2-wbs-confirm swal2-wbs-confirm--success',
                        cancelButton: 'swal2-wbs-cancel'
                    }
                });
            @endif

            @if($errors->any())
                const wbsErrors = @json($errors->all());
                const wbsErrorHtml = '<div style="text-align:left;">' +
                    '<div style="font-weight:800;margin-bottom:8px;color:#0f172a;">Terjadi kesalahan:</div>' +
                    '<ul style="margin:0;padding-left:18px;">' +
                    wbsErrors.map(function (error) {
                        return '<li style="margin-bottom:5px;">' + String(error).replace(/[&<>"']/g, function (char) {
                            return {
                                '&': '&amp;',
                                '<': '&lt;',
                                '>': '&gt;',
                                '"': '&quot;',
                                "'": '&#039;'
                            }[char];
                        }) + '</li>';
                    }).join('') +
                    '</ul>' +
                    '</div>';

                WbsSwal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: wbsErrorHtml,
                    confirmButtonText: 'Mengerti',
                    customClass: {
                        popup: 'swal2-wbs-popup',
                        title: 'swal2-wbs-title',
                        htmlContainer: 'swal2-wbs-html',
                        confirmButton: 'swal2-wbs-confirm swal2-wbs-confirm--danger',
                        cancelButton: 'swal2-wbs-cancel'
                    }
                });
            @endif

            const themeBtn = document.getElementById('wbsThemeToggle');
            if (themeBtn) {
                themeBtn.addEventListener('click', function () {
                    const current = html.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            }

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

            const notifBtn      = document.getElementById('wbsNotifButton');
            const notifDropdown = document.getElementById('wbsNotifDropdown');

            function openNotif() {
                notifDropdown.classList.add('show');
                notifBtn.setAttribute('aria-expanded', 'true');
            }

            function closeNotif() {
                if (!notifDropdown || !notifBtn) {
                    return;
                }

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

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                    closeNotif();
                }
            });

            document.querySelectorAll('.js-wbs-logout-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.confirmed === '1') {
                        return;
                    }

                    event.preventDefault();

                    WbsSwal.fire({
                        icon: 'question',
                        title: 'Logout dari WBS?',
                        text: 'Anda akan keluar dari sesi saat ini.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'swal2-wbs-popup',
                            title: 'swal2-wbs-title',
                            htmlContainer: 'swal2-wbs-html',
                            confirmButton: 'swal2-wbs-confirm swal2-wbs-confirm--danger',
                            cancelButton: 'swal2-wbs-cancel'
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            submitFormDirectly(form);
                        }
                    });
                });
            });

            document.querySelectorAll('.js-wbs-confirm-submit').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.confirmed === '1') {
                        return;
                    }

                    event.preventDefault();

                    const title = form.getAttribute('data-title') || 'Lanjutkan proses?';
                    const text = form.getAttribute('data-text') || 'Pastikan data yang Anda masukkan sudah benar.';
                    const confirmText = form.getAttribute('data-confirm') || 'Ya, Lanjutkan';
                    const type = form.getAttribute('data-type') || 'primary';

                    const confirmClassMap = {
                        primary: 'swal2-wbs-confirm swal2-wbs-confirm--primary',
                        success: 'swal2-wbs-confirm swal2-wbs-confirm--success',
                        danger: 'swal2-wbs-confirm swal2-wbs-confirm--danger',
                        delete: 'swal2-wbs-confirm swal2-wbs-confirm--danger',
                        warning: 'swal2-wbs-confirm swal2-wbs-confirm--warning'
                    };

                    WbsSwal.fire({
                        icon: type === 'danger' || type === 'delete' ? 'warning' : 'question',
                        title: title,
                        text: text,
                        showCancelButton: true,
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'swal2-wbs-popup',
                            title: 'swal2-wbs-title',
                            htmlContainer: 'swal2-wbs-html',
                            confirmButton: confirmClassMap[type] || confirmClassMap.primary,
                            cancelButton: 'swal2-wbs-cancel'
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            submitFormDirectly(form);
                        }
                    });
                });
            });
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