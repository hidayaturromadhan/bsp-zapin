@extends('layouts.app')

@section('title', $pageTitle ?? 'Operasional')
@section('body_class', 'page-operational')

@section('content')
@php
    $gasYears = collect($gasYears ?? [])->values();
    $gasValues = collect($gasValues ?? [])->map(fn ($v) => (float) $v)->values();

    $crudeYears = collect($crudeYears ?? [])->values();
    $crudeValues = collect($crudeValues ?? [])->map(fn ($v) => (float) $v)->values();

    $vitolYears = collect($vitolYears ?? [])->values();
    $vitolValues = collect($vitolValues ?? [])->map(fn ($v) => (float) $v)->values();

    $gasMax = max((float) $gasValues->max(), 0.0001);
    $crudeMax = max((float) $crudeValues->max(), 0.0001);
    $vitolMax = max((float) $vitolValues->max(), 0.0001);

    $formatNumber = function ($value) {
        $value = (float) $value;
        $abs = abs($value);

        if ($value == 0.0) {
            return '0';
        }

        if ($abs < 1) {
            $formatted = number_format($value, 4, ',', '.');
        } elseif ($abs < 10) {
            $formatted = number_format($value, 3, ',', '.');
        } elseif ($abs < 100) {
            $formatted = number_format($value, 2, ',', '.');
        } elseif ($abs < 1000) {
            $formatted = number_format($value, 1, ',', '.');
        } else {
            $formatted = number_format($value, 0, ',', '.');
        }

        if (str_contains($formatted, ',')) {
            $formatted = rtrim(rtrim($formatted, '0'), ',');
        }

        return $formatted;
    };

    $makeRows = function ($years, $values, $max) {
        return collect($years)->map(function ($year, $index) use ($values, $max) {
            $value = (float) ($values[$index] ?? 0);
            $percent = $max > 0 ? ($value / $max) * 100 : 0;

            return [
                'year' => $year,
                'value' => $value,
                'percent' => $value <= 0 ? 0 : max(5, min(100, $percent)),
            ];
        })->values();
    };

    $makeScale = function ($max) {
        $max = max((float) $max, 0.0001);

        return collect([4, 3, 2, 1, 0])->map(function ($step) use ($max) {
            return [
                'value' => ($max / 4) * $step,
                'percent' => $step * 25,
            ];
        });
    };

    $gasRows = $makeRows($gasYears, $gasValues, $gasMax);
    $crudeRows = $makeRows($crudeYears, $crudeValues, $crudeMax);
    $vitolRows = $makeRows($vitolYears, $vitolValues, $vitolMax);

    $gasScale = $makeScale($gasMax);
    $crudeScale = $makeScale($crudeMax);
    $vitolScale = $makeScale($vitolMax);
@endphp

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
            radial-gradient(circle at 8% 42%, rgba(23,63,8,.06), transparent 26%),
            radial-gradient(circle at 92% 70%, rgba(154,111,10,.08), transparent 24%),
            linear-gradient(180deg, #f8faf7 0%, #ffffff 48%, #f7faf6 100%);
        overflow: hidden;
        position: relative;
    }

    .op-public-hero {
        position: relative;
        width: 100%;
        min-height: 390px;
        margin: 0;
        padding: 56px 0 70px;
        color: #fff;
        background:
            radial-gradient(circle at 10% 22%, rgba(255,255,255,.16), transparent 27%),
            radial-gradient(circle at 86% 24%, rgba(246,210,139,.18), transparent 30%),
            radial-gradient(ellipse 70% 90% at 50% 105%, rgba(47,125,50,.22), transparent 65%),
            linear-gradient(135deg, #102d06 0%, #173f08 48%, #21560e 100%);
        overflow: hidden;
    }

    .op-public-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.055) 1px, transparent 1px);
        background-size: 56px 56px;
        mask-image: linear-gradient(to bottom, rgba(0,0,0,.72), transparent 76%);
        pointer-events: none;
    }

    .op-public-hero::after {
        content: '';
        position: absolute;
        width: 440px;
        height: 440px;
        right: -180px;
        bottom: -230px;
        border-radius: 999px;
        background: rgba(255,255,255,.075);
        pointer-events: none;
    }

    .op-public-hero-inner {
        position: relative;
        z-index: 2;
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    .op-hero-card {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at 12% 22%, rgba(238,246,235,.9), transparent 26%),
            radial-gradient(circle at 86% 18%, rgba(246,210,139,.18), transparent 24%),
            linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
        border: 1px solid rgba(255,255,255,.55);
        border-radius: 30px;
        padding: 58px 48px 52px;
        color: #10220c;
        box-shadow:
            0 30px 80px rgba(5,18,2,.34),
            0 8px 22px rgba(5,18,2,.16),
            inset 0 1px 0 rgba(255,255,255,.9);
    }

    .op-hero-card::before {
        content: "";
        position: absolute;
        inset: 16px;
        border: 1px solid rgba(23,63,8,.07);
        border-radius: 24px;
        pointer-events: none;
    }

    .op-hero-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .op-hero-orb-1 {
        width: 280px;
        height: 280px;
        top: -100px;
        right: -80px;
        background: rgba(32,71,18,.07);
    }

    .op-hero-orb-2 {
        width: 180px;
        height: 180px;
        bottom: -70px;
        left: -52px;
        background: rgba(32,71,18,.08);
    }

    .op-hero-orb-3 {
        width: 86px;
        height: 86px;
        top: 38px;
        left: 48px;
        background: rgba(154,111,10,.09);
    }

    .op-public-kicker {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-height: 34px;
        padding: 0 14px;
        border-radius: 999px;
        background: rgba(23,63,8,.055);
        border: 1px solid rgba(23,63,8,.10);
        color: #204712;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .13em;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    .op-public-kicker::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #204712;
        box-shadow: 0 0 0 4px rgba(32,71,18,.12);
        flex-shrink: 0;
    }

    .op-public-title {
        position: relative;
        z-index: 2;
        margin: 0;
        max-width: 840px;
        font-size: clamp(38px, 5vw, 64px);
        line-height: 1.04;
        font-weight: 900;
        letter-spacing: -.055em;
        color: #0f1f0a;
    }

    .op-public-desc {
        position: relative;
        z-index: 2;
        margin: 20px 0 0;
        max-width: 820px;
        font-size: 15.5px;
        line-height: 1.85;
        color: #5a6b55;
    }

    .op-public-content {
        width: min(1240px, calc(100% - 48px));
        margin: -42px auto 0;
        padding: 0 0 76px;
        position: relative;
        z-index: 3;
    }

    .op-chart-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
        align-items: stretch;
    }

    .op-chart-card {
        background: rgba(255,255,255,.98);
        border: 1px solid rgba(229,231,235,.88);
        border-radius: 26px;
        box-shadow:
            0 4px 6px rgba(15,23,42,.035),
            0 12px 26px rgba(15,23,42,.07),
            0 2px 0 rgba(255,255,255,.88) inset;
        overflow: hidden;
        height: 100%;
        transition: transform .22s ease, box-shadow .22s ease;
    }

    .op-chart-card:hover {
        transform: translateY(-3px);
        box-shadow:
            0 6px 12px rgba(15,23,42,.05),
            0 18px 36px rgba(15,23,42,.09),
            0 2px 0 rgba(255,255,255,.88) inset;
    }

    .op-chart-head {
        padding: 22px 24px 16px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .op-chart-title {
        margin: 0;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #0f172a;
    }

    .op-chart-desc {
        margin-top: 7px;
        font-size: 13.5px;
        line-height: 1.65;
        color: #64748b;
    }

    .op-chart-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 0 13px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .op-chart-badge--gas {
        background: linear-gradient(135deg, #fefce8 0%, #fef08a 100%);
        color: #713f12;
        border: 1px solid #fde047;
    }

    .op-chart-badge--crude {
        background: linear-gradient(135deg, #f8fafc 0%, #d1d5db 100%);
        color: #111827;
        border: 1px solid #9ca3af;
    }

    .op-chart-badge--vitol {
        background: linear-gradient(135deg, #edf4ff 0%, #d5e8fc 100%);
        color: #1d4f91;
        border: 1px solid #b8d6f9;
    }

    .op-chart-body {
        padding: 22px 24px 26px;
    }

    .op-chart-box {
        width: 100%;
        min-height: 350px;
        border-radius: 20px;
        background:
            linear-gradient(to top, rgba(15,23,42,.055) 1px, transparent 1px),
            linear-gradient(160deg, #f4f8fb 0%, #e8f0f6 45%, #dde8f0 100%);
        background-size: 100% 25%, 100% 100%;
        border: 1px solid #dce6ef;
        padding: 18px 16px 16px;
        display: grid;
        grid-template-columns: 52px minmax(0, 1fr);
        gap: 12px;
        box-shadow:
            inset 0 2px 8px rgba(15,23,42,.05),
            inset 0 1px 0 rgba(255,255,255,.78);
    }

    .op-chart-scale {
        height: 292px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding-bottom: 28px;
        border-right: 1px dashed rgba(100,116,139,.22);
        padding-right: 8px;
    }

    .op-chart-scale-item {
        font-size: 10.5px;
        font-weight: 800;
        color: #64748b;
        line-height: 1;
        text-align: right;
        white-space: nowrap;
    }

    .op-css-chart {
        min-width: 0;
        height: 292px;
        display: flex;
        align-items: end;
        gap: 12px;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 0;
    }

    .op-css-chart::-webkit-scrollbar {
        height: 8px;
    }

    .op-css-chart::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .op-bar-item {
        min-width: 54px;
        flex: 1;
        height: 292px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
        position: relative;
    }

    .op-bar-value {
        font-size: 10.5px;
        font-weight: 900;
        color: #475569;
        max-width: 64px;
        text-align: center;
        line-height: 1.15;
        opacity: .95;
        word-break: break-word;
    }

    .op-bar-track {
        width: 100%;
        height: 206px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        position: relative;
    }

    .op-bar {
        width: min(34px, 82%);
        height: var(--height);
        min-height: 0;
        border-radius: 12px 12px 4px 4px;
        position: relative;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,.55),
            0 10px 18px rgba(15,23,42,.12);
        transition: transform .18s ease, filter .18s ease;
    }

    .op-bar-item:hover .op-bar {
        transform: translateY(-4px);
        filter: brightness(1.04);
    }

    .op-bar--gas {
        background: linear-gradient(180deg, #fef08a 0%, #facc15 48%, #a66b00 100%);
    }

    .op-bar--crude {
        background: linear-gradient(180deg, #e5e7eb 0%, #6b7280 45%, #030712 100%);
    }

    .op-bar--vitol {
        background: linear-gradient(180deg, #93c5fd 0%, #3b82f6 48%, #1e40af 100%);
    }

    .op-bar-year {
        font-size: 12px;
        font-weight: 900;
        color: #334155;
        line-height: 1;
        white-space: nowrap;
    }

    .op-chart-legend {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 14px;
        font-size: 12px;
        font-weight: 800;
        color: #475569;
    }

    .op-chart-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 4px;
        display: inline-block;
    }

    .op-chart-legend-dot--gas {
        background: linear-gradient(180deg, #fef08a 0%, #facc15 55%, #a66b00 100%);
    }

    .op-chart-legend-dot--crude {
        background: linear-gradient(180deg, #e5e7eb 0%, #6b7280 50%, #030712 100%);
    }

    .op-chart-legend-dot--vitol {
        background: linear-gradient(180deg, #93c5fd 0%, #3b82f6 55%, #1e40af 100%);
    }

    .op-empty {
        padding: 54px 20px;
        text-align: center;
        color: #64748b;
        background: linear-gradient(160deg, #f4f8fb 0%, #e8f0f6 45%, #dde8f0 100%);
        border-radius: 20px;
        border: 1px solid #dce6ef;
    }

    .op-empty-title {
        margin-bottom: 8px;
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
    }

    @media (max-width: 1024px) {
        .op-chart-grid {
            grid-template-columns: 1fr;
        }

        .op-bar-item {
            min-width: 62px;
        }
    }

    @media (max-width: 768px) {
        .op-public-hero {
            min-height: 340px;
            padding: 34px 0 66px;
        }

        .op-public-hero-inner,
        .op-public-content {
            width: min(100% - 24px, 1240px);
        }

        .op-hero-card {
            padding: 38px 22px 34px;
            border-radius: 24px;
        }

        .op-public-content {
            margin-top: -32px;
            padding-bottom: 46px;
        }

        .op-public-title {
            font-size: clamp(32px, 10vw, 48px);
            letter-spacing: -.04em;
        }

        .op-public-desc {
            font-size: 14.2px;
            line-height: 1.76;
        }

        .op-chart-card {
            border-radius: 22px;
        }

        .op-chart-head,
        .op-chart-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .op-chart-box {
            min-height: 320px;
            grid-template-columns: 46px minmax(0, 1fr);
            gap: 10px;
            padding: 16px 12px 14px;
        }

        .op-chart-scale {
            height: 260px;
            padding-bottom: 28px;
        }

        .op-chart-scale-item {
            font-size: 9.5px;
        }

        .op-css-chart {
            height: 260px;
            gap: 10px;
        }

        .op-bar-item {
            height: 260px;
            min-width: 58px;
        }

        .op-bar-track {
            height: 178px;
        }

        .op-bar-value {
            font-size: 10px;
            max-width: 58px;
        }
    }
</style>

<div class="op-public-page">
    <section class="op-public-hero">
        <div class="op-public-hero-inner">
            <div class="op-hero-card">
                <div class="op-hero-orb op-hero-orb-1"></div>
                <div class="op-hero-orb op-hero-orb-2"></div>
                <div class="op-hero-orb op-hero-orb-3"></div>

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
                    @if($gasRows->count())
                        <div class="op-chart-box">
                            <div class="op-chart-scale" aria-hidden="true">
                                @foreach($gasScale as $scale)
                                    <div class="op-chart-scale-item">{{ $formatNumber($scale['value']) }}</div>
                                @endforeach
                            </div>

                            <div class="op-css-chart" aria-label="{{ $opText['gasTitle'] ?? 'Tren Tahunan Gas' }}">
                                @foreach($gasRows as $row)
                                    <div class="op-bar-item" title="{{ $row['year'] }}: {{ $formatNumber($row['value']) }}">
                                        <div class="op-bar-value">{{ $formatNumber($row['value']) }}</div>
                                        <div class="op-bar-track">
                                            <div class="op-bar op-bar--gas" style="--height: {{ $row['percent'] }}%;"></div>
                                        </div>
                                        <div class="op-bar-year">{{ $row['year'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="op-chart-legend">
                            <span class="op-chart-legend-dot op-chart-legend-dot--gas"></span>
                            <span>{{ $opText['gasDataset'] ?? 'MSCF' }}</span>
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
                    @if($crudeRows->count())
                        <div class="op-chart-box">
                            <div class="op-chart-scale" aria-hidden="true">
                                @foreach($crudeScale as $scale)
                                    <div class="op-chart-scale-item">{{ $formatNumber($scale['value']) }}</div>
                                @endforeach
                            </div>

                            <div class="op-css-chart" aria-label="{{ $opText['crudeTitle'] ?? 'Tren Tahunan Crude Oil' }}">
                                @foreach($crudeRows as $row)
                                    <div class="op-bar-item" title="{{ $row['year'] }}: {{ $formatNumber($row['value']) }}">
                                        <div class="op-bar-value">{{ $formatNumber($row['value']) }}</div>
                                        <div class="op-bar-track">
                                            <div class="op-bar op-bar--crude" style="--height: {{ $row['percent'] }}%;"></div>
                                        </div>
                                        <div class="op-bar-year">{{ $row['year'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="op-chart-legend">
                            <span class="op-chart-legend-dot op-chart-legend-dot--crude"></span>
                            <span>{{ $opText['crudeDataset'] ?? 'Produksi' }}</span>
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
                    @if($vitolRows->count())
                        <div class="op-chart-box">
                            <div class="op-chart-scale" aria-hidden="true">
                                @foreach($vitolScale as $scale)
                                    <div class="op-chart-scale-item">{{ $formatNumber($scale['value']) }}</div>
                                @endforeach
                            </div>

                            <div class="op-css-chart" aria-label="{{ $opText['vitolTitle'] ?? 'Tren Tahunan VITOL' }}">
                                @foreach($vitolRows as $row)
                                    <div class="op-bar-item" title="{{ $row['year'] }}: {{ $formatNumber($row['value']) }}">
                                        <div class="op-bar-value">{{ $formatNumber($row['value']) }}</div>
                                        <div class="op-bar-track">
                                            <div class="op-bar op-bar--vitol" style="--height: {{ $row['percent'] }}%;"></div>
                                        </div>
                                        <div class="op-bar-year">{{ $row['year'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="op-chart-legend">
                            <span class="op-chart-legend-dot op-chart-legend-dot--vitol"></span>
                            <span>{{ $opText['vitolDataset'] ?? 'Quantity' }}</span>
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
@endsection