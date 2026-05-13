<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Operational Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#173f08">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --op-sidebar-width: 292px;
            --op-topbar-height: 76px;

            --op-bg: #f4f7f3;
            --op-bg-soft: #f8faf7;
            --op-surface: #ffffff;
            --op-surface-soft: #f8fafc;
            --op-surface-hover: #f1f5f1;

            --op-border: #e2e8e0;
            --op-border-strong: #cfd8cb;

            --op-text: #111827;
            --op-text-soft: #64748b;
            --op-text-muted: #94a3b8;

            --op-primary: #173f08;
            --op-primary-2: #2f7d32;
            --op-primary-soft: #eef7ec;
            --op-primary-border: #cfe5ca;

            --op-accent: #d4a843;
            --op-accent-2: #b88711;
            --op-accent-soft: #fff8e6;

            --op-success-bg: #ecfdf3;
            --op-success-text: #047857;
            --op-success-border: #bbf7d0;

            --op-danger-bg: #fff1f2;
            --op-danger-text: #be123c;
            --op-danger-border: #fecdd3;

            --op-warning-bg: #fff8eb;
            --op-warning-text: #9a6700;
            --op-warning-border: #fde68a;

            --op-info-bg: #eef5ff;
            --op-info-text: #1d4ed8;
            --op-info-border: #bfdbfe;

            --op-sidebar-bg-1: #173f08;
            --op-sidebar-bg-2: #123407;
            --op-sidebar-bg-3: #0d2705;
            --op-sidebar-text: #ffffff;
            --op-sidebar-text-soft: rgba(255, 255, 255, .76);
            --op-sidebar-text-muted: rgba(255, 255, 255, .56);
            --op-sidebar-card: rgba(255, 255, 255, .075);
            --op-sidebar-card-border: rgba(255, 255, 255, .11);
            --op-sidebar-icon: rgba(255, 255, 255, .09);

            --op-topbar-bg: rgba(244, 247, 243, .86);

            --op-shadow-xs: 0 1px 2px rgba(15, 23, 42, .04);
            --op-shadow-sm: 0 8px 20px rgba(15, 23, 42, .06);
            --op-shadow: 0 16px 40px rgba(15, 23, 42, .08);
            --op-shadow-lg: 0 24px 70px rgba(15, 23, 42, .16);

            --op-radius-xs: 8px;
            --op-radius-sm: 12px;
            --op-radius-md: 16px;
            --op-radius-lg: 20px;
            --op-radius-xl: 24px;

            --op-ease: cubic-bezier(.4, 0, .2, 1);
            --op-duration: 190ms;
        }

        html[data-theme="dark"] {
            --op-bg: #0b1220;
            --op-bg-soft: #101827;
            --op-surface: #111827;
            --op-surface-soft: #0f172a;
            --op-surface-hover: #172033;

            --op-border: #243043;
            --op-border-strong: #334155;

            --op-text: #e5e7eb;
            --op-text-soft: #94a3b8;
            --op-text-muted: #64748b;

            --op-primary: #4ade80;
            --op-primary-2: #22c55e;
            --op-primary-soft: rgba(74, 222, 128, .12);
            --op-primary-border: rgba(74, 222, 128, .22);

            --op-accent: #facc15;
            --op-accent-2: #eab308;
            --op-accent-soft: rgba(250, 204, 21, .12);

            --op-success-bg: rgba(22, 101, 52, .18);
            --op-success-text: #86efac;
            --op-success-border: rgba(134, 239, 172, .28);

            --op-danger-bg: rgba(127, 29, 29, .18);
            --op-danger-text: #fca5a5;
            --op-danger-border: rgba(248, 113, 113, .28);

            --op-warning-bg: rgba(133, 77, 14, .18);
            --op-warning-text: #fcd34d;
            --op-warning-border: rgba(252, 211, 77, .28);

            --op-info-bg: rgba(29, 78, 216, .18);
            --op-info-text: #93c5fd;
            --op-info-border: rgba(147, 197, 253, .26);

            --op-sidebar-bg-1: #0f172a;
            --op-sidebar-bg-2: #111827;
            --op-sidebar-bg-3: #0b1220;
            --op-sidebar-text: #f8fafc;
            --op-sidebar-text-soft: rgba(248, 250, 252, .74);
            --op-sidebar-text-muted: rgba(248, 250, 252, .54);
            --op-sidebar-card: rgba(255, 255, 255, .045);
            --op-sidebar-card-border: rgba(255, 255, 255, .07);
            --op-sidebar-icon: rgba(255, 255, 255, .06);

            --op-topbar-bg: rgba(11, 18, 32, .86);

            --op-shadow-xs: 0 1px 2px rgba(0, 0, 0, .18);
            --op-shadow-sm: 0 8px 20px rgba(0, 0, 0, .22);
            --op-shadow: 0 16px 40px rgba(0, 0, 0, .30);
            --op-shadow-lg: 0 24px 70px rgba(0, 0, 0, .48);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--op-bg);
            color: var(--op-text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            background:
                radial-gradient(circle at 12% 0%, rgba(47, 125, 50, .12), transparent 30%),
                radial-gradient(circle at 86% 10%, rgba(212, 168, 67, .12), transparent 28%),
                linear-gradient(180deg, var(--op-bg-soft) 0%, var(--op-bg) 100%);
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(23, 63, 8, .035) 1px, transparent 1px),
                linear-gradient(180deg, rgba(23, 63, 8, .025) 1px, transparent 1px);
            background-size: 46px 46px;
            opacity: .58;
        }

        html[data-theme="dark"] body::before {
            background:
                radial-gradient(circle at 12% 0%, rgba(74, 222, 128, .10), transparent 30%),
                radial-gradient(circle at 86% 10%, rgba(250, 204, 21, .08), transparent 28%),
                linear-gradient(180deg, var(--op-bg-soft) 0%, var(--op-bg) 100%);
        }

        html[data-theme="dark"] body::after {
            opacity: .18;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        img,
        svg {
            display: block;
        }

        .op-app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--op-sidebar-width) minmax(0, 1fr);
        }

        .op-sidebar-backdrop {
            display: none;
        }

        .op-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 30;
            color: var(--op-sidebar-text);
            padding: 20px 16px;
            background:
                radial-gradient(circle at 20% 0%, rgba(212, 168, 67, .16), transparent 28%),
                linear-gradient(180deg, var(--op-sidebar-bg-1) 0%, var(--op-sidebar-bg-2) 54%, var(--op-sidebar-bg-3) 100%);
            box-shadow: 12px 0 34px rgba(15, 23, 42, .12);
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .18) transparent;
        }

        .op-sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .op-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .op-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .18);
            border-radius: 999px;
        }

        .op-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 14px;
            border-radius: var(--op-radius-lg);
            background: var(--op-sidebar-card);
            border: 1px solid var(--op-sidebar-card-border);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .08);
        }

        .op-brand-logo {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            display: grid;
            place-items: center;
            box-shadow:
                0 10px 24px rgba(0, 0, 0, .16),
                inset 0 1px 0 rgba(255, 255, 255, .4);
        }

        .op-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .op-brand-title {
            color: var(--op-sidebar-text);
            font-size: 17px;
            line-height: 1.15;
            font-weight: 900;
            letter-spacing: -.035em;
        }

        .op-brand-subtitle {
            margin-top: 4px;
            color: var(--op-sidebar-text-soft);
            font-size: 11.5px;
            line-height: 1.45;
            font-weight: 500;
        }

        .op-side-group-title {
            padding: 0 9px;
            font-size: 11px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: var(--op-sidebar-text-muted);
        }

        .op-nav {
            display: grid;
            gap: 7px;
        }

        .op-nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 54px;
            padding: 9px 12px;
            border-radius: 16px;
            color: rgba(255, 255, 255, .84);
            border: 1px solid transparent;
            transition:
                background var(--op-duration) var(--op-ease),
                border-color var(--op-duration) var(--op-ease),
                color var(--op-duration) var(--op-ease),
                transform var(--op-duration) var(--op-ease),
                box-shadow var(--op-duration) var(--op-ease);
        }

        .op-nav-link:hover {
            background: rgba(255, 255, 255, .075);
            color: #ffffff;
            border-color: rgba(255, 255, 255, .10);
            transform: translateX(2px);
        }

        .op-nav-link.is-active {
            background: linear-gradient(90deg, rgba(255, 255, 255, .14), rgba(255, 255, 255, .075));
            color: #ffffff;
            border-color: rgba(255, 255, 255, .13);
            box-shadow:
                inset 3px 0 0 var(--op-accent),
                0 10px 22px rgba(0, 0, 0, .10);
        }

        .op-nav-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 13px;
            display: grid;
            place-items: center;
            color: currentColor;
            background: var(--op-sidebar-icon);
            transition:
                background var(--op-duration) var(--op-ease),
                color var(--op-duration) var(--op-ease);
        }

        .op-nav-link.is-active .op-nav-icon {
            background: rgba(212, 168, 67, .19);
            color: #fff2c7;
        }

        .op-nav-text {
            min-width: 0;
            display: grid;
            gap: 2px;
        }

        .op-nav-title {
            display: block;
            font-size: 13.8px;
            line-height: 1.25;
            font-weight: 800;
            color: inherit;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .op-nav-subtitle {
            display: block;
            font-size: 11px;
            line-height: 1.35;
            font-weight: 500;
            color: var(--op-sidebar-text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .op-sidebar-footer {
            margin-top: auto;
            padding: 14px;
            border-radius: var(--op-radius-lg);
            background: var(--op-sidebar-card);
            border: 1px solid var(--op-sidebar-card-border);
            display: grid;
            gap: 12px;
        }

        .op-user-box {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .op-user-avatar {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 13px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--op-accent), var(--op-accent-2));
            color: #ffffff;
            font-weight: 900;
            box-shadow: 0 10px 20px rgba(212, 168, 67, .22);
        }

        .op-user-meta {
            min-width: 0;
        }

        .op-user-name {
            color: var(--op-sidebar-text);
            font-size: 13.5px;
            line-height: 1.25;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .op-user-role {
            margin-top: 2px;
            color: var(--op-sidebar-text-muted);
            font-size: 11.5px;
            line-height: 1.3;
            font-weight: 600;
        }

        .op-logout-btn {
            width: 100%;
            min-height: 42px;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 13px;
            background: rgba(255, 255, 255, .12);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 800;
            transition:
                background var(--op-duration) var(--op-ease),
                border-color var(--op-duration) var(--op-ease),
                transform var(--op-duration) var(--op-ease);
        }

        .op-logout-btn:hover {
            background: rgba(255, 255, 255, .18);
            border-color: rgba(255, 255, 255, .16);
            transform: translateY(-1px);
        }

        .op-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .op-topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            min-height: var(--op-topbar-height);
            padding: 14px 28px;
            background: var(--op-topbar-bg);
            backdrop-filter: blur(16px) saturate(1.2);
            -webkit-backdrop-filter: blur(16px) saturate(1.2);
            border-bottom: 1px solid rgba(148, 163, 184, .18);
        }

        .op-topbar-inner {
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .op-topbar-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .op-mobile-menu-btn,
        .op-theme-btn {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 14px;
            border: 1px solid var(--op-border);
            background: var(--op-surface);
            color: var(--op-text);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition:
                background var(--op-duration) var(--op-ease),
                border-color var(--op-duration) var(--op-ease),
                transform var(--op-duration) var(--op-ease),
                box-shadow var(--op-duration) var(--op-ease);
            box-shadow: var(--op-shadow-xs);
        }

        .op-mobile-menu-btn:hover,
        .op-theme-btn:hover {
            background: var(--op-surface-hover);
            border-color: var(--op-border-strong);
            transform: translateY(-1px);
            box-shadow: var(--op-shadow-sm);
        }

        .op-mobile-menu-btn {
            display: none;
        }

        .op-topbar-title {
            min-width: 0;
            display: inline-flex;
            align-items: center;
            min-height: 42px;
            padding: 0 15px;
            border-radius: 999px;
            background: var(--op-primary-soft);
            border: 1px solid var(--op-primary-border);
            color: var(--op-primary);
            font-size: 13.5px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .op-topbar-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .op-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 13px;
            border: 1px solid var(--op-border);
            background: var(--op-surface);
            color: var(--op-text);
            font-size: 13.5px;
            line-height: 1;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
            transition:
                background var(--op-duration) var(--op-ease),
                border-color var(--op-duration) var(--op-ease),
                color var(--op-duration) var(--op-ease),
                transform var(--op-duration) var(--op-ease),
                box-shadow var(--op-duration) var(--op-ease),
                filter var(--op-duration) var(--op-ease);
            box-shadow: var(--op-shadow-xs);
        }

        .op-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--op-shadow-sm);
        }

        .op-btn--primary {
            background: linear-gradient(135deg, var(--op-primary), var(--op-primary-2));
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(23, 63, 8, .16);
        }

        .op-btn--primary:hover {
            filter: brightness(1.04);
        }

        .op-btn--soft {
            background: var(--op-surface);
            color: var(--op-primary);
            border-color: var(--op-primary-border);
        }

        .op-btn--tv {
            background: linear-gradient(135deg, var(--op-accent), var(--op-accent-2));
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 12px 24px rgba(212, 168, 67, .22);
        }

        .op-btn--tv:hover {
            filter: brightness(1.04);
        }

        .op-content {
            width: 100%;
            min-width: 0;
            flex: 1;
            padding: 28px;
        }

        .op-page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .op-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: var(--op-text-soft);
            font-size: 13px;
            line-height: 1.4;
            font-weight: 600;
            flex-wrap: wrap;
        }

        .op-breadcrumb span {
            display: inline-flex;
            align-items: center;
        }

        .op-breadcrumb span:not(:last-child) {
            opacity: .86;
        }

        .op-breadcrumb-sep {
            opacity: .46;
        }

        .op-page-title {
            margin: 0;
            color: var(--op-text);
            font-size: clamp(26px, 3vw, 34px);
            line-height: 1.12;
            font-weight: 900;
            letter-spacing: -.045em;
        }

        .op-page-desc {
            margin: 10px 0 0;
            max-width: 760px;
            color: var(--op-text-soft);
            font-size: 14px;
            line-height: 1.75;
            font-weight: 500;
        }

        .op-card {
            background: rgba(255, 255, 255, .94);
            border: 1px solid var(--op-border);
            border-radius: var(--op-radius-xl);
            box-shadow: var(--op-shadow);
            overflow: hidden;
        }

        html[data-theme="dark"] .op-card {
            background: rgba(17, 24, 39, .94);
        }

        .op-card-head {
            padding: 20px 22px;
            border-bottom: 1px solid var(--op-border);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            background:
                radial-gradient(circle at top right, rgba(47, 125, 50, .055), transparent 30%),
                linear-gradient(180deg, rgba(255, 255, 255, .40), transparent);
        }

        html[data-theme="dark"] .op-card-head {
            background:
                radial-gradient(circle at top right, rgba(74, 222, 128, .07), transparent 30%),
                linear-gradient(180deg, rgba(255, 255, 255, .025), transparent);
        }

        .op-card-title {
            margin: 0;
            color: var(--op-text);
            font-size: 19px;
            line-height: 1.25;
            font-weight: 900;
            letter-spacing: -.025em;
        }

        .op-card-desc {
            margin: 6px 0 0;
            color: var(--op-text-soft);
            font-size: 13px;
            line-height: 1.7;
            font-weight: 500;
        }

        .op-card-body {
            padding: 22px;
        }

        .op-grid-2,
        .op-grid-3,
        .op-grid-4 {
            display: grid;
            gap: 18px;
        }

        .op-grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .op-grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .op-grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .op-stat {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, var(--op-surface) 0%, var(--op-surface-soft) 100%);
            border: 1px solid var(--op-border);
            border-radius: var(--op-radius-lg);
            padding: 18px;
            box-shadow: var(--op-shadow-xs);
        }

        .op-stat::after {
            content: '';
            position: absolute;
            right: -22px;
            top: -24px;
            width: 92px;
            height: 92px;
            border-radius: 999px;
            background: rgba(47, 125, 50, .08);
            pointer-events: none;
        }

        html[data-theme="dark"] .op-stat::after {
            background: rgba(74, 222, 128, .07);
        }

        .op-stat-label {
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
            color: var(--op-text-soft);
            font-size: 11px;
            line-height: 1.3;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .105em;
        }

        .op-stat-value {
            position: relative;
            z-index: 1;
            color: var(--op-text);
            font-size: 28px;
            line-height: 1.1;
            font-weight: 900;
            letter-spacing: -.04em;
            word-break: break-word;
        }

        .op-stat-sub {
            position: relative;
            z-index: 1;
            margin-top: 8px;
            color: var(--op-text-soft);
            font-size: 13px;
            line-height: 1.6;
            font-weight: 500;
        }

        .op-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 15px;
            border: 1px solid transparent;
            font-size: 14px;
            line-height: 1.6;
            font-weight: 700;
        }

        .op-alert--success {
            background: var(--op-success-bg);
            color: var(--op-success-text);
            border-color: var(--op-success-border);
        }

        .op-alert--danger {
            background: var(--op-danger-bg);
            color: var(--op-danger-text);
            border-color: var(--op-danger-border);
        }

        .op-alert--warning {
            background: var(--op-warning-bg);
            color: var(--op-warning-text);
            border-color: var(--op-warning-border);
        }

        .op-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 18px;
        }

        .op-field {
            min-width: 0;
        }

        .op-field.full {
            grid-column: 1 / -1;
        }

        .op-label {
            display: block;
            margin-bottom: 8px;
            color: var(--op-text);
            font-size: 13px;
            line-height: 1.35;
            font-weight: 900;
        }

        .op-input,
        .op-select,
        .op-textarea {
            width: 100%;
            min-height: 46px;
            border: 1px solid var(--op-border-strong);
            border-radius: 13px;
            background: var(--op-surface);
            color: var(--op-text);
            padding: 0 14px;
            outline: none;
            transition:
                background var(--op-duration) var(--op-ease),
                border-color var(--op-duration) var(--op-ease),
                box-shadow var(--op-duration) var(--op-ease);
        }

        .op-input::placeholder,
        .op-textarea::placeholder {
            color: var(--op-text-muted);
        }

        .op-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 42px;
            background-image:
                linear-gradient(45deg, transparent 50%, var(--op-text-soft) 50%),
                linear-gradient(135deg, var(--op-text-soft) 50%, transparent 50%);
            background-position:
                calc(100% - 18px) 20px,
                calc(100% - 13px) 20px;
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
        }

        .op-textarea {
            min-height: 130px;
            padding: 12px 14px;
            resize: vertical;
            line-height: 1.7;
        }

        .op-input:hover,
        .op-select:hover,
        .op-textarea:hover {
            background: var(--op-surface-hover);
            border-color: var(--op-primary-border);
        }

        .op-input:focus,
        .op-select:focus,
        .op-textarea:focus {
            border-color: var(--op-primary-2);
            box-shadow: 0 0 0 4px rgba(47, 125, 50, .12);
        }

        html[data-theme="dark"] .op-input:focus,
        html[data-theme="dark"] .op-select:focus,
        html[data-theme="dark"] .op-textarea:focus {
            box-shadow: 0 0 0 4px rgba(74, 222, 128, .12);
        }

        .op-help {
            margin-top: 7px;
            color: var(--op-text-soft);
            font-size: 12px;
            line-height: 1.6;
            font-weight: 500;
        }

        .op-table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--op-radius-lg);
            border: 1px solid var(--op-border);
            scrollbar-width: thin;
            scrollbar-color: var(--op-border-strong) transparent;
        }

        .op-table-wrap::-webkit-scrollbar {
            height: 8px;
        }

        .op-table-wrap::-webkit-scrollbar-track {
            background: transparent;
        }

        .op-table-wrap::-webkit-scrollbar-thumb {
            background: var(--op-border-strong);
            border-radius: 999px;
        }

        .op-table {
            width: 100%;
            min-width: 860px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .op-table thead th {
            background: var(--op-surface-soft);
            color: var(--op-text);
            padding: 14px 16px;
            border-bottom: 1px solid var(--op-border);
            font-size: 12px;
            line-height: 1.35;
            font-weight: 900;
            text-align: left;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .045em;
        }

        .op-table tbody td {
            background: var(--op-surface);
            color: var(--op-text);
            padding: 14px 16px;
            border-bottom: 1px solid var(--op-border);
            vertical-align: middle;
            font-size: 14px;
            line-height: 1.55;
        }

        .op-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .op-table tbody tr:hover td {
            background: var(--op-surface-hover);
        }

        .op-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 12px;
            border-radius: 999px;
            font-size: 12px;
            line-height: 1;
            font-weight: 900;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .op-badge--green {
            background: var(--op-success-bg);
            color: var(--op-success-text);
            border-color: var(--op-success-border);
        }

        .op-badge--gold {
            background: var(--op-warning-bg);
            color: var(--op-warning-text);
            border-color: var(--op-warning-border);
        }

        .op-badge--blue {
            background: var(--op-info-bg);
            color: var(--op-info-text);
            border-color: var(--op-info-border);
        }

        .op-badge--red {
            background: var(--op-danger-bg);
            color: var(--op-danger-text);
            border-color: var(--op-danger-border);
        }

        .op-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .op-btn-xs {
            min-height: 36px;
            padding: 0 12px;
            border-radius: 11px;
            font-size: 12.8px;
            font-weight: 800;
        }

        .op-btn-edit {
            background: var(--op-surface-soft);
            border-color: var(--op-border);
            color: var(--op-text);
        }

        .op-btn-danger {
            background: var(--op-danger-bg);
            border-color: var(--op-danger-border);
            color: var(--op-danger-text);
        }

        .op-empty {
            padding: 44px 20px;
            text-align: center;
            color: var(--op-text-soft);
            font-size: 14px;
            line-height: 1.7;
        }

        .op-empty-title {
            margin-bottom: 8px;
            color: var(--op-text);
            font-size: 18px;
            line-height: 1.3;
            font-weight: 900;
        }

        .op-pagination {
            width: 100%;
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 5px;
        }

        .op-pagination nav[role="navigation"],
        .op-pagination nav[aria-label*="Pagination"],
        .op-pagination nav[aria-label*="pagination"] {
            width: auto !important;
            max-width: 100%;
            display: block;
        }

        .op-pagination nav[role="navigation"] > div,
        .op-pagination nav[aria-label*="Pagination"] > div,
        .op-pagination nav[aria-label*="pagination"] > div {
            width: auto !important;
            max-width: 100%;
        }

        .op-pagination nav[role="navigation"] .hidden.sm\:flex,
        .op-pagination nav[aria-label*="Pagination"] .hidden.sm\:flex,
        .op-pagination nav[aria-label*="pagination"] .hidden.sm\:flex {
            display: flex !important;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .op-pagination nav[role="navigation"] .relative,
        .op-pagination nav[aria-label*="Pagination"] .relative,
        .op-pagination nav[aria-label*="pagination"] .relative {
            position: relative;
        }

        .op-pagination nav[role="navigation"] svg,
        .op-pagination nav[aria-label*="Pagination"] svg,
        .op-pagination nav[aria-label*="pagination"] svg {
            width: 18px !important;
            height: 18px !important;
            min-width: 18px !important;
            min-height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            display: inline-block !important;
            vertical-align: middle !important;
            flex-shrink: 0 !important;
        }

        .op-pagination nav[role="navigation"] a,
        .op-pagination nav[role="navigation"] span,
        .op-pagination nav[aria-label*="Pagination"] a,
        .op-pagination nav[aria-label*="Pagination"] span,
        .op-pagination nav[aria-label*="pagination"] a,
        .op-pagination nav[aria-label*="pagination"] span {
            min-width: 40px;
            min-height: 40px;
            padding: 0 12px;
            border-radius: 12px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box;
            white-space: nowrap;
            font-size: 14px;
            line-height: 1;
            font-weight: 800;
        }

        .op-pagination nav[role="navigation"] a,
        .op-pagination nav[aria-label*="Pagination"] a,
        .op-pagination nav[aria-label*="pagination"] a {
            background: var(--op-surface);
            color: var(--op-text);
            border: 1px solid var(--op-border);
            box-shadow: var(--op-shadow-xs);
            transition:
                transform var(--op-duration) var(--op-ease),
                border-color var(--op-duration) var(--op-ease),
                box-shadow var(--op-duration) var(--op-ease);
        }

        .op-pagination nav[role="navigation"] a:hover,
        .op-pagination nav[aria-label*="Pagination"] a:hover,
        .op-pagination nav[aria-label*="pagination"] a:hover {
            transform: translateY(-1px);
            border-color: var(--op-border-strong);
            box-shadow: var(--op-shadow-sm);
        }

        .op-pagination nav[role="navigation"] span[aria-current="page"],
        .op-pagination nav[aria-label*="Pagination"] span[aria-current="page"],
        .op-pagination nav[aria-label*="pagination"] span[aria-current="page"] {
            background: linear-gradient(135deg, var(--op-primary), var(--op-primary-2));
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 12px 22px rgba(23, 63, 8, .16);
        }

        .op-pagination nav[role="navigation"] span[aria-disabled="true"],
        .op-pagination nav[aria-label*="Pagination"] span[aria-disabled="true"],
        .op-pagination nav[aria-label*="pagination"] span[aria-disabled="true"] {
            background: var(--op-surface-soft);
            color: var(--op-text-soft);
            border: 1px solid var(--op-border);
            cursor: not-allowed;
        }

        .op-pagination nav[role="navigation"] p,
        .op-pagination nav[aria-label*="Pagination"] p,
        .op-pagination nav[aria-label*="pagination"] p {
            margin: 0;
            color: var(--op-text-soft);
            font-size: 14px;
            line-height: 1.5;
            font-weight: 600;
        }

        :focus-visible {
            outline: 3px solid rgba(47, 125, 50, .28);
            outline-offset: 3px;
            border-radius: var(--op-radius-xs);
        }

        html[data-theme="dark"] :focus-visible {
            outline-color: rgba(74, 222, 128, .26);
        }

        @media (max-width: 1280px) {
            .op-grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .op-grid-3 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1024px) {
            .op-app {
                grid-template-columns: 1fr;
            }

            .op-sidebar-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 39;
                background: rgba(2, 6, 23, .48);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition:
                    opacity var(--op-duration) var(--op-ease),
                    visibility var(--op-duration) var(--op-ease);
            }

            .op-sidebar-backdrop.is-open {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .op-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: min(var(--op-sidebar-width), 86vw);
                height: 100vh;
                z-index: 40;
                transform: translateX(-100%);
                transition: transform 230ms var(--op-ease);
                box-shadow: var(--op-shadow-lg);
            }

            .op-sidebar.is-open {
                transform: translateX(0);
            }

            .op-mobile-menu-btn {
                display: inline-flex;
            }

            .op-main {
                width: 100%;
            }

            .op-topbar {
                padding-left: 20px;
                padding-right: 20px;
            }

            .op-content {
                padding: 24px 20px;
            }
        }

        @media (max-width: 768px) {
            .op-topbar {
                padding: 12px 16px;
            }

            .op-topbar-inner {
                align-items: stretch;
                flex-direction: column;
                gap: 12px;
            }

            .op-topbar-left {
                width: 100%;
            }

            .op-topbar-title {
                flex: 1 1 auto;
                justify-content: center;
                text-align: center;
                min-width: 0;
                font-size: 13px;
            }

            .op-topbar-actions {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 10px;
            }

            .op-topbar-actions .op-btn {
                width: 100%;
            }

            .op-topbar-actions .op-theme-btn {
                width: 42px;
            }

            .op-content {
                padding: 20px 16px 28px;
            }

            .op-page-head {
                margin-bottom: 20px;
            }

            .op-page-title {
                font-size: 27px;
            }

            .op-grid-2,
            .op-grid-3,
            .op-grid-4,
            .op-form-grid {
                grid-template-columns: 1fr;
            }

            .op-card {
                border-radius: 20px;
            }

            .op-card-head,
            .op-card-body {
                padding-left: 16px;
                padding-right: 16px;
            }

            .op-card-title {
                font-size: 18px;
            }

            .op-btn {
                width: 100%;
            }

            .op-actions .op-btn,
            .op-actions form,
            .op-actions button {
                width: auto;
            }

            .op-pagination {
                justify-content: flex-start;
            }

            .op-pagination nav[role="navigation"] a,
            .op-pagination nav[role="navigation"] span,
            .op-pagination nav[aria-label*="Pagination"] a,
            .op-pagination nav[aria-label*="Pagination"] span,
            .op-pagination nav[aria-label*="pagination"] a,
            .op-pagination nav[aria-label*="pagination"] span {
                min-width: 38px;
                min-height: 38px;
                padding: 0 10px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .op-content {
                padding-left: 12px;
                padding-right: 12px;
            }

            .op-topbar {
                padding-left: 12px;
                padding-right: 12px;
            }

            .op-topbar-actions {
                grid-template-columns: 1fr 42px;
            }

            .op-page-title {
                font-size: 24px;
            }

            .op-page-desc {
                font-size: 13.5px;
            }

            .op-card-head,
            .op-card-body {
                padding: 15px;
            }

            .op-stat {
                padding: 16px;
            }

            .op-stat-value {
                font-size: 25px;
            }

            .op-table {
                min-width: 760px;
            }
        }

        @media (max-width: 380px) {
            .op-brand-title {
                font-size: 15px;
            }

            .op-brand-subtitle {
                font-size: 10.8px;
            }

            .op-sidebar {
                width: 88vw;
                padding: 18px 13px;
            }

            .op-topbar-title {
                font-size: 12px;
                padding-left: 10px;
                padding-right: 10px;
            }

            .op-mobile-menu-btn,
            .op-theme-btn {
                width: 40px;
                height: 40px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $operationalUser = auth()->user();
        $operationalName = session('user_name') ?? $operationalUser?->name ?? 'User Operational';
        $operationalInitial = strtoupper(mb_substr($operationalName, 0, 1));
    @endphp

    <div class="op-sidebar-backdrop" id="opSidebarBackdrop"></div>

    <div class="op-app">
        <aside class="op-sidebar" id="opSidebar">
            <div class="op-brand">
                <div class="op-brand-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="BSP Logo">
                </div>

                <div>
                    <div class="op-brand-title">Operational Panel</div>
                    <div class="op-brand-subtitle">BSP Zapin Operational Monitoring</div>
                </div>
            </div>

            <div class="op-side-group-title">Menu Utama</div>

            <nav class="op-nav">
                <a href="{{ route('operational.dashboard') }}" class="op-nav-link {{ request()->routeIs('operational.dashboard') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Dashboard</span>
                        <span class="op-nav-subtitle">Ringkasan data operasional</span>
                    </span>
                </a>

                <a href="{{ route('operational.flow-gas.index') }}" class="op-nav-link {{ request()->routeIs('operational.flow-gas.*') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3h18v18H3z"></path>
                            <path d="M8 15V9"></path>
                            <path d="M12 15V6"></path>
                            <path d="M16 15v-3"></path>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Data Flow Gas</span>
                        <span class="op-nav-subtitle">Flowcomp A dan B</span>
                    </span>
                </a>

                <a href="{{ route('operational.crude.index') }}" class="op-nav-link {{ request()->routeIs('operational.crude.*') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2s4 4.35 4 8a4 4 0 1 1-8 0c0-3.65 4-8 4-8z"></path>
                            <path d="M6 20h12"></path>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Data Crude Oil</span>
                        <span class="op-nav-subtitle">Produksi crude oil harian</span>
                    </span>
                </a>

                <a href="{{ route('operational.vitol.index') }}" class="op-nav-link {{ request()->routeIs('operational.vitol.*') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 17h10"></path>
                            <rect x="15" y="14" width="5" height="5" rx="1"></rect>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Data VITOL</span>
                        <span class="op-nav-subtitle">Oil trading ke BPC</span>
                    </span>
                </a>

                <a href="{{ route('operational.broadcast.index') }}" class="op-nav-link {{ request()->routeIs('operational.broadcast.*') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 5h16v10H7l-3 3V5z"></path>
                            <path d="M8 9h8"></path>
                            <path d="M8 12h5"></path>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Broadcast TV</span>
                        <span class="op-nav-subtitle">Atur running text TV</span>
                    </span>
                </a>

                <a href="{{ route('operational.display-tokens.index') }}" class="op-nav-link {{ request()->routeIs('operational.display-tokens.*') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 7h1a5 5 0 0 1 0 10h-1"></path>
                            <path d="M9 7H8a5 5 0 0 0 0 10h1"></path>
                            <path d="M8 12h8"></path>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Display Token</span>
                        <span class="op-nav-subtitle">Akses public TV aman</span>
                    </span>
                </a>
            </nav>

            <div class="op-sidebar-footer">
                <div class="op-user-box">
                    <div class="op-user-avatar">{{ $operationalInitial }}</div>
                    <div class="op-user-meta">
                        <div class="op-user-name">{{ $operationalName }}</div>
                        <div class="op-user-role">Operational</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="op-logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <main class="op-main">
            <div class="op-topbar">
                <div class="op-topbar-inner">
                    <div class="op-topbar-left">
                        <button type="button" class="op-mobile-menu-btn" id="opMobileMenuBtn" aria-label="Buka menu" aria-expanded="false">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </button>

                        <div class="op-topbar-title">Operational Monitoring System</div>
                    </div>

                    <div class="op-topbar-actions">
                        <a href="{{ route('operational.tv') }}" class="op-btn op-btn--tv" target="_blank" rel="noopener noreferrer">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="14" rx="2"></rect>
                                <path d="M8 20h8"></path>
                                <path d="M12 18v2"></path>
                            </svg>
                            TV Monitoring
                        </a>

                        <button type="button" class="op-theme-btn" id="opThemeToggle" aria-label="Toggle theme">
                            <svg id="opThemeIconSun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="5"></circle>
                                <line x1="12" y1="1" x2="12" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="23"></line>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                <line x1="1" y1="12" x2="3" y2="12"></line>
                                <line x1="21" y1="12" x2="23" y2="12"></line>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                            </svg>

                            <svg id="opThemeIconMoon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="op-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (function () {
            'use strict';

            const html = document.documentElement;
            const themeToggle = document.getElementById('opThemeToggle');
            const sunIcon = document.getElementById('opThemeIconSun');
            const moonIcon = document.getElementById('opThemeIconMoon');
            const savedTheme = localStorage.getItem('op-theme');

            function applyTheme(theme) {
                const selectedTheme = theme === 'dark' ? 'dark' : 'light';

                html.setAttribute('data-theme', selectedTheme);
                localStorage.setItem('op-theme', selectedTheme);

                if (selectedTheme === 'dark') {
                    if (sunIcon) {
                        sunIcon.style.display = 'none';
                    }

                    if (moonIcon) {
                        moonIcon.style.display = 'block';
                    }
                } else {
                    if (sunIcon) {
                        sunIcon.style.display = 'block';
                    }

                    if (moonIcon) {
                        moonIcon.style.display = 'none';
                    }
                }
            }

            applyTheme(savedTheme === 'dark' ? 'dark' : 'light');

            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    const currentTheme = html.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                    applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
                });
            }

            const sidebar = document.getElementById('opSidebar');
            const backdrop = document.getElementById('opSidebarBackdrop');
            const mobileBtn = document.getElementById('opMobileMenuBtn');

            function openSidebar() {
                if (sidebar) {
                    sidebar.classList.add('is-open');
                }

                if (backdrop) {
                    backdrop.classList.add('is-open');
                }

                if (mobileBtn) {
                    mobileBtn.setAttribute('aria-expanded', 'true');
                }

                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) {
                    sidebar.classList.remove('is-open');
                }

                if (backdrop) {
                    backdrop.classList.remove('is-open');
                }

                if (mobileBtn) {
                    mobileBtn.setAttribute('aria-expanded', 'false');
                }

                document.body.style.overflow = '';
            }

            if (mobileBtn) {
                mobileBtn.addEventListener('click', function () {
                    if (sidebar && sidebar.classList.contains('is-open')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    closeSidebar();
                }
            });

            document.querySelectorAll('.op-nav-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 1024) {
                        closeSidebar();
                    }
                });
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>