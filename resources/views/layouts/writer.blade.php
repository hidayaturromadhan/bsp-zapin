<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Writer Panel') — {{ config('app.name', 'BSP Zapin') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
            --danger:#dc2626;
            --danger-soft:#fef2f2;
            --warning:#d97706;
            --warning-soft:#fffbeb;
            --success:#15803d;
            --success-soft:#f0fdf4;
            --info:#2563eb;
            --info-soft:#eff6ff;
            --white:#ffffff;
            --shadow:0 10px 30px rgba(15, 23, 42, .08);
            --radius:20px;
            --radius-sm:12px;
            --sidebar-w:280px;
            --topbar-h:74px;
        }

        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{
            margin:0;
            font-family:'Plus Jakarta Sans',sans-serif;
            background:var(--bg);
            color:var(--text);
        }

        a{text-decoration:none;color:inherit}
        button,input,textarea,select{font:inherit}
        img{max-width:100%}

        .w-shell{
            min-height:100vh;
            display:grid;
            grid-template-columns:var(--sidebar-w) 1fr;
        }

        .w-sidebar{
            position:sticky;
            top:0;
            height:100vh;
            overflow-y:auto;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 30%),
                linear-gradient(180deg,#173f08 0%,#102d06 100%);
            color:var(--white);
            padding:22px 18px 20px;
            border-right:1px solid rgba(255,255,255,.08);
            z-index:50;
        }

        .w-sidebar::-webkit-scrollbar{width:8px}
        .w-sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.16);border-radius:999px}

        .w-brand{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:22px;
            padding:8px 6px 2px;
        }

        .w-brand-logo{
            width:44px;
            height:44px;
            border-radius:14px;
            background:rgba(255,255,255,.08);
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            flex-shrink:0;
            border:1px solid rgba(255,255,255,.08);
        }

        .w-brand-logo img{
            width:34px;
            height:34px;
            object-fit:contain;
        }

        .w-brand-title{
            font-size:22px;
            font-weight:900;
            letter-spacing:-.03em;
            line-height:1.1;
            margin:0 0 3px;
        }

        .w-brand-subtitle{
            font-size:13px;
            color:rgba(255,255,255,.72);
            margin:0;
        }

        .w-role-badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            min-height:40px;
            padding:0 14px;
            border-radius:999px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.10);
            color:#f8fafc;
            font-weight:800;
            margin:8px 0 18px;
        }

        .w-role-dot{
            width:8px;
            height:8px;
            border-radius:999px;
            background:#bbf7d0;
            box-shadow:0 0 0 4px rgba(187,247,208,.14);
        }

        .w-nav-group{margin-top:16px}

        .w-nav-label{
            display:block;
            font-size:11px;
            font-weight:800;
            letter-spacing:.12em;
            text-transform:uppercase;
            color:rgba(255,255,255,.52);
            margin:0 10px 10px;
        }

        .w-nav{
            display:grid;
            gap:6px;
        }

        .w-nav-item{
            display:flex;
            align-items:center;
            gap:12px;
            min-height:48px;
            padding:0 14px;
            border-radius:14px;
            color:rgba(255,255,255,.88);
            transition:background .18s ease,color .18s ease,transform .18s ease;
        }

        .w-nav-item:hover{
            background:rgba(255,255,255,.08);
            color:var(--white);
            transform:translateX(2px);
        }

        .w-nav-item.active{
            background:var(--primary-soft);
            color:var(--primary);
            box-shadow:inset 0 0 0 1px rgba(23,63,8,.06);
        }

        .w-nav-icon{
            width:19px;
            height:19px;
            flex-shrink:0;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .w-nav-icon svg{
            width:19px;
            height:19px;
            stroke:currentColor;
        }

        .w-nav-text{
            font-size:15px;
            font-weight:800;
            line-height:1.2;
        }

        .w-main{
            min-width:0;
            display:flex;
            flex-direction:column;
        }

        .w-topbar{
            position:sticky;
            top:0;
            z-index:30;
            height:var(--topbar-h);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            padding:14px 28px;
            background:
                radial-gradient(circle at left center, rgba(255,255,255,.08), transparent 26%),
                linear-gradient(90deg,#102d06 0%,#21560e 100%);
            color:var(--white);
            border-bottom:1px solid rgba(255,255,255,.08);
        }

        .w-topbar-left{
            display:flex;
            align-items:center;
            gap:14px;
            min-width:0;
        }

        .w-mobile-menu{
            display:none;
            width:42px;
            height:42px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background:rgba(255,255,255,.08);
            color:var(--white);
            align-items:center;
            justify-content:center;
            cursor:pointer;
            flex-shrink:0;
        }

        .w-topbar-title{
            min-width:0;
        }

        .w-topbar-title strong{
            display:block;
            font-size:17px;
            font-weight:900;
            line-height:1.2;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .w-topbar-title span{
            display:block;
            font-size:12px;
            color:rgba(255,255,255,.72);
            margin-top:3px;
        }

        .w-topbar-right{
            display:flex;
            align-items:center;
            gap:12px;
            flex-shrink:0;
        }

        .w-user{
            display:flex;
            align-items:center;
            gap:10px;
            padding:7px 10px;
            border-radius:999px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.10);
        }

        .w-user-avatar{
            width:34px;
            height:34px;
            border-radius:999px;
            background:#eef6eb;
            color:#173f08;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
            font-weight:900;
            flex-shrink:0;
        }

        .w-user-copy{
            line-height:1.2;
            min-width:0;
        }

        .w-user-name{
            font-size:13px;
            font-weight:900;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            max-width:150px;
        }

        .w-user-role{
            font-size:11px;
            color:rgba(255,255,255,.68);
            margin-top:2px;
            text-transform:capitalize;
        }

        .w-logout-btn{
            min-height:42px;
            padding:0 14px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background:rgba(255,255,255,.08);
            color:#fff;
            font-weight:800;
            cursor:pointer;
            transition:background .18s ease,transform .18s ease;
        }

        .w-logout-btn:hover{
            background:rgba(255,255,255,.14);
            transform:translateY(-1px);
        }

        .w-content{
            padding:28px;
            width:100%;
            max-width:1500px;
        }

        .a-page-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:18px;
            margin-bottom:20px;
        }

        .a-page-head-copy{min-width:0}

        .a-breadcrumb{
            display:flex;
            align-items:center;
            gap:8px;
            font-size:12px;
            font-weight:800;
            color:var(--text-soft);
            margin-bottom:8px;
            flex-wrap:wrap;
        }

        .a-breadcrumb a{color:var(--primary)}
        .a-breadcrumb-sep{color:#94a3b8}

        .a-page-title{
            margin:0;
            font-size:28px;
            line-height:1.15;
            letter-spacing:-.04em;
            font-weight:900;
            color:var(--text);
        }

        .a-page-desc{
            margin:8px 0 0;
            color:var(--text-soft);
            font-size:14px;
            line-height:1.7;
        }

        .a-card{
            background:var(--surface);
            border:1px solid var(--line);
            border-radius:var(--radius);
            padding:18px;
            box-shadow:var(--shadow);
        }

        .a-card-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:14px;
            margin-bottom:16px;
        }

        .a-card-title{
            font-size:16px;
            font-weight:900;
            color:var(--text);
        }

        .a-card-desc{
            margin-top:4px;
            font-size:13px;
            color:var(--text-soft);
            line-height:1.5;
        }

        .a-label{
            display:block;
            font-size:13px;
            font-weight:800;
            color:#334155;
            margin-bottom:7px;
        }

        .a-input{
            width:100%;
            min-height:44px;
            border:1px solid #cbd5e1;
            border-radius:12px;
            background:#fff;
            color:#0f172a;
            padding:10px 12px;
            outline:none;
            transition:border-color .16s ease,box-shadow .16s ease;
        }

        textarea.a-input{
            min-height:110px;
            resize:vertical;
            line-height:1.7;
        }

        .a-input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(23,63,8,.10);
        }

        .a-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            min-height:44px;
            padding:0 16px;
            border-radius:12px;
            border:1px solid transparent;
            font-size:14px;
            font-weight:900;
            cursor:pointer;
            transition:transform .16s ease,background .16s ease,border-color .16s ease;
            white-space:nowrap;
        }

        .a-btn:hover{transform:translateY(-1px)}

        .a-btn--primary{
            background:var(--primary);
            color:#fff;
            border-color:var(--primary);
        }

        .a-btn--primary:hover{
            background:var(--primary-2);
            border-color:var(--primary-2);
        }

        .a-btn--secondary{
            background:#fff;
            color:#334155;
            border-color:#cbd5e1;
        }

        .a-btn--light{
            background:#f8fafc;
            color:#334155;
            border-color:#e2e8f0;
        }

        .a-btn--danger{
            background:var(--danger);
            color:#fff;
            border-color:var(--danger);
        }

        .a-btn--sm{
            min-height:34px;
            padding:0 11px;
            border-radius:10px;
            font-size:12px;
        }

        .a-alert{
            padding:13px 15px;
            border-radius:14px;
            margin-bottom:16px;
            font-size:14px;
            font-weight:700;
            line-height:1.6;
            border:1px solid transparent;
        }

        .a-alert--success{
            background:var(--success-soft);
            color:#166534;
            border-color:#bbf7d0;
        }

        .a-alert--danger{
            background:var(--danger-soft);
            color:#991b1b;
            border-color:#fecaca;
        }

        .a-alert--info{
            background:var(--info-soft);
            color:#1e40af;
            border-color:#bfdbfe;
        }

        .a-alert--warning{
            background:var(--warning-soft);
            color:#92400e;
            border-color:#fde68a;
        }

        .a-table-wrap{
            width:100%;
            overflow-x:auto;
            border:1px solid var(--line-soft);
            border-radius:16px;
        }

        .a-table{
            width:100%;
            border-collapse:collapse;
            min-width:900px;
            background:#fff;
        }

        .a-table th{
            background:#f8fafc;
            color:#475569;
            font-size:12px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.04em;
            padding:13px 14px;
            text-align:left;
            border-bottom:1px solid var(--line);
        }

        .a-table td{
            padding:14px;
            border-bottom:1px solid var(--line-soft);
            vertical-align:top;
            font-size:14px;
        }

        .a-table tbody tr:hover{background:#fbfdfb}
        .a-table tbody tr:last-child td{border-bottom:none}

        .a-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-height:28px;
            padding:0 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            border:1px solid transparent;
            white-space:nowrap;
        }

        .a-badge--gray{
            background:#f1f5f9;
            color:#475569;
            border-color:#e2e8f0;
        }

        .a-badge--blue{
            background:#eff6ff;
            color:#1d4ed8;
            border-color:#bfdbfe;
        }

        .a-badge--orange{
            background:#fffbeb;
            color:#b45309;
            border-color:#fde68a;
        }

        .a-badge--green{
            background:#f0fdf4;
            color:#15803d;
            border-color:#bbf7d0;
        }

        .a-badge--red{
            background:#fef2f2;
            color:#b91c1c;
            border-color:#fecaca;
        }

        .a-empty{
            padding:40px 18px;
            text-align:center;
            color:#64748b;
        }

        .a-empty-title{
            font-size:18px;
            font-weight:900;
            color:#0f172a;
            margin-bottom:6px;
        }

        .a-empty-desc{
            font-size:14px;
            margin-bottom:16px;
        }

        .w-overlay{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(15,23,42,.48);
            z-index:40;
        }

        @media (max-width:1100px){
            .w-shell{grid-template-columns:1fr}
            .w-sidebar{
                position:fixed;
                left:0;
                top:0;
                transform:translateX(-100%);
                transition:transform .22s ease;
                width:var(--sidebar-w);
            }

            body.sidebar-open .w-sidebar{transform:translateX(0)}
            body.sidebar-open .w-overlay{display:block}
            .w-mobile-menu{display:inline-flex}
            .w-content{padding:22px}
        }

        @media (max-width:760px){
            .w-topbar{padding:12px 16px}
            .w-user-copy{display:none}
            .w-logout-btn{padding:0 11px}
            .w-content{padding:16px}
            .a-page-head{
                display:grid;
                gap:14px;
            }
            .a-page-title{font-size:24px}
            .a-card{padding:15px;border-radius:16px}
        }
    </style>

    @stack('styles')
</head>

<body>
@php
    $user = Auth::user();
    $userName = $user?->name ?? session('user_name', 'Writer');
    $userRole = $user?->role ?? session('user_role', 'writer');

    $initials = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');

    $initials = $initials ?: 'W';

    $isDashboardActive = request()->routeIs('writer.dashboard');
    $isNewsActive = request()->routeIs('writer.news.*');
    $isTjslActive = request()->routeIs('writer.tjsl.*');
@endphp

<div class="w-overlay" id="writerOverlay"></div>

<div class="w-shell">
    <aside class="w-sidebar" id="writerSidebar">
        <div class="w-brand">
            <div class="w-brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
            </div>
            <div class="w-brand-copy">
                <p class="w-brand-title">Writer</p>
                <p class="w-brand-subtitle">BSP Zapin Panel</p>
            </div>
        </div>

        <div class="w-role-badge">
            <span class="w-role-dot"></span>
            <span>Writer Area</span>
        </div>

        <div class="w-nav-group">
            <span class="w-nav-label">Menu Utama</span>

            <nav class="w-nav">
                <a href="{{ route('writer.dashboard') }}" class="w-nav-item {{ $isDashboardActive ? 'active' : '' }}">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 13h8V3H3v10Z"/>
                            <path d="M13 21h8V11h-8v10Z"/>
                            <path d="M13 3h8v6h-8V3Z"/>
                            <path d="M3 21h8v-6H3v6Z"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">Dashboard</span>
                </a>

                <a href="{{ route('writer.news.index') }}" class="w-nav-item {{ $isNewsActive ? 'active' : '' }}">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            <path d="M8 7h8"/>
                            <path d="M8 11h8"/>
                            <path d="M8 15h5"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">News</span>
                </a>

                <a href="{{ route('writer.tjsl.index') }}" class="w-nav-item {{ $isTjslActive ? 'active' : '' }}">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                            <path d="M9 12l2 2 4-5"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">TJSL</span>
                </a>
            </nav>
        </div>

        <div class="w-nav-group">
            <span class="w-nav-label">Website</span>

            <nav class="w-nav">
                <a href="{{ route('web.home', ['locale' => 'id']) }}" target="_blank" class="w-nav-item">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">Lihat Website</span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="w-main">
        <header class="w-topbar">
            <div class="w-topbar-left">
                <button type="button" class="w-mobile-menu" id="writerMenuBtn" aria-label="Buka Menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                        <path d="M4 6h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 18h16"/>
                    </svg>
                </button>

                <div class="w-topbar-title">
                    <strong>@yield('title', 'Writer Panel')</strong>
                    <span>Kelola konten website publik BSP Zapin</span>
                </div>
            </div>

            <div class="w-topbar-right">
                <div class="w-user">
                    <div class="w-user-avatar">{{ strtoupper($initials) }}</div>
                    <div class="w-user-copy">
                        <div class="w-user-name">{{ $userName }}</div>
                        <div class="w-user-role">{{ $userRole }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-logout-btn">Logout</button>
                </form>
            </div>
        </header>

        <section class="w-content">
            @yield('content')
        </section>
    </main>
</div>

<script>
    const writerMenuBtn = document.getElementById('writerMenuBtn');
    const writerOverlay = document.getElementById('writerOverlay');

    if (writerMenuBtn) {
        writerMenuBtn.addEventListener('click', function () {
            document.body.classList.add('sidebar-open');
        });
    }

    if (writerOverlay) {
        writerOverlay.addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    }
</script>

@stack('scripts')
</body>
</html>