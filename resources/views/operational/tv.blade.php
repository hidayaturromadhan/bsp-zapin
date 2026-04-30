<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operational Display BSPZ</title>
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
            --broadcast-h:52px;
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
            grid-template-rows: var(--hdr-h) 1fr var(--broadcast-h);
            gap:var(--gap);
        }

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

        .body{
            display:grid;
            grid-template-rows: 1fr 1fr;
            gap:var(--gap);
            min-height:0;
        }

        .row{
            display:grid;
            grid-template-columns: 1fr 1.18fr 1fr;
            gap:var(--gap);
            min-height:0;
        }

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
        .b-gas-month{ background:var(--teal-soft); color:var(--teal); }
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

        .chart-wrap canvas{
            width:100% !important;
            height:100% !important;
        }

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

        .broadcast-bar{
            position:relative;
            overflow:hidden;
            border-radius:18px;
            background:linear-gradient(180deg, rgba(13,27,42,.92), rgba(13,27,42,.86));
            border:1px solid rgba(255,255,255,.14);
            box-shadow:var(--shadow);
            display:flex;
            align-items:center;
            min-height:0;
        }

        .broadcast-label{
            position:relative;
            z-index:2;
            flex:0 0 auto;
            display:flex;
            align-items:center;
            justify-content:center;
            height:100%;
            min-width:165px;
            padding:0 18px;
            font-size:11px;
            font-weight:900;
            letter-spacing:.12em;
            text-transform:uppercase;
            color:#fff;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:inset -1px 0 0 rgba(255,255,255,.18);
        }

        .broadcast-track{
            position:relative;
            flex:1 1 auto;
            height:100%;
            overflow:hidden;
            display:flex;
            align-items:center;
        }

        .broadcast-content{
            display:inline-flex;
            align-items:center;
            white-space:nowrap;
            will-change:transform;
            animation:broadcast-marquee 45s linear infinite;
            padding-left:100%;
        }

        .broadcast-item{
            display:inline-flex;
            align-items:center;
            font-size:15px;
            font-weight:700;
            color:#f8fbff;
            letter-spacing:.01em;
        }

        .broadcast-item .tag{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            margin-right:10px;
            padding:5px 10px;
            border-radius:999px;
            background:rgba(240,180,41,.16);
            border:1px solid rgba(240,180,41,.25);
            color:#ffd979;
            font-size:10px;
            font-weight:900;
            letter-spacing:.10em;
            text-transform:uppercase;
        }

        .broadcast-sep{
            display:inline-block;
            margin:0 18px;
            color:rgba(255,255,255,.40);
            font-weight:900;
        }

        .broadcast-empty{
            padding:0 18px;
            font-size:14px;
            font-weight:700;
            color:rgba(255,255,255,.72);
        }

        @keyframes broadcast-marquee{
            0%{ transform:translateX(0); }
            100%{ transform:translateX(-100%); }
        }

        @media (max-width: 1366px){
            .page{
                grid-template-rows: 78px 1fr 48px;
                padding:10px;
            }

            .hdr{
                grid-template-columns: 250px 1fr 220px;
                padding:0 16px;
            }

            .brand-name{ font-size:16px; }
            .clock{ font-size:26px; }

            .pt{
                min-width:60px;
                padding:6px 8px;
            }

            .pt-val{ font-size:12px; }

            .broadcast-label{
                min-width:145px;
                font-size:10px;
            }

            .broadcast-item{
                font-size:13px;
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

        <div class="hdr-right">
            <div class="clock" id="clockEl">--:--:--</div>
            <div class="date-row" id="dateEl">--</div>
        </div>
    </header>

    <main class="body">
        <section class="row row-top">
            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">BSP Crude Oil Trucking with TBM</div>
                        <div class="card-sub">Produksi Crude Oil 14 Hari Terakhir</div>
                    </div>
                    <span class="badge b-crude">Crude</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="cCrudeDaily" role="img" aria-label="Crude Daily chart"></canvas>
                </div>
            </div>

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

                <div class="video-pill">{{ $monthLabel ?? now()->translatedFormat('F Y') }}</div>
            </div>

            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">OIL TRADING WITH VITOL TO BPC</div>
                        <div class="card-sub">Quantity VITOL per bulan tahun aktif</div>
                    </div>
                    <span class="badge b-vitol">VITOL</span>
                </div>
                <div class="chart-wrap">
                    <canvas id="cVitol" role="img" aria-label="VITOL Monthly chart"></canvas>
                </div>
            </div>
        </section>

        <section class="row row-bottom">
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

            <div class="card">
                <div class="card-head">
                    <div>
                        <div class="card-title">Distribution Gas With Pertagas to BSP</div>
                        <div class="card-sub">Trend Penyaluran Gas Tahunan</div>
                    </div>
                    <span class="badge b-yr">Yearly</span>
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

        <div id="reminderIcon" style="
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
    const iconEl = document.getElementById('reminderIcon');

    const schedule = {
        "08:00": {
            title: "Jam Masuk",
            text: "Waktu kerja dimulai. Yuk mulai aktivitas hari ini dengan semangat!",
            icon: "⏰"
        },
        "12:00": {
            title: "Jam Istirahat",
            text: "Saatnya recharge. Istirahat dulu ya sejenak untuk makan siang dan sholat.",
            icon: "⏰"
        },
        "16:30": {
            title: "Jam Pulang",
            text: "Kerja selesai. Jangan lupa closing aktivitas hari ini ya",
            icon: "⏰"
        }
    };

    function playAlarmSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();

            function beep(time, freq, duration = 0.3) {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.type = 'sine';
                osc.frequency.value = freq;

                gain.gain.setValueAtTime(0.001, time);
                gain.gain.exponentialRampToValueAtTime(0.5, time + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, time + duration);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(time);
                osc.stop(time + duration);
            }

            const now = ctx.currentTime;
            beep(now, 880, 0.28);
            beep(now + 0.35, 660, 0.28);
            beep(now + 0.70, 990, 0.32);

        } catch (e) {
            console.log('Audio error');
        }
    }

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

    function showReminder(title, text, key, icon = '⏰') {
        playAlarmSound();

        titleEl.innerText = title;
        textEl.innerText = text;
        if (iconEl) iconEl.innerText = icon;

        alertWrap.style.display = 'block';

        setTimeout(() => {
            box.style.transform = 'translateY(0)';
            box.style.opacity = '1';
        }, 50);

        progress.style.transition = 'none';
        progress.style.transform = 'scaleX(1)';
        setTimeout(() => {
            progress.style.transition = 'transform 8s linear';
            progress.style.transform = 'scaleX(0)';
        }, 50);

        localStorage.setItem(key, 'shown');

        setTimeout(hideReminder, 8000);
    }

    window.hideReminder = function () {
        box.style.transform = 'translateY(-20px)';
        box.style.opacity = '0';

        setTimeout(() => {
            alertWrap.style.display = 'none';
        }, 300);
    };

    function checkReminder() {
        const time = getWIB();
        const today = new Date().toISOString().slice(0,10);
        const data = schedule[time];

        if (!data) return;

        const key = "reminder-" + today + "-" + time;
        if (localStorage.getItem(key)) return;

        showReminder(data.title, data.text, key, data.icon || '⏰');
    }

    window.__tvReminder = {
        showReminder,
        getWIB
    };

    setInterval(checkReminder, 1000);

    document.addEventListener('click', () => {
        if (window.AudioContext) {
            new AudioContext().resume();
        }
    }, { once: true });

})();
</script>

<script>
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

let ptimes = {};
let prayerAlertMap = {};

const PKEYS = [
    { api:'subuh',   id:'shubuh',  label:'Shubuh'  },
    { api:'dhuha',   id:'dhuha',   label:'Dhuha'   },
    { api:'dzuhur',  id:'dzuhur',  label:'Dzuhur'  },
    { api:'ashar',   id:'ashar',   label:'Ashar'   },
    { api:'maghrib', id:'maghrib', label:'Maghrib' },
    { api:'isya',    id:'isya',    label:'Isya'    }
];

function getPekanbaruDateParts() {
    const now = new Date();
    const parts = new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Asia/Jakarta',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).formatToParts(now);

    let year = '', month = '', day = '';
    parts.forEach(p => {
        if (p.type === 'year') year = p.value;
        if (p.type === 'month') month = p.value;
        if (p.type === 'day') day = p.value;
    });

    return {
        year,
        month,
        day,
        ymd: `${year}-${month}-${day}`,
        dayNumber: Number(day),
        monthNumber: Number(month),
        yearNumber: Number(year)
    };
}

function fmtPrayer(s){
    if (!s) return '--:--';
    return String(s).substring(0,5);
}

function toMin(s){
    if(!s || s === '--:--') return -1;
    const [h,m] = s.split(':').map(Number);
    return (h * 60) + m;
}

function highlightActivePrayer(){
    const nowWIB = window.__tvReminder?.getWIB ? window.__tvReminder.getWIB() : '--:--';
    const cur = toMin(nowWIB);
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

function buildPrayerAlertMap() {
    prayerAlertMap = {};
    PKEYS.forEach(p => {
        const val = ptimes[p.id] || '--:--';
        if (val !== '--:--') {
            prayerAlertMap[val] = {
                id: p.id,
                label: p.label,
                time: val
            };
        }
    });
}

function checkPrayerReminder() {
    const currentTime = window.__tvReminder?.getWIB ? window.__tvReminder.getWIB() : '--:--';
    const prayer = prayerAlertMap[currentTime];
    if (!prayer) return;

    const pekanbaruDate = getPekanbaruDateParts().ymd;
    const key = `prayer-alert-${pekanbaruDate}-${prayer.id}-${prayer.time}`;

    if (localStorage.getItem(key)) return;

    const title = `Waktu Sholat ${prayer.label}`;
    const text = `Saat ini telah masuk waktu sholat ${prayer.label} untuk Kota Pekanbaru (${prayer.time} WIB).`;
    window.__tvReminder.showReminder(title, text, key, '🕌');
}

function loadPrayerTimes(){
    const dateInfo = getPekanbaruDateParts();

    fetch('https://equran.id/api/v2/shalat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            provinsi: 'Riau',
            kabkota: 'Kota Pekanbaru',
            bulan: dateInfo.monthNumber,
            tahun: dateInfo.yearNumber
        })
    })
    .then(r => r.json())
    .then(data => {
        const jadwalBulanan = data?.data?.jadwal || [];
        const jadwalHariIni = jadwalBulanan.find(item => Number(item.tanggal) === dateInfo.dayNumber);

        if (!jadwalHariIni) {
            throw new Error('Jadwal hari ini tidak ditemukan');
        }

        PKEYS.forEach(p => {
            ptimes[p.id] = fmtPrayer(jadwalHariIni[p.api]);
            const el = document.getElementById('pv-' + p.id);
            if (el) el.textContent = ptimes[p.id];
        });

        buildPrayerAlertMap();
        highlightActivePrayer();
        checkPrayerReminder();
    })
    .catch(err => console.warn('Prayer time error:', err));
}

loadPrayerTimes();
setInterval(highlightActivePrayer, 30000);
setInterval(checkPrayerReminder, 1000);
setInterval(() => {
    const now = new Date();
    const jakarta = new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).formatToParts(now);

    let h = '', m = '';
    jakarta.forEach(p => {
        if (p.type === 'hour') h = p.value;
        if (p.type === 'minute') m = p.value;
    });

    if (h === '00' && Number(m) < 2) {
        loadPrayerTimes();
    }
}, 60000);

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

/* GAS DAILY */
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

/* GAS MONTHLY */
(function(){
    const el = document.getElementById('cGasMonthly');
    if (!el) return;

    const ctx = el.getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 300);
    grad.addColorStop(0, 'rgba(14,122,114,.90)');
    grad.addColorStop(1, 'rgba(14,122,114,.52)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($gasMonthlyChartLabels ?? []),
            datasets: [{
                label: 'Avg MSCF',
                data: @json($gasMonthlyChartValues ?? []),
                backgroundColor: grad,
                borderColor: 'rgba(14,122,114,1)',
                borderWidth: 0,
                borderRadius: { topLeft: 6, topRight: 6 },
                borderSkipped: false,
                maxBarThickness: 28,
                _3dColor: 'rgba(86,188,180,.82)',
                _3dColorDark: 'rgba(7,88,82,.86)'
            }]
        },
        options: baseOpts()
    });
})();

/* CRUDE DAILY */
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

/* VITOL MONTHLY */
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

/* GAS YEARLY */
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
</script>

</body>
</html>