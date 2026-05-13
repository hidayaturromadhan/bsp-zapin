@extends('layouts.app')

@section('content')
<style>
/* ─── Reset ─────────────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box}

html,body{
    margin:0!important;
    padding:0!important;
    overflow-x:hidden;
}

.n-main{
    max-width:none!important;
    width:100%!important;
    margin:0!important;
    padding:0!important;
    overflow:visible!important;
}

/* ─── Page shell ────────────────────────────────────────────── */
.auth-page{
    position:relative;
    width:100vw;
    min-height:calc(100vh - 78px);
    margin-left:calc(50% - 50vw)!important;
    margin-right:calc(50% - 50vw)!important;
    overflow:hidden;

    /* Light / white gradient bg */
    background:
        radial-gradient(ellipse 65% 50% at 0% 5%, rgba(212,168,67,.09) 0%, transparent 55%),
        radial-gradient(ellipse 55% 45% at 100% 95%, rgba(47,125,50,.11) 0%, transparent 55%),
        radial-gradient(ellipse 45% 40% at 55% 25%, rgba(47,125,50,.06) 0%, transparent 55%),
        linear-gradient(155deg, #f9fbf8 0%, #eff7ee 35%, #e9f5e8 65%, #f3f9f0 100%);
}

/* Subtle dot-grid overlay for texture */
.auth-page::before{
    content:'';
    position:absolute;
    inset:0;
    background-image:radial-gradient(circle, rgba(47,125,50,.07) 1px, transparent 1px);
    background-size:26px 26px;
    pointer-events:none;
    z-index:0;
}

/* ─── Blobs ─────────────────────────────────────────────────── */
.auth-blob{
    position:absolute;
    border-radius:50%;
    pointer-events:none;
    z-index:0;
    animation:blobFloat 11s ease-in-out infinite;
}

.auth-blob-1{
    width:440px;height:440px;
    left:-160px;top:-100px;
    background:radial-gradient(circle, rgba(47,125,50,.07) 0%, transparent 70%);
    animation-delay:0s;
}

.auth-blob-2{
    width:280px;height:280px;
    right:-60px;top:35%;
    background:radial-gradient(circle, rgba(212,168,67,.08) 0%, transparent 70%);
    animation-delay:-4s;
}

.auth-blob-3{
    width:360px;height:360px;
    left:25%;bottom:-130px;
    background:radial-gradient(circle, rgba(47,125,50,.06) 0%, transparent 70%);
    animation-delay:-7s;
}

@keyframes blobFloat{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-18px)}
}

/* ─── Wrapper — this must fill the page vertically ──────────── */
.login-wrapper{
    position:relative;
    z-index:2;
    width:100%;
    min-height:calc(100vh - 78px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px 16px 56px;
}

/* ─── Card ──────────────────────────────────────────────────── */
.login-card{
    width:100%;
    max-width:400px;
    background:#fff;
    border:1px solid rgba(47,125,50,.13);
    border-radius:22px;
    box-shadow:
        0 1px 3px rgba(0,0,0,.04),
        0 6px 20px rgba(47,125,50,.07),
        0 20px 56px rgba(0,0,0,.07),
        inset 0 1px 0 rgba(255,255,255,.9);
    overflow:hidden;
    animation:cardIn .42s cubic-bezier(.22,.68,0,1.15) both;
}

@keyframes cardIn{
    from{opacity:0;transform:translateY(16px) scale(.978)}
    to{opacity:1;transform:none}
}

/* ─── Header ────────────────────────────────────────────────── */
.login-header{
    padding:28px 30px 26px;
    background:
        radial-gradient(ellipse 80% 65% at 95% 8%, rgba(255,255,255,.12) 0%, transparent 100%),
        radial-gradient(ellipse 50% 70% at 4% 98%, rgba(255,255,255,.10) 0%, transparent 100%),
        linear-gradient(138deg, #0d2705 0%, #163906 48%, #226824 80%, #2f7d32 100%);
    position:relative;
    overflow:hidden;
}

.login-header::after{
    content:'';
    position:absolute;
    top:0;left:-75%;
    width:45%;height:100%;
    background:linear-gradient(100deg, transparent 30%, rgba(255,255,255,.07) 50%, transparent 70%);
    pointer-events:none;
}

.login-brand{
    display:flex;align-items:center;gap:11px;
    margin-bottom:16px;
    position:relative;z-index:1;
}

.login-brand-logo{
    width:40px;height:40px;
    object-fit:contain;flex-shrink:0;
    filter:brightness(1.1) drop-shadow(0 2px 6px rgba(0,0,0,.22));
}

.login-brand-name{
    font-size:13px;font-weight:800;
    color:var(--gold-lt);
    line-height:1.2;letter-spacing:-.01em;
}

.login-brand-sub{
    font-size:10px;color:rgba(255,255,255,.50);
    font-style:italic;display:block;margin-top:2px;
}

.login-title{
    font-size:22px;font-weight:800;
    color:#fff;letter-spacing:-.03em;line-height:1.2;
    margin:0;position:relative;z-index:1;
}

.login-subtitle{
    font-size:12.5px;color:rgba(255,255,255,.64);
    margin:6px 0 0;line-height:1.55;
    position:relative;z-index:1;
}

/* ─── Body ──────────────────────────────────────────────────── */
.login-body{padding:26px 30px 30px}

/* ─── Alerts ────────────────────────────────────────────────── */
.error-alert,.success-alert{
    display:flex;align-items:flex-start;gap:10px;
    padding:12px 14px;border-radius:12px;
    font-size:13px;margin-bottom:20px;line-height:1.55;
    animation:fadeDown .2s ease both;
}

@keyframes fadeDown{
    from{opacity:0;transform:translateY(-5px)}
    to{opacity:1;transform:none}
}

.error-alert{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
.success-alert{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0}

.error-alert-dot,.success-alert-dot{
    width:6px;height:6px;border-radius:50%;flex-shrink:0;margin-top:5px;
}
.error-alert-dot{background:#ef4444}
.success-alert-dot{background:#22c55e}

/* ─── Force login ───────────────────────────────────────────── */
.force-login-box{
    background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;
    padding:13px 15px;margin-bottom:18px;color:#9a3412;font-size:13px;line-height:1.6;
}
.force-login-title{font-weight:800;color:#7c2d12;margin-bottom:4px;font-size:13.5px}

/* ─── Form ──────────────────────────────────────────────────── */
.form-group{margin-bottom:16px}

.form-label{
    display:block;font-size:11.5px;font-weight:800;
    color:var(--text2);margin-bottom:7px;
    letter-spacing:.05em;text-transform:uppercase;
}

.form-input{
    width:100%;height:46px;padding:0 14px;
    border:1.5px solid var(--line);border-radius:12px;
    font-family:var(--font);font-size:14px;color:var(--text);
    background:#fff;outline:none;
    transition:border-color .15s,box-shadow .15s,background .15s;
}

.form-input::placeholder{color:var(--text3)}

.form-input:focus{
    border-color:var(--g500);
    box-shadow:0 0 0 3.5px rgba(47,125,50,.13);
    background:var(--g50);
}

/* ─── Password toggle ───────────────────────────────────────── */
.password-wrap{position:relative}
.password-wrap .form-input{padding-right:48px}

.password-toggle{
    position:absolute;top:50%;right:10px;
    width:34px;height:34px;transform:translateY(-50%);
    border:none;background:transparent;color:var(--text3);cursor:pointer;
    display:inline-flex;align-items:center;justify-content:center;
    border-radius:9px;transition:background .15s,color .15s;padding:0;
}
.password-toggle:hover{background:var(--line2);color:var(--text)}
.password-toggle svg{width:18px;height:18px;display:block}
.password-toggle .eye-off{display:none}
.password-toggle.is-visible .eye{display:none}
.password-toggle.is-visible .eye-off{display:block}

/* ─── Remember me + forgot ─────────────────────────────────── */
.form-options{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.form-check{
    display:flex;
    align-items:center;
    gap:8px;
    margin:0;
}

.form-check input[type="checkbox"]{
    width:16px;
    height:16px;
    accent-color:var(--g500);
    cursor:pointer;
    flex-shrink:0;
}

.form-check label{
    font-size:13px;
    color:var(--text3);
    cursor:pointer;
    user-select:none;
}

.forgot-link{
    font-size:13px;
    color:var(--g700);
    font-weight:800;
    text-decoration:none;
    transition:color .13s;
    white-space:nowrap;
}

.forgot-link:hover{
    color:var(--g900);
    text-decoration:underline;
}

/* ─── Submit ────────────────────────────────────────────────── */
.btn-submit{
    width:100%;min-height:46px;padding:0 16px;
    background:var(--g700);color:#fff;
    font-family:var(--font);font-weight:800;font-size:14px;
    border:none;border-radius:12px;cursor:pointer;letter-spacing:.03em;
    position:relative;overflow:hidden;
    transition:background .18s,box-shadow .18s,transform .12s;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.14), 0 2px 8px rgba(30,82,16,.18);
}
.btn-submit::after{
    content:'';position:absolute;inset:0;
    background:linear-gradient(180deg,rgba(255,255,255,.11) 0%,transparent 55%);
    pointer-events:none;
}
.btn-submit:hover{
    background:var(--g800);transform:translateY(-1px);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.12), 0 6px 18px rgba(30,82,16,.24);
}
.btn-submit:active{transform:none;box-shadow:inset 0 1px 0 rgba(255,255,255,.10)}
.btn-submit.is-force{background:#dc2626;box-shadow:inset 0 1px 0 rgba(255,255,255,.12),0 2px 8px rgba(220,38,38,.18)}
.btn-submit.is-force:hover{background:#b91c1c;box-shadow:inset 0 1px 0 rgba(255,255,255,.10),0 6px 18px rgba(220,38,38,.24)}

/* ─── Divider ───────────────────────────────────────────────── */
.login-divider{
    display:flex;align-items:center;gap:12px;
    margin:20px 0;color:var(--text3);font-size:12px;letter-spacing:.04em;
}
.login-divider::before,.login-divider::after{content:'';flex:1;height:1px;background:var(--line)}

/* ─── Google ────────────────────────────────────────────────── */
.btn-google{
    display:flex;align-items:center;justify-content:center;gap:10px;
    width:100%;min-height:46px;padding:0 16px;
    background:#fff;border:1.5px solid var(--line);border-radius:12px;
    font-family:var(--font);font-size:14px;font-weight:700;color:var(--text2);
    cursor:pointer;text-decoration:none;
    transition:background .15s,border-color .15s,box-shadow .15s,transform .12s;
}
.btn-google:hover{
    background:#f9fafb;border-color:#d1d5db;color:var(--text);
    box-shadow:0 3px 10px rgba(0,0,0,.07);transform:translateY(-1px);
}
.btn-google:active{transform:none;box-shadow:none}
.btn-google-icon{width:18px;height:18px;flex-shrink:0}

/* ─── Register link ─────────────────────────────────────────── */
.register-link{text-align:center;font-size:13px;color:var(--text3);margin-top:20px;margin-bottom:0}
.register-link a{color:var(--g700);font-weight:800;text-decoration:none;transition:color .13s}
.register-link a:hover{color:var(--g900);text-decoration:underline}

/* ─── Mobile ────────────────────────────────────────────────── */
@media(max-width:480px){
    .auth-page,.login-wrapper{min-height:calc(100vh - 70px)}
    .login-wrapper{padding:28px 14px 40px}
    .login-card{border-radius:20px}
    .login-header{padding:24px 22px 22px}
    .login-header::after{display:none}
    .login-body{padding:22px 22px 26px}
    .login-title{font-size:20px}
    .form-input{height:50px;font-size:15px}
    .btn-submit,.btn-google{min-height:50px;font-size:15px}

    .form-options{
        align-items:flex-start;
        flex-direction:column;
        gap:10px;
    }
}

@media(min-width:481px) and (max-width:768px){
    .login-wrapper{padding:36px 24px 44px}
}
</style>

<div class="auth-page">
    <span class="auth-blob auth-blob-1"></span>
    <span class="auth-blob auth-blob-2"></span>
    <span class="auth-blob auth-blob-3"></span>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-header">
                <div class="login-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin" class="login-brand-logo" width="40" height="40" loading="eager">
                    <div>
                        <span class="login-brand-name">PT Bumi Siak Pusako Zapin</span>
                        <span class="login-brand-sub">the energy company</span>
                    </div>
                </div>
                <h1 class="login-title">Masuk ke Akun</h1>
                <p class="login-subtitle">Gunakan kredensial atau akun Google Anda untuk melanjutkan</p>
            </div>

            <div class="login-body">

                @if($errors->any())
                    <div class="error-alert" role="alert">
                        <span class="error-alert-dot" aria-hidden="true"></span>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="success-alert" role="status">
                        <span class="success-alert-dot" aria-hidden="true"></span>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->has('force'))
                    <div class="force-login-box" role="alert">
                        <div class="force-login-title">Akun masih aktif di perangkat lain</div>
                        <div>Masukkan password kembali, lalu klik tombol merah di bawah untuk mengambil alih sesi lama dan masuk di perangkat ini.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" id="loginForm" novalidate>
                    @csrf
                    <input type="hidden" name="force_login" value="{{ $errors->has('force') ? 1 : 0 }}">

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-input" required autocomplete="email" inputmode="email"
                            placeholder="nama@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password"
                                class="form-input" required autocomplete="current-password"
                                placeholder="Masukkan password">
                            <button type="button" class="password-toggle" data-target="password" aria-label="Tampilkan password">
                                <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M3 3l18 18"/>
                                    <path d="M10.6 10.6A3 3 0 0 0 14 14"/>
                                    <path d="M9.9 4.25A10.7 10.7 0 0 1 12 4c6.5 0 10 8 10 8a18.3 18.3 0 0 1-3.1 4.5"/>
                                    <path d="M6.6 6.6C3.7 8.6 2 12 2 12s3.5 8 10 8a10.7 10.7 0 0 0 4.4-.95"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Ingat saya di perangkat ini</label>
                        </div>

                        @if(Route::has('password.forgot.form'))
                            <a href="{{ route('password.forgot.form') }}" class="forgot-link">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-submit {{ $errors->has('force') ? 'is-force' : '' }}">
                        {{ $errors->has('force') ? 'Paksa Login di Perangkat Ini' : 'Masuk' }}
                    </button>
                </form>

                <div class="login-divider" aria-hidden="true">atau</div>

                <a href="{{ route('google.redirect') }}" class="btn-google">
                    <svg class="btn-google-icon" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                        <path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
                        <path d="M3.964 10.71A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                        <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
                    </svg>
                    Masuk dengan Google
                </a>

                @if(Route::has('register'))
                    <p class="register-link">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                    </p>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';
    document.querySelectorAll('.password-toggle').forEach(function(btn){
        btn.addEventListener('click', function(){
            var input = document.getElementById(btn.getAttribute('data-target'));
            if(!input) return;
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('is-visible', show);
            btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });
})();
</script>
@endsection