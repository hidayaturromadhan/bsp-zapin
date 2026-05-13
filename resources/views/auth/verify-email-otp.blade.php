<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email WBS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Open Sans", "Segoe UI", system-ui, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .16), transparent 34%),
                linear-gradient(135deg, #eff6ff 0%, #f8fafc 48%, #ffffff 100%);
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 22px;
        }

        .otp-page {
            width: 100%;
            max-width: 480px;
        }

        .otp-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .14);
            overflow: hidden;
        }

        .otp-head {
            padding: 26px 28px;
            background: #2563eb;
            color: #ffffff;
        }

        .otp-title {
            margin: 0;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .otp-subtitle {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, .86);
            font-size: 14px;
            line-height: 1.7;
        }

        .otp-body {
            padding: 28px;
        }

        .otp-info {
            margin: 0 0 18px;
            font-size: 14px;
            color: #475569;
            line-height: 1.75;
        }

        .otp-info strong {
            color: #0f172a;
            font-weight: 900;
        }

        .otp-alert-success,
        .otp-alert-error {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 13.5px;
            line-height: 1.6;
            font-weight: 700;
        }

        .otp-alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .otp-alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .otp-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 900;
            color: #334155;
        }

        .otp-input {
            width: 100%;
            height: 56px;
            border: 1.5px solid #cbd5e1;
            border-radius: 16px;
            padding: 0 16px;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 8px;
            text-align: center;
            color: #0f172a;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .otp-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .16);
            background: #f8fafc;
        }

        .otp-help {
            margin-top: 8px;
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.6;
        }

        .otp-actions {
            display: grid;
            gap: 10px;
            margin-top: 20px;
        }

        .otp-btn {
            min-height: 46px;
            border: 1px solid transparent;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 900;
            text-decoration: none;
            transition: transform .18s ease, background .18s ease, border-color .18s ease;
        }

        .otp-btn:hover {
            transform: translateY(-1px);
        }

        .otp-btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 12px 28px rgba(37, 99, 235, .22);
        }

        .otp-btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .otp-btn-light {
            background: #ffffff;
            border-color: #cbd5e1;
            color: #334155;
        }

        .otp-btn-light:hover {
            background: #f8fafc;
        }

        .otp-footer {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #64748b;
        }

        .otp-footer form {
            margin: 0;
        }

        .otp-link-btn {
            border: 0;
            background: transparent;
            padding: 0;
            color: #2563eb;
            font-weight: 900;
            cursor: pointer;
            font: inherit;
        }

        @media (max-width: 520px) {
            body {
                padding: 14px;
            }

            .otp-head,
            .otp-body {
                padding: 22px;
            }

            .otp-title {
                font-size: 21px;
            }

            .otp-input {
                font-size: 20px;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>
    <main class="otp-page">
        <div class="otp-card">
            <div class="otp-head">
                <h1 class="otp-title">Verifikasi Email</h1>
                <p class="otp-subtitle">
                    Masukkan kode OTP yang dikirim ke email untuk mengaktifkan akun WBS.
                </p>
            </div>

            <div class="otp-body">
                @if(session('success'))
                    <div class="otp-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="otp-alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <p class="otp-info">
                    Kode OTP dikirim ke email:
                    <br>
                    <strong>{{ auth()->user()?->email }}</strong>
                </p>

                <form method="POST" action="{{ route('verification.otp.verify') }}">
                    @csrf

                    <label for="otp_code" class="otp-label">Kode OTP</label>
                    <input
                        type="text"
                        name="otp_code"
                        id="otp_code"
                        class="otp-input"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        placeholder="______"
                        required
                        autofocus
                    >

                    <div class="otp-help">
                        Kode berlaku selama 10 menit. Cek inbox atau folder spam jika email belum terlihat.
                    </div>

                    <div class="otp-actions">
                        <button type="submit" class="otp-btn otp-btn-primary">
                            Verifikasi Email
                        </button>
                    </div>
                </form>

                <div class="otp-footer">
                    <form method="POST" action="{{ route('verification.otp.resend') }}">
                        @csrf
                        <button type="submit" class="otp-link-btn">
                            Kirim ulang kode
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="otp-link-btn">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>