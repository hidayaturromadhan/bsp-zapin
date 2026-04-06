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

    .login-body {
        padding: 30px 32px 32px;
    }

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

    /* Two-column grid for name+email */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .form-group {
        margin-bottom: 16px;
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

    /* Password strength bar */
    .pw-strength {
        display: flex;
        gap: 4px;
        margin-top: 8px;
        height: 3px;
    }

    .pw-strength-seg {
        flex: 1;
        border-radius: 2px;
        background: var(--line);
        transition: background .25s;
    }

    .pw-strength-seg.active-weak   { background: #ef4444; }
    .pw-strength-seg.active-fair   { background: #f59e0b; }
    .pw-strength-seg.active-good   { background: #22c55e; }
    .pw-strength-seg.active-strong { background: var(--g500); }

    /* Terms note */
    .terms-note {
        font-size: 12px;
        color: var(--text3);
        line-height: 1.6;
        margin-bottom: 20px;
        padding: 10px 13px;
        background: var(--line2);
        border-radius: 8px;
        border-left: 3px solid var(--g200);
    }

    .terms-note a {
        color: var(--g700);
        font-weight: 600;
        text-decoration: none;
    }

    .terms-note a:hover { text-decoration: underline; }

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

    @media (max-width: 480px) {
        .login-header { padding: 26px 22px 22px; }
        .login-body { padding: 24px 22px 26px; }
        .login-title { font-size: 19px; }
        .form-row { grid-template-columns: 1fr; gap: 0; }
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
            <h1 class="login-title">Buat Akun Baru</h1>
            <p class="login-subtitle">Isi data di bawah untuk mendaftar</p>
        </div>

        {{-- Body --}}
        <div class="login-body">

            @if($errors->any())
                <div class="error-alert">
                    <span class="error-alert-dot"></span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-input"
                        required
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                    >
                </div>

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
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                    >
                    <div class="pw-strength" id="pwStrength" aria-hidden="true">
                        <div class="pw-strength-seg" id="seg1"></div>
                        <div class="pw-strength-seg" id="seg2"></div>
                        <div class="pw-strength-seg" id="seg3"></div>
                        <div class="pw-strength-seg" id="seg4"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Ulangi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                        required
                        autocomplete="new-password"
                        placeholder="Ketik ulang password"
                    >
                </div>

                <div class="terms-note">
                    Dengan mendaftar, Anda menyetujui
                    <a href="{{ route('legal.terms', ['locale' => app()->getLocale()]) }}">Syarat & Ketentuan</a>
                    serta
                    <a href="{{ route('legal.privacy', ['locale' => app()->getLocale()]) }}">Kebijakan Privasi</a>
                    PT Bumi Siak Pusako Zapin.
                </div>

                <button type="submit" class="btn-submit">Daftar Sekarang</button>
            </form>

            <div class="login-divider">atau</div>
            <p class="register-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </p>

        </div>
    </div>
</div>

<script>
(function () {
    var pw = document.getElementById('password');
    var segs = [
        document.getElementById('seg1'),
        document.getElementById('seg2'),
        document.getElementById('seg3'),
        document.getElementById('seg4'),
    ];
    var classes = ['active-weak', 'active-fair', 'active-good', 'active-strong'];

    function getStrength(val) {
        if (!val) return 0;
        var score = 0;
        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++;
        return score;
    }

    if (pw) {
        pw.addEventListener('input', function () {
            var s = getStrength(this.value);
            segs.forEach(function (seg, i) {
                seg.className = 'pw-strength-seg';
                if (i < s) seg.classList.add(classes[s - 1]);
            });
        });
    }
})();
</script>
@endsection