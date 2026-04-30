<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Reviewer Panel') — {{ config('app.name', 'BSP Zapin') }}</title>

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
            --shadow:0 10px 30px rgba(15,23,42,.08);
            --radius:20px;
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

        .r-shell{
            min-height:100vh;
            display:grid;
            grid-template-columns:var(--sidebar-w) 1fr;
        }

        .r-sidebar{
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

        .r-brand{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:22px;
            padding:8px 6px 2px;
        }

        .r-brand-logo{
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

        .r-brand-logo img{
            width:34px;
            height:34px;
            object-fit:contain;
        }

        .r-brand-title{
            font-size:22px;
            font-weight:900;
            letter-spacing:-.03em;
            line-height:1.1;
            margin:0 0 3px;
        }

        .r-brand-subtitle{
            font-size:13px;
            color:rgba(255,255,255,.72);
            margin:0;
        }

        .r-role-badge{
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

        .r-role-dot{
            width:8px;
            height:8px;
            border-radius:999px;
            background:#bfdbfe;
            box-shadow:0 0 0 4px rgba(191,219,254,.14);
        }

        .r-nav-group{margin-top:16px}

        .r-nav-label{
            display:block;
            font-size:11px;
            font-weight:800;
            letter-spacing:.12em;
            text-transform:uppercase;
            color:rgba(255,255,255,.52);
            margin:0 10px 10px;
        }

        .r-nav{
            display:grid;
            gap:6px;
        }

        .r-nav-item{
            display:flex;
            align-items:center;
            gap:12px;
            min-height:48px;
            padding:0 14px;
            border-radius:14px;
            color:rgba(255,255,255,.88);
            transition:background .18s ease,color .18s ease,transform .18s ease;
        }

        .r-nav-item:hover{
            background:rgba(255,255,255,.08);
            color:var(--white);
            transform:translateX(2px);
        }

        .r-nav-item.active{
            background:var(--primary-soft);
            color:var(--primary);
            box-shadow:inset 0 0 0 1px rgba(23,63,8,.06);
        }

        .r-nav-icon{
            width:19px;
            height:19px;
            flex-shrink:0;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .r-nav-icon svg{
            width:19px;
            height:19px;
            stroke:currentColor;
        }

        .r-nav-text{
            font-size:15px;
            font-weight:800;
            line-height:1.2;
        }

        .r-main{
            min-width:0;
            display:flex;
            flex-direction:column;
        }

        .r-topbar{
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

        .r-topbar-left{
            display:flex;
            align-items:center;
            gap:14px;
            min-width:0;
        }

        .r-mobile-menu{
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

        .r-topbar-title strong{
            display:block;
            font-size:17px;
            font-weight:900;
            line-height:1.2;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .r-topbar-title span{
            display:block;
            font-size:12px;
            color:rgba(255,255,255,.72);
            margin-top:3px;
        }

        .r-topbar-right{
            display:flex;
            align-items:center;
            gap:12px;
            flex-shrink:0;
        }

        .r-user{
            display:flex;
            align-items:center;
            gap:10px;
            padding:7px 10px;
            border-radius:999px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.10);
        }

        .r-user-avatar{
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

        .r-user-name{
            font-size:13px;
            font-weight:900;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            max-width:150px;
        }

        .r-user-role{
            font-size:11px;
            color:rgba(255,255,255,.68);
            margin-top:2px;
            text-transform:capitalize;
        }

        .r-logout-btn{
            min-height:42px;
            padding:0 14px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background:rgba(255,255,255,.08);
            color:#fff;
            font-weight:800;
            cursor:pointer;
        }

        .r-content{
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

        .r-overlay{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(15,23,42,.48);
            z-index:40;
        }

        @media (max-width:1100px){
            .r-shell{grid-template-columns:1fr}
            .r-sidebar{
                position:fixed;
                left:0;
                top:0;
                transform:translateX(-100%);
                transition:transform .22s ease;
                width:var(--sidebar-w);
            }

            body.sidebar-open .r-sidebar{transform:translateX(0)}
            body.sidebar-open .r-overlay{display:block}
            .r-mobile-menu{display:inline-flex}
            .r-content{padding:22px}
        }

        @media (max-width:760px){
            .r-topbar{padding:12px 16px}
            .r-user-copy{display:none}
            .r-logout-btn{padding:0 11px}
            .r-content{padding:16px}
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
    $userName = $user?->name ?? session('user_name', 'Reviewer');
    $userRole = $user?->role ?? session('user_role', 'reviewer');

    $initials = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');

    $initials = $initials ?: 'R';

    $isDashboardActive = request()->routeIs('reviewer.dashboard');
    $isNewsActive = request()->routeIs('reviewer.news.*');
    $isTjslActive = request()->routeIs('reviewer.tjsl.*');
@endphp

<div class="r-overlay" id="reviewerOverlay"></div>

<div class="r-shell">
    <aside class="r-sidebar" id="reviewerSidebar">
        <div class="r-brand">
            <div class="r-brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
            </div>
            <div>
                <p class="r-brand-title">Reviewer</p>
                <p class="r-brand-subtitle">BSP Zapin Panel</p>
            </div>
        </div>

        <div class="r-role-badge">
            <span class="r-role-dot"></span>
            <span>Reviewer Area</span>
        </div>

        <div class="r-nav-group">
            <span class="r-nav-label">Menu Utama</span>

            <nav class="r-nav">
                <a href="{{ route('reviewer.dashboard') }}" class="r-nav-item {{ $isDashboardActive ? 'active' : '' }}">
                    <span class="r-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 13h8V3H3v10Z"/>
                            <path d="M13 21h8V11h-8v10Z"/>
                            <path d="M13 3h8v6h-8V3Z"/>
                            <path d="M3 21h8v-6H3v6Z"/>
                        </svg>
                    </span>
                    <span class="r-nav-text">Dashboard</span>
                </a>

                <a href="{{ route('reviewer.news.index') }}" class="r-nav-item {{ $isNewsActive ? 'active' : '' }}">
                    <span class="r-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            <path d="M8 7h8"/>
                            <path d="M8 11h8"/>
                            <path d="M8 15h5"/>
                        </svg>
                    </span>
                    <span class="r-nav-text">Review News</span>
                </a>

                <a href="{{ route('reviewer.tjsl.index') }}" class="r-nav-item {{ $isTjslActive ? 'active' : '' }}">
                    <span class="r-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                            <path d="M9 12l2 2 4-5"/>
                        </svg>
                    </span>
                    <span class="r-nav-text">Review TJSL</span>
                </a>
            </nav>
        </div>

        <div class="r-nav-group">
            <span class="r-nav-label">Website</span>

            <nav class="r-nav">
                <a href="{{ route('web.home', ['locale' => 'id']) }}" target="_blank" class="r-nav-item">
                    <span class="r-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                    </span>
                    <span class="r-nav-text">Lihat Website</span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="r-main">
        <header class="r-topbar">
            <div class="r-topbar-left">
                <button type="button" class="r-mobile-menu" id="reviewerMenuBtn" aria-label="Buka Menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                        <path d="M4 6h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 18h16"/>
                    </svg>
                </button>

                <div class="r-topbar-title">
                    <strong>@yield('title', 'Reviewer Panel')</strong>
                    <span>Validasi dan publikasi konten website BSP Zapin</span>
                </div>
            </div>

            <div class="r-topbar-right">
                <div class="r-user">
                    <div class="r-user-avatar">{{ strtoupper($initials) }}</div>
                    <div class="r-user-copy">
                        <div class="r-user-name">{{ $userName }}</div>
                        <div class="r-user-role">{{ $userRole }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="r-logout-btn">Logout</button>
                </form>
            </div>
        </header>

        <section class="r-content">
            @yield('content')
        </section>
    </main>
</div>

<script>
    const reviewerMenuBtn = document.getElementById('reviewerMenuBtn');
    const reviewerOverlay = document.getElementById('reviewerOverlay');

    if (reviewerMenuBtn) {
        reviewerMenuBtn.addEventListener('click', function () {
            document.body.classList.add('sidebar-open');
        });
    }

    if (reviewerOverlay) {
        reviewerOverlay.addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    }
</script>

@stack('scripts')
</body>
</html>