<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtpMail;
use App\Models\EmailVerificationOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailVerificationOtpController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user && $user->email_verified_at) {
            return redirect()->route('wbs.pelapor.dashboard');
        }

        return view('auth.verify-email-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $otp = EmailVerificationOtp::query()
            ->where('user_id', $user->id)
            ->where('email', $user->email)
            ->where('otp_code', $request->input('otp_code'))
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if (! $otp) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP tidak valid.',
            ]);
        }

        if ($otp->expires_at->isPast()) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode.',
            ]);
        }

        $otp->update([
            'verified_at' => now(),
        ]);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return redirect()
            ->route('wbs.pelapor.dashboard')
            ->with('success', 'Email berhasil diverifikasi.');
    }

    public function resend()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at) {
            return redirect()->route('wbs.pelapor.dashboard');
        }

        $otpCode = (string) random_int(100000, 999999);

        EmailVerificationOtp::query()
            ->where('user_id', $user->id)
            ->whereNull('verified_at')
            ->delete();

        EmailVerificationOtp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        /*
        |--------------------------------------------------------------------------
        | QUEUE EMAIL OTP RESEND
        |--------------------------------------------------------------------------
        | Email resend OTP masuk antrean jobs dan diproses oleh cron queue worker.
        |--------------------------------------------------------------------------
        */
        Mail::to($user->email)->queue(new EmailVerificationOtpMail($user, $otpCode));

        return back()->with('success', 'Kode OTP baru sedang diproses dan akan dikirim ke email Anda.');
    }
}