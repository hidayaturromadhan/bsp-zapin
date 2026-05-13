<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Akun BSP Zapin</title>
</head>
<body style="margin:0; padding:0; background:#f3f6fb; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb; padding:28px 12px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px; background:#ffffff; border-radius:18px; overflow:hidden; border:1px solid #e2e8f0; box-shadow:0 18px 44px rgba(15,23,42,.10);">
                    <tr>
                        <td style="padding:26px 28px; background:linear-gradient(135deg,#0d2705,#163906,#2f7d32); color:#ffffff;">
                            <div style="font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#f6d28b;">
                                PT Bumi Siak Pusako Zapin
                            </div>

                            <h1 style="margin:8px 0 0; font-size:22px; line-height:1.25; color:#ffffff;">
                                Reset Password Akun
                            </h1>

                            <p style="margin:7px 0 0; font-size:13px; line-height:1.6; color:rgba(255,255,255,.78);">
                                Permintaan pengaturan ulang password akun Anda.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 14px; font-size:15px; line-height:1.7; color:#0f172a;">
                                Halo <strong>{{ $user->name ?? 'Pengguna' }}</strong>,
                            </p>

                            <p style="margin:0 0 18px; font-size:14px; line-height:1.8; color:#475569;">
                                Kami menerima permintaan untuk mengatur ulang password akun Anda.
                                Silakan klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            <div style="text-align:center; margin:28px 0;">
                                <a href="{{ $resetUrl }}" style="display:inline-block; padding:14px 24px; background:#1f5f20; color:#ffffff; text-decoration:none; border-radius:12px; font-size:14px; font-weight:800;">
                                    Reset Password
                                </a>
                            </div>

                            <p style="margin:0 0 14px; font-size:13px; line-height:1.8; color:#64748b;">
                                Link reset password ini berlaku selama <strong>60 menit</strong>.
                                Jika Anda tidak meminta reset password, abaikan email ini.
                            </p>

                            <div style="margin-top:20px; padding:14px 16px; border-radius:12px; background:#f8fafc; border:1px solid #e2e8f0;">
                                <p style="margin:0 0 8px; font-size:12px; line-height:1.6; color:#64748b;">
                                    Jika tombol tidak dapat diklik, salin link berikut ke browser:
                                </p>

                                <p style="margin:0; font-size:12px; line-height:1.7; color:#2563eb; word-break:break-all;">
                                    {{ $resetUrl }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:16px 28px; background:#f8fafc; border-top:1px solid #e2e8f0;">
                            <p style="margin:0; font-size:12px; line-height:1.7; color:#94a3b8;">
                                Email ini dikirim otomatis oleh sistem BSP Zapin. Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="margin:18px 0 0; font-size:12px; color:#94a3b8;">
                    © {{ date('Y') }} PT Bumi Siak Pusako Zapin
                </p>
            </td>
        </tr>
    </table>
</body>
</html>