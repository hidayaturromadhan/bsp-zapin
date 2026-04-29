<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operational Display — BSP</title>
    <meta http-equiv="refresh" content="300">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root{
            --gold:#c8920a;
            --gold-2:#f0b429;
            --gold-soft:#fff3d4;

            --green:#1e6e2e;
            --green-soft:#e7f6ea;

            --blue:#1a5fac;
            --blue-soft:#e8f0fb;

            --red:#b83232;
            --red-soft:#fdeaea;

            --teal:#0e7a72;
            --teal-soft:#e4f7f5;

            --ink:#0d1b2a;
            --ink-2:#2e3f52;
            --ink-3:#5f738a;
            --ink-4:#8ea4bb;

            --bg:#edf3f9;
            --card:#ffffff;
            --card-2:rgba(255,255,255,.84);
            --line:rgba(13,27,42,.10);
            --line-soft:rgba(13,27,42,.06);
            --shadow:0 10px 32px rgba(13,27,42,.10), 0 2px 8px rgba(13,27,42,.05);

            --hdr-h:82px;
            --gap:12px;
            --radius:20px;
        }

        html, body{
            width:100%;
            height:100%;
            font-family:'Inter', sans-serif;
            color:var(--ink);
            overflow:hidden;
        }

        body{
            position:relative;
            background: linear-gradient(180deg, #f4f8fc 0%, #edf3f9 100%);
        }

        /* Background image blur + brighten */
        body::before{
            content:'';
            position:fixed;
            inset:-30px;
            z-index:0;
            pointer-events:none;
            background: url('{{ asset('images/bg.JPG') }}') center center / cover no-repeat;
            filter: blur(10px) brightness(1.18) saturate(1.05);
            transform: scale(1.08);
        }

        /* Light overlay above blurred background */
        body::after{
            content:'';
            position:fixed;
            inset:0;
            z-index:0;
            pointer-events:none;
            background:
                linear-gradient(rgba(255,255,255,.34), rgba(255,255,255,.26)),
                radial-gradient(circle at top left, rgba(255,255,255,.28), transparent 34%),
                radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 28%);
        }

        .page{
            position:relative;
            z-index:1;
            width:100vw;
            height:100vh;
            padding:12px;
            display:grid;
            grid-template-rows: var(--hdr-h) 90px 1fr;
            gap:var(--gap);
        }

        /* HEADER */
        .hdr{
            display:grid;
            grid-template-columns: 280px 1fr 250px;
            align-items:center;
            gap:14px;
            padding:0 20px;
            border-radius:22px;
            background:linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.80));
            border:1px solid rgba(255,255,255,.38);
            box-shadow:var(--shadow);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .brand{
            display:flex;
            align-items:center;
            gap:14px;
            min-width:0;
        }

        .brand-logo{
            width:48px;
            height:48px;
            flex-shrink:0;
            display:grid;
            place-items:center;
            border-radius:14px;
            background:linear-gradient(180deg, #fff, #f7f9fb);
            border:1px solid rgba(13,27,42,.08);
            box-shadow:0 3px 10px rgba(13,27,42,.08);
        }

        .brand-logo img{
            width:40px;
            height:40px;
            object-fit:contain;
        }

        .brand-name{
            font-size:18px;
            font-weight:900;
            letter-spacing:-.04em;
            color:var(--gold);
            line-height:1.05;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .brand-tag{
            margin-top:3px;
            font-size:10px;
            font-weight:800;
            letter-spacing:.16em;
            text-transform:uppercase;
            color:var(--green);
        }

        /* PRAYER */
        .prayer-strip{
            display:flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            min-width:0;
        }

        .pt{
            min-width:68px;
            padding:7px 10px;
            border-radius:12px;
            background:linear-gradient(180deg, rgba(255,255,255,.96), rgba(245,249,252,.88));
            border:1px solid rgba(255,255,255,.45);
            display:flex;
            flex-direction:column;
            align-items:center;
            transition:all .25s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .pt-name{
            font-size:9px;
            font-weight:900;
            letter-spacing:.10em;
            text-transform:uppercase;
            color:var(--ink-3);
        }

        .pt-val{
            margin-top:2px;
            font-size:13px;
            font-weight:900;
            color:var(--ink-2);
            line-height:1.2;
        }

        .pt.active{
            background:linear-gradient(180deg, #fff7df, #ffefc4);
            border-color:rgba(200,146,10,.34);
            box-shadow:0 0 0 1.5px rgba(200,146,10,.20), 0 8px 18px rgba(200,146,10,.12);
            transform:translateY(-1px);
        }

        .pt.active .pt-name,
        .pt.active .pt-val{
            color:var(--gold);
        }

        .hdr-right{
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:2px;
        }

        .clock{
            font-size:30px;
            font-weight:900;
            letter-spacing:-.06em;
            color:var(--gold);
            line-height:1;
        }

        .date-row{
            font-size:11px;
            font-weight:700;
            color:var(--ink-2);
        }

        .period-row{
            font-size:11px;
            font-weight:800;
            color:var(--green);
            letter-spacing:.04em;
            text-transform:uppercase;
        }

        /* KPI BAR */
        .kpi-row{
            display:grid;
            grid-template-columns: repeat(4, 1fr);
            gap:var(--gap);
            min-height:0;
        }

        .kpi{
            position:relative;
            border-radius:18px;
            background:linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.80));
            border:1px solid rgba(255,255,255,.36);
            box-shadow:var(--shadow);
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:14px 16px;
            overflow:hidden;
            backdrop-filter:blur(12px);
            -webkit-backdrop-filter:blur(12px);
        }

        .kpi::after{
            content:'';
            position:absolute;
            right:-24px;
            top:-24px;
            width:92px;
            height:92px;
            border-radius:50%;
            opacity:.12;
        }

        .kpi.gas::after{ background:var(--gold); }
        .kpi.crude::after{ background:var(--red); }
        .kpi.vitol::after{ background:var(--blue); }
        .kpi.year::after{ background:var(--green); }

        .kpi-info{
            position:relative;
            z-index:2;
            min-width:0;
        }

        .kpi-label{
            font-size:10px;
            font-weight:800;
            letter-spacing:.10em;
            text-transform:uppercase;
            color:var(--ink-3);
        }

        .kpi-value{
            margin-top:3px;
            font-size:26px;
            font-weight:900;
            letter-spacing:-.05em;
            color:var(--ink);
            line-height:1;
            white-space:nowrap;
        }

        .kpi-meta{
            margin-top:4px;
            font-size:10px;
            font-weight:700;
            color:var(--ink-4);
        }

        .kpi-tag{
            position:relative;
            z-index:2;
            font-size:10px;
            font-weight:900;
            letter-spacing:.08em;
            text-transform:uppercase;
            padding:6px 10px;
            border-radius:999px;
            white-space:nowrap;
        }

        .kpi.gas .kpi-tag{
            background:var(--gold-soft);
            color:var(--gold);
        }

        .kpi.crude .kpi-tag{
            background:var(--red-soft);
            color:var(--red);
        }

        .kpi.vitol .kpi-tag{
            background:var(--blue-soft);
            color:var(--blue);
        }

        .kpi.year .kpi-tag{
            background:var(--green-soft);
            color:var(--green);
        }

        /* BODY */
        .body{
            display:grid;
            grid-template-columns: 1.05fr 1fr;
            gap:var(--gap);
            min-height:0;
        }

        .left, .right{
            display:grid;
            gap:var(--gap);
            min-height:0;
        }

        .left{
            grid-template-rows: 1.04fr .96fr;
        }

        .right{
            grid-template-rows: 1fr 1fr;
        }

        /* CARD */
        .card{
            background:linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.80));
            border:1px solid rgba(255,255,255,.36);
            border-radius:22px;
            box-shadow:var(--shadow);
            overflow:hidden;
            display:flex;
            flex-direction:column;
            min-height:0;
            position:relative;
            backdrop-filter:blur(12px);
            -webkit-backdrop-filter:blur(12px);
        }

        .card::before{
            content:'';
            position:absolute;
            inset:0;
            pointer-events:none;
            background:linear-gradient(180deg, rgba(255,255,255,.12), transparent 24%);
            opacity:.7;
        }

        .card-head{
            flex-shrink:0;
            padding:14px 18px 0;
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:12px;
            position:relative;
            z-index:2;
        }

        .card-title{
            font-size:14px;
            font-weight:900;
            letter-spacing:-.02em;
            color:var(--ink);
        }

        .card-sub{
            margin-top:3px;
            font-size:10.5px;
            color:var(--ink-4);
            font-weight:600;
        }

        .badge{
            flex-shrink:0;
            font-size:9px;
            font-weight:900;
            letter-spacing:.10em;
            text-transform:uppercase;
            padding:5px 10px;
            border-radius:999px;
            white-space:nowrap;
        }

        .b-gas{ background:var(--gold-soft); color:var(--gold); }
        .b-crude{ background:var(--red-soft); color:var(--red); }
        .b-vitol{ background:var(--blue-soft); color:var(--blue); }
        .b-yr{ background:var(--green-soft); color:var(--green); }

        .chart-wrap{
            position:relative;
            flex:1 1 auto;
            min-height:0;
            padding:10px 14px 14px;
            z-index:2;
        }

        .chart-wrap canvas,
        .dual-pane canvas{
            width:100% !important;
            height:100% !important;
        }

        /* VIDEO CARD */
        .video-card{
            background:#07111e;
            padding:0;
            position:relative;
            border:1px solid rgba(255,255,255,.08);
        }

        .video-card video{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
            filter:saturate(1.04) contrast(1.03);
        }

        .video-card::after{
            content:'';
            position:absolute;
            inset:0;
            background:
                linear-gradient(180deg, rgba(5,10,18,.03), rgba(5,10,18,.20)),
                radial-gradient(circle at bottom right, rgba(200,146,10,.14), transparent 26%);
            pointer-events:none;
        }

        .video-topbar{
            position:absolute;
            top:14px;
            left:14px;
            right:14px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            z-index:3;
        }

        .live-pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 11px;
            border-radius:999px;
            background:rgba(255,255,255,.20);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border:1px solid rgba(255,255,255,.22);
            color:#fff;
            font-size:10px;
            font-weight:900;
            letter-spacing:.10em;
            text-transform:uppercase;
        }

        .live-dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:#ff4d4f;
            box-shadow:0 0 0 0 rgba(255,77,79,.55);
            animation:pulse 1.8s infinite;
        }

        @keyframes pulse{
            0%{ box-shadow:0 0 0 0 rgba(255,77,79,.55); }
            70%{ box-shadow:0 0 0 10px rgba(255,77,79,0); }
            100%{ box-shadow:0 0 0 0 rgba(255,77,79,0); }
        }

        .video-pill{
            position:absolute;
            right:16px;
            bottom:14px;
            z-index:3;
            background:rgba(255,255,255,.16);
            backdrop-filter:blur(12px);
            -webkit-backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,.22);
            color:#fff;
            font-size:10px;
            font-weight:900;
            letter-spacing:.10em;
            text-transform:uppercase;
            padding:6px 12px;
            border-radius:999px;
        }

        /* DUAL */
        .dual{
            flex:1 1 auto;
            min-height:0;
            display:grid;
            grid-template-columns: 1fr 1px 1fr;
            gap:0;
            padding:10px 14px 14px;
            position:relative;
            z-index:2;
        }

        .dual-div{
            background:linear-gradient(180deg, transparent, var(--line), transparent);
            margin:8px 6px;
            border-radius:99px;
        }

        .dual-pane{
            min-height:0;
            position:relative;
        }

        /* BOTTOM MICRO INFO */
        .micro-info{
            position:absolute;
            left:14px;
            bottom:12px;
            z-index:3;
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }

        .micro-chip{
            background:rgba(255,255,255,.16);
            color:#fff;
            border:1px solid rgba(255,255,255,.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius:999px;
            padding:5px 10px;
            font-size:9px;
            font-weight:800;
            letter-spacing:.06em;
            text-transform:uppercase;
        }

        @media (max-width: 1366px){
            .page{
                grid-template-rows: 78px 84px 1fr;
                padding:10px;
            }

            .hdr{
                grid-template-columns: 250px 1fr 220px;
                padding:0 16px;
            }

            .brand-name{ font-size:16px; }
            .clock{ font-size:26px; }

            .kpi-value{ font-size:22px; }

            .pt{
                min-width:60px;
                padding:6px 8px;
            }

            .pt-val{ font-size:12px; }
        }
    </style>
</head>
<body>

<audio id="tvBackgroundMusic" autoplay loop preload="auto">
    <source src="{{ asset('audio/tv-music.mp3') }}" type="audio/mpeg">
    Browser Anda tidak mendukung audio.
</audio>

@php
    $gasTodayValue = collect($gasDailyChartValues ?? [])->last() ?? 0;
    $crudeTodayValue = collect($crudeDailyChartValues ?? [])->last() ?? 0;
    $vitolLastValue = collect($vitolMonthlyChartValues ?? [])->last() ?? 0;
    $gasYearValue = collect($gasYearlyChartValues ?? [])->last() ?? 0;
@endphp

<div class="page">

    <header class="hdr">
        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Logo">
            </div>
            <div>
                <div class="brand-name">PT Bumi Siak Pusako</div>
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

        <div class="hdr-right">
            <div class="clock" id="clockEl">--:--:--</div>
            <div class="date-row" id="dateEl">--</div>
            <div class="period-row">{{ $monthLabel ?? 'Periode Aktif' }}</div>
        </div>
    </header>

    <section class="kpi-row">
        <div class="kpi gas">
            <div class="kpi-info">
                <div class="kpi-label">Flow Gas Daily</div>
                <div class="kpi-value">{{ number_format((float)$gasTodayValue, 0, ',', '.') }}</div>
                <div class="kpi-meta">MSCF · nilai terbaru</div>
            </div>
            <div class="kpi-tag">Gas</div>
        </div>

        <div class="kpi crude">
            <div class="kpi-info">
                <div class="kpi-label">Crude Daily</div>
                <div class="kpi-value">{{ number_format((float)$crudeTodayValue, 0, ',', '.') }}</div>
                <div class="kpi-meta">BOPD / produksi terbaru</div>
            </div>
            <div class="kpi-tag">Crude</div>
        </div>

        <div class="kpi vitol">
            <div class="kpi-info">
                <div class="kpi-label">VITOL Monthly</div>
                <div class="kpi-value">{{ number_format((float)$vitolLastValue, 0, ',', '.') }}</div>
                <div class="kpi-meta">Quantity · bulan aktif</div>
            </div>
            <div class="kpi-tag">VITOL</div>
        </div>

        <div class="kpi year">
            <div class="kpi-info">
                <div class="kpi-label">Gas Yearly</div>
                <div class="kpi-value">{{ number_format((float)$gasYearValue, 0, ',', '.') }}</div>
                <div class="kpi-meta">MSCF · tahun terakhir</div>
            </div>
            <div class="kpi-tag">Yearly</div>
        </div>
    </section>

    <main class="body">
        <div class="left">
            <div class="card video-card">
                <div class="video-topbar">
                    <div class="live-pill">
                        <span class="live-dot"></span>
                        Operational Monitoring
                    </div>
                </div>

                <video autoplay muted loop playsinline>
                    <source src="{{ asset('videos/company-profile.mp4') }}" type="video/mp4">
                </video>

                <div class="micro-info">
                    <div class="micro-chip">BSP Monitoring</div>
                    <div class="micro-chip">Realtime Display</div>
                </div>

                <div class="video-pill">{{ $monthLabel ?? 'Periode Aktif' }}</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">Flow Gas — Daily</div>
                        <div class="card-sub">Total MSCF harian bulan aktif</div>
                    </div>
                    <span class="badge b-gas">Gas Daily</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="cGasDaily" role="img" aria-label="Flow Gas Daily chart"></canvas>
                </div>
            </div>
        </div>

        <div class="right">
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">Crude — Daily</div>
                        <div class="card-sub">Produksi crude harian bulan aktif</div>
                    </div>
                    <span class="badge b-crude">Crude</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="cCrudeDaily" role="img" aria-label="Crude Daily chart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">VITOL Monthly &amp; Gas Yearly</div>
                        <div class="card-sub">Quantity VITOL dan tren gas antar tahun</div>
                    </div>
                    <span class="badge b-vitol">Dual Analytics</span>
                </div>

                <div class="dual">
                    <div class="dual-pane">
                        <canvas id="cVitol" role="img" aria-label="VITOL Monthly chart"></canvas>
                    </div>
                    <div class="dual-div"></div>
                    <div class="dual-pane">
                        <canvas id="cGasYearly" role="img" aria-label="Gas Yearly chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- ================= REMINDER ALERT ================= -->
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
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(14px);
        border-radius: 16px;
        padding: 16px 16px 14px 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        border: 1px solid rgba(0,0,0,0.06);
        transform: translateY(-20px);
        opacity: 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    ">

        <div style="
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg,#ffd86b,#f4a100);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            box-shadow: 0 6px 16px rgba(244,161,0,0.4);
            font-size: 20px;
        ">⏰</div>

        <div id="reminderTitle" style="
            font-weight: 800;
            font-size: 16px;
            margin-bottom: 4px;
            color: #111;
        "></div>

        <div id="reminderText" style="
            font-size: 13px;
            color: #555;
            line-height: 1.5;
        "></div>

        <button onclick="hideReminder()" style="
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: transparent;
            font-size: 16px;
            cursor: pointer;
            color: #888;
        ">✕</button>

        <div id="reminderProgress" style="
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg,#f4a100,#ffd86b);
            transform-origin: left;
        "></div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {

    const alertWrap = document.getElementById('reminderAlert');
    const box = document.getElementById('reminderBox');
    const titleEl = document.getElementById('reminderTitle');
    const textEl = document.getElementById('reminderText');
    const progress = document.getElementById('reminderProgress');

    const schedule = {
        "08:00": {
            title: "Jam Masuk",
            text: "Waktu kerja dimulai. Yuk mulai aktivitas 🚀"
        },
        "12:00": {
            title: "Jam Istirahat",
            text: "Saatnya recharge. Istirahat dulu ya ☕"
        },
        "16:30": {
            title: "Jam Pulang",
            text: "Kerja selesai. Jangan lupa closing aktivitas 👍"
        }
    };

    /* ================= SOUND ================= */
    function playAlarmSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();

            function beep(time, freq) {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.type = 'sine';
                osc.frequency.value = freq;

                gain.gain.setValueAtTime(0.001, time);
                gain.gain.exponentialRampToValueAtTime(0.5, time + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, time + 0.3);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(time);
                osc.stop(time + 0.3);
            }

            const now = ctx.currentTime;
            beep(now, 880);
            beep(now + 0.35, 660);

        } catch (e) {
            console.log('Audio error');
        }
    }

    /* ================= TIME WIB ================= */
    function getWIB() {
        const now = new Date();
        const parts = new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        }).formatToParts(now);

        let h = '', m = '';
        parts.forEach(p => {
            if (p.type === 'hour') h = p.value;
            if (p.type === 'minute') m = p.value;
        });

        return `${h}:${m}`;
    }

    /* ================= SHOW ALERT ================= */
    function showReminder(title, text, key) {

        playAlarmSound(); // 🔊 suara

        titleEl.innerText = title;
        textEl.innerText = text;

        alertWrap.style.display = 'block';

        setTimeout(() => {
            box.style.transform = 'translateY(0)';
            box.style.opacity = '1';
        }, 50);

        // progress bar
        progress.style.transition = 'none';
        progress.style.transform = 'scaleX(1)';
        setTimeout(() => {
            progress.style.transition = 'transform 8s linear';
            progress.style.transform = 'scaleX(0)';
        }, 50);

        localStorage.setItem(key, 'shown');

        setTimeout(hideReminder, 8000);
    }

    /* ================= HIDE ALERT ================= */
    window.hideReminder = function () {
        box.style.transform = 'translateY(-20px)';
        box.style.opacity = '0';

        setTimeout(() => {
            alertWrap.style.display = 'none';
        }, 300);
    };

    /* ================= CHECK ================= */
    function checkReminder() {
        const time = getWIB();
        const today = new Date().toISOString().slice(0,10);
        const data = schedule[time];

        if (!data) return;

        const key = "reminder-" + today + "-" + time;
        if (localStorage.getItem(key)) return;

        showReminder(data.title, data.text, key);
    }

    setInterval(checkReminder, 1000);

    /* ================= ENABLE AUDIO ================= */
    document.addEventListener('click', () => {
        if (window.AudioContext) {
            new AudioContext().resume();
        }
    }, { once: true });

})();
</script>
<script>
/* ─────────────────────────────
   CLOCK
───────────────────────────── */
function tick(){
    const n = new Date();
    const p = v => String(v).padStart(2, '0');
    const clockEl = document.getElementById('clockEl');
    const dateEl  = document.getElementById('dateEl');

    if (clockEl) {
        clockEl.textContent = `${p(n.getHours())}:${p(n.getMinutes())}:${p(n.getSeconds())}`;
    }

    if (dateEl) {
        dateEl.textContent = n.toLocaleDateString('id-ID', {
            weekday:'long',
            day:'numeric',
            month:'long',
            year:'numeric'
        });
    }
}
tick();
setInterval(tick, 1000);

/* ─────────────────────────────
   PRAYER TIMES
───────────────────────────── */
let ptimes = {};
const PKEYS = [
    { api:'Fajr',    id:'shubuh'  },
    { api:'Sunrise', id:'dhuha'   },
    { api:'Dhuhr',   id:'dzuhur'  },
    { api:'Asr',     id:'ashar'   },
    { api:'Maghrib', id:'maghrib' },
    { api:'Isha',    id:'isya'    }
];

function fmtPrayer(s){
    return s ? s.substring(0,5) : '--:--';
}

function toMin(s){
    if(!s || s === '--:--') return -1;
    const [h,m] = s.split(':').map(Number);
    return (h * 60) + m;
}

function highlightActivePrayer(){
    const n = new Date();
    const cur = n.getHours() * 60 + n.getMinutes();
    let active = PKEYS[0].id;

    PKEYS.forEach(p => {
        const m = toMin(ptimes[p.id] || '--:--');
        if (m !== -1 && cur >= m) active = p.id;
    });

    PKEYS.forEach(p => {
        const el = document.getElementById('pt-' + p.id);
        if (el) el.classList.toggle('active', p.id === active);
    });
}

function loadPrayerTimes(){
    const d = new Date();
    fetch(`https://api.aladhan.com/v1/timings/${d.getDate()}-${d.getMonth()+1}-${d.getFullYear()}?latitude=0.5071&longitude=101.4478&method=4&timezone=Asia/Jakarta`)
        .then(r => r.json())
        .then(data => {
            const t = data?.data?.timings || {};
            PKEYS.forEach(p => {
                ptimes[p.id] = fmtPrayer(t[p.api]);
                const el = document.getElementById('pv-' + p.id);
                if (el) el.textContent = ptimes[p.id];
            });
            highlightActivePrayer();
        })
        .catch(err => console.warn('Prayer time error:', err));
}

loadPrayerTimes();
setInterval(highlightActivePrayer, 60000);
setInterval(() => {
    const n = new Date();
    if (n.getHours() === 0 && n.getMinutes() < 2) {
        loadPrayerTimes();
    }
}, 60000);

/* ─────────────────────────────
   CHART 3D PLUGIN
───────────────────────────── */
const Bar3DPlugin = {
    id: 'bar3d',
    beforeDatasetsDraw(chart) {
        const { ctx } = chart;

        chart.data.datasets.forEach((ds, di) => {
            const meta = chart.getDatasetMeta(di);
            if (meta.type !== 'bar') return;

            const depth = 10;

            meta.data.forEach((bar) => {
                const { x, y, width, base } = bar.getProps(['x','y','width','base'], true);
                const h = base - y;
                if (h <= 0) return;

                const hc = ds._3dColor || ds.borderColor;
                const bc = ds._3dColorDark || ds.borderColor;

                ctx.save();
                ctx.beginPath();
                ctx.moveTo(x + width / 2, y);
                ctx.lineTo(x + width / 2 + depth, y - depth * 0.5);
                ctx.lineTo(x + width / 2 + depth, base - depth * 0.5);
                ctx.lineTo(x + width / 2, base);
                ctx.closePath();
                ctx.fillStyle = bc;
                ctx.globalAlpha = 0.84;
                ctx.fill();
                ctx.restore();

                ctx.save();
                ctx.beginPath();
                ctx.moveTo(x - width / 2, y);
                ctx.lineTo(x + width / 2, y);
                ctx.lineTo(x + width / 2 + depth, y - depth * 0.5);
                ctx.lineTo(x - width / 2 + depth, y - depth * 0.5);
                ctx.closePath();
                ctx.fillStyle = hc;
                ctx.globalAlpha = 0.92;
                ctx.fill();
                ctx.restore();
            });
        });
    }
};

Chart.register(Bar3DPlugin);

/* ─────────────────────────────
   SHARED OPTIONS
───────────────────────────── */
const gridC = 'rgba(13,27,42,.07)';
const tickC = '#8ea4bb';

function baseOpts(extraX = {}) {
    return {
        maintainAspectRatio: false,
        responsive: true,
        animation: {
            duration: 900,
            easing: 'easeOutQuart'
        },
        layout: {
            padding: { top: 12, right: 16, bottom: 2, left: 2 }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(13,27,42,.92)',
                titleColor: '#ffffff',
                bodyColor: '#d2dfeb',
                padding: 10,
                cornerRadius: 10,
                displayColors: false
            }
        },
        scales: {
            x: {
                ticks: {
                    color: tickC,
                    font: { size: 9.5, weight: '700' },
                    maxRotation: 0,
                    autoSkip: true,
                    maxTicksLimit: 12,
                    ...extraX
                },
                grid: {
                    color: gridC,
                    lineWidth: 1
                },
                border: {
                    color: 'rgba(13,27,42,.10)',
                    width: 1
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: tickC,
                    font: { size: 9.5, weight: '700' },
                    padding: 4,
                    callback: v => v >= 1000 ? (v / 1000).toFixed(1) + 'k' : v
                },
                grid: {
                    color: (ctx) => ctx.index === 0 ? 'rgba(13,27,42,.13)' : gridC,
                    lineWidth: (ctx) => ctx.index === 0 ? 1.4 : 1,
                    drawTicks: false
                },
                border: {
                    dash: [4,4],
                    color: 'transparent'
                }
            }
        }
    };
}

/* ─────────────────────────────
   FLOW GAS DAILY
───────────────────────────── */
(function(){
    const el = document.getElementById('cGasDaily');
    if (!el) return;

    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0, 'rgba(200,146,10,.36)');
    grad.addColorStop(.62, 'rgba(200,146,10,.09)');
    grad.addColorStop(1, 'rgba(200,146,10,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($gasDailyChartLabels ?? []),
            datasets: [{
                label: 'MSCF',
                data: @json($gasDailyChartValues ?? []),
                fill: true,
                tension: .42,
                backgroundColor: grad,
                borderColor: '#c8920a',
                borderWidth: 2.6,
                pointBackgroundColor: '#c8920a',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1.6,
                pointRadius: 3.4,
                pointHoverRadius: 5.4,
                pointHoverBorderWidth: 2.2
            }]
        },
        options: baseOpts()
    });
})();

/* ─────────────────────────────
   CRUDE DAILY
───────────────────────────── */
(function(){
    const el = document.getElementById('cCrudeDaily');
    if (!el) return;

    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 300);
    grad.addColorStop(0, 'rgba(184,50,50,.92)');
    grad.addColorStop(1, 'rgba(184,50,50,.56)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($crudeDailyChartLabels ?? []),
            datasets: [{
                label: 'Produksi',
                data: @json($crudeDailyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'rgba(184,50,50,1)',
                borderWidth: 0,
                borderRadius: { topLeft: 6, topRight: 6 },
                borderSkipped: false,
                maxBarThickness: 30,
                _3dColor: 'rgba(221,110,110,.82)',
                _3dColorDark: 'rgba(138,18,18,.85)'
            }]
        },
        options: baseOpts()
    });
})();

/* ─────────────────────────────
   VITOL MONTHLY
───────────────────────────── */
(function(){
    const el = document.getElementById('cVitol');
    if (!el) return;

    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(26,95,172,.90)');
    grad.addColorStop(1, 'rgba(26,95,172,.52)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($vitolMonthlyChartLabels ?? []),
            datasets: [{
                label: 'Quantity',
                data: @json($vitolMonthlyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'rgba(26,95,172,1)',
                borderWidth: 0,
                borderRadius: { topLeft: 5, topRight: 5 },
                borderSkipped: false,
                maxBarThickness: 28,
                _3dColor: 'rgba(90,158,232,.82)',
                _3dColorDark: 'rgba(10,53,128,.86)'
            }]
        },
        options: {
            ...baseOpts(),
            plugins: {
                ...baseOpts().plugins,
                title: {
                    display: true,
                    text: 'VITOL',
                    color: '#1a5fac',
                    font: { size: 10, weight: '900' },
                    padding: { bottom: 5 }
                }
            }
        }
    });
})();

/* ─────────────────────────────
   GAS YEARLY
───────────────────────────── */
(function(){
    const el = document.getElementById('cGasYearly');
    if (!el) return;

    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(30,110,46,.90)');
    grad.addColorStop(1, 'rgba(30,110,46,.52)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($gasYearlyChartLabels ?? []),
            datasets: [{
                label: 'MSCF',
                data: @json($gasYearlyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'rgba(30,110,46,1)',
                borderWidth: 0,
                borderRadius: { topLeft: 5, topRight: 5 },
                borderSkipped: false,
                maxBarThickness: 38,
                _3dColor: 'rgba(90,191,115,.82)',
                _3dColorDark: 'rgba(8,61,21,.86)'
            }]
        },
        options: {
            ...baseOpts(),
            plugins: {
                ...baseOpts().plugins,
                title: {
                    display: true,
                    text: 'Gas Yearly',
                    color: '#1e6e2e',
                    font: { size: 10, weight: '900' },
                    padding: { bottom: 5 }
                }
            }
        }
    });
})();

/* ─────────────────────────────
   AUDIO
───────────────────────────── */
(function () {
    const audio = document.getElementById('tvBackgroundMusic');
    if (!audio) return;

    audio.volume = 0.35;

    const tryPlay = () => {
        audio.play().catch(() => {});
    };

    tryPlay();

    document.addEventListener('click', tryPlay, { once: true });
    document.addEventListener('keydown', tryPlay, { once: true });

    audio.addEventListener('ended', function () {
        audio.currentTime = 0;
        tryPlay();
    });
})();
</script>
</body>
</html>