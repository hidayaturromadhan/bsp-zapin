<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reviewer Panel — {{ config('app.name', 'BSP Zapin') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --g950: #0b1f05;
            --g900: #0f2906;
            --g850: #123107;
            --g800: #173f08;
            --g700: #21560e;
            --g600: #2b6b16;
            --g500: #2f7d32;

            --bg: #f4f7f6;
            --surface: #ffffff;
            --surface-soft: #f8fbf9;
            --text: #0f172a;
            --text-soft: #64748b;
            --line: #e2e8f0;
            --line-soft: #edf2f7;

            --shadow-sm: 0 8px 20px rgba(15, 23, 42, .05);
            --shadow-md: 0 14px 32px rgba(15, 23, 42, .08);

            --radius: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;

            --sidebar-w: 280px;
            --topbar-h: 74px;
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
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input,
        textarea,
        select {
            font: inherit;
        }

        .r-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar-w) minmax(0, 1fr);
        }

        .r-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 28%),
                linear-gradient(180deg, #173f08 0%, #0f2906 100%);
            color: #fff;
            padding: 22px 18px 20px;
            border-right: 1px solid rgba(255,255,255,.08);
        }

        .r-sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .r-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.16);
            border-radius: 999px;
        }

        .r-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: 18px;
        }

        .r-brand-logo {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .r-brand-logo img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .r-brand-copy {
            min-width: 0;
        }

        .r-brand-title {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -.03em;
            margin: 0;
        }

        .r-brand-subtitle {
            margin-top: 4px;
            font-size: 12px;
            color: rgba(255,255,255,.72);
        }

        .r-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 2px 0 18px;
            padding: 9px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: #eaffdf;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.08);
        }

        .r-role-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #c7f9b1;
            display: inline-block;
            box-shadow: 0 0 0 4px rgba(199,249,177,.14);
        }

        .r-nav-group {
            margin-bottom: 20px;
        }

        .r-nav-label {
            display: block;
            padding: 0 10px 10px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
        }

        .r-nav {
            display: grid;
            gap: 6px;
        }

        .r-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 48px;
            padding: 0 14px;
            border-radius: 14px;
            color: rgba(255,255,255,.86);
            font-size: 14px;
            font-weight: 700;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .r-nav-item:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
            transform: translateX(2px);
        }

        .r-nav-item.active {
            background: #eef6eb;
            color: #173f08;
            box-shadow: inset 3px 0 0 #2f7d32;
        }

        .r-nav-icon {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .r-nav-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .r-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .r-topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            min-height: var(--topbar-h);
            background:
                radial-gradient(circle at left center, rgba(255,255,255,.08), transparent 26%),
                linear-gradient(90deg, var(--g900), var(--g700));
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,.08);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .r-topbar-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .r-topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 13px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.08);
            white-space: nowrap;
        }

        .r-topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .r-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
        }

        .r-btn:hover {
            transform: translateY(-1px);
        }

        .r-btn svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
        }

        .r-btn-light {
            background: rgba(255,255,255,.08);
            color: #fff;
            border-color: rgba(255,255,255,.12);
        }

        .r-btn-light:hover {
            background: rgba(255,255,255,.14);
        }

        .r-btn-white {
            background: #fff;
            color: var(--g800);
        }

        .r-btn-white:hover {
            background: #f4f7f4;
        }

        .r-content {
            padding: 28px;
        }

        .r-page {
            max-width: 1320px;
            margin: 0 auto;
        }

        .r-mobile-menu {
            display: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.08);
            color: #fff;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .r-mobile-menu svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        .r-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, .42);
            opacity: 0;
            visibility: hidden;
            transition: .2s ease;
            z-index: 39;
        }

        @media (max-width: 1080px) {
            .r-shell {
                grid-template-columns: 1fr;
            }

            .r-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                width: min(86vw, 320px);
                height: 100vh;
                transform: translateX(-100%);
                transition: transform .24s ease;
                z-index: 40;
                box-shadow: 0 20px 40px rgba(0,0,0,.24);
            }

            .r-shell.is-sidebar-open .r-sidebar {
                transform: translateX(0);
            }

            .r-shell.is-sidebar-open .r-overlay {
                opacity: 1;
                visibility: visible;
            }

            .r-mobile-menu {
                display: inline-flex;
            }
        }

        @media (max-width: 760px) {
            .r-topbar,
            .r-content {
                padding-left: 16px;
                padding-right: 16px;
            }

            .r-topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .r-topbar-left,
            .r-topbar-actions {
                width: 100%;
            }

            .r-topbar-actions {
                justify-content: stretch;
            }

            .r-topbar-actions > * {
                flex: 1 1 auto;
            }

            .r-btn,
            .r-topbar-actions form {
                width: 100%;
            }

            .r-topbar-badge {
                width: 100%;
                justify-content: center;
            }

            .r-brand-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="r-shell" id="reviewerShell">
        <div class="r-overlay" id="reviewerOverlay"></div>

        <aside class="r-sidebar" id="reviewerSidebar">
            <div class="r-brand">
                <div class="r-brand-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BSP Zapin">
                </div>

                <div class="r-brand-copy">
                    <div class="r-brand-title">BSP Zapin</div>
                    <div class="r-brand-subtitle">Reviewer Panel</div>
                </div>
            </div>

            <div class="r-role-pill">
                <span class="r-role-dot"></span>
                Reviewer Workspace
            </div>

            <div class="r-nav-group">
                <span class="r-nav-label">Utama</span>

                <nav class="r-nav">
                    <a href="{{ route('reviewer.dashboard') }}"
                       class="r-nav-item {{ request()->routeIs('reviewer.dashboard') ? 'active' : '' }}">
                        <span class="r-nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <path d="M3 13.5 12 5l9 8.5"/>
                                <path d="M5 11.5V20h14v-8.5"/>
                            </svg>
                        </span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('reviewer.news.index') }}"
                       class="r-nav-item {{ request()->routeIs('reviewer.news.*') ? 'active' : '' }}">
                        <span class="r-nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <rect x="4" y="4" width="16" height="16" rx="2"/>
                                <path d="M8 8h8M8 12h8M8 16h5"/>
                            </svg>
                        </span>
                        <span>Review Queue</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="r-main">
            <div class="r-topbar">
                <div class="r-topbar-left">
                    <button type="button" class="r-mobile-menu" id="reviewerMenuButton" aria-label="Buka menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M4 7h16M4 12h16M4 17h16"/>
                        </svg>
                    </button>

                    <div class="r-topbar-badge">
                        <span class="r-role-dot"></span>
                        BSP Zapin Reviewer CMS
                    </div>
                </div>

                <div class="r-topbar-actions">
                    <a href="{{ route('web.home', ['locale' => 'id']) }}" class="r-btn r-btn-light" target="_blank">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M7 17 17 7"/>
                            <path d="M8 7h9v9"/>
                        </svg>
                        <span>Lihat Website</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="r-btn r-btn-white">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <path d="M16 17l5-5-5-5"/>
                                <path d="M21 12H9"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="r-content">
                <div class="r-page">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        (function () {
            const shell = document.getElementById('reviewerShell');
            const overlay = document.getElementById('reviewerOverlay');
            const menuButton = document.getElementById('reviewerMenuButton');

            if (!shell || !overlay || !menuButton) return;

            function openSidebar() {
                shell.classList.add('is-sidebar-open');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                shell.classList.remove('is-sidebar-open');
                document.body.style.overflow = '';
            }

            menuButton.addEventListener('click', function () {
                if (shell.classList.contains('is-sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1080) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>