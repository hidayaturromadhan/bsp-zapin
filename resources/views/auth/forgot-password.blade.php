@extends('layouts.app')

@section('content')
<style>
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
    position:absolute;
    inset:0;
    background-image:radial-gradient(circle, rgba(47,125,50,.07) 1px, transparent 1px);
    background-size:26px 26px;
    pointer-events:none;
    z-index:0;
}

.auth-blob{
    position:absolute;
    border-radius:50%;
    pointer-events:none;
    z-index:0;
    animation:blobFloat 11s ease-in-out infinite;
}

.auth-blob-1{
    width:440px;
    height:440px;
    left:-160px;
    top:-100px;
    background:radial-gradient(circle, rgba(47,125,50,.07) 0%, transparent 70%);
    animation-delay:0s;
}

.auth-blob-2{
    width:280px;
    height:280px;
    right:-60px;
    top:35%;
    background:radial-gradient(circle, rgba(212,168,67,.08) 0%, transparent 70%);
    animation-delay:-4s;
}

.auth-blob-3{
    width:360px;
    height:360px;
    left:25%;
    bottom:-130px;
    background:radial-gradient(circle, rgba(47,125,50,.06) 0%, transparent 70%);
    animation-delay:-7s;
}

@keyframes blobFloat{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-18px)}
}

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

.login-card{
    width:100%;
    max-width:420px;
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

.login-header{
    padding:28px 30px 26px;
    background:
        radial-gradient(ellipse 80% 65% at 95% 8%, rgba(255,255,255,.12) 0%, transparent 100%),
        radial-gradient(ellipse 50% 70% at 4% 98%, rgba(255,255,255,.10) 0%, transparent 100%),
        linear-gradient(138deg, #0d2705 0%, #163906 48%, #226824 80%, #2f7d32 100%);
    position:relative;
    overflow:hidden;
}

.login-brand{
    display:flex;
    align-items:center;
    gap:11px;
    margin-bottom:16px;
    position:relative;
    z-index:1;
}

.login-brand-logo{
    width:40px;
    height:40px;
    object-fit:contain;
    flex-shrink:0;
    filter:brightness(1.1) drop-shadow(0 2px 6px rgba(0,0,0,.22));
}

.login-brand-name{
    font-size:13px;
    font-weight:800;
    color:#f6d28b;
    line-height:1.2;
    letter-spacing:-.01em;
}

.login-brand-sub{
    font-size:10px;
    color:rgba(255,255,255,.50);
    font-style:italic;
    display:block;
    margin-top:2px;
}

.login-title{
    font-size:22px;
    font-weight:800;
    color:#fff;
    letter-spacing:-.03em;
    line-height:1.2;
    margin:0;
    position:relative;
    z-index:1;
}

.login-subtitle{
    font-size:12.5px;
    color:rgba(255,255,255,.64);
    margin:6px 0 0;
    line-height:1.55;
    position:relative;
    z-index:1;
}

.login-body{
    padding:26px 30px 30px;
}

.error-alert,.success-alert{
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:12px 14px;
    border-radius:12px;
    font-size:13px;
    margin-bottom:20px;
    line-height:1.55;
    animation:fadeDown .2s ease both;
}

@keyframes fadeDown{
    from{opacity:0;transform:translateY(-5px)}
    to{opacity:1;transform:none}
}

.error-alert{
    background:#fef2f2;
    color:#b91c1c;
    border:1px solid #fecaca;
}

.success-alert{
    background:#f0fdf4;
    color:#166534;
    border:1px solid #bbf7d0;
}

.error-alert-dot,.success-alert-dot{
    width:6px;
    height:6px;
    border-radius:50%;
    flex-shrink:0;
    margin-top:5px;
}

.error-alert-dot{background:#ef4444}
.success-alert-dot{background:#22c55e}

.form-group{
    margin-bottom:16px;
}

.form-label{
    display:block;
    font-size:11.5px;
    font-weight:800;
    color:#475569;
    margin-bottom:7px;
    letter-spacing:.05em;
    text-transform:uppercase;
}

.form-input{
    width:100%;
    height:46px;
    padding:0 14px;
    border:1.5px solid #d7ded3;
    border-radius:12px;
    font-family:inherit;
    font-size:14px;
    color:#0f172a;
    background:#fff;
    outline:none;
    transition:border-color .15s,box-shadow .15s,background .15s;
}

.form-input::placeholder{
    color:#94a3b8;
}

.form-input:focus{
    border-color:#2f7d32;
    box-shadow:0 0 0 3.5px rgba(47,125,50,.13);
    background:#f8fbf7;
}

.form-help{
    margin-top:8px;
    color:#64748b;
    font-size:12.5px;
    line-height:1.6;
}

.btn-submit{
    width:100%;
    min-height:46px;
    padding:0 16px;
    background:#1f5f20;
    color:#fff;
    font-family:inherit;
    font-weight:800;
    font-size:14px;
    border:none;
    border-radius:12px;
    cursor:pointer;
    letter-spacing:.03em;
    position:relative;
    overflow:hidden;
    transition:background .18s,box-shadow .18s,transform .12s;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.14), 0 2px 8px rgba(30,82,16,.18);
}

.btn-submit:hover{
    background:#173f08;
    transform:translateY(-1px);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.12), 0 6px 18px rgba(30,82,16,.24);
}

.auth-link-wrap{
    margin-top:18px;
    text-align:center;
    font-size:13px;
    color:#64748b;
}

.auth-link-wrap a{
    color:#1f5f20;
    font-weight:800;
    text-decoration:none;
}

.auth-link-wrap a:hover{
    color:#0d2705;
    text-decoration:underline;
}

@media(max-width:480px){
    .auth-page,.login-wrapper{
        min-height:calc(100vh - 70px);
    }

    .login-wrapper{
        padding:28px 14px 40px;
    }

    .login-card{
        border-radius:20px;
    }

    .login-header{
        padding:24px 22px 22px;
    }

    .login-body{
        padding:22px 22px 26px;
    }

    .login-title{
        font-size:20px;
    }

    .form-input{
        height:50px;
        font-size:15px;
    }

    .btn-submit{
        min-height:50px;
        font-size:15px;
    }
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

                <h1 class="login-title">Lupa Password</h1>
                <p class="login-subtitle">Masukkan email akun Anda untuk menerima link reset password.</p>
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

                <form method="POST" action="{{ route('password.forgot.send') }}" novalidate>
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
                            inputmode="email"
                            placeholder="nama@email.com"
                            autofocus
                        >

                        <div class="form-help">
                            Link reset password berlaku selama 60 menit dan akan dikirim ke email jika akun terdaftar.
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="auth-link-wrap">
                    Sudah ingat password?
                    <a href="{{ route('login') }}">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection