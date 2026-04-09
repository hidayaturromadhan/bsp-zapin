@extends('layouts.app')

@section('content')
@php
    $locale = in_array(request()->segment(1), ['id', 'en']) ? request()->segment(1) : 'id';
    $isEn   = $locale === 'en';
@endphp

<style>
    .err-page {
        min-height: 72vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 24px;
    }

    .err-inner {
        max-width: 540px;
        width: 100%;
        text-align: center;
    }

    .err-illustration {
        width: 180px;
        height: 180px;
        margin: 0 auto 32px;
        position: relative;
    }

    .err-illustration svg {
        width: 100%;
        height: 100%;
    }

    .err-code {
        font-size: clamp(72px, 14vw, 120px);
        font-weight: 900;
        line-height: 1;
        letter-spacing: -.06em;
        background: linear-gradient(135deg, #173f08 0%, #4a9e2f 60%, #d4a843 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 8px;
        user-select: none;
    }

    .err-divider {
        width: 48px;
        height: 3px;
        border-radius: 999px;
        background: linear-gradient(90deg, #173f08, #d4a843);
        margin: 0 auto 20px;
    }

    .err-title {
        font-size: clamp(20px, 3vw, 26px);
        font-weight: 800;
        color: #111827;
        margin: 0 0 12px;
        letter-spacing: -.03em;
    }

    .err-desc {
        font-size: 15px;
        line-height: 1.85;
        color: #6b7280;
        margin: 0 auto 32px;
        max-width: 400px;
    }

    .err-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .err-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 46px;
        padding: 0 22px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all .15s ease;
    }

    .err-btn--primary {
        background: #173f08;
        color: #fff;
        border: 1px solid #173f08;
        box-shadow: 0 4px 14px rgba(23,63,8,.22);
    }

    .err-btn--primary:hover {
        background: #21560e;
        border-color: #21560e;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(23,63,8,.28);
    }

    .err-btn--secondary {
        background: #fff;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .err-btn--secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        transform: translateY(-2px);
    }

    .err-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 999px;
        background: #fef9ec;
        border: 1px solid #fde68a;
        color: #92400e;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .err-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #d97706;
        flex-shrink: 0;
    }
</style>

<div class="err-page">
    <div class="err-inner">

        {{-- Illustration --}}
        <div class="err-illustration">
            <svg viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Ground circle -->
                <ellipse cx="90" cy="158" rx="58" ry="10" fill="#eef5eb"/>
                <!-- Page body -->
                <rect x="38" y="28" width="104" height="130" rx="12" fill="#f0f4ef" stroke="#d1deca" stroke-width="1.5"/>
                <!-- Lines on page -->
                <rect x="54" y="52" width="72" height="7" rx="3.5" fill="#d8e6d4"/>
                <rect x="54" y="66" width="56" height="7" rx="3.5" fill="#d8e6d4"/>
                <rect x="54" y="80" width="64" height="7" rx="3.5" fill="#d8e6d4"/>
                <!-- Torn bottom -->
                <path d="M38 128 Q47 122 56 128 Q65 134 74 128 Q83 122 92 128 Q101 134 110 128 Q119 122 128 128 Q137 134 142 128 L142 158 Q137 152 128 158 Q119 164 110 158 Q101 152 92 158 Q83 164 74 158 Q65 152 56 158 Q47 164 38 158 Z" fill="#e8f0e5" stroke="#d1deca" stroke-width="1"/>
                <!-- X mark circle -->
                <circle cx="90" cy="100" r="22" fill="#fef2f2" stroke="#fca5a5" stroke-width="1.5"/>
                <line x1="82" y1="92" x2="98" y2="108" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="98" y1="92" x2="82" y2="108" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
                <!-- Stars -->
                <circle cx="32" cy="50" r="3" fill="#d4a843" opacity=".7"/>
                <circle cx="150" cy="40" r="2" fill="#4a9e2f" opacity=".6"/>
                <circle cx="155" cy="68" r="3.5" fill="#d4a843" opacity=".5"/>
                <circle cx="28" cy="80" r="2.5" fill="#4a9e2f" opacity=".5"/>
            </svg>
        </div>

        <div class="err-badge">
            <span class="err-badge-dot"></span>
            {{ $isEn ? 'Error 404' : 'Error 404' }}
        </div>

        <div class="err-code">404</div>
        <div class="err-divider"></div>

        <h1 class="err-title">
            {{ $isEn ? 'Page Not Found' : 'Halaman Tidak Ditemukan' }}
        </h1>
        <p class="err-desc">
            {{ $isEn
                ? 'The page you are looking for may have been moved, deleted, or never existed. Please check the URL or go back to home.'
                : 'Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau tidak pernah ada. Periksa kembali URL atau kembali ke beranda.' }}
        </p>

        <div class="err-actions">
            <a href="{{ route('web.home', ['locale' => $locale]) }}" class="err-btn err-btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                {{ $isEn ? 'Back to Home' : 'Kembali ke Beranda' }}
            </a>
            <a href="javascript:history.back()" class="err-btn err-btn--secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                {{ $isEn ? 'Go Back' : 'Kembali' }}
            </a>
        </div>

    </div>
</div>
@endsection