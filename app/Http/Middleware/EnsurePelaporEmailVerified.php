<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePelaporEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Hindari Redirect Loop
        |--------------------------------------------------------------------------
        | Route verifikasi OTP, resend OTP, dan logout tidak boleh dipaksa redirect
        | lagi ke halaman verifikasi.
        |--------------------------------------------------------------------------
        */
        if ($request->routeIs(
            'verification.otp.form',
            'verification.otp.verify',
            'verification.otp.resend',
            'logout'
        )) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Khusus Pelapor Wajib Verifikasi Email
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'pelapor' && ! $user->email_verified_at) {
            return redirect()
                ->route('verification.otp.form')
                ->with('success', 'Silakan verifikasi email terlebih dahulu.');
        }

        return $next($request);
    }
}