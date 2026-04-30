<!doctype html>
<html lang="{{ in_array(request()->segment(1), ['id','en']) ? request()->segment(1) : 'id' }}">
<head>
    @php
        $locale = in_array(request()->segment(1), ['id','en']) ? request()->segment(1) : 'id';
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $locale === 'id' ? 'Website Sedang Maintenance' : 'Website Under Maintenance' }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --g900: #173f08;
            --g800: #1e5210;
            --g500: #2f7d32;
            --g200: #c8e6c9;
            --g100: #eef5eb;
            --g50:  #f4f9f2;
            --gold: #9a6f0a;
            --gold-lt: #d4a843;
            --text: #111827;
            --text2: #374151;
            --text3: #6b7280;
            --line: #e5e7eb;
            --white: #ffffff;
            --font: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif;
            --ease: cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: var(--font);
            color: var(--text);
            background:
                radial-gradient(circle at 15% 20%, rgba(47,125,50,.10) 0%, transparent 38%),
                radial-gradient(circle at 85% 15%, rgba(212,168,67,.13) 0%, transparent 32%),
                radial-gradient(circle at 72% 88%, rgba(23,63,8,.09) 0%, transparent 40%),
                linear-gradient(135deg, #f0f7ee 0%, #e8f4e8 50%, #f5f0e8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 28px;
        }

        /* ── Background orbs – subtle drift only ── */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
        }
        .orb-1 {
            width: 260px; height: 260px;
            left: -60px; top: -60px;
            background: radial-gradient(circle, rgba(47,125,50,.10) 0%, transparent 70%);
            animation: drift-a 10s ease-in-out infinite;
        }
        .orb-2 {
            width: 200px; height: 200px;
            right: -40px; top: 10%;
            background: radial-gradient(circle, rgba(212,168,67,.13) 0%, transparent 70%);
            animation: drift-b 13s ease-in-out infinite;
        }
        .orb-3 {
            width: 280px; height: 280px;
            right: -70px; bottom: -60px;
            background: radial-gradient(circle, rgba(23,63,8,.08) 0%, transparent 70%);
            animation: drift-a 16s ease-in-out infinite reverse;
        }
        @keyframes drift-a {
            0%, 100% { transform: translate(0, 0); }
            50%       { transform: translate(16px, -20px); }
        }
        @keyframes drift-b {
            0%, 100% { transform: translate(0, 0); }
            50%       { transform: translate(-14px, 18px); }
        }

        /* ── Card ── */
        .maintenance-card {
            position: relative;
            width: min(540px, 100%);
            text-align: center;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(16px) saturate(1.6);
            -webkit-backdrop-filter: blur(16px) saturate(1.6);
            border: 1px solid rgba(200,230,201,.80);
            border-radius: 28px;
            padding: 44px 40px 36px;
            overflow: hidden;
            animation: card-in .6s var(--ease) both;
        }
        @keyframes card-in {
            from { opacity: 0; transform: translateY(20px) scale(.97); }
            to   { opacity: 1; transform: translateY(0)   scale(1); }
        }

        /* Animated top bar */
        .maintenance-card::before {
            content: '';
            position: absolute;
            inset: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--g500), var(--gold-lt), var(--g500));
            background-size: 300% 100%;
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer {
            0%   { background-position:  100% 0; }
            100% { background-position: -200% 0; }
        }

        /* ── Spinner rings ── */
        .rings {
            position: relative;
            width: 108px;
            height: 108px;
            margin: 0 auto 22px;
        }
        .ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2.5px solid transparent;
        }
        .ring-1 {
            border-top-color: var(--g500);
            animation: spin-cw 1.2s linear infinite;
        }
        .ring-2 {
            inset: 11px;
            border-bottom-color: var(--gold-lt);
            animation: spin-ccw 1.9s linear infinite;
        }
        .ring-3 {
            inset: 24px;
            border-top-color: rgba(47,125,50,.45);
            animation: spin-cw 2.8s linear infinite;
        }
        @keyframes spin-cw  { to { transform: rotate( 360deg); } }
        @keyframes spin-ccw { to { transform: rotate(-360deg); } }

        .logo {
            position: absolute;
            inset: 30px;
            width: calc(100% - 60px);
            height: calc(100% - 60px);
            object-fit: contain;
            border-radius: 50%;
            animation: breathe 3s ease-in-out infinite;
        }
        @keyframes breathe {
            0%, 100% { transform: scale(1);    opacity: .88; }
            50%       { transform: scale(1.08); opacity: 1;   }
        }

        /* ── Typography ── */
        .brand   { font-size: 13.5px; font-weight: 800; color: var(--gold);  letter-spacing: .015em; margin-bottom: 3px; }
        .tagline { font-size: 11.5px; color: var(--g500); font-style: italic; margin-bottom: 22px; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 13px;
            border-radius: 999px;
            background: var(--g100);
            color: var(--g900);
            font-size: 11.5px;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--g500);
            animation: badge-pulse 1.8s ease-in-out infinite;
        }
        @keyframes badge-pulse {
            0%,100% { transform: scale(1);   box-shadow: 0 0 0 0   rgba(47,125,50,.45); }
            50%     { transform: scale(1.25); box-shadow: 0 0 0 5px rgba(47,125,50,0);   }
        }

        h1 {
            font-size: clamp(24px, 4.5vw, 36px);
            line-height: 1.13;
            font-weight: 800;
            letter-spacing: -.035em;
            color: var(--g900);
            margin-bottom: 13px;
        }
        p.desc {
            color: var(--text3);
            font-size: 15px;
            line-height: 1.8;
            max-width: 420px;
            margin: 0 auto 26px;
        }

        /* ── Progress bar ── */
        .progress {
            width: 100%;
            height: 4px;
            background: #f3f4f6;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--g500), var(--gold-lt), var(--g500));
            background-size: 200% 100%;
            animation: prog 2s ease-in-out infinite;
        }
        @keyframes prog {
            0%   { width: 15%; transform: translateX(-30%);  background-position:  200% 0; }
            50%  { width: 70%; }
            100% { width: 15%; transform: translateX(640%);  background-position: -200% 0; }
        }

        /* ── Dots ── */
        .dots {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-bottom: 26px;
        }
        .dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            animation: dot-jump 1.3s ease-in-out infinite;
        }
        .dot:nth-child(1) { background: var(--g500); }
        .dot:nth-child(2) { background: var(--gold-lt); animation-delay: .2s; }
        .dot:nth-child(3) { background: var(--g500);    animation-delay: .4s; }
        @keyframes dot-jump {
            0%,70%,100% { transform: translateY(0)   scale(1);    opacity: .4; }
            35%          { transform: translateY(-6px) scale(1.3); opacity: 1;  }
        }

        /* ── Divider & contact section ── */
        .section-divider {
            border: none;
            border-top: 1px solid var(--line);
            margin-bottom: 20px;
        }

        .contact-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .mail-icon {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: var(--g100);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .mail-icon svg { display: block; }

        .contact-text { text-align: left; }
        .contact-label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--text3);
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .contact-link {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--g500);
            text-decoration: none;
            border-bottom: 1px dashed rgba(47,125,50,.4);
            transition: color .2s var(--ease), border-color .2s var(--ease);
        }
        .contact-link:hover {
            color: var(--g900);
            border-color: var(--g900);
        }

        /* ── Footer note ── */
        .footer-note {
            font-size: 11.5px;
            color: var(--text3);
        }
        .footer-note strong { color: var(--gold); font-weight: 800; }

        /* ── Responsive ── */
        @media (max-width: 560px) {
            body { padding: 16px; }
            .maintenance-card { border-radius: 22px; padding: 32px 22px 28px; }
            .rings { width: 88px; height: 88px; }
            .logo { inset: 24px; width: calc(100% - 48px); height: calc(100% - 48px); }
            h1 { font-size: 22px; }
            p.desc { font-size: 14px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

    <span class="bg-orb orb-1" aria-hidden="true"></span>
    <span class="bg-orb orb-2" aria-hidden="true"></span>
    <span class="bg-orb orb-3" aria-hidden="true"></span>

    <main class="maintenance-card" role="main">

        {{-- Spinner + Logo --}}
        <div class="rings" aria-hidden="true">
            <div class="ring ring-1"></div>
            <div class="ring ring-2"></div>
            <div class="ring ring-3"></div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo BSP Zapin" class="logo">
        </div>

        <div class="brand">PT Bumi Siak Pusako Zapin</div>
        <div class="tagline">the energy company</div>

        <div class="badge">
            <span class="badge-dot"></span>
            {{ $locale === 'id' ? 'Maintenance Mode' : 'Maintenance Mode' }}
        </div>

        <h1>
            {{ $locale === 'id'
                ? 'Website Sedang Dalam Pemeliharaan'
                : 'Website Under Maintenance' }}
        </h1>

        <p class="desc">
            {{ $locale === 'id'
                ? 'Kami sedang melakukan pembaruan sistem untuk memberikan layanan yang lebih baik. Silakan coba kembali beberapa saat lagi.'
                : 'We are currently updating our system to provide a better service. Please check back shortly.' }}
        </p>

        <div class="progress" aria-hidden="true">
            <div class="progress-fill"></div>
        </div>

        <div class="dots" aria-hidden="true">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>

        {{-- Support contact --}}
        <hr class="section-divider">

        <div class="contact-row">
            <div class="mail-icon" aria-hidden="true">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                     stroke="#2f7d32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="m2 7 10 7 10-7"/>
                </svg>
            </div>
            <div class="contact-text">
                <div class="contact-label">
                    {{ $locale === 'id' ? 'Butuh bantuan? Hubungi kami' : 'Need help? Contact us' }}
                </div>
                <a class="contact-link" href="mailto:support@bspzapin.co.id">
                    support@bspzapin.co.id
                </a>
            </div>
        </div>

        <div class="footer-note">
            <strong>BSP Zapin</strong> &mdash;
            {{ $locale === 'id'
                ? 'Perusahaan energi nasional yang berkomitmen pada keberlanjutan.'
                : 'A national energy company committed to sustainability.' }}
        </div>

    </main>

</body>
</html>