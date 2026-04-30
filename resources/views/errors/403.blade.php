<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(255, 214, 102, .28), transparent 28%),
                radial-gradient(circle at 85% 15%, rgba(77, 171, 89, .32), transparent 30%),
                linear-gradient(135deg, #102b07 0%, #173f08 45%, #071b03 100%);
            color: #fff;
            overflow: hidden;
        }

        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            position: relative;
        }

        .glow {
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(250, 204, 21, .18);
            filter: blur(22px);
            animation: floatGlow 6s ease-in-out infinite;
        }

        .glow.one {
            top: -120px;
            left: -80px;
        }

        .glow.two {
            right: -120px;
            bottom: -110px;
            background: rgba(34, 197, 94, .2);
            animation-delay: 1.2s;
        }

        @keyframes floatGlow {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(24px) scale(1.06);
            }
        }

        .card {
            width: 100%;
            max-width: 760px;
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 42px 34px;
            border-radius: 28px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .18);
            box-shadow: 0 28px 80px rgba(0, 0, 0, .35);
            backdrop-filter: blur(18px);
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.14), transparent);
            transform: translateX(-100%);
            animation: shine 4s ease-in-out infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }
            45%, 100% {
                transform: translateX(100%);
            }
        }

        .logo-wrap {
            width: 92px;
            height: 92px;
            margin: 0 auto 22px;
            border-radius: 24px;
            background: rgba(255,255,255,.96);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 35px rgba(0,0,0,.25);
            position: relative;
            z-index: 2;
        }

        .logo-wrap img {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .status {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(250, 204, 21, .16);
            border: 1px solid rgba(250, 204, 21, .34);
            color: #fde68a;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #facc15;
            box-shadow: 0 0 0 6px rgba(250, 204, 21, .12);
        }

        h1 {
            position: relative;
            z-index: 2;
            font-size: clamp(54px, 11vw, 104px);
            line-height: .92;
            margin: 0;
            font-weight: 950;
            letter-spacing: -.08em;
            color: #ffffff;
            text-shadow: 0 12px 34px rgba(0,0,0,.34);
        }

        h2 {
            position: relative;
            z-index: 2;
            margin: 18px 0 10px;
            font-size: clamp(24px, 4vw, 36px);
            line-height: 1.18;
            font-weight: 900;
            letter-spacing: -.04em;
        }

        p {
            position: relative;
            z-index: 2;
            max-width: 560px;
            margin: 0 auto;
            color: rgba(255,255,255,.74);
            font-size: 15px;
            line-height: 1.8;
        }

        .actions {
            position: relative;
            z-index: 2;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 850;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn-primary {
            color: #173f08;
            background: #facc15;
            box-shadow: 0 14px 28px rgba(250, 204, 21, .24);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 34px rgba(250, 204, 21, .32);
        }

        .btn-secondary {
            color: #fff;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            background: rgba(255,255,255,.18);
        }

        .hint {
            position: relative;
            z-index: 2;
            margin-top: 22px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(15, 23, 42, .22);
            border: 1px solid rgba(255,255,255,.12);
            color: rgba(255,255,255,.68);
            font-size: 13px;
            line-height: 1.7;
        }

        .orb {
            position: absolute;
            border-radius: 999px;
            background: rgba(255,255,255,.1);
            animation: drift 8s ease-in-out infinite;
        }

        .orb.a {
            width: 18px;
            height: 18px;
            left: 8%;
            top: 22%;
        }

        .orb.b {
            width: 12px;
            height: 12px;
            right: 12%;
            top: 34%;
            animation-delay: 1s;
        }

        .orb.c {
            width: 22px;
            height: 22px;
            right: 18%;
            bottom: 18%;
            animation-delay: 2s;
        }

        @keyframes drift {
            0%, 100% {
                transform: translateY(0);
                opacity: .45;
            }
            50% {
                transform: translateY(-22px);
                opacity: 1;
            }
        }

        @media (max-width: 560px) {
            .error-page {
                padding: 18px;
            }

            .card {
                padding: 34px 22px;
                border-radius: 22px;
            }

            .logo-wrap {
                width: 78px;
                height: 78px;
                border-radius: 20px;
            }

            .logo-wrap img {
                width: 58px;
                height: 58px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="error-page">
        <div class="glow one"></div>
        <div class="glow two"></div>

        <span class="orb a"></span>
        <span class="orb b"></span>
        <span class="orb c"></span>

        <section class="card">
            <div class="logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <div class="status">
                <span class="dot"></span>
                Access Restricted
            </div>

            <h1>403</h1>
            <h2>Anda Tidak Memiliki Akses</h2>

            <p>
                Halaman ini hanya dapat diakses oleh pengguna dengan hak akses tertentu.
                Silakan pastikan akun yang digunakan sudah sesuai dengan role yang dibutuhkan.
            </p>

            <div class="actions">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('login') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <a href="{{ route('login') }}" class="btn btn-primary">
                    Login Ulang
                </a>
            </div>

            <div class="hint">
                Jika Anda merasa seharusnya memiliki akses ke halaman ini, silakan hubungi administrator sistem.
            </div>
        </section>
    </main>
</body>
</html>