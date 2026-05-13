<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                /*
                |--------------------------------------------------------------------------
                | Pelapor Belum Verifikasi Email
                |--------------------------------------------------------------------------
                | Kalau user sudah login dan akses login/register lagi,
                | arahkan ke halaman OTP.
                |--------------------------------------------------------------------------
                */
                if ($user->role === 'pelapor' && ! $user->email_verified_at) {
                    if (! $request->routeIs('verification.otp.form')) {
                        return redirect()->route('verification.otp.form');
                    }

                    return $next($request);
                }

                return redirect()->to($this->redirectPath($user));
            }
        }

        return $next($request);
    }

    private function redirectPath($user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'reviewer' => route('reviewer.dashboard'),
            'writer' => route('writer.dashboard'),
            'operational' => route('operational.dashboard'),
            'wbs_admin', 'wbs_officer' => route('wbs.admin.dashboard'),
            'pelapor' => route('wbs.pelapor.dashboard'),
            default => route('web.home', ['locale' => 'id']),
        };
    }
}