<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error | BSP Zapin</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, .18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(21, 128, 61, .16), transparent 34%),
                linear-gradient(135deg, #f8fafc 0%, #eef7ee 100%);
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px;
        }

        .error-card {
            width: 100%;
            max-width: 760px;
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(226, 232, 240, .9);
            border-radius: 30px;
            box-shadow: 0 28px 80px rgba(15, 23, 42, .14);
            overflow: hidden;
        }

        .error-header {
            padding: 26px 30px;
            background: linear-gradient(135deg, #173f08 0%, #245b12 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-box {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .96);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .16);
        }

        .logo-box img {
            width: 44px;
            height: 44px;
            object-fit: contain;
        }

        .brand-title {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .brand-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: rgba(255, 255, 255, .78);
            font-weight: 600;
        }

        .error-body {
            padding: 42px 34px 36px;
            text-align: center;
        }

        .error-code {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 118px;
            height: 48px;
            padding: 0 22px;
            border-radius: 999px;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            font-size: clamp(30px, 5vw, 46px);
            line-height: 1.08;
            font-weight: 900;
            letter-spacing: -.055em;
            color: #0f172a;
        }

        .desc {
            max-width: 560px;
            margin: 16px auto 0;
            color: #64748b;
            font-size: 15px;
            line-height: 1.8;
            font-weight: 600;
        }

        .safe-note {
            margin: 26px auto 0;
            max-width: 560px;
            padding: 14px 16px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 13px;
            line-height: 1.7;
            font-weight: 600;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 900;
            transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #173f08;
            color: #ffffff;
            box-shadow: 0 14px 30px rgba(23, 63, 8, .22);
        }

        .btn-primary:hover {
            background: #245b12;
        }

        .btn-secondary {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .footer {
            padding: 18px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
        }

        @media (max-width: 640px) {
            body {
                padding: 18px;
            }

            .error-header {
                padding: 22px;
            }

            .error-body {
                padding: 34px 22px 30px;
            }

            .brand-title {
                font-size: 17px;
            }

            .brand-subtitle {
                font-size: 12px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="error-card">
        <header class="error-header">
            <div class="logo-box">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
            </div>

            <div>
                <p class="brand-title">PT Bumi Siak Pusako Zapin</p>
                <p class="brand-subtitle">Official Website</p>
            </div>
        </header>

        <section class="error-body">
            <div class="error-code">Error 500</div>

            <h1>Terjadi gangguan pada server</h1>

            <p class="desc">
                Sistem sedang mengalami kendala sementara. Silakan kembali ke halaman utama
                atau coba muat ulang halaman beberapa saat lagi.
            </p>

            <div class="safe-note">
                Untuk keamanan, detail teknis error tidak ditampilkan kepada publik.
            </div>

            <div class="actions">
                <a href="{{ route('web.home', ['locale' => 'id']) }}" class="btn btn-primary">
                    Kembali ke Beranda
                </a>

                <a href="javascript:window.location.reload()" class="btn btn-secondary">
                    Muat Ulang
                </a>
            </div>
        </section>

        <footer class="footer">
            © {{ date('Y') }} PT Bumi Siak Pusako Zapin. All rights reserved.
        </footer>
    </main>
</body>
</html>