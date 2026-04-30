@extends('layouts.app')

@section('content')
<style>
    .error-section {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at 15% 20%, rgba(245,185,66,.34), transparent 30%),
            radial-gradient(circle at 85% 15%, rgba(34,197,94,.26), transparent 32%),
            radial-gradient(circle at 70% 90%, rgba(21,128,61,.28), transparent 36%),
            linear-gradient(135deg, var(--g900) 0%, var(--g800) 45%, var(--g600) 100%);
    }

    .error-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(120deg, rgba(255,255,255,.08) 0 1px, transparent 1px 82px),
            linear-gradient(30deg, rgba(255,255,255,.06) 0 1px, transparent 1px 92px);
        opacity: .55;
    }

    .error-card {
        width: 100%;
        max-width: 540px;
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,.94);
        backdrop-filter: blur(18px);
        border: 1px solid rgba(255,255,255,.5);
        border-radius: 28px;
        padding: 42px 34px;
        text-align: center;
        box-shadow: 0 28px 80px rgba(0,0,0,.22);
    }

    .error-icon {
        width: 86px;
        height: 86px;
        margin: 0 auto 22px;
        border-radius: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #d97706, #f59e0b);
        color: #fff;
        box-shadow: 0 16px 34px rgba(217,119,6,.30);
    }

    .error-code {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        background: #fffbeb;
        color: #92400e;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 16px;
        letter-spacing: .04em;
    }

    .error-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #f59e0b;
        box-shadow: 0 0 14px rgba(245,158,11,.8);
    }

    .error-title {
        margin: 0;
        font-size: 30px;
        line-height: 1.15;
        font-weight: 900;
        color: var(--text);
        letter-spacing: -.04em;
    }

    .error-text {
        margin: 13px auto 28px;
        max-width: 420px;
        font-size: 14px;
        line-height: 1.7;
        color: var(--text3);
    }

    .error-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-error-primary,
    .btn-error-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 18px;
        border-radius: 13px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        transition: .18s ease;
    }

    .btn-error-primary {
        background: linear-gradient(135deg, var(--g700), var(--g500));
        color: #fff;
        box-shadow: 0 10px 24px rgba(30,82,16,.25);
    }

    .btn-error-primary:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(30,82,16,.32);
    }

    .btn-error-secondary {
        background: #fff;
        color: var(--text2);
        border: 1.5px solid var(--line);
    }

    .btn-error-secondary:hover {
        color: var(--text);
        background: var(--line2);
        transform: translateY(-1px);
    }

    .error-note {
        margin-top: 24px;
        padding: 12px 14px;
        border-radius: 14px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        font-size: 12.5px;
        line-height: 1.6;
    }

    @media (max-width: 480px) {
        .error-section {
            min-height: calc(100vh - 64px);
            padding: 20px 12px;
        }

        .error-card {
            padding: 34px 22px;
            border-radius: 22px;
        }

        .error-title {
            font-size: 25px;
        }

        .error-icon {
            width: 76px;
            height: 76px;
            border-radius: 22px;
        }
    }
</style>

<section class="error-section">
    <div class="error-card">
        <div class="error-icon">
            <svg width="42" height="42" viewBox="0 0 24 24" fill="none">
                <path d="M12 7v5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M12 16h.01" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/>
                <path d="M6.2 5.5A8.5 8.5 0 1 1 4 12" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
                <path d="M4 4v5h5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <div class="error-code">
            <span class="error-dot"></span>
            ERROR 419
        </div>

        <h1 class="error-title">Sesi Halaman Kedaluwarsa</h1>

        <p class="error-text">
            Halaman ini sudah terlalu lama terbuka atau token keamanan tidak valid.
            Silakan muat ulang halaman, lalu coba kirim form kembali.
        </p>

        <div class="error-actions">
            <a href="{{ route('login') }}" class="btn-error-primary">
                Kembali ke Login
            </a>

            <a href="{{ url()->previous() }}" class="btn-error-secondary">
                Muat Ulang
            </a>
        </div>

        <div class="error-note">
            Tips: jika Anda sedang mengisi form, hindari membiarkan halaman terlalu lama sebelum dikirim.
        </div>
    </div>
</section>
@endsection