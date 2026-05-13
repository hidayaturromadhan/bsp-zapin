<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Laravel Auth dengan Session Manual
        |--------------------------------------------------------------------------
        | Jika Laravel Auth belum membaca user, tapi session manual user_id masih ada,
        | maka sistem mencoba login ulang user tersebut.
        |--------------------------------------------------------------------------
        */
        if (! $user && $request->session()->has('user_id')) {
            $sessionUserId = $request->session()->get('user_id');

            $sessionUser = User::query()
                ->where('id', $sessionUserId)
                ->where('is_active', true)
                ->first();

            if ($sessionUser) {
                Auth::login($sessionUser);
                $user = $sessionUser;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Jika Tetap Tidak Ada User
        |--------------------------------------------------------------------------
        | Bersihkan session manual agar tidak terjadi redirect loop.
        |--------------------------------------------------------------------------
        */
        if (! $user) {
            $request->session()->forget([
                'user_id',
                'user_role',
                'user_name',
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Jika Akun Nonaktif
        |--------------------------------------------------------------------------
        */
        if (! $user->is_active) {
            Auth::logout();

            $request->session()->forget([
                'user_id',
                'user_role',
                'user_name',
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Anda nonaktif.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Sinkronkan Session Manual
        |--------------------------------------------------------------------------
        | Session manual hanya sebagai pelengkap agar kode lama tetap berjalan.
        |--------------------------------------------------------------------------
        */
        $request->session()->put([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
        ]);

        return $next($request);
    }
}