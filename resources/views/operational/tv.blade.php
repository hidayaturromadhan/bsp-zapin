<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operational Display BSPZ</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans (body/UI) + DM Sans (display/numbers) + Outfit (labels) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gas: #f0b429;
            --gas-dark: #b7791f;
            --gas-soft: #fff6d8;

            --green: #1e6e2e;
            --green-2: #2f9e44;
            --green-soft: #e7f6ea;

            --blue: #1a5fac;
            --blue-soft: #e8f0fb;

            --crude: #111827;
            --crude-2: #374151;
            --crude-soft: #f1f5f9;

            --ink: #0d1b2a;
            --ink-2: #2e3f52;
            --ink-3: #5f738a;
            --ink-4: #8ea4bb;

            --bg: #f7fbf8;
            --card: #ffffff;
            --card-2: rgba(255,255,255,.92);
            --line: rgba(13,27,42,.10);
            --line-soft: rgba(13,27,42,.06);
            --shadow: 0 10px 30px rgba(13,27,42,.08), 0 2px 8px rgba(13,27,42,.04);

            --hdr-h: 82px;
            --broadcast-h: 52px;
            --gap: 12px;
            --radius: 20px;

            /* ─── Typography ─── */
            --font-ui:      'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            --font-display: 'DM Sans', 'Plus Jakarta Sans', sans-serif;
            --font-label:   'Outfit', 'Plus Jakarta Sans', sans-serif;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: var(--font-ui);
            color: var(--ink);
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        body {
            position: relative;
            background:
                radial-gradient(circle at 12% 10%, rgba(47,158,68,.18), transparent 30%),
                radial-gradient(circle at 88% 18%, rgba(132,204,22,.14), transparent 28%),
                radial-gradient(circle at 75% 88%, rgba(30,110,46,.12), transparent 30%),
                linear-gradient(135deg, #ffffff 0%, #f8fff9 42%, #eef9f0 100%);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(30,110,46,.045) 1px, transparent 1px),
                linear-gradient(180deg, rgba(30,110,46,.035) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: .65;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.85), transparent 36%),
                linear-gradient(180deg, rgba(255,255,255,.72), rgba(255,255,255,.28));
        }

        .page {
            position: relative;
            z-index: 1;
            width: 100vw;
            height: 100vh;
            padding: 12px;
            display: grid;
            grid-template-rows: var(--hdr-h) 1fr var(--broadcast-h);
            gap: var(--gap);
        }

        /* ═══════════════════════════════
           HEADER
        ═══════════════════════════════ */
        .hdr {
            display: grid;
            grid-template-columns: 280px 1fr auto 220px;
            align-items: center;
            gap: 14px;
            padding: 0 20px;
            border-radius: 22px;
            background: rgba(255,255,255,.94);
            border: 1px solid rgba(30,110,46,.11);
            box-shadow: var(--shadow);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff, #f7faf8);
            border: 1px solid rgba(30,110,46,.10);
            box-shadow: 0 3px 10px rgba(13,27,42,.07);
        }

        .brand-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .brand-name {
            font-family: var(--font-display);
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.025em;
            color: var(--green);
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-tag {
            margin-top: 4px;
            font-family: var(--font-label);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gas-dark);
        }

        /* ═══════════════════════════════
           PRAYER STRIP
        ═══════════════════════════════ */
        .prayer-strip {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 0;
        }

        .pt {
            min-width: 68px;
            padding: 7px 10px;
            border-radius: 12px;
            background: linear-gradient(180deg, #ffffff, #f7fbf8);
            border: 1px solid rgba(30,110,46,.10);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all .25s ease;
        }

        .pt-name {
            font-family: var(--font-label);
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: var(--ink-3);
        }

        .pt-val {
            margin-top: 3px;
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 700;
            color: var(--ink-2);
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .pt.active {
            background: linear-gradient(180deg, #fff8df, #fff0bf);
            border-color: rgba(240,180,41,.44);
            box-shadow: 0 0 0 1.5px rgba(240,180,41,.20), 0 8px 18px rgba(240,180,41,.12);
            transform: translateY(-1px);
        }

        .pt.active .pt-name,
        .pt.active .pt-val {
            color: var(--gas-dark);
        }

        /* ═══════════════════════════════
           WEATHER STRIP
        ═══════════════════════════════ */
        .weather-strip {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 0 2px;
            min-width: 0;
        }

        /* Kiri: suhu besar + deskripsi */
        .weather-main {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1px;
        }

        .weather-current {
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1;
        }

        .weather-icon {
            font-size: 26px;
            line-height: 1;
        }

        .weather-temp {
            font-family: var(--font-display);
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -.04em;
            color: var(--ink);
            font-variant-numeric: tabular-nums;
            line-height: 1;
        }

        .weather-desc {
            font-family: var(--font-label);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: var(--ink-3);
            text-align: right;
            white-space: nowrap;
        }

        /* Kanan: 3 prediksi jam */
        .weather-forecast {
            display: flex;
            align-items: stretch;
            gap: 5px;
        }

        .wf-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            padding: 5px 9px;
            border-radius: 10px;
            background: linear-gradient(180deg, rgba(30,110,46,.07), rgba(30,110,46,.03));
            border: 1px solid rgba(30,110,46,.11);
            min-width: 42px;
        }

        .wf-label {
            font-family: var(--font-label);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--ink-4);
            line-height: 1;
        }

        .wf-icon {
            font-size: 16px;
            line-height: 1.1;
        }

        .wf-temp {
            font-family: var(--font-display);
            font-size: 11.5px;
            font-weight: 700;
            color: var(--ink-2);
            font-variant-numeric: tabular-nums;
            line-height: 1;
        }

        /* ═══════════════════════════════
           HEADER RIGHT — CLOCK
           Format: HH:MM (tanpa detik)
        ═══════════════════════════════ */
        .hdr-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }

        .clock {
            font-family: var(--font-display);
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -.04em;
            color: var(--green);
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        /* Blinking colon */
        .clock-colon {
            display: inline-block;
            animation: blink-colon 1s step-start infinite;
        }

        @keyframes blink-colon {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.15; }
        }

        .date-row {
            font-family: var(--font-ui);
            font-size: 11px;
            font-weight: 600;
            color: var(--ink-2);
            letter-spacing: .01em;
        }

        /* ═══════════════════════════════
           BODY GRID
        ═══════════════════════════════ */
        .body {
            display: grid;
            grid-template-rows: 1fr 1fr;
            gap: var(--gap);
            min-height: 0;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1.18fr 1fr;
            gap: var(--gap);
            min-height: 0;
        }

        .card {
            background: rgba(255,255,255,.94);
            border: 1px solid rgba(30,110,46,.10);
            border-radius: 22px;
            box-shadow: var(--shadow);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 0;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at top right, rgba(47,158,68,.055), transparent 32%),
                linear-gradient(180deg, rgba(255,255,255,.22), transparent 26%);
            opacity: .95;
        }

        .card-head {
            flex-shrink: 0;
            padding: 14px 18px 0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .card-title {
            font-family: var(--font-ui);
            font-size: 13.5px;
            font-weight: 800;
            letter-spacing: -.015em;
            color: var(--ink);
            line-height: 1.3;
        }

        .card-sub {
            margin-top: 3px;
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 500;
            color: var(--ink-4);
            letter-spacing: .01em;
        }

        /* ═══════════════════════════════
           BADGES
        ═══════════════════════════════ */
        .badge {
            flex-shrink: 0;
            font-family: var(--font-label);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .b-gas,
        .b-gas-month,
        .b-yr {
            background: var(--gas-soft);
            color: var(--gas-dark);
            border: 1px solid rgba(240,180,41,.22);
        }

        .b-crude {
            background: var(--crude-soft);
            color: var(--crude);
            border: 1px solid rgba(17,24,39,.14);
        }

        .b-vitol {
            background: var(--blue-soft);
            color: var(--blue);
            border: 1px solid rgba(26,95,172,.16);
        }

        .chart-wrap {
            position: relative;
            flex: 1 1 auto;
            min-height: 0;
            padding: 10px 14px 14px;
            z-index: 2;
            contain: layout paint;
        }

        .chart-wrap canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* Crude legend inline */
        .crude-legend {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 18px 6px;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        .crude-legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-family: var(--font-label);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: var(--ink-3);
        }

        .crude-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .crude-legend-dot.vacuum {
            background: linear-gradient(135deg, #111827, #374151);
        }

        .crude-legend-dot.road {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
        }

        /* ═══════════════════════════════
           VIDEO CARD
        ═══════════════════════════════ */
        .video-card {
            background: #07111e;
            padding: 0;
            position: relative;
            border: 1px solid rgba(17,24,39,.18);
            contain: layout paint;
        }

        .video-card video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(1.02) contrast(1.01);
            background: #07111e;
        }

        .video-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(5,10,18,.02), rgba(5,10,18,.15)),
                radial-gradient(circle at bottom right, rgba(47,158,68,.18), transparent 28%);
            pointer-events: none;
        }

        .video-topbar {
            position: absolute;
            top: 14px;
            left: 14px;
            right: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 3;
        }

        .live-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 11px;
            border-radius: 999px;
            background: rgba(255,255,255,.20);
            border: 1px solid rgba(255,255,255,.22);
            color: #fff;
            font-family: var(--font-label);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ff4d4f;
            box-shadow: 0 0 0 0 rgba(255,77,79,.55);
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(255,77,79,.55); }
            70%  { box-shadow: 0 0 0 10px rgba(255,77,79,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,77,79,0); }
        }

        .video-pill {
            position: absolute;
            right: 16px;
            bottom: 14px;
            z-index: 3;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.22);
            color: #fff;
            font-family: var(--font-label);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 999px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        /* ═══════════════════════════════
           BROADCAST BAR
        ═══════════════════════════════ */
        .broadcast-bar {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(13,27,42,.92), rgba(13,27,42,.86));
            border: 1px solid rgba(255,255,255,.14);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            min-height: 0;
        }

        .broadcast-label {
            position: relative;
            z-index: 2;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-width: 165px;
            padding: 0 18px;
            font-family: var(--font-label);
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: #fff;
            background: linear-gradient(135deg, var(--green), var(--green-2));
            box-shadow: inset -1px 0 0 rgba(255,255,255,.18);
        }

        .broadcast-track {
            position: relative;
            flex: 1 1 auto;
            height: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .broadcast-content {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            will-change: transform;
            animation: broadcast-marquee 45s linear infinite;
            padding-left: 100%;
        }

        .broadcast-item {
            display: inline-flex;
            align-items: center;
            font-family: var(--font-ui);
            font-size: 14.5px;
            font-weight: 600;
            color: #f0f6ff;
            letter-spacing: .005em;
        }

        .broadcast-item .tag {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(240,180,41,.16);
            border: 1px solid rgba(240,180,41,.25);
            color: #ffd979;
            font-family: var(--font-label);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .broadcast-sep {
            display: inline-block;
            margin: 0 18px;
            color: rgba(255,255,255,.40);
            font-weight: 700;
        }

        .broadcast-empty {
            padding: 0 18px;
            font-family: var(--font-ui);
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,.65);
        }

        @keyframes broadcast-marquee {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* ═══════════════════════════════
           REMINDER ALERT
        ═══════════════════════════════ */
        #reminderTitle {
            font-family: var(--font-display) !important;
            font-weight: 700 !important;
            font-size: 15.5px !important;
            letter-spacing: -.015em !important;
            color: #111827 !important;
        }

        #reminderText {
            font-family: var(--font-ui) !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #4b5563 !important;
            line-height: 1.55 !important;
        }

        /* ═══════════════════════════════
           RESPONSIVE
        ═══════════════════════════════ */
        @media (max-width: 1366px) {
            .page {
                grid-template-rows: 78px 1fr 48px;
                padding: 10px;
            }

            .hdr {
                grid-template-columns: 250px 1fr auto 190px;
                padding: 0 16px;
            }

            .brand-name { font-size: 15px; }

            .clock { font-size: 30px; }

            .pt {
                min-width: 60px;
                padding: 6px 8px;
            }

            .pt-val { font-size: 12px; }

            .weather-temp { font-size: 22px; }
            .weather-icon { font-size: 20px; }
            .wf-icon { font-size: 13px; }
            .wf-item { min-width: 36px; padding: 4px 7px; }
            .wf-temp { font-size: 10px; }

            .broadcast-label {
                min-width: 145px;
                font-size: 9.5px;
            }

            .broadcast-item {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
<div class="page">
    <header class="hdr">
        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Logo">
            </div>

            <div>
                <div class="brand-name">PT Bumi Siak Pusako Zapin</div>
                <div class="brand-tag">the energy company</div>
            </div>
        </div>

        <div class="prayer-strip" id="prayerStrip">
            <div class="pt" id="pt-shubuh"><span class="pt-name">Shubuh</span><span class="pt-val" id="pv-shubuh">--:--</span></div>
            <div class="pt" id="pt-dhuha"><span class="pt-name">Dhuha</span><span class="pt-val" id="pv-dhuha">--:--</span></div>
            <div class="pt" id="pt-dzuhur"><span class="pt-name">Dzuhur</span><span class="pt-val" id="pv-dzuhur">--:--</span></div>
            <div class="pt" id="pt-ashar"><span class="pt-name">Ashar</span><span class="pt-val" id="pv-ashar">--:--</span></div>
            <div class="pt" id="pt-maghrib"><span class="pt-name">Maghrib</span><span class="pt-val" id="pv-maghrib">--:--</span></div>
            <div class="pt" id="pt-isya"><span class="pt-name">Isya</span><span class="pt-val" id="pv-isya">--:--</span></div>
        </div>

        {{-- ─── WEATHER ─── --}}
        <div class="weather-strip" id="weatherStrip">
            {{-- Suhu & kondisi saat ini --}}
            <div class="weather-main">
                <div class="weather-current">
                    <span class="weather-icon" id="wIcon">🌤️</span>
                    <span class="weather-temp" id="wTemp">--°</span>
                </div>
                <div class="weather-desc" id="wDesc">Memuat cuaca...</div>
            </div>

            {{-- Prediksi 3 jam ke depan --}}
            <div class="weather-forecast" id="wForecast">
                <div class="wf-item">
                    <span class="wf-label" id="wf0-label">--:--</span>
                    <span class="wf-icon" id="wf0-icon">🌤️</span>
                    <span class="wf-temp" id="wf0-temp">--°</span>
                </div>
                <div class="wf-item">
                    <span class="wf-label" id="wf1-label">--:--</span>
                    <span class="wf-icon" id="wf1-icon">🌤️</span>
                    <span class="wf-temp" id="wf1-temp">--°</span>
                </div>
                <div class="wf-item">
                    <span class="wf-label" id="wf2-label">--:--</span>
                    <span class="wf-icon" id="wf2-icon">🌤️</span>
                    <span class="wf-temp" id="wf2-temp">--°</span>
                </div>
            </div>
        </div>

        <div class="hdr-right">
            {{-- Clock: HH:MM format dengan blinking colon --}}
            <div class="clock" id="clockEl">--<span class="clock-colon">:</span>--</div>
            <div class="date-row" id="dateEl">--</div>
        </div>
    </header>

    <main class="body">
        <section class="row row-top">
            {{-- ─── CRUDE OIL ─── --}}
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">BSP Crude Oil Trucking with TBM</div>
                        <div class="card-sub">Vacuum Truck &amp; Road Tank 14 Hari Terakhir</div>
                    </div>
                    <span class="badge b-crude">Crude</span>
                </div>

                {{-- Custom legend (lebih kontrol daripada Chart.js built-in) --}}
                <div class="crude-legend">
                    <div class="crude-legend-item">
                        <span class="crude-legend-dot vacuum"></span>
                        Vacuum Truck
                    </div>
                    <div class="crude-legend-item">
                        <span class="crude-legend-dot road"></span>
                        Road Tank
                    </div>
                </div>

                <div class="chart-wrap">
                    <canvas id="cCrudeDaily" role="img" aria-label="Crude Daily chart"></canvas>
                </div>
            </div>

            {{-- ─── VIDEO ─── --}}
            <div class="card video-card">
                <div class="video-topbar">
                    <div class="live-pill">
                        <span class="live-dot"></span>
                        Operational Monitoring
                    </div>
                </div>

                <video
                    id="companyVideo"
                    autoplay
                    muted
                    loop
                    playsinline
                    preload="auto"
                    controlslist="nodownload noplaybackrate noremoteplayback"
                    disablepictureinpicture
                >
                    <source src="{{ asset('videos/company-profile.mp4') }}" type="video/mp4">
                </video>

                <div class="video-pill">{{ $monthLabel ?? now()->translatedFormat('F Y') }}</div>
            </div>

            {{-- ─── VITOL ─── --}}
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">OIL TRADING WITH VITOL TO BPC</div>
                    </div>
                    <span class="badge b-vitol">VITOL</span>
                </div>

                <div class="chart-wrap">
                    <canvas id="cVitol" role="img" aria-label="VITOL Monthly chart"></canvas>
                </div>
            </div>
        </section>

        <section class="row row-bottom">
            {{-- ─── GAS DAILY ─── --}}
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">Distribution Gas With Pertagas to BSP</div>
                        <div class="card-sub">Trend Penyaluran Gas Harian</div>
                    </div>
                    <span class="badge b-gas">Gas Daily</span>
                </div>

                <div class="chart-wrap">
                    <canvas id="cGasDaily" role="img" aria-label="Flow Gas Daily chart"></canvas>
                </div>
            </div>

            {{-- ─── GAS MONTHLY ─── --}}
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">Distribution Gas With Pertagas to BSP</div>
                        <div class="card-sub">Rata-rata Flow Gas per Bulan</div>
                    </div>
                    <span class="badge b-gas-month">Gas Monthly</span>
                </div>

                <div class="chart-wrap">
                    <canvas id="cGasMonthly" role="img" aria-label="Flow Gas Monthly chart"></canvas>
                </div>
            </div>

            {{-- ─── GAS YEARLY ─── --}}
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">Distribution Gas With Pertagas to BSP</div>
                        <div class="card-sub">Trend Penyaluran Gas Tahunan</div>
                    </div>
                    <span class="badge b-yr">Gas Yearly</span>
                </div>

                <div class="chart-wrap">
                    <canvas id="cGasYearly" role="img" aria-label="Gas Yearly chart"></canvas>
                </div>
            </div>
        </section>
    </main>

    <section class="broadcast-bar">
        <div class="broadcast-label">Broadcast</div>

        <div class="broadcast-track">
            @php
                $activeBroadcasts = collect($broadcastItems ?? [])->filter(function ($item) {
                    return !empty($item['enabled']) && !empty($item['message']);
                })->values();
            @endphp

            @if($activeBroadcasts->isNotEmpty())
                <div class="broadcast-content">
                    @foreach($activeBroadcasts as $item)
                        <div class="broadcast-item">
                            @if(!empty($item['label']))
                                <span class="tag">{{ $item['label'] }}</span>
                            @endif
                            <span>{{ $item['message'] }}</span>
                        </div>

                        @if(!$loop->last)
                            <span class="broadcast-sep">✦</span>
                        @endif
                    @endforeach

                    <span class="broadcast-sep">✦</span>

                    @foreach($activeBroadcasts as $item)
                        <div class="broadcast-item">
                            @if(!empty($item['label']))
                                <span class="tag">{{ $item['label'] }}</span>
                            @endif
                            <span>{{ $item['message'] }}</span>
                        </div>

                        @if(!$loop->last)
                            <span class="broadcast-sep">✦</span>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="broadcast-empty">Belum ada broadcast aktif.</div>
            @endif
        </div>
    </section>
</div>

{{-- ─── REMINDER ALERT ─── --}}
<div id="reminderAlert" style="
    position: fixed;
    top: 90px;
    right: 20px;
    z-index: 9999;
    width: 340px;
    max-width: calc(100vw - 30px);
    display: none;
">
    <div id="reminderBox" style="
        background: rgba(255,255,255,0.97);
        border-radius: 16px;
        padding: 16px 16px 14px 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.16);
        border: 1px solid rgba(30,110,46,0.12);
        transform: translateY(-20px);
        opacity: 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    ">
        <div id="reminderIcon" style="
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg,#fff2bd,#f0b429);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            box-shadow: 0 6px 16px rgba(240,180,41,0.28);
            font-size: 20px;
        ">⏰</div>

        <div id="reminderTitle"></div>
        <div id="reminderText"></div>

        <button onclick="hideReminder()" style="
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: transparent;
            font-size: 16px;
            cursor: pointer;
            color: #64748b;
        ">✕</button>

        <div id="reminderProgress" style="
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg,#1e6e2e,#f0b429);
            transform-origin: left;
        "></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

{{-- ═══════════════════════════════════════════════════════
     ① PAGE RELOAD — 6 JAM SEKALI
     ═══════════════════════════════════════════════════════ --}}
<script>
(function () {
    var SIX_HOURS_MS = 6 * 60 * 60 * 1000;

    var reloadTimer = setTimeout(function () {
        window.location.reload();
    }, SIX_HOURS_MS);

    if (!sessionStorage.getItem('tv_load_ts')) {
        sessionStorage.setItem('tv_load_ts', Date.now().toString());
    }

    window.addEventListener('visibilitychange', function () {
        if (document.hidden) { return; }

        var loadTs   = parseInt(sessionStorage.getItem('tv_load_ts') || '0', 10);
        var elapsed  = Date.now() - loadTs;

        if (elapsed >= SIX_HOURS_MS) {
            window.location.reload();
            return;
        }

        clearTimeout(reloadTimer);
        reloadTimer = setTimeout(function () {
            window.location.reload();
        }, SIX_HOURS_MS - elapsed);
    });
})();
</script>

{{-- ═══════════════════════════════════════════════════════
     ② LIVE-DATA POLLING — cek perubahan data setiap 5 menit
     ═══════════════════════════════════════════════════════ --}}
<script>
(function () {
    var POLL_INTERVAL_MS = 5 * 60 * 1000;
    var HASH_ENDPOINT    = '/operational/tv/data-hash';
    var lastHash         = null;

    function checkDataHash() {
        fetch(HASH_ENDPOINT, { cache: 'no-store' })
            .then(function (res) {
                if (!res.ok) { throw new Error('Non-2xx'); }
                return res.json();
            })
            .then(function (body) {
                var currentHash = body && body.hash ? String(body.hash) : null;
                if (!currentHash) { return; }
                if (lastHash === null) { lastHash = currentHash; return; }
                if (currentHash !== lastHash) { window.location.reload(); }
            })
            .catch(function () {});
    }

    checkDataHash();
    setInterval(checkDataHash, POLL_INTERVAL_MS);
})();
</script>

{{-- ═══════════════════════════════════════════════════════
     ③ VIDEO — robust autoplay & error recovery
     ═══════════════════════════════════════════════════════ --}}
<script>
(function () {
    var video = document.getElementById('companyVideo');
    if (!video) { return; }

    function tryPlay() {
        var p = video.play();
        if (p && typeof p.catch === 'function') {
            p.catch(function (err) { console.warn('Video autoplay blocked:', err); });
        }
    }

    video.addEventListener('loadedmetadata', tryPlay);

    video.addEventListener('stalled', function () { video.load(); tryPlay(); });

    video.addEventListener('error', function () {
        setTimeout(function () { video.load(); tryPlay(); }, 3000);
    });

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden && video.paused) { tryPlay(); }
    });

    tryPlay();
})();
</script>

{{-- ═══════════════════════════════════════════════════════
     ④ REMINDER ALERT (jam kerja)
     ═══════════════════════════════════════════════════════ --}}
<script>
(function () {
    var alertWrap = document.getElementById('reminderAlert');
    var box       = document.getElementById('reminderBox');
    var titleEl   = document.getElementById('reminderTitle');
    var textEl    = document.getElementById('reminderText');
    var progress  = document.getElementById('reminderProgress');
    var iconEl    = document.getElementById('reminderIcon');

    var schedule = {
        "08:00": { title: "Jam Masuk",    text: "Waktu kerja dimulai. Yuk mulai aktivitas hari ini dengan semangat!",         icon: "⏰" },
        "12:00": { title: "Jam Istirahat", text: "Saatnya recharge. Istirahat dulu ya sejenak untuk makan siang dan sholat.", icon: "⏰" },
        "16:30": { title: "Jam Pulang",   text: "Kerja selesai. Jangan lupa closing aktivitas hari ini ya.",                  icon: "⏰" }
    };

    function playAlarmSound() {
        try {
            var ctx  = new (window.AudioContext || window.webkitAudioContext)();
            var now  = ctx.currentTime;
            var last = now + 0.70 + 0.32;

            function beep(t, freq, dur) {
                var osc  = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.001, t);
                gain.gain.exponentialRampToValueAtTime(0.5,   t + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, t + dur);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(t);
                osc.stop(t + dur);
            }

            beep(now,        880, 0.28);
            beep(now + 0.35, 660, 0.28);
            beep(now + 0.70, 990, 0.32);

            setTimeout(function () { ctx.close().catch(function () {}); },
                Math.ceil((last + 0.3) * 1000));
        } catch (e) {}
    }

    function getWIB() {
        var parts = new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit', minute: '2-digit', hour12: false
        }).formatToParts(new Date());
        var h = '', m = '';
        parts.forEach(function (p) {
            if (p.type === 'hour')   h = p.value;
            if (p.type === 'minute') m = p.value;
        });
        return h + ':' + m;
    }

    function showReminder(title, text, key, icon) {
        playAlarmSound();
        titleEl.innerText = title;
        textEl.innerText  = text;
        if (iconEl) { iconEl.innerText = icon || '⏰'; }

        alertWrap.style.display = 'block';
        setTimeout(function () {
            box.style.transform = 'translateY(0)';
            box.style.opacity   = '1';
        }, 50);

        progress.style.transition = 'none';
        progress.style.transform  = 'scaleX(1)';
        setTimeout(function () {
            progress.style.transition = 'transform 8s linear';
            progress.style.transform  = 'scaleX(0)';
        }, 50);

        localStorage.setItem(key, 'shown');
        setTimeout(hideReminder, 8000);
    }

    window.hideReminder = function () {
        box.style.transform = 'translateY(-20px)';
        box.style.opacity   = '0';
        setTimeout(function () { alertWrap.style.display = 'none'; }, 300);
    };

    function checkReminder() {
        var time  = getWIB();
        var today = new Date().toISOString().slice(0, 10);
        var data  = schedule[time];
        if (!data) { return; }
        var key   = 'reminder-' + today + '-' + time;
        if (localStorage.getItem(key)) { return; }
        showReminder(data.title, data.text, key, data.icon);
    }

    window.__tvReminder = { showReminder: showReminder, getWIB: getWIB };

    setInterval(checkReminder, 1000);

    document.addEventListener('click', function () {
        if (window.AudioContext) { new AudioContext().resume(); }
    }, { once: true });
})();
</script>

{{-- ═══════════════════════════════════════════════════════
     ⑤ CLOCK (HH:MM) + DATE
     ═══════════════════════════════════════════════════════ --}}
<script>
var _dateFmt = new Intl.DateTimeFormat('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
});
var _cachedDateStr = '';
var _cachedDateDay = -1;

function tick() {
    var n      = new Date();
    var p      = function (v) { return String(v).padStart(2, '0'); };
    var clkEl  = document.getElementById('clockEl');
    var dateEl = document.getElementById('dateEl');

    if (clkEl) {
        clkEl.innerHTML = p(n.getHours()) +
            '<span class="clock-colon">:</span>' +
            p(n.getMinutes());
    }

    if (dateEl) {
        var d = n.getDate();
        if (d !== _cachedDateDay) {
            _cachedDateDay  = d;
            _cachedDateStr  = _dateFmt.format(n);
        }
        dateEl.textContent = _cachedDateStr;
    }
}

tick();
setInterval(tick, 1000);
</script>

{{-- ═══════════════════════════════════════════════════════
     ⑥ PRAYER TIMES
     ═══════════════════════════════════════════════════════ --}}
<script>
var ptimes        = {};
var prayerAlertMap = {};

var PKEYS = [
    { api: 'subuh',   id: 'shubuh',  label: 'Shubuh'  },
    { api: 'dhuha',   id: 'dhuha',   label: 'Dhuha'   },
    { api: 'dzuhur',  id: 'dzuhur',  label: 'Dzuhur'  },
    { api: 'ashar',   id: 'ashar',   label: 'Ashar'   },
    { api: 'maghrib', id: 'maghrib', label: 'Maghrib' },
    { api: 'isya',    id: 'isya',    label: 'Isya'    }
];

function getPekanbaruDateParts() {
    var parts = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Asia/Jakarta',
        year: 'numeric', month: '2-digit', day: '2-digit'
    }).formatToParts(new Date());
    var year = '', month = '', day = '';
    parts.forEach(function (p) {
        if (p.type === 'year')  year  = p.value;
        if (p.type === 'month') month = p.value;
        if (p.type === 'day')   day   = p.value;
    });
    return {
        year: year, month: month, day: day,
        ymd: year + '-' + month + '-' + day,
        dayNumber:   Number(day),
        monthNumber: Number(month),
        yearNumber:  Number(year)
    };
}

function fmtPrayer(s) {
    return s ? String(s).substring(0, 5) : '--:--';
}

function toMin(s) {
    if (!s || s === '--:--') { return -1; }
    var parts = s.split(':').map(Number);
    return parts[0] * 60 + parts[1];
}

function highlightActivePrayer() {
    var nowWIB = window.__tvReminder && window.__tvReminder.getWIB
        ? window.__tvReminder.getWIB()
        : '--:--';
    var cur    = toMin(nowWIB);
    var active = PKEYS[0].id;

    PKEYS.forEach(function (p) {
        var m = toMin(ptimes[p.id] || '--:--');
        if (m !== -1 && cur >= m) { active = p.id; }
    });

    PKEYS.forEach(function (p) {
        var el = document.getElementById('pt-' + p.id);
        if (el) { el.classList.toggle('active', p.id === active); }
    });
}

function buildPrayerAlertMap() {
    prayerAlertMap = {};
    PKEYS.forEach(function (p) {
        var val = ptimes[p.id] || '--:--';
        if (val !== '--:--') {
            prayerAlertMap[val] = { id: p.id, label: p.label, time: val };
        }
    });
}

function checkPrayerReminder() {
    var time   = window.__tvReminder && window.__tvReminder.getWIB
        ? window.__tvReminder.getWIB()
        : '--:--';
    var prayer = prayerAlertMap[time];
    if (!prayer) { return; }

    var ymd = getPekanbaruDateParts().ymd;
    var key = 'prayer-alert-' + ymd + '-' + prayer.id + '-' + prayer.time;
    if (localStorage.getItem(key)) { return; }

    window.__tvReminder.showReminder(
        'Waktu Sholat ' + prayer.label,
        'Saat ini telah masuk waktu sholat ' + prayer.label + ' untuk Kota Pekanbaru (' + prayer.time + ' WIB).',
        key,
        '🕌'
    );
}

function applyPrayerSchedule(jadwal) {
    PKEYS.forEach(function (p) {
        ptimes[p.id] = fmtPrayer(jadwal[p.api]);
        var el = document.getElementById('pv-' + p.id);
        if (el) { el.textContent = ptimes[p.id]; }
    });
    buildPrayerAlertMap();
    highlightActivePrayer();
    checkPrayerReminder();
}

function loadPrayerTimes() {
    var info = getPekanbaruDateParts();
    var key  = 'prayer-pekanbaru-' + info.year + '-' + info.month;
    var cached = localStorage.getItem(key);

    if (cached) {
        try {
            var parsed = JSON.parse(cached);
            var list   = parsed && parsed.jadwal ? parsed.jadwal : [];
            var today  = list.find(function (item) {
                return Number(item.tanggal) === info.dayNumber;
            });
            if (today) { applyPrayerSchedule(today); return; }
        } catch (e) { localStorage.removeItem(key); }
    }

    fetch('https://equran.id/api/v2/shalat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            provinsi: 'Riau',
            kabkota: 'Kota Pekanbaru',
            bulan: info.monthNumber,
            tahun: info.yearNumber
        })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var list  = data && data.data && data.data.jadwal ? data.data.jadwal : [];
        var today = list.find(function (item) {
            return Number(item.tanggal) === info.dayNumber;
        });
        if (!today) { throw new Error('Jadwal hari ini tidak ditemukan'); }
        localStorage.setItem(key, JSON.stringify({ saved_at: Date.now(), jadwal: list }));
        applyPrayerSchedule(today);
    })
    .catch(function (err) { console.warn('Prayer time error:', err); });
}

loadPrayerTimes();
setInterval(highlightActivePrayer, 30000);
setInterval(checkPrayerReminder, 1000);

var _jakartaTimeFmt = new Intl.DateTimeFormat('en-GB', {
    timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false
});

setInterval(function () {
    var parts = _jakartaTimeFmt.formatToParts(new Date());
    var h = '', m = '';
    parts.forEach(function (p) {
        if (p.type === 'hour')   h = p.value;
        if (p.type === 'minute') m = p.value;
    });
    if (h === '00' && Number(m) < 2) { loadPrayerTimes(); }
}, 60000);
</script>

{{-- ═══════════════════════════════════════════════════════
     ⑥b WEATHER — Open-Meteo, Pekanbaru (0.5070°N, 101.4478°E)
         Refresh setiap 30 menit. Tanpa API key.
     ═══════════════════════════════════════════════════════ --}}
<script>
(function () {
    /* Koordinat Kota Pekanbaru */
    var LAT = 0.5070;
    var LON = 101.4478;

    /* WMO Weather Interpretation Codes → emoji + deskripsi singkat */
    var WMO_MAP = {
        0:  { icon: '☀️',  desc: 'Cerah'             },
        1:  { icon: '🌤️', desc: 'Sebagian Berawan'   },
        2:  { icon: '⛅',  desc: 'Berawan'             },
        3:  { icon: '☁️',  desc: 'Mendung'            },
        45: { icon: '🌫️', desc: 'Berkabut'            },
        48: { icon: '🌫️', desc: 'Berkabut Beku'       },
        51: { icon: '🌦️', desc: 'Gerimis Ringan'      },
        53: { icon: '🌦️', desc: 'Gerimis'             },
        55: { icon: '🌧️', desc: 'Gerimis Lebat'       },
        61: { icon: '🌧️', desc: 'Hujan Ringan'        },
        63: { icon: '🌧️', desc: 'Hujan'               },
        65: { icon: '🌧️', desc: 'Hujan Lebat'         },
        71: { icon: '🌨️', desc: 'Salju Ringan'        },
        73: { icon: '🌨️', desc: 'Salju'               },
        75: { icon: '🌨️', desc: 'Salju Lebat'         },
        77: { icon: '🌨️', desc: 'Butiran Salju'       },
        80: { icon: '🌦️', desc: 'Hujan Lokal Ringan'  },
        81: { icon: '🌧️', desc: 'Hujan Lokal'         },
        82: { icon: '⛈️',  desc: 'Hujan Lokal Lebat'  },
        85: { icon: '🌨️', desc: 'Salju Lokal'         },
        86: { icon: '🌨️', desc: 'Salju Lokal Lebat'   },
        95: { icon: '⛈️',  desc: 'Badai Petir'         },
        96: { icon: '⛈️',  desc: 'Petir + Hujan Es'    },
        99: { icon: '⛈️',  desc: 'Petir + Hujan Es'    }
    };

    function wmoInfo(code) {
        return WMO_MAP[code] || { icon: '🌡️', desc: 'Cuaca Tidak Diketahui' };
    }

    function roundTemp(t) {
        return Math.round(Number(t));
    }

    /* Cari index jam terdekat dalam array hourly.time (ISO strings) */
    function nearestHourIndex(times, offsetHours) {
        var now = new Date();
        var target = new Date(now.getTime() + offsetHours * 3600000);
        /* Bulatkan ke jam terdekat */
        target.setMinutes(0, 0, 0);
        var targetISO = target.toISOString().slice(0, 13); /* "YYYY-MM-DDTHH" */
        for (var i = 0; i < times.length; i++) {
            if (times[i].slice(0, 13) === targetISO) { return i; }
        }
        /* Fallback: cari paling dekat */
        var best = 0;
        var bestDiff = Infinity;
        var targetMs = target.getTime();
        for (var j = 0; j < times.length; j++) {
            var diff = Math.abs(new Date(times[j]).getTime() - targetMs);
            if (diff < bestDiff) { bestDiff = diff; best = j; }
        }
        return best;
    }

    function applyWeather(data) {
        var current = data.current;
        var hourly  = data.hourly;

        /* ── Kondisi saat ini ── */
        var nowTemp = roundTemp(current.temperature_2m);
        var nowCode = current.weather_code;
        var nowInfo = wmoInfo(nowCode);

        var wIcon = document.getElementById('wIcon');
        var wTemp = document.getElementById('wTemp');
        var wDesc = document.getElementById('wDesc');

        if (wIcon) { wIcon.textContent = nowInfo.icon; }
        if (wTemp) { wTemp.textContent = nowTemp + '°'; }
        if (wDesc) { wDesc.textContent = nowInfo.desc + ' · Pekanbaru'; }

        /* ── Prediksi +3j, +6j, +9j — label pakai jam aktual WIB ── */
        var offsets = [3, 6, 9];
        offsets.forEach(function (offset, idx) {
            var i    = nearestHourIndex(hourly.time, offset);
            var temp = roundTemp(hourly.temperature_2m[i]);
            var code = hourly.weather_code[i];
            var info = wmoInfo(code);

            /* Hitung jam aktual target dalam zona WIB */
            var targetDate = new Date(new Date().getTime() + offset * 3600000);
            targetDate.setMinutes(0, 0, 0);
            var targetParts = new Intl.DateTimeFormat('en-GB', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit', minute: '2-digit', hour12: false
            }).formatToParts(targetDate);
            var tH = '', tM = '';
            targetParts.forEach(function (p) {
                if (p.type === 'hour')   tH = p.value;
                if (p.type === 'minute') tM = p.value;
            });
            var labelTime = tH + ':' + tM; /* mis. "15:00" */

            var labelEl = document.getElementById('wf' + idx + '-label');
            var iconEl  = document.getElementById('wf' + idx + '-icon');
            var tempEl  = document.getElementById('wf' + idx + '-temp');
            if (labelEl) { labelEl.textContent = labelTime; }
            if (iconEl)  { iconEl.textContent  = info.icon; }
            if (tempEl)  { tempEl.textContent  = temp + '°'; }
        });
    }

    function fetchWeather() {
        var url = 'https://api.open-meteo.com/v1/forecast'
            + '?latitude='  + LAT
            + '&longitude=' + LON
            + '&current=temperature_2m,weather_code'
            + '&hourly=temperature_2m,weather_code'
            + '&timezone=Asia%2FJakarta'
            + '&forecast_days=2';

        fetch(url, { cache: 'no-store' })
            .then(function (res) {
                if (!res.ok) { throw new Error('Weather API non-2xx'); }
                return res.json();
            })
            .then(function (data) {
                applyWeather(data);
            })
            .catch(function (err) {
                console.warn('Weather fetch error:', err);
                var wDesc = document.getElementById('wDesc');
                if (wDesc) { wDesc.textContent = 'Data cuaca tidak tersedia'; }
            });
    }

    /* Muat pertama kali, lalu refresh tiap 30 menit */
    fetchWeather();
    setInterval(fetchWeather, 30 * 60 * 1000);
})();
</script>

{{-- ═══════════════════════════════════════════════════════
     ⑦ CHARTS — Chart.js dengan gradient lebih kaya
        Mekanisme Chart.js tidak berubah sama sekali.
     ═══════════════════════════════════════════════════════ --}}
<script>
/* ─── Global defaults ─── */
Chart.defaults.font.family = "'DM Sans', 'Plus Jakarta Sans', sans-serif";

var gridC = 'rgba(13,27,42,.07)';
var tickC = '#8ea4bb';

/* ─── Warna dasar ─── */
var COLOR_GAS              = '#f0b429';
var COLOR_GAS_DARK         = '#b7791f';
var COLOR_CRUDE            = '#111827';
var COLOR_CRUDE_DARK       = '#030712';
var COLOR_CRUDE_ROAD_TANK  = '#9ca3af';
var COLOR_CRUDE_RT_BORDER  = '#6b7280';
var COLOR_VITOL            = '#1a5fac';
var COLOR_VITOL_DARK       = '#0a357f';

/* ─── baseOpts ─── */
function baseOpts(extraX) {
    return {
        maintainAspectRatio: false,
        responsive: true,
        animation: false,
        resizeDelay: 500,
        devicePixelRatio: Math.min(window.devicePixelRatio || 1, 1.4),
        layout: { padding: { top: 12, right: 16, bottom: 2, left: 2 } },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(13,27,42,.92)',
                titleColor: '#ffffff',
                bodyColor: '#d2dfeb',
                padding: 10,
                cornerRadius: 10,
                displayColors: false,
                titleFont: { family: "'DM Sans', sans-serif",          weight: '600', size: 12 },
                bodyFont:  { family: "'Plus Jakarta Sans', sans-serif", weight: '500', size: 11 }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: tickC,
                    font: { family: "'DM Sans', sans-serif", size: 9.5, weight: '600' },
                    maxRotation: 0,
                    autoSkip: true,
                    maxTicksLimit: 12
                },
                grid: { color: gridC, lineWidth: 1 },
                border: { color: 'rgba(13,27,42,.10)', width: 1 }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: tickC,
                    font: { family: "'DM Sans', sans-serif", size: 9.5, weight: '600' },
                    padding: 4,
                    callback: function (v) {
                        return v >= 1000 ? (v / 1000).toFixed(1) + 'k' : v;
                    }
                },
                grid: {
                    color: function (ctx) {
                        return ctx.index === 0 ? 'rgba(13,27,42,.13)' : gridC;
                    },
                    lineWidth: function (ctx) { return ctx.index === 0 ? 1.4 : 1; },
                    drawTicks: false
                },
                border: { dash: [4, 4], color: 'transparent' }
            }
        }
    };
}

/* ══════════════════════════════════════
   GAS DAILY — line chart + gradient fill
   ══════════════════════════════════════ */
(function () {
    var el = document.getElementById('cGasDaily');
    if (!el) { return; }
    var ctx  = el.getContext('2d');

    var grad = ctx.createLinearGradient(0, 0, 0, 300);
    grad.addColorStop(0,    'rgba(240,180,41,.52)');
    grad.addColorStop(0.35, 'rgba(251,146,60,.28)');
    grad.addColorStop(0.70, 'rgba(240,180,41,.10)');
    grad.addColorStop(1,    'rgba(240,180,41,0)');

    var lineGrad = ctx.createLinearGradient(0, 0, el.offsetWidth || 800, 0);
    lineGrad.addColorStop(0,   '#f0b429');
    lineGrad.addColorStop(0.5, '#fb923c');
    lineGrad.addColorStop(1,   '#f0b429');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($gasDailyChartLabels ?? []),
            datasets: [{
                label: 'MSCF',
                data: @json($gasDailyChartValues ?? []),
                fill: true,
                tension: .38,
                backgroundColor: grad,
                borderColor: lineGrad,
                borderWidth: 2.8,
                pointBackgroundColor: COLOR_GAS,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1.6,
                pointRadius: 3.2,
                pointHoverRadius: 5
            }]
        },
        options: baseOpts()
    });
})();

/* ══════════════════════════════════════
   GAS MONTHLY — bar chart + rich gradient
   ══════════════════════════════════════ */
(function () {
    var el = document.getElementById('cGasMonthly');
    if (!el) { return; }
    var ctx  = el.getContext('2d');

    var grad = ctx.createLinearGradient(0, 0, 0, 320);
    grad.addColorStop(0,    'rgba(251,191,36,.98)');
    grad.addColorStop(0.40, 'rgba(240,180,41,.88)');
    grad.addColorStop(0.75, 'rgba(217,119,6,.70)');
    grad.addColorStop(1,    'rgba(183,121,31,.50)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($gasMonthlyChartLabels ?? []),
            datasets: [{
                label: 'Avg MSCF',
                data: @json($gasMonthlyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'transparent',
                borderWidth: 0,
                borderRadius: { topLeft: 7, topRight: 7 },
                borderSkipped: false,
                maxBarThickness: 28
            }]
        },
        options: baseOpts()
    });
})();

/* ══════════════════════════════════════
   CRUDE DAILY — stacked bar
   ══════════════════════════════════════ */
(function () {
    var el = document.getElementById('cCrudeDaily');
    if (!el) { return; }
    var ctx = el.getContext('2d');

    var gradVT = ctx.createLinearGradient(0, 0, 0, 320);
    gradVT.addColorStop(0,    'rgba(17,24,39,.97)');
    gradVT.addColorStop(0.50, 'rgba(31,41,55,.90)');
    gradVT.addColorStop(1,    'rgba(55,65,81,.78)');

    var gradRT = ctx.createLinearGradient(0, 0, 0, 200);
    gradRT.addColorStop(0,    'rgba(148,163,184,.95)');
    gradRT.addColorStop(0.55, 'rgba(156,163,175,.85)');
    gradRT.addColorStop(1,    'rgba(107,114,128,.65)');

    var crudeOpts = baseOpts();
    crudeOpts.plugins.legend = { display: false };
    crudeOpts.plugins.tooltip = {
        backgroundColor: 'rgba(13,27,42,.94)',
        titleColor: '#ffffff',
        bodyColor: '#e5e7eb',
        padding: 10,
        cornerRadius: 10,
        displayColors: true,
        titleFont: { family: "'DM Sans', sans-serif",          weight: '600', size: 12 },
        bodyFont:  { family: "'Plus Jakarta Sans', sans-serif", weight: '500', size: 11 },
        callbacks: {
            label: function (context) {
                var val = Number(context.raw || 0);
                return context.dataset.label + ': ' + val.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 4
                });
            }
        }
    };
    crudeOpts.scales.x.stacked = true;
    crudeOpts.scales.y.stacked = true;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($crudeDailyChartLabels ?? []),
            datasets: [
                {
                    label: 'Vacuum Truck',
                    data: @json($crudeDailyVacuumTruckValues ?? []),
                    backgroundColor: gradVT,
                    borderColor: 'transparent',
                    borderWidth: 0,
                    stack: 'crude',
                    borderRadius: { topLeft: 0, topRight: 0, bottomLeft: 6, bottomRight: 6 },
                    borderSkipped: false,
                    maxBarThickness: 32
                },
                {
                    label: 'Road Tank',
                    data: @json($crudeDailyRoadTankValues ?? []),
                    backgroundColor: gradRT,
                    borderColor: 'transparent',
                    borderWidth: 0,
                    stack: 'crude',
                    borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                    borderSkipped: false,
                    maxBarThickness: 32
                }
            ]
        },
        options: crudeOpts
    });
})();

/* ══════════════════════════════════════
   VITOL — bar chart + blue gradient
   ══════════════════════════════════════ */
(function () {
    var el = document.getElementById('cVitol');
    if (!el) { return; }
    var ctx  = el.getContext('2d');

    var grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0,    'rgba(56,132,220,.95)');
    grad.addColorStop(0.40, 'rgba(26,95,172,.90)');
    grad.addColorStop(0.80, 'rgba(10,53,127,.72)');
    grad.addColorStop(1,    'rgba(10,53,127,.50)');

    var vitolOpts = baseOpts();
    vitolOpts.plugins.title = { display: false };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($vitolMonthlyChartLabels ?? []),
            datasets: [{
                label: 'Quantity',
                data: @json($vitolMonthlyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'transparent',
                borderWidth: 0,
                borderRadius: { topLeft: 7, topRight: 7 },
                borderSkipped: false,
                maxBarThickness: 28
            }]
        },
        options: vitolOpts
    });
})();

/* ══════════════════════════════════════
   GAS YEARLY — bar chart + warm amber gradient
   ══════════════════════════════════════ */
(function () {
    var el = document.getElementById('cGasYearly');
    if (!el) { return; }
    var ctx  = el.getContext('2d');

    var grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0,    'rgba(251,191,36,.98)');
    grad.addColorStop(0.30, 'rgba(240,180,41,.92)');
    grad.addColorStop(0.65, 'rgba(217,119,6,.76)');
    grad.addColorStop(1,    'rgba(183,121,31,.55)');

    var yearlyOpts = baseOpts();
    yearlyOpts.plugins.title = { display: false };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($gasYearlyChartLabels ?? []),
            datasets: [{
                label: 'MSCF',
                data: @json($gasYearlyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'transparent',
                borderWidth: 0,
                borderRadius: { topLeft: 7, topRight: 7 },
                borderSkipped: false,
                maxBarThickness: 40
            }]
        },
        options: yearlyOpts
    });
})();
</script>

</body>
</html>