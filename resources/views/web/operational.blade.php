@extends('layouts.app')

@section('title', $pageTitle ?? 'Operasional')
@section('body_class', 'page-operational')

@section('content')
<style>
    body.page-operational .n-main {
        max-width: none !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }

    body.page-operational .f-bar {
        margin-top: 0 !important;
    }

    .op-public-page {
        width: 100%;
        margin: 0;
        padding: 0;
        background:
            radial-gradient(circle at 12% 10%, rgba(47,125,50,.10), transparent 24%),
            radial-gradient(circle at 86% 8%, rgba(212,168,67,.14), transparent 22%),
            linear-gradient(180deg, #f8fafc 0%, #eef4f7 100%);
        overflow: hidden;
    }

    .op-public-hero {
        position: relative;
        width: 100%;
        min-height: 420px;
        margin: 0;
        padding: 78px 0 84px;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(23,63,8,.96) 0%, rgba(47,125,50,.92) 58%, rgba(79,157,69,.90) 100%),
            url('{{ asset('images/bg.JPG') }}') center center / cover no-repeat;
        overflow: hidden;
    }

    .op-public-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 86% 12%, rgba(255,255,255,.18), transparent 24%),
            radial-gradient(circle at 0% 100%, rgba(255,255,255,.10), transparent 22%);
        pointer-events: none;
    }

    .op-public-hero::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 80px;
        background: linear-gradient(180deg, transparent 0%, #f8fafc 100%);
        pointer-events: none;
    }

    .op-public-hero-inner {
        position: relative;
        z-index: 1;
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    .op-public-kicker {
        display: inline-flex;
        align-items: center;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 999px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.20);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .10em;
        text-transform: uppercase;
        margin-bottom: 18px;
        backdrop-filter: blur(10px);
    }

    .op-public-title {
        margin: 0;
        max-width: 820px;
        font-size: clamp(42px, 6vw, 78px);
        line-height: .96;
        font-weight: 800;
        letter-spacing: -.055em;
        text-shadow: 0 12px 34px rgba(0,0,0,.14);
    }

    .op-public-desc {
        margin: 20px 0 0;
        max-width: 760px;
        font-size: 17px;
        line-height: 1.85;
        color: rgba(255,255,255,.92);
    }

    .op-public-content {
        width: min(1240px, calc(100% - 48px));
        margin: -40px auto 0;
        padding: 0 0 76px;
        position: relative;
        z-index: 2;
    }

    .op-chart-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
        align-items: stretch;
    }

    .op-chart-card {
        background: rgba(255,255,255,.96);
        border: 1px solid rgba(229,231,235,.86);
        border-radius: 28px;
        box-shadow:
            0 4px 6px rgba(15,23,42,.04),
            0 10px 20px rgba(15,23,42,.06),
            0 24px 48px rgba(15,23,42,.08),
            0 2px 0 rgba(255,255,255,.9) inset;
        overflow: hidden;
        backdrop-filter: blur(14px);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .op-chart-card:hover {
        transform: translateY(-4px);
        box-shadow:
            0 8px 12px rgba(15,23,42,.06),
            0 16px 32px rgba(15,23,42,.08),
            0 32px 64px rgba(15,23,42,.12),
            0 2px 0 rgba(255,255,255,.9) inset;
    }

    .op-chart-head {
        padding: 24px 26px 18px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .op-chart-title {
        margin: 0;
        font-size: 23px;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #0f172a;
    }

    .op-chart-desc {
        margin-top: 7px;
        font-size: 14px;
        line-height: 1.7;
        color: #64748b;
    }

    .op-chart-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .op-chart-badge--gas {
        background: linear-gradient(135deg, #fefce8 0%, #fef08a 100%);
        color: #713f12;
        border: 1px solid #fde047;
        box-shadow: 0 2px 6px rgba(234,179,8,.20);
    }

    .op-chart-badge--crude {
        background: linear-gradient(135deg, #fff1f2 0%, #fecdd3 100%);
        color: #9f1239;
        border: 1px solid #fda4af;
        box-shadow: 0 2px 6px rgba(220,38,38,.15);
    }

    .op-chart-badge--vitol {
        background: linear-gradient(135deg, #edf4ff 0%, #d5e8fc 100%);
        color: #1d4f91;
        border: 1px solid #b8d6f9;
        box-shadow: 0 2px 6px rgba(37,99,235,.15);
    }

    .op-chart-body {
        padding: 24px 26px 28px;
    }

    .op-chart-wrap {
        position: relative;
        width: 100%;
        height: 430px;
        border-radius: 22px;
        overflow: hidden;
        background: linear-gradient(160deg, #f4f8fb 0%, #e8f0f6 40%, #dde8f0 100%);
        border: 1px solid #dce6ef;
        padding: 18px;
        box-shadow:
            inset 0 2px 8px rgba(15,23,42,.06),
            inset 0 1px 0 rgba(255,255,255,.8);
    }

    .op-chart-wrap::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.9), transparent);
        pointer-events: none;
        z-index: 1;
    }

    .op-chart-wrap canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .op-empty {
        padding: 58px 20px;
        text-align: center;
        color: #64748b;
        background: linear-gradient(160deg, #f4f8fb 0%, #e8f0f6 40%, #dde8f0 100%);
        border-radius: 22px;
        border: 1px solid #dce6ef;
    }

    .op-empty-title {
        margin-bottom: 8px;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }


    /* =========================
       SKELETON LOADING ONLY
    ========================= */
    .op-skeleton-layer {
        position: absolute;
        inset: 0;
        z-index: 30;
        background:
            radial-gradient(circle at 12% 10%, rgba(47,125,50,.10), transparent 24%),
            radial-gradient(circle at 86% 8%, rgba(212,168,67,.14), transparent 22%),
            linear-gradient(180deg, #f8fafc 0%, #eef4f7 100%);
        transition: opacity .35s ease, visibility .35s ease;
    }

    .op-skeleton-layer.is-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .op-real-content {
        opacity: 0;
        transition: opacity .35s ease;
    }

    .op-real-content.is-loaded {
        opacity: 1;
    }

    .op-skeleton-hero {
        position: relative;
        width: 100%;
        min-height: 420px;
        margin: 0;
        padding: 78px 0 84px;
        background:
            linear-gradient(135deg, rgba(23,63,8,.96) 0%, rgba(47,125,50,.92) 58%, rgba(79,157,69,.90) 100%),
            url('{{ asset('images/bg.JPG') }}') center center / cover no-repeat;
        overflow: hidden;
    }

    .op-skeleton-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 86% 12%, rgba(255,255,255,.18), transparent 24%),
            radial-gradient(circle at 0% 100%, rgba(255,255,255,.10), transparent 22%);
        pointer-events: none;
    }

    .op-skeleton-hero::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 80px;
        background: linear-gradient(180deg, transparent 0%, #f8fafc 100%);
        pointer-events: none;
    }

    .op-skeleton-hero-inner {
        position: relative;
        z-index: 1;
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    .op-skeleton-content {
        width: min(1240px, calc(100% - 48px));
        margin: -40px auto 0;
        padding: 0 0 76px;
        position: relative;
        z-index: 2;
    }

    .op-sk-line,
    .op-sk-chart {
        position: relative;
        overflow: hidden;
        background: #e5e7eb;
    }

    .op-sk-line::after,
    .op-sk-chart::after {
        content: '';
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.65), transparent);
        animation: opSkeletonShimmer 1.35s infinite;
    }

    @keyframes opSkeletonShimmer {
        100% { transform: translateX(100%); }
    }

    .op-sk-kicker {
        width: 190px;
        height: 38px;
        border-radius: 999px;
        background: rgba(255,255,255,.22);
        margin-bottom: 18px;
    }

    .op-sk-title {
        width: min(720px, 86%);
        height: clamp(48px, 6vw, 78px);
        border-radius: 18px;
        background: rgba(255,255,255,.22);
        margin-bottom: 20px;
    }

    .op-sk-desc {
        width: min(640px, 78%);
        height: 18px;
        border-radius: 999px;
        background: rgba(255,255,255,.22);
    }

    .op-sk-desc-small {
        width: min(460px, 60%);
        height: 18px;
        border-radius: 999px;
        background: rgba(255,255,255,.18);
        margin-top: 12px;
    }

    .op-sk-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
        align-items: stretch;
    }

    .op-sk-chart-card {
        background: rgba(255,255,255,.96);
        border: 1px solid rgba(229,231,235,.86);
        border-radius: 28px;
        box-shadow:
            0 4px 6px rgba(15,23,42,.04),
            0 10px 20px rgba(15,23,42,.06),
            0 24px 48px rgba(15,23,42,.08),
            0 2px 0 rgba(255,255,255,.9) inset;
        overflow: hidden;
        backdrop-filter: blur(14px);
        height: 100%;
    }

    .op-sk-chart-head {
        padding: 24px 26px 18px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .op-sk-chart-title {
        width: 210px;
        height: 28px;
        border-radius: 999px;
    }

    .op-sk-chart-desc {
        width: 250px;
        height: 14px;
        border-radius: 999px;
        margin-top: 12px;
    }

    .op-sk-chart-badge {
        width: 92px;
        height: 34px;
        border-radius: 999px;
    }

    .op-sk-chart-body {
        padding: 24px 26px 28px;
    }

    .op-sk-chart-box {
        width: 100%;
        height: 430px;
        border-radius: 22px;
        border: 1px solid #dce6ef;
        background: linear-gradient(160deg, #f4f8fb 0%, #e8f0f6 40%, #dde8f0 100%);
    }

    @media (max-width: 1024px) {
        .op-sk-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .op-skeleton-hero {
            min-height: 360px;
            padding: 52px 0 76px;
        }

        .op-skeleton-hero-inner,
        .op-skeleton-content {
            width: min(100% - 24px, 1240px);
        }

        .op-skeleton-content {
            margin-top: -34px;
            padding-bottom: 48px;
        }

        .op-sk-title {
            height: clamp(42px, 12vw, 56px);
        }

        .op-sk-chart-card {
            border-radius: 22px;
        }

        .op-sk-chart-head,
        .op-sk-chart-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .op-sk-chart-box {
            height: 340px;
        }
    }

    @media (max-width: 1024px) {
        .op-chart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .op-public-hero {
            min-height: 360px;
            padding: 52px 0 76px;
        }

        .op-public-hero-inner,
        .op-public-content {
            width: min(100% - 24px, 1240px);
        }

        .op-public-content {
            margin-top: -34px;
            padding-bottom: 48px;
        }

        .op-public-title {
            font-size: clamp(34px, 12vw, 52px);
        }

        .op-public-desc {
            font-size: 15px;
        }

        .op-chart-card {
            border-radius: 22px;
        }

        .op-chart-head,
        .op-chart-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .op-chart-wrap {
            height: 340px;
            padding: 12px;
        }
    }
</style>

<div class="op-public-page">
    <div id="opSkeletonLayer" class="op-skeleton-layer" aria-hidden="true">
        <section class="op-skeleton-hero">
            <div class="op-skeleton-hero-inner">
                <div class="op-sk-line op-sk-kicker"></div>
                <div class="op-sk-line op-sk-title"></div>
                <div class="op-sk-line op-sk-desc"></div>
                <div class="op-sk-line op-sk-desc-small"></div>
            </div>
        </section>

        <div class="op-skeleton-content">
            <div class="op-sk-grid">
                @for ($i = 0; $i < 3; $i++)
                    <section class="op-sk-chart-card">
                        <div class="op-sk-chart-head">
                            <div>
                                <div class="op-sk-line op-sk-chart-title"></div>
                                <div class="op-sk-line op-sk-chart-desc"></div>
                            </div>
                            <div class="op-sk-line op-sk-chart-badge"></div>
                        </div>
                        <div class="op-sk-chart-body">
                            <div class="op-sk-chart op-sk-chart-box"></div>
                        </div>
                    </section>
                @endfor
            </div>
        </div>
    </div>

    <div id="opRealContent" class="op-real-content">
        <section class="op-public-hero">

            <div class="op-public-hero-inner">
                <div class="op-public-kicker">
                    {{ $opText['kicker'] ?? 'Insight Operasional' }}
                </div>

                <h1 class="op-public-title">
                    {{ $pageTitle ?? 'Operasional' }}
                </h1>

                <p class="op-public-desc">
                    {{ $opText['description'] ?? 'Kegiatan operasional PT Bumi Siak Pusako Zapin berfokus pada pengelolaan dan penyaluran energi secara andal, efisien, dan berkelanjutan. Melalui dukungan infrastruktur serta penerapan standar keselamatan dan kinerja yang tinggi, Perusahaan memastikan distribusi gas dan kegiatan operasional lainnya berjalan optimal dalam memenuhi kebutuhan energi di wilayah operasional.' }}
                </p>
            </div>
        </section>

        <div class="op-public-content">
            <div class="op-chart-grid">
                <section class="op-chart-card">
                    <div class="op-chart-head">
                        <div>
                            <h2 class="op-chart-title">{{ $opText['gasTitle'] ?? 'Tren Tahunan Gas' }}</h2>
                            <div class="op-chart-desc">{{ $opText['gasDesc'] ?? 'Total penyaluran gas per tahun berdasarkan data harian.' }}</div>
                        </div>
                        <div class="op-chart-badge op-chart-badge--gas">Gas</div>
                    </div>

                    <div class="op-chart-body">
                        @if(!empty($gasYears) && count($gasYears) > 0)
                            <div class="op-chart-wrap">
                                <canvas id="gasYearlyChart"></canvas>
                            </div>
                        @else
                            <div class="op-empty">
                                <div class="op-empty-title">{{ $opText['gasEmptyTitle'] ?? 'Belum ada data Gas' }}</div>
                                <div>{{ $opText['gasEmptyDesc'] ?? 'Grafik akan tampil setelah data gas tersedia.' }}</div>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="op-chart-card">
                    <div class="op-chart-head">
                        <div>
                            <h2 class="op-chart-title">{{ $opText['crudeTitle'] ?? 'Tren Tahunan Crude Oil' }}</h2>
                            <div class="op-chart-desc">{{ $opText['crudeDesc'] ?? 'Total produksi crude oil per tahun berdasarkan data harian.' }}</div>
                        </div>
                        <div class="op-chart-badge op-chart-badge--crude">Crude Oil</div>
                    </div>

                    <div class="op-chart-body">
                        @if(!empty($crudeYears) && count($crudeYears) > 0)
                            <div class="op-chart-wrap">
                                <canvas id="crudeYearlyChart"></canvas>
                            </div>
                        @else
                            <div class="op-empty">
                                <div class="op-empty-title">{{ $opText['crudeEmptyTitle'] ?? 'Belum ada data Crude Oil' }}</div>
                                <div>{{ $opText['crudeEmptyDesc'] ?? 'Grafik akan tampil setelah data crude oil tersedia.' }}</div>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="op-chart-card">
                    <div class="op-chart-head">
                        <div>
                            <h2 class="op-chart-title">{{ $opText['vitolTitle'] ?? 'Tren Tahunan VITOL' }}</h2>
                            <div class="op-chart-desc">{{ $opText['vitolDesc'] ?? 'Total quantity VITOL per tahun berdasarkan data bulanan.' }}</div>
                        </div>
                        <div class="op-chart-badge op-chart-badge--vitol">VITOL</div>
                    </div>

                    <div class="op-chart-body">
                        @if(!empty($vitolYears) && count($vitolYears) > 0)
                            <div class="op-chart-wrap">
                                <canvas id="vitolYearlyChart"></canvas>
                            </div>
                        @else
                            <div class="op-empty">
                                <div class="op-empty-title">{{ $opText['vitolEmptyTitle'] ?? 'Belum ada data VITOL' }}</div>
                                <div>{{ $opText['vitolEmptyDesc'] ?? 'Grafik akan tampil setelah data VITOL tersedia.' }}</div>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const tickColor = '#475569';
    const gridColor = 'rgba(15, 23, 42, 0.06)';

    function formatNumber(value) {
        return Number(value || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    }

    function createSmoothGradient(ctx, stops) {
        const canvasHeight = ctx.canvas.offsetHeight || 430;
        const gradient = ctx.createLinearGradient(0, 0, 0, canvasHeight);
        stops.forEach(([offset, color]) => gradient.addColorStop(offset, color));
        return gradient;
    }

    const plugin3DSide = {
        id: 'barSide3D',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);
                if (meta.type !== 'bar') return;

                meta.data.forEach((bar) => {
                    const { x, y, width, base } = bar;
                    const sideW = Math.max(width * 0.07, 6);
                    const depth = Math.max((base - y) * 0.04, 4);
                    const colorSide = dataset._3dSideColor || 'rgba(0,0,0,0.18)';
                    const colorTop = dataset._3dTopColor || 'rgba(255,255,255,0.28)';

                    ctx.save();

                    ctx.beginPath();
                    ctx.moveTo(x + width / 2, y);
                    ctx.lineTo(x + width / 2 + sideW, y - depth);
                    ctx.lineTo(x + width / 2 + sideW, base - depth);
                    ctx.lineTo(x + width / 2, base);
                    ctx.closePath();
                    ctx.fillStyle = colorSide;
                    ctx.fill();

                    ctx.beginPath();
                    ctx.moveTo(x - width / 2, y);
                    ctx.lineTo(x - width / 2 + sideW, y - depth);
                    ctx.lineTo(x + width / 2 + sideW, y - depth);
                    ctx.lineTo(x + width / 2, y);
                    ctx.closePath();

                    const topGrad = ctx.createLinearGradient(
                        x - width / 2, y,
                        x + width / 2 + sideW, y - depth
                    );

                    topGrad.addColorStop(0, colorTop);
                    topGrad.addColorStop(1, 'rgba(255,255,255,0.06)');
                    ctx.fillStyle = topGrad;
                    ctx.fill();

                    ctx.restore();
                });
            });
        }
    };

    Chart.register(plugin3DSide);

    function buildBarChart(canvasId, labels, values, label, gradStops, sideColor, topColor, borderColor) {
        const canvas = document.getElementById(canvasId);
        if (!canvas || typeof Chart === 'undefined') return;

        const ctx = canvas.getContext('2d');
        const gradient = createSmoothGradient(ctx, gradStops);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    backgroundColor: gradient,
                    borderColor: borderColor,
                    borderWidth: 0,
                    borderRadius: {
                        topLeft: 10,
                        topRight: 10,
                        bottomLeft: 3,
                        bottomRight: 3
                    },
                    borderSkipped: false,
                    barPercentage: 0.58,
                    categoryPercentage: 0.72,
                    _3dSideColor: sideColor,
                    _3dTopColor: topColor,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1800,
                    easing: 'easeOutElastic'
                },
                layout: {
                    padding: {
                        top: 24,
                        right: 16,
                        bottom: 4,
                        left: 4
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: tickColor,
                            font: { size: 12, weight: '700' },
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.92)',
                        titleColor: '#ffffff',
                        bodyColor: 'rgba(255,255,255,0.85)',
                        padding: { top: 10, right: 16, bottom: 10, left: 16 },
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return label + ': ' + formatNumber(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            color: tickColor,
                            font: { size: 12, weight: '700' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false,
                            lineWidth: 1
                        },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 },
                            callback: function(value) {
                                return formatNumber(value);
                            }
                        }
                    }
                }
            }
        });
    }

    buildBarChart(
        'gasYearlyChart',
        @json($gasYears ?? []),
        @json($gasValues ?? []),
        @json($opText['gasDataset'] ?? 'MSCF'),
        [
            [0.00, 'rgba(254, 240, 138, 1.00)'],
            [0.18, 'rgba(253, 224,  71, 0.98)'],
            [0.42, 'rgba(250, 204,  21, 0.97)'],
            [0.68, 'rgba(234, 179,   8, 0.97)'],
            [0.85, 'rgba(202, 138,   4, 0.98)'],
            [1.00, 'rgba(161, 107,   6, 1.00)']
        ],
        'rgba(120, 80, 0, 0.22)',
        'rgba(254, 249, 195, 0.55)',
        'rgba(161, 107, 6, 1)'
    );

    buildBarChart(
        'crudeYearlyChart',
        @json($crudeYears ?? []),
        @json($crudeValues ?? []),
        @json($opText['crudeDataset'] ?? 'Produksi'),
        [
            [0.00, 'rgba(252, 165, 165, 1.00)'],
            [0.18, 'rgba(248, 113, 113, 0.98)'],
            [0.40, 'rgba(239,  68,  68, 0.97)'],
            [0.62, 'rgba(220,  38,  38, 0.97)'],
            [0.82, 'rgba(185,  28,  28, 0.98)'],
            [1.00, 'rgba(127,  29,  29, 1.00)']
        ],
        'rgba(100, 10, 10, 0.22)',
        'rgba(254, 226, 226, 0.55)',
        'rgba(127, 29, 29, 1)'
    );

    buildBarChart(
        'vitolYearlyChart',
        @json($vitolYears ?? []),
        @json($vitolValues ?? []),
        @json($opText['vitolDataset'] ?? 'Quantity'),
        [
            [0.00, 'rgba(147, 197, 253, 1.00)'],
            [0.18, 'rgba( 96, 165, 250, 0.98)'],
            [0.40, 'rgba( 59, 130, 246, 0.97)'],
            [0.62, 'rgba( 37,  99, 235, 0.97)'],
            [0.82, 'rgba( 29,  78, 216, 0.98)'],
            [1.00, 'rgba( 30,  64, 175, 1.00)']
        ],
        'rgba(20, 40, 140, 0.22)',
        'rgba(219, 234, 254, 0.55)',
        'rgba(30, 64, 175, 1)'
    );
})();
</script>

<script>
(function () {
    function finishOperationalSkeleton() {
        var skeleton = document.getElementById('opSkeletonLayer');
        var content = document.getElementById('opRealContent');

        if (content) {
            content.classList.add('is-loaded');
        }

        if (skeleton) {
            skeleton.classList.add('is-hidden');
            setTimeout(function () {
                skeleton.style.display = 'none';
            }, 400);
        }
    }

    if (document.readyState === 'complete') {
        setTimeout(finishOperationalSkeleton, 500);
    } else {
        window.addEventListener('load', function () {
            setTimeout(finishOperationalSkeleton, 500);
        });
    }

    setTimeout(finishOperationalSkeleton, 2200);
})();
</script>
@endsection