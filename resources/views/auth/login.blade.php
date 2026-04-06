@extends('layouts.app')

@section('content')
<style>
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 72vh;
        padding: 24px 16px;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,.07), 0 2px 8px rgba(0,0,0,.04);
        overflow: hidden;
    }

    /* Card header strip */
    .login-header {
        background: linear-gradient(135deg, var(--g800) 0%, var(--g700) 60%, var(--g500) 100%);
        padding: 32px 32px 28px;
        position: relative;
        overflow: hidden;
    }

    .login-header::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }

    .login-header::after {
        content: '';
        position: absolute;
        bottom: -24px;
        left: 20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }

    .login-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        position: relative;
        z-index: 1;
    }

    .login-brand-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
        filter: brightness(1.15) drop-shadow(0 2px 4px rgba(0,0,0,.2));
    }

    .login-brand-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--gold-lt);
        line-height: 1.2;
        letter-spacing: -.01em;
    }

    .login-brand-sub {
        font-size: 10.5px;
        color: rgba(255,255,255,.45);
        font-style: italic;
        display: block;
        margin-top: 2px;
    }

    .login-title {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.02em;
        line-height: 1.2;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .login-subtitle {
        font-size: 13px;
        color: rgba(255,255,255,.55);
        margin-top: 5px;
        position: relative;
        z-index: 1;
    }

    /* Card body */
    .login-body {
        padding: 30px 32px 32px;
    }

    /* Error alert */
    .error-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #fef2f2;
        color: #b91c1c;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 22px;
        border: 1px solid #fecaca;
        line-height: 1.5;
    }

    .error-alert-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #ef4444;
        flex-shrink: 0;
        margin-top: 5px;
    }

    /* Google SSO button */
    .btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 11px 14px;
        background: var(--white);
        border: 1.5px solid var(--line);
        border-radius: 10px;
        font-family: var(--font);
        font-size: 14px;
        font-weight: 600;
        color: var(--text2);
        cursor: pointer;
        text-decoration: none;
        transition: background .15s, border-color .15s, box-shadow .15s, transform .1s;
        margin-bottom: 20px;
    }

    .btn-google:hover {
        background: var(--line2);
        border-color: #d1d5db;
        box-shadow: 0 2px 8px rgba(0,0,0,.07);
        transform: translateY(-1px);
        color: var(--text);
    }

    .btn-google:active {
        transform: translateY(0);
        box-shadow: none;
    }

    .btn-google-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* Form */
    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--text2);
        margin-bottom: 7px;
        letter-spacing: .02em;
        text-transform: uppercase;
    }

    .form-input {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--line);
        border-radius: 10px;
        font-family: var(--font);
        font-size: 14px;
        color: var(--text);
        background: var(--white);
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
        box-sizing: border-box;
    }

    .form-input::placeholder { color: var(--text3); }

    .form-input:focus {
        border-color: var(--g500);
        box-shadow: 0 0 0 3px rgba(47,125,50,.12);
        background: var(--g50);
    }

    /* Checkbox row */
    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
    }

    .form-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--g500);
        cursor: pointer;
        flex-shrink: 0;
    }

    .form-check label {
        font-size: 13px;
        color: var(--text3);
        cursor: pointer;
        user-select: none;
    }

    /* Submit button */
    .btn-submit {
        width: 100%;
        padding: 12px;
        background: var(--g700);
        color: #fff;
        font-family: var(--font);
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        letter-spacing: .02em;
        transition: background .18s, box-shadow .18s, transform .1s;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,.08) 0%, transparent 60%);
        pointer-events: none;
    }

    .btn-submit:hover {
        background: var(--g800);
        box-shadow: 0 4px 16px rgba(30,82,16,.28);
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: translateY(0);
        box-shadow: none;
    }

    /* Divider */
    .login-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 22px 0;
        color: var(--text3);
        font-size: 12px;
    }

    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--line);
    }

    /* Register link */
    .register-link {
        text-align: center;
        font-size: 13.5px;
        color: var(--text3);
    }

    .register-link a {
        color: var(--g700);
        font-weight: 700;
        text-decoration: none;
        transition: color .13s;
    }

    .register-link a:hover {
        color: var(--g900);
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-header { padding: 26px 22px 22px; }
        .login-body { padding: 24px 22px 26px; }
        .login-title { font-size: 19px; }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        {{-- Header --}}
        <div class="login-header">
            <div class="login-brand">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin" class="login-brand-logo">
                <div>
                    <span class="login-brand-name">PT Bumi Siak Pusako Zapin</span>
                    <span class="login-brand-sub">the energy company</span>
                </div>
            </div>
            <h1 class="login-title">Masuk ke Akun</h1>
            <p class="login-subtitle">Gunakan kredensial Anda untuk melanjutkan</p>
        </div>

        {{-- Body --}}
        <div class="login-body">

            @if($errors->any())
                <div class="error-alert">
                    <span class="error-alert-dot"></span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input"
                        required
                        autocomplete="email"
                        placeholder="nama@email.com"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                    >
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <div class="login-divider">atau</div>

            {{-- Google SSO --}}
            <a href="#" class="btn-google">
                <svg class="btn-google-icon" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
                    <path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            @if(Route::has('register'))
                <p class="register-link" style="margin-top:20px;">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </p>
            @endif

        </div>
    </div>
</div>
@endsection