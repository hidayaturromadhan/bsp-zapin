<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi Email WBS</title>
</head>
<body style="margin:0; padding:0; background:#f3f6fb; font-family:Arial, sans-serif; color:#0f172a;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb; padding:28px 12px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:18px; overflow:hidden; border:1px solid #e2e8f0;">
                    <tr>
                        <td style="padding:24px 26px; background:#2563eb; color:#ffffff;">
                            <h1 style="margin:0; font-size:20px;">Verifikasi Email WBS</h1>
                            <p style="margin:6px 0 0; font-size:13px; opacity:.9;">Whistleblowing System BSP Zapin</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px;">
                            <p style="margin:0 0 12px; font-size:15px;">Halo {{ $user->name }},</p>

                            <p style="margin:0 0 18px; font-size:14px; line-height:1.7; color:#475569;">
                                Gunakan kode OTP berikut untuk memverifikasi email akun WBS Anda.
                            </p>

                            <div style="padding:18px; text-align:center; border-radius:14px; background:#eff6ff; border:1px solid #bfdbfe;">
                                <div style="font-size:30px; font-weight:800; letter-spacing:8px; color:#1d4ed8;">
                                    {{ $otpCode }}
                                </div>
                            </div>

                            <p style="margin:18px 0 0; font-size:13px; line-height:1.7; color:#64748b;">
                                Kode ini berlaku selama 10 menit. Jika Anda tidak merasa membuat akun WBS, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:16px 26px; background:#f8fafc; color:#94a3b8; font-size:12px;">
                            Email ini dikirim otomatis oleh sistem WBS BSP Zapin.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>