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

    background:
        radial-gradient(ellipse 65% 50% at 0% 5%, rgba(212,168,67,.09) 0%, transparent 55%),
        radial-gradient(ellipse 55% 45% at 100% 95%, rgba(47,125,50,.11) 0%, transparent 55%),
        radial-gradient(ellipse 45% 40% at 55% 25%, rgba(47,125,50,.06) 0%, transparent 55%),
        linear-gradient(155deg, #f9fbf8 0%, #eff7ee 35%, #e9f5e8 65%, #f3f9f0 100%);
}

.auth-page::before{
    content:'';
    position:absolute;inset:0;
    background-image:radial-gradient(circle, rgba(47,125,50,.07) 1px, transparent 1px);
    background-size:26px 26px;
    pointer-events:none;z-index:0;
}

/* ─── Blobs ─────────────────────────────────────────────────── */
.auth-blob{
    position:absolute;border-radius:50%;
    pointer-events:none;z-index:0;
    animation:blobFloat 11s ease-in-out infinite;
}

.auth-blob-1{
    width:440px;height:440px;left:-160px;top:-100px;
    background:radial-gradient(circle,rgba(47,125,50,.07) 0%,transparent 70%);
    animation-delay:0s;
}
.auth-blob-2{
    width:280px;height:280px;right:-60px;top:35%;
    background:radial-gradient(circle,rgba(212,168,67,.08) 0%,transparent 70%);
    animation-delay:-4s;
}
.auth-blob-3{
    width:360px;height:360px;left:25%;bottom:-130px;
    background:radial-gradient(circle,rgba(47,125,50,.06) 0%,transparent 70%);
    animation-delay:-7s;
}

@keyframes blobFloat{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-18px)}
}

/* ─── Wrapper ───────────────────────────────────────────────── */
.login-wrapper{
    position:relative;z-index:2;
    width:100%;
    min-height:calc(100vh - 78px);
    display:flex;align-items:center;justify-content:center;
    padding:48px 16px 56px;
}

/* ─── Card ──────────────────────────────────────────────────── */
.login-card{
    width:100%;max-width:420px;
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
        radial-gradient(ellipse 80% 65% at 95% 8%,rgba(255,255,255,.12) 0%,transparent 100%),
        radial-gradient(ellipse 50% 70% at 4% 98%,rgba(255,255,255,.10) 0%,transparent 100%),
        linear-gradient(138deg,#0d2705 0%,#163906 48%,#226824 80%,#2f7d32 100%);
    position:relative;overflow:hidden;
}

.login-header::after{
    content:'';position:absolute;
    top:0;left:-75%;width:45%;height:100%;
    background:linear-gradient(100deg,transparent 30%,rgba(255,255,255,.07) 50%,transparent 70%);
    pointer-events:none;
}

.login-brand{display:flex;align-items:center;gap:11px;margin-bottom:16px;position:relative;z-index:1}

.login-brand-logo{
    width:40px;height:40px;object-fit:contain;flex-shrink:0;
    filter:brightness(1.1) drop-shadow(0 2px 6px rgba(0,0,0,.22));
}

.login-brand-name{font-size:13px;font-weight:800;color:var(--gold-lt);line-height:1.2;letter-spacing:-.01em}
.login-brand-sub{font-size:10px;color:rgba(255,255,255,.50);font-style:italic;display:block;margin-top:2px}

.login-title{font-size:22px;font-weight:800;color:#fff;letter-spacing:-.03em;line-height:1.2;margin:0;position:relative;z-index:1}
.login-subtitle{font-size:12.5px;color:rgba(255,255,255,.64);margin:6px 0 0;line-height:1.55;position:relative;z-index:1}

/* ─── Body ──────────────────────────────────────────────────── */
.login-body{padding:26px 30px 30px}

/* ─── Alert ─────────────────────────────────────────────────── */
.error-alert{
    display:flex;align-items:flex-start;gap:10px;
    background:#fef2f2;color:#b91c1c;
    padding:12px 14px;border-radius:12px;font-size:13px;
    margin-bottom:20px;border:1px solid #fecaca;line-height:1.55;
    animation:fadeDown .2s ease both;
}

@keyframes fadeDown{
    from{opacity:0;transform:translateY(-5px)}
    to{opacity:1;transform:none}
}

.error-alert-dot{width:6px;height:6px;border-radius:50%;background:#ef4444;flex-shrink:0;margin-top:5px}

/* ─── Form ──────────────────────────────────────────────────── */
.form-group{margin-bottom:16px}

.form-label{
    display:block;font-size:11.5px;font-weight:800;color:var(--text2);
    margin-bottom:7px;letter-spacing:.05em;text-transform:uppercase;
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

/* ─── Password strength ─────────────────────────────────────── */
.pw-strength{display:flex;gap:5px;margin-top:8px;height:3px}
.pw-strength-seg{flex:1;border-radius:3px;background:var(--line);transition:background .25s}
.pw-strength-seg.active-weak  {background:#ef4444}
.pw-strength-seg.active-fair  {background:#f59e0b}
.pw-strength-seg.active-good  {background:#22c55e}
.pw-strength-seg.active-strong{background:var(--g500)}

.pw-strength-label{font-size:11px;color:var(--text3);margin-top:5px;min-height:1em;transition:color .25s}

/* ─── Terms ─────────────────────────────────────────────────── */
.terms-note{
    font-size:12px;color:var(--text3);line-height:1.65;
    margin-bottom:20px;padding:11px 13px;
    background:rgba(47,125,50,.04);border-radius:10px;
    border-left:3px solid var(--g200);
}
.terms-note a{color:var(--g700);font-weight:700;text-decoration:none}
.terms-note a:hover{text-decoration:underline}

/* ─── Submit ────────────────────────────────────────────────── */
.btn-submit{
    width:100%;min-height:46px;padding:0 16px;
    background:var(--g700);color:#fff;
    font-family:var(--font);font-weight:800;font-size:14px;
    border:none;border-radius:12px;cursor:pointer;letter-spacing:.03em;
    position:relative;overflow:hidden;
    transition:background .18s,box-shadow .18s,transform .12s;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.14),0 2px 8px rgba(30,82,16,.18);
}
.btn-submit::after{
    content:'';position:absolute;inset:0;
    background:linear-gradient(180deg,rgba(255,255,255,.11) 0%,transparent 55%);
    pointer-events:none;
}
.btn-submit:hover{
    background:var(--g800);transform:translateY(-1px);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.12),0 6px 18px rgba(30,82,16,.24);
}
.btn-submit:active{transform:none;box-shadow:inset 0 1px 0 rgba(255,255,255,.10)}

/* ─── Divider ───────────────────────────────────────────────── */
.login-divider{
    display:flex;align-items:center;gap:12px;
    margin:20px 0;color:var(--text3);font-size:12px;letter-spacing:.04em;
}
.login-divider::before,.login-divider::after{content:'';flex:1;height:1px;background:var(--line)}

/* ─── Login link ────────────────────────────────────────────── */
.register-link{text-align:center;font-size:13px;color:var(--text3);margin:0}
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
    .btn-submit{min-height:50px;font-size:15px}
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
                <h1 class="login-title">Buat Akun Baru</h1>
                <p class="login-subtitle">Isi data di bawah untuk mendaftar</p>
            </div>

            <div class="login-body">

                @if($errors->any())
                    <div class="error-alert" role="alert">
                        <span class="error-alert-dot" aria-hidden="true"></span>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" novalidate>
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="form-input" required autocomplete="name" autocapitalize="words"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-input" required autocomplete="email" inputmode="email"
                            placeholder="nama@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-input" required autocomplete="new-password"
                            placeholder="Minimal 8 karakter">
                        <div class="pw-strength" id="pwStrength" aria-hidden="true">
                            <div class="pw-strength-seg" id="seg1"></div>
                            <div class="pw-strength-seg" id="seg2"></div>
                            <div class="pw-strength-seg" id="seg3"></div>
                            <div class="pw-strength-seg" id="seg4"></div>
                        </div>
                        <div class="pw-strength-label" id="pwLabel" aria-live="polite"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Ulangi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-input" required autocomplete="new-password"
                            placeholder="Ketik ulang password">
                    </div>

                    <div class="terms-note">
                        Dengan mendaftar, Anda menyetujui
                        <a href="{{ route('legal.terms', ['locale' => app()->getLocale()]) }}">Syarat &amp; Ketentuan</a>
                        serta
                        <a href="{{ route('legal.privacy', ['locale' => app()->getLocale()]) }}">Kebijakan Privasi</a>
                        PT Bumi Siak Pusako Zapin.
                    </div>

                    <button type="submit" class="btn-submit">Daftar Sekarang</button>
                </form>

                <div class="login-divider" aria-hidden="true">atau</div>

                <p class="register-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </p>

            </div>
        </div>
    </div>
</div>

<script>
(function(){
    'use strict';

    var pw    = document.getElementById('password');
    var segs  = ['seg1','seg2','seg3','seg4'].map(function(id){ return document.getElementById(id); });
    var label = document.getElementById('pwLabel');
    var cls   = ['active-weak','active-fair','active-good','active-strong'];
    var txt   = ['Lemah','Sedang','Kuat','Sangat Kuat'];
    var clr   = ['#ef4444','#f59e0b','#22c55e','var(--g600)'];

    function strength(v){
        if(!v) return 0;
        var s=0;
        if(v.length>=8)  s++;
        if(v.length>=12) s++;
        if(/[A-Z]/.test(v)&&/[a-z]/.test(v)) s++;
        if(/[0-9]/.test(v)||/[^A-Za-z0-9]/.test(v)) s++;
        return s;
    }

    if(pw){
        pw.addEventListener('input',function(){
            var s=strength(this.value);
            segs.forEach(function(seg,i){
                seg.className='pw-strength-seg';
                if(i<s) seg.classList.add(cls[s-1]);
            });
            if(label){
                label.textContent=this.value?txt[s-1]||'':'';
                label.style.color=this.value?clr[s-1]:'';
            }
        });
    }
})();
</script>
@endsection