<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Writer Panel — {{ config('app.name', 'BSP Zapin') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#f4f7f6;
            --surface:#ffffff;
            --surface-soft:#f8fbf9;
            --text:#0f172a;
            --text-soft:#64748b;
            --line:#e2e8f0;
            --line-soft:#edf2f7;
            --primary:#173f08;
            --primary-2:#21560e;
            --primary-soft:#eef6eb;
            --white:#ffffff;
            --shadow:0 10px 30px rgba(15, 23, 42, .08);
            --radius:20px;
            --radius-sm:12px;
            --sidebar-w:280px;
            --topbar-h:74px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
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

        .w-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar-w) 1fr;
        }

        .w-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 30%),
                linear-gradient(180deg, #173f08 0%, #102d06 100%);
            color: var(--white);
            padding: 22px 18px 20px;
            border-right: 1px solid rgba(255,255,255,.08);
        }

        .w-sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .w-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.16);
            border-radius: 999px;
        }

        .w-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            padding: 8px 6px 2px;
        }

        .w-brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,.08);
        }

        .w-brand-logo img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .w-brand-copy {
            min-width: 0;
        }

        .w-brand-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.1;
            margin: 0 0 3px;
        }

        .w-brand-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,.72);
            margin: 0;
        }

        .w-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            color: #f8fafc;
            font-weight: 700;
            margin: 8px 0 18px;
        }

        .w-role-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #bbf7d0;
            box-shadow: 0 0 0 4px rgba(187,247,208,.14);
        }

        .w-nav-group {
            margin-top: 16px;
        }

        .w-nav-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.52);
            margin: 0 10px 10px;
        }

        .w-nav {
            display: grid;
            gap: 6px;
        }

        .w-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 48px;
            padding: 0 14px;
            border-radius: 14px;
            color: rgba(255,255,255,.88);
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .w-nav-item:hover {
            background: rgba(255,255,255,.08);
            color: var(--white);
            transform: translateX(2px);
        }

        .w-nav-item.active {
            background: var(--primary-soft);
            color: var(--primary);
            box-shadow: inset 0 0 0 1px rgba(23,63,8,.06);
        }

        .w-nav-icon {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .w-nav-icon svg {
            width: 19px;
            height: 19px;
            stroke: currentColor;
        }

        .w-nav-text {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .w-main {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .w-topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 28px;
            background:
                radial-gradient(circle at left center, rgba(255,255,255,.08), transparent 26%),
                linear-gradient(90deg, #102d06 0%, #21560e 100%);
            color: var(--white);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .w-topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .w-mobile-menu {
            display: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.08);
            color: var(--white);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .w-mobile-menu svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        .w-topbar-title {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .w-topbar-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 14px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            white-space: nowrap;
        }

        .w-topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .w-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 14px;
            border-radius: 12px;
            font-weight: 700;
            transition: transform .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
        }

        .w-btn:hover {
            transform: translateY(-1px);
        }

        .w-btn svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
        }

        .w-btn-light {
            background: rgba(255,255,255,.08);
            color: var(--white);
            border: 1px solid rgba(255,255,255,.14);
        }

        .w-btn-light:hover {
            background: rgba(255,255,255,.12);
        }

        .w-btn-white {
            background: var(--white);
            color: var(--primary);
            border: 1px solid rgba(255,255,255,.12);
            cursor: pointer;
        }

        .w-btn-white:hover {
            background: #f8fafc;
        }

        .w-content {
            padding: 28px;
        }

        .w-content > *:first-child {
            margin-top: 0;
        }

        .w-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, .42);
            opacity: 0;
            visibility: hidden;
            transition: .2s ease;
            z-index: 39;
        }

        @media (max-width: 1100px) {
            .w-shell {
                grid-template-columns: 1fr;
            }

            .w-sidebar {
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

            .w-shell.is-sidebar-open .w-sidebar {
                transform: translateX(0);
            }

            .w-shell.is-sidebar-open .w-overlay {
                opacity: 1;
                visibility: visible;
            }

            .w-mobile-menu {
                display: inline-flex;
            }

            .w-topbar {
                padding: 14px 18px;
            }

            .w-content {
                padding: 20px 18px;
            }
        }

        @media (max-width: 720px) {
            .w-topbar {
                height: auto;
                min-height: var(--topbar-h);
                align-items: flex-start;
                flex-direction: column;
            }

            .w-topbar-left,
            .w-topbar-actions {
                width: 100%;
            }

            .w-topbar-actions {
                justify-content: stretch;
            }

            .w-topbar-actions > * {
                flex: 1 1 auto;
            }

            .w-btn,
            .w-topbar-actions form {
                width: 100%;
            }

            .w-topbar-chip {
                width: 100%;
                justify-content: center;
            }

            .w-brand-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<div class="w-shell" id="writerShell">
    <div class="w-overlay" id="writerOverlay"></div>

    <aside class="w-sidebar" id="writerSidebar" aria-label="Writer Sidebar">
        <div class="w-brand">
            <div class="w-brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
            </div>
            <div class="w-brand-copy">
                <h1 class="w-brand-title">BSP Zapin</h1>
                <p class="w-brand-subtitle">Writer Panel</p>
            </div>
        </div>

        <div class="w-role-badge">
            <span class="w-role-dot"></span>
            <span>Writer Workspace</span>
        </div>

        <div class="w-nav-group">
            <span class="w-nav-label">Utama</span>

            <nav class="w-nav">
                <a href="{{ route('writer.dashboard') }}"
                   class="w-nav-item {{ request()->routeIs('writer.dashboard') ? 'active' : '' }}">
                    <span class="w-nav-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M3 13.5 12 5l9 8.5"/>
                            <path d="M5 11.5V20h14v-8.5"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">Dashboard</span>
                </a>

                <a href="{{ route('writer.news.index') }}"
                   class="w-nav-item {{ request()->routeIs('writer.news.index') ? 'active' : '' }}">
                    <span class="w-nav-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <path d="M8 8h8M8 12h8M8 16h5"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">News Saya</span>
                </a>

                <a href="{{ route('writer.news.create') }}"
                   class="w-nav-item {{ request()->routeIs('writer.news.create') ? 'active' : '' }}">
                    <span class="w-nav-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">Tulis News</span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="w-main">
        <header class="w-topbar">
            <div class="w-topbar-left">
                <button type="button" class="w-mobile-menu" id="writerMenuButton" aria-label="Buka menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>

                <div class="w-topbar-title">
                    <div class="w-topbar-chip">
                        <span>Writer CMS</span>
                    </div>
                </div>
            </div>

            <div class="w-topbar-actions">
                <a href="{{ route('web.home', ['locale' => 'id']) }}" class="w-btn w-btn-light" target="_blank">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <path d="M7 17 17 7"/>
                        <path d="M8 7h9v9"/>
                    </svg>
                    <span>Lihat Website</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-btn w-btn-white">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <path d="M16 17l5-5-5-5"/>
                            <path d="M21 12H9"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <section class="w-content">
            @yield('content')
        </section>
    </main>
</div>

<script>
    (function () {
        const shell = document.getElementById('writerShell');
        const sidebar = document.getElementById('writerSidebar');
        const overlay = document.getElementById('writerOverlay');
        const menuButton = document.getElementById('writerMenuButton');

        if (!shell || !sidebar || !overlay || !menuButton) return;

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
            if (window.innerWidth > 1100) {
                closeSidebar();
            }
        });
    })();
</script>
</body>
</html>