@extends('layouts.app')

@section('title', $pageTitle ?? 'Operasional')

@section('content')
<style>
    * { box-sizing: border-box; }

    .op-page {
        height: calc(100vh - 60px);
        display: flex;
        flex-direction: column;
        padding: 20px 0 24px;
        background:
            radial-gradient(circle at top left, rgba(47,125,50,.06), transparent 20%),
            radial-gradient(circle at top right, rgba(212,168,67,.07), transparent 16%);
    }

    .op-container {
        width: min(1280px, calc(100% - 40px));
        margin: 0 auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .op-hero {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 26px 32px;
        margin-bottom: 16px;
        flex-shrink: 0;
        color: #fff;
        background: linear-gradient(135deg, #173f08 0%, #2f7d32 55%, #4f9d45 100%);
        box-shadow: 0 12px 32px rgba(23,63,8,.18);
    }

    .op-hero::before {
        content: '';
        position: absolute;
        width: 240px; height: 240px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        top: -90px; right: -50px;
    }

    .op-hero::after {
        content: '';
        position: absolute;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
        left: -50px; bottom: -70px;
    }

    .op-hero-inner {
        position: relative;
        z-index: 1;
    }

    .op-kicker {
        display: inline-flex;
        align-items: center;
        padding: 4px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.16);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .op-title {
        margin: 0;
        font-size: clamp(28px, 3.5vw, 46px);
        line-height: 1.05;
        font-weight: 800;
        letter-spacing: -.025em;
    }

    .op-desc {
        margin: 10px 0 0;
        font-size: 14px;
        line-height: 1.7;
        color: rgba(255,255,255,.88);
    }

    .op-card {
        flex: 1;
        min-height: 0;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 8px 28px rgba(15,23,42,.07);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .op-card-head {
        padding: 18px 24px 14px;
        border-bottom: 1px solid #eef2f7;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .op-card-title {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -.018em;
    }

    .op-card-sub {
        font-size: 12px;
        color: #64748b;
    }

    .op-card-body {
        flex: 1;
        min-height: 0;
        padding: 16px 22px 22px;
        display: flex;
        flex-direction: column;
    }

    .op-chart-wrap {
        flex: 1;
        min-height: 0;
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: linear-gradient(180deg, #f8fbfd 0%, #edf3f7 100%);
        border: 1px solid #e5edf3;
        padding: 12px 12px 8px;
    }

    .op-chart-canvas {
        width: 100% !important;
        height: 100% !important;
        display: block;
    }

    .op-chart-error {
        display: none;
        margin-top: 10px;
        padding: 10px 14px;
        border-radius: 12px;
        background: #fff4f4;
        border: 1px solid #f3c6c6;
        color: #b42318;
        font-size: 13px;
        font-weight: 700;
    }

    .op-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        text-align: center;
        color: #64748b;
    }

    .op-empty-title {
        margin-bottom: 8px;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .op-page {
            height: auto;
            min-height: calc(100vh - 60px);
            padding: 14px 0 20px;
        }

        .op-container {
            width: calc(100% - 24px);
        }

        .op-hero {
            padding: 20px;
            border-radius: 18px;
        }

        .op-card {
            border-radius: 18px;
        }

        .op-card-head,
        .op-card-body {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>

<div class="op-page">
    <div class="op-container">

        <section class="op-hero">
            <div class="op-hero-inner">
                <div class="op-kicker">Insight Operasional</div>
                <h1 class="op-title">{{ $pageTitle ?? 'Operasional' }}</h1>
                <p class="op-desc">Visualisasi tren operasional tahunan menggunakan data real.</p>
            </div>
        </section>

        @if($years->isNotEmpty() && $values->isNotEmpty())
            <section class="op-card">
                <div class="op-card-head">
                    <h2 class="op-card-title">Tren Operasional Tahunan</h2>
                    <span class="op-card-sub">{{ $years->first() }} – {{ $years->last() }}</span>
                </div>
                <div class="op-card-body">
                    <div class="op-chart-wrap">
                        <canvas id="opChart" class="op-chart-canvas"
                            role="img"
                            aria-label="Grafik batang tren operasional tahunan {{ $years->first() }} hingga {{ $years->last() }}">
                        </canvas>
                    </div>
                    <div id="opChartError" class="op-chart-error"></div>
                </div>
            </section>
        @else
            <section class="op-card">
                <div class="op-empty">
                    <div class="op-empty-title">Belum ada data operasional tahunan</div>
                    <div>Setelah data tahunan tersedia, grafik akan tampil di sini.</div>
                </div>
            </section>
        @endif

    </div>
</div>

@if($years->isNotEmpty() && $values->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const errorBox = document.getElementById('opChartError');
    const canvas   = document.getElementById('opChart');

    function showError(msg) {
        if (!errorBox) return;
        errorBox.style.display = 'block';
        errorBox.textContent   = msg;
    }

    if (!canvas)                      return showError('Canvas grafik tidak ditemukan.');
    if (typeof Chart === 'undefined') return showError('Chart.js gagal dimuat.');

    const labels = @json($years->values());
    const values = @json($values->values());

    if (!labels.length || !values.length) return showError('Data grafik tidak tersedia.');

    const ctx = canvas.getContext('2d');

    const frontGrad = ctx.createLinearGradient(0, 0, 0, 500);
    frontGrad.addColorStop(0,   '#5db85a');
    frontGrad.addColorStop(0.5, '#2f7d32');
    frontGrad.addColorStop(1,   '#173f08');

    const fake3DPlugin = {
        id: 'fake3DPlugin',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            const meta    = chart.getDatasetMeta(0);
            const dx = 14, dy = 9;

            ctx.save();

            meta.data.forEach((bar, i) => {
                const p    = bar.getProps(['x','y','base','width'], true);
                const l    = p.x - p.width / 2;
                const r    = p.x + p.width / 2;
                const top  = p.y;
                const base = p.base;
                if (base - top < 2) return;

                /* Side face */
                const sideGrad = ctx.createLinearGradient(r, top, r + dx, top);
                sideGrad.addColorStop(0, 'rgba(171,125,29,0.98)');
                sideGrad.addColorStop(1, 'rgba(120,80,8,0.95)');

                ctx.shadowColor   = 'rgba(0,0,0,0.22)';
                ctx.shadowBlur    = 10;
                ctx.shadowOffsetX = 5;
                ctx.shadowOffsetY = 5;

                ctx.beginPath();
                ctx.moveTo(r,      top);
                ctx.lineTo(r + dx, top  - dy);
                ctx.lineTo(r + dx, base - dy);
                ctx.lineTo(r,      base);
                ctx.closePath();
                ctx.fillStyle = sideGrad;
                ctx.fill();

                ctx.shadowColor   = 'transparent';
                ctx.shadowBlur    = 0;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 0;

                /* Top face */
                const topGrad = ctx.createLinearGradient(l, top - dy, r + dx, top);
                topGrad.addColorStop(0,   'rgba(155,220,140,0.97)');
                topGrad.addColorStop(0.4, 'rgba(200,235,170,0.97)');
                topGrad.addColorStop(1,   'rgba(212,185,100,0.95)');

                ctx.beginPath();
                ctx.moveTo(l,      top);
                ctx.lineTo(l + dx, top - dy);
                ctx.lineTo(r + dx, top - dy);
                ctx.lineTo(r,      top);
                ctx.closePath();
                ctx.fillStyle = topGrad;
                ctx.fill();

                /* Top edge highlight */
                ctx.beginPath();
                ctx.moveTo(l, top);
                ctx.lineTo(r, top);
                ctx.strokeStyle = 'rgba(255,255,255,0.20)';
                ctx.lineWidth   = 1;
                ctx.stroke();

                /* Value label */
                const labelText = Number(values[i]).toLocaleString('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                });
                ctx.fillStyle   = '#173f08';
                ctx.font        = '700 11px system-ui, sans-serif';
                ctx.textAlign   = 'center';
                ctx.shadowColor = 'rgba(255,255,255,0.55)';
                ctx.shadowBlur  = 3;
                ctx.fillText(labelText, p.x + dx / 2, top - dy - 8);
                ctx.shadowBlur  = 0;
            });

            ctx.restore();
        }
    };

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data:               values,
                backgroundColor:    frontGrad,
                borderColor:        'rgba(23,63,8,0.85)',
                borderWidth:        1,
                borderRadius:       { topLeft: 8, topRight: 8, bottomLeft: 0, bottomRight: 0 },
                borderSkipped:      'bottom',
                barPercentage:      0.55,
                categoryPercentage: 0.7
            }]
        },
        options: {
            responsive:          true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing:   'easeOutCubic'
            },
            layout: {
                padding: { top: 36, right: 28, bottom: 4, left: 4 }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor:      '#fff',
                    bodyColor:       'rgba(255,255,255,0.82)',
                    padding:         12,
                    cornerRadius:    10,
                    displayColors:   false,
                    callbacks: {
                        label: ctx => 'Nilai: ' + Number(ctx.raw).toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 4
                        })
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        color: '#475569',
                        font:  { size: 12, weight: '700' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color:      'rgba(15,23,42,0.07)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font:  { size: 11 },
                        callback: v => Number(v).toLocaleString('id-ID')
                    }
                }
            }
        },
        plugins: [fake3DPlugin]
    });
})();
</script>
@endif

@endsection