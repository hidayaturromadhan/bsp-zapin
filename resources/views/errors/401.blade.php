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
            radial-gradient(circle at 15% 20%, rgba(59,130,246,.30), transparent 30%),
            radial-gradient(circle at 85% 15%, rgba(37,99,235,.25), transparent 32%),
            linear-gradient(135deg, #1e3a8a 0%, #1e40af 45%, #2563eb 100%);
    }

    .error-card {
        width: 100%;
        max-width: 520px;
        background: rgba(255,255,255,.95);
        backdrop-filter: blur(16px);
        border-radius: 28px;
        padding: 42px 34px;
        text-align: center;
        box-shadow: 0 28px 80px rgba(0,0,0,.25);
    }

    .error-icon {
        width: 86px;
        height: 86px;
        margin: 0 auto 22px;
        border-radius: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: #fff;
        box-shadow: 0 16px 34px rgba(37,99,235,.4);
    }

    .error-code {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .error-title {
        margin: 0;
        font-size: 30px;
        font-weight: 900;
        color: var(--text);
    }

    .error-text {
        margin: 14px auto 26px;
        font-size: 14px;
        color: var(--text3);
    }

    .btn-primary {
        display: inline-block;
        padding: 12px 18px;
        border-radius: 12px;
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: #1e40af;
    }
</style>

<section class="error-section">
    <div class="error-card">
        <div class="error-icon">
            🔒
        </div>

        <div class="error-code">ERROR 401</div>

        <h1 class="error-title">Unauthorized</h1>

        <p class="error-text">
            Anda belum login atau sesi telah berakhir.
            Silakan login terlebih dahulu untuk mengakses halaman ini.
        </p>

        <a href="{{ route('login') }}" class="btn-primary">
            Login Sekarang
        </a>
    </div>
</section>
@endsection