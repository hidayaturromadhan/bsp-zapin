<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Operational Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --op-bg: #f5f7fb;
            --op-surface: #ffffff;
            --op-surface-soft: #f8fafc;
            --op-border: #e5e7eb;
            --op-border-strong: #d1d5db;
            --op-text: #111827;
            --op-text-soft: #6b7280;
            --op-primary: #173f08;
            --op-primary-2: #2f7d32;
            --op-accent: #d4a843;
            --op-success-bg: #eef8ee;
            --op-success-text: #17603a;
            --op-danger-bg: #fff1f2;
            --op-danger-text: #be123c;
            --op-warning-bg: #fff8eb;
            --op-warning-text: #9a6700;
            --op-sidebar-bg-1: rgba(20, 64, 8, .98);
            --op-sidebar-bg-2: rgba(19, 63, 8, .98);
            --op-sidebar-bg-3: rgba(14, 49, 6, .98);
            --op-sidebar-text: #ffffff;
            --op-sidebar-text-soft: rgba(255,255,255,.72);
            --op-sidebar-text-muted: rgba(255,255,255,.56);
            --op-sidebar-card: rgba(255,255,255,.06);
            --op-sidebar-card-border: rgba(255,255,255,.08);
            --op-sidebar-icon: rgba(255,255,255,.08);
            --op-topbar-bg: rgba(245,247,251,.88);
            --op-shadow: 0 16px 36px rgba(15, 23, 42, .08);
            --op-shadow-soft: 0 10px 24px rgba(15, 23, 42, .05);
            --op-radius-xl: 24px;
            --op-radius-lg: 18px;
            --op-radius-md: 14px;
        }

        html[data-theme="dark"] {
            --op-bg: #0b1220;
            --op-surface: #111827;
            --op-surface-soft: #0f172a;
            --op-border: #243043;
            --op-border-strong: #334155;
            --op-text: #e5e7eb;
            --op-text-soft: #94a3b8;
            --op-primary: #3d8f2b;
            --op-primary-2: #173f08;
            --op-accent: #e7bf5f;
            --op-success-bg: rgba(22, 101, 52, .18);
            --op-success-text: #86efac;
            --op-danger-bg: rgba(127, 29, 29, .18);
            --op-danger-text: #fca5a5;
            --op-warning-bg: rgba(133, 77, 14, .18);
            --op-warning-text: #fcd34d;
            --op-sidebar-bg-1: #0f172a;
            --op-sidebar-bg-2: #111827;
            --op-sidebar-bg-3: #0b1220;
            --op-sidebar-text: #f8fafc;
            --op-sidebar-text-soft: rgba(248,250,252,.72);
            --op-sidebar-text-muted: rgba(248,250,252,.56);
            --op-sidebar-card: rgba(255,255,255,.04);
            --op-sidebar-card-border: rgba(255,255,255,.06);
            --op-sidebar-icon: rgba(255,255,255,.06);
            --op-topbar-bg: rgba(11,18,32,.88);
            --op-shadow: 0 16px 36px rgba(0, 0, 0, .28);
            --op-shadow-soft: 0 10px 24px rgba(0, 0, 0, .22);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: var(--op-bg);
            color: var(--op-text);
        }

        body {
            min-height: 100vh;
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

        .op-app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            background: var(--op-bg);
        }

        .op-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            background:
                linear-gradient(180deg, var(--op-sidebar-bg-1) 0%, var(--op-sidebar-bg-2) 55%, var(--op-sidebar-bg-3) 100%);
            color: var(--op-sidebar-text);
            padding: 22px 18px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            box-shadow: 10px 0 30px rgba(0, 0, 0, .08);
            z-index: 20;
        }

        .op-sidebar-backdrop {
            display: none;
        }

        .op-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            border-radius: 18px;
            background: var(--op-sidebar-card);
            border: 1px solid var(--op-sidebar-card-border);
        }

        .op-brand-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            overflow: hidden;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.95);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.3);
            flex-shrink: 0;
        }

        .op-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            padding: 6px;
        }

        .op-brand-title {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
            color: var(--op-sidebar-text);
        }

        .op-brand-subtitle {
            font-size: 12px;
            color: var(--op-sidebar-text-soft);
            margin-top: 3px;
            line-height: 1.5;
        }

        .op-side-group-title {
            padding: 0 8px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            color: var(--op-sidebar-text-muted);
            text-transform: uppercase;
        }

        .op-nav {
            display: grid;
            gap: 8px;
        }

        .op-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 50px;
            padding: 0 14px;
            border-radius: 14px;
            color: rgba(255,255,255,.82);
            border: 1px solid transparent;
            transition: .18s ease;
        }

        .op-nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
            border-color: rgba(255,255,255,.08);
            transform: translateX(2px);
        }

        .op-nav-link.is-active {
            background: linear-gradient(90deg, rgba(255,255,255,.12), rgba(255,255,255,.07));
            color: #fff;
            border-color: rgba(255,255,255,.10);
            box-shadow: inset 3px 0 0 var(--op-accent);
        }

        .op-nav-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: var(--op-sidebar-icon);
        }

        .op-nav-link.is-active .op-nav-icon {
            background: rgba(212,168,67,.18);
            color: #fff2c7;
        }

        .op-nav-text {
            display: grid;
            gap: 2px;
            min-width: 0;
        }

        .op-nav-title {
            font-size: 14px;
            font-weight: 700;
            color: inherit;
        }

        .op-nav-subtitle {
            font-size: 11px;
            color: var(--op-sidebar-text-muted);
            line-height: 1.4;
        }

        .op-sidebar-footer {
            margin-top: auto;
            padding: 14px;
            border-radius: 18px;
            background: var(--op-sidebar-card);
            border: 1px solid var(--op-sidebar-card-border);
        }

        .op-user-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--op-sidebar-text);
        }

        .op-user-role {
            margin-top: 4px;
            font-size: 12px;
            color: var(--op-sidebar-text-soft);
        }

        .op-logout-btn {
            width: 100%;
            margin-top: 12px;
            min-height: 42px;
            border: 0;
            border-radius: 12px;
            background: rgba(255,255,255,.12);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
        }

        .op-logout-btn:hover {
            background: rgba(255,255,255,.18);
        }

        .op-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .op-topbar {
            position: sticky;
            top: 0;
            z-index: 15;
            background: var(--op-topbar-bg);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(229,231,235,.14);
            padding: 18px 28px;
        }

        .op-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .op-topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .op-mobile-menu-btn,
        .op-theme-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--op-border);
            background: var(--op-surface);
            color: var(--op-text);
            cursor: pointer;
            transition: .18s ease;
            box-shadow: var(--op-shadow-soft);
            flex-shrink: 0;
        }

        .op-mobile-menu-btn:hover,
        .op-theme-btn:hover {
            transform: translateY(-1px);
        }

        .op-mobile-menu-btn {
            display: none;
        }

        .op-topbar-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--op-primary);
            padding: 10px 14px;
            border-radius: 999px;
            background: #eef5eb;
            border: 1px solid #dbe9dc;
        }

        html[data-theme="dark"] .op-topbar-title {
            background: rgba(61, 143, 43, .15);
            border-color: rgba(61, 143, 43, .28);
            color: #bbf7d0;
        }

        .op-topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .op-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 12px;
            border: 1px solid var(--op-border);
            background: var(--op-surface);
            color: var(--op-text);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .18s ease;
        }

        .op-btn:hover {
            transform: translateY(-1px);
        }

        .op-btn--primary {
            background: linear-gradient(135deg, var(--op-primary), var(--op-primary-2));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 12px 22px rgba(23,63,8,.16);
        }

        .op-btn--soft {
            background: var(--op-surface);
            color: var(--op-primary);
            border-color: #dbe9dc;
        }

        html[data-theme="dark"] .op-btn--soft {
            border-color: var(--op-border);
            color: #bbf7d0;
        }

        .op-btn--tv {
            background: linear-gradient(135deg, #d4a843, #b88711);
            border-color: transparent;
            color: #ffffff;
            box-shadow: 0 12px 22px rgba(212,168,67,.22);
        }

        .op-btn--tv:hover {
            transform: translateY(-1px);
            filter: brightness(1.03);
        }

        .op-content {
            padding: 28px;
            min-width: 0;
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
            font-size: 13px;
            color: var(--op-text-soft);
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .op-breadcrumb-sep {
            opacity: .5;
        }

        .op-page-title {
            margin: 0;
            font-size: 34px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -.03em;
            color: var(--op-text);
        }

        .op-page-desc {
            margin: 10px 0 0;
            max-width: 760px;
            font-size: 14px;
            line-height: 1.75;
            color: var(--op-text-soft);
        }

        .op-card {
            background: var(--op-surface);
            border: 1px solid var(--op-border);
            border-radius: var(--op-radius-xl);
            box-shadow: var(--op-shadow);
        }

        .op-card-head {
            padding: 20px 22px;
            border-bottom: 1px solid var(--op-border);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .op-card-title {
            margin: 0;
            font-size: 19px;
            font-weight: 800;
            color: var(--op-text);
        }

        .op-card-desc {
            margin: 6px 0 0;
            font-size: 13px;
            line-height: 1.7;
            color: var(--op-text-soft);
        }

        .op-card-body {
            padding: 22px;
        }

        .op-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .op-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .op-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .op-stat {
            background: linear-gradient(180deg, var(--op-surface) 0%, var(--op-surface-soft) 100%);
            border: 1px solid var(--op-border);
            border-radius: 18px;
            padding: 18px;
        }

        .op-stat-label {
            font-size: 11px;
            font-weight: 800;
            color: var(--op-text-soft);
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 10px;
        }

        .op-stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--op-text);
            line-height: 1.1;
            letter-spacing: -.03em;
            word-break: break-word;
        }

        .op-stat-sub {
            margin-top: 8px;
            font-size: 13px;
            color: var(--op-text-soft);
            line-height: 1.6;
        }

        .op-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            border: 1px solid transparent;
            font-size: 14px;
            line-height: 1.6;
            font-weight: 600;
        }

        .op-alert--success {
            background: var(--op-success-bg);
            color: var(--op-success-text);
            border-color: #cfe9d3;
        }

        .op-alert--danger {
            background: #fff4f4;
            color: #b42318;
            border-color: #f3c6c6;
        }

        html[data-theme="dark"] .op-alert--danger {
            background: rgba(127, 29, 29, .18);
            color: #fca5a5;
            border-color: rgba(248, 113, 113, .26);
        }

        .op-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 18px;
        }

        .op-field.full {
            grid-column: 1 / -1;
        }

        .op-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 800;
            color: var(--op-text);
        }

        .op-input,
        .op-select,
        .op-textarea {
            width: 100%;
            min-height: 46px;
            border: 1px solid var(--op-border-strong);
            border-radius: 12px;
            background: var(--op-surface);
            color: var(--op-text);
            padding: 0 14px;
            transition: .18s ease;
        }

        .op-textarea {
            min-height: 130px;
            padding: 12px 14px;
            resize: vertical;
        }

        .op-input:focus,
        .op-select:focus,
        .op-textarea:focus {
            outline: none;
            border-color: #7aa46d;
            box-shadow: 0 0 0 4px rgba(47,125,50,.10);
        }

        .op-help {
            margin-top: 7px;
            font-size: 12px;
            color: var(--op-text-soft);
            line-height: 1.6;
        }

        .op-table-wrap {
            overflow-x: auto;
            width: 100%;
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
            font-size: 12px;
            font-weight: 800;
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid var(--op-border);
            white-space: nowrap;
        }

        .op-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--op-border);
            vertical-align: middle;
            font-size: 14px;
            color: var(--op-text);
            background: var(--op-surface);
        }

        .op-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .op-table tbody tr:hover td {
            background: var(--op-surface-soft);
        }

        .op-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .op-badge--green {
            background: #eef8ee;
            color: #17603a;
        }

        .op-badge--gold {
            background: #fff8eb;
            color: #9a6700;
        }

        .op-badge--blue {
            background: #eef5ff;
            color: #1d4ed8;
        }

        html[data-theme="dark"] .op-badge--green {
            background: rgba(22, 101, 52, .22);
            color: #86efac;
        }

        html[data-theme="dark"] .op-badge--gold {
            background: rgba(133, 77, 14, .22);
            color: #fcd34d;
        }

        html[data-theme="dark"] .op-badge--blue {
            background: rgba(29, 78, 216, .22);
            color: #93c5fd;
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
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
        }

        .op-btn-edit {
            background: var(--op-surface-soft);
            border-color: var(--op-border);
        }

        .op-btn-danger {
            background: #fff5f5;
            border-color: #fecaca;
            color: #b42318;
        }

        html[data-theme="dark"] .op-btn-danger {
            background: rgba(127, 29, 29, .18);
            border-color: rgba(248, 113, 113, .26);
            color: #fca5a5;
        }

        .op-empty {
            padding: 42px 20px;
            text-align: center;
            color: var(--op-text-soft);
        }

        .op-empty-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--op-text);
            margin-bottom: 8px;
        }

        .op-pagination {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
            overflow-x: auto;
            overflow-y: hidden;
            width: 100%;
            padding-bottom: 4px;
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
        }

        .op-pagination nav[role="navigation"] a,
        .op-pagination nav[aria-label*="Pagination"] a,
        .op-pagination nav[aria-label*="pagination"] a {
            background: var(--op-surface);
            color: var(--op-text);
            border: 1px solid var(--op-border);
            box-shadow: var(--op-shadow-soft);
            transition: .18s ease;
        }

        .op-pagination nav[role="navigation"] a:hover,
        .op-pagination nav[aria-label*="Pagination"] a:hover,
        .op-pagination nav[aria-label*="pagination"] a:hover {
            transform: translateY(-1px);
            border-color: var(--op-border-strong);
        }

        .op-pagination nav[role="navigation"] span[aria-current="page"],
        .op-pagination nav[aria-label*="Pagination"] span[aria-current="page"],
        .op-pagination nav[aria-label*="pagination"] span[aria-current="page"] {
            background: linear-gradient(135deg, var(--op-primary), var(--op-primary-2));
            color: #fff;
            border: 1px solid transparent;
            box-shadow: 0 12px 22px rgba(23,63,8,.16);
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
            font-size: 14px;
            color: var(--op-text-soft);
        }

        html[data-theme="dark"] .op-pagination nav[role="navigation"] a,
        html[data-theme="dark"] .op-pagination nav[aria-label*="Pagination"] a,
        html[data-theme="dark"] .op-pagination nav[aria-label*="pagination"] a {
            background: var(--op-surface);
            border-color: var(--op-border);
            color: var(--op-text);
        }

        html[data-theme="dark"] .op-pagination nav[role="navigation"] span[aria-disabled="true"],
        html[data-theme="dark"] .op-pagination nav[aria-label*="Pagination"] span[aria-disabled="true"],
        html[data-theme="dark"] .op-pagination nav[aria-label*="pagination"] span[aria-disabled="true"] {
            background: var(--op-surface-soft);
            border-color: var(--op-border);
            color: var(--op-text-soft);
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
                background: rgba(2, 6, 23, .45);
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: .2s ease;
                z-index: 29;
            }

            .op-sidebar-backdrop.is-open {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
            }

            .op-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                width: min(290px, 86vw);
                height: 100vh;
                transform: translateX(-100%);
                transition: transform .22s ease;
                z-index: 30;
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
        }

        @media (max-width: 768px) {
            .op-topbar,
            .op-content {
                padding-left: 16px;
                padding-right: 16px;
            }

            .op-page-title {
                font-size: 28px;
            }

            .op-grid-2,
            .op-grid-3,
            .op-grid-4,
            .op-form-grid {
                grid-template-columns: 1fr;
            }

            .op-card-head,
            .op-card-body {
                padding-left: 16px;
                padding-right: 16px;
            }

            .op-topbar-title {
                font-size: 13px;
                min-height: 40px;
                display: inline-flex;
                align-items: center;
            }

            .op-topbar-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .op-btn {
                width: 100%;
            }

            .op-topbar-actions .op-btn,
            .op-topbar-actions .op-theme-btn {
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
            .op-topbar-inner {
                align-items: stretch;
            }

            .op-topbar-left,
            .op-topbar-actions {
                width: 100%;
            }

            .op-topbar-actions {
                justify-content: space-between;
            }

            .op-topbar-title {
                flex: 1 1 auto;
                justify-content: center;
                text-align: center;
            }

            .op-topbar-actions .op-btn,
            .op-topbar-actions .op-theme-btn {
                flex: 1 1 auto;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
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
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
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
                        <span class="op-nav-title">Data Crude</span>
                        <span class="op-nav-subtitle">Produksi crude harian</span>
                    </span>
                </a>

                <a href="{{ route('operational.vitol.index') }}" class="op-nav-link {{ request()->routeIs('operational.vitol.*') ? 'is-active' : '' }}">
                    <span class="op-nav-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16"></path>
                            <path d="M4 12h16"></path>
                            <path d="M4 17h10"></path>
                            <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        </svg>
                    </span>
                    <span class="op-nav-text">
                        <span class="op-nav-title">Data VITOL</span>
                        <span class="op-nav-subtitle">Quantity, fee, commission</span>
                    </span>
                </a>
            </nav>

            <div class="op-sidebar-footer">
                <div class="op-user-name">{{ session('user_name') ?? auth()->user()->name ?? 'User Operational' }}</div>

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
                        <button type="button" class="op-mobile-menu-btn" id="opMobileMenuBtn" aria-label="Buka menu">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </button>

                        <div class="op-topbar-title">Operational Monitoring System</div>
                    </div>

                    <div class="op-topbar-actions">
                        <a href="{{ url('operational/tv') }}" class="op-btn op-btn--tv">
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
            const html = document.documentElement;
            const themeToggle = document.getElementById('opThemeToggle');
            const sunIcon = document.getElementById('opThemeIconSun');
            const moonIcon = document.getElementById('opThemeIconMoon');
            const savedTheme = localStorage.getItem('op-theme');

            function applyTheme(theme) {
                html.setAttribute('data-theme', theme);
                localStorage.setItem('op-theme', theme);

                if (theme === 'dark') {
                    if (sunIcon) sunIcon.style.display = 'none';
                    if (moonIcon) moonIcon.style.display = 'block';
                } else {
                    if (sunIcon) sunIcon.style.display = 'block';
                    if (moonIcon) moonIcon.style.display = 'none';
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
                if (sidebar) sidebar.classList.add('is-open');
                if (backdrop) backdrop.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('is-open');
                if (backdrop) backdrop.classList.remove('is-open');
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

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    closeSidebar();
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>