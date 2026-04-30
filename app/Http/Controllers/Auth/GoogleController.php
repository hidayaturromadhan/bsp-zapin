<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    private int $inactiveLimitMinutes = 3;

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (! $googleUser->getEmail()) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun Google tidak memiliki email yang valid.',
                    ]);
            }

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (! $user) {
                $user = User::create([
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(str()->random(32)),
                    'role' => 'pelapor',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
            } else {
                $user->forceFill([
                    'google_id' => $user->google_id ?: $googleUser->getId(),
                    'name' => $user->name ?: ($googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User'),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ])->save();
            }

            if (! $user->is_active) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun Anda nonaktif.',
                    ]);
            }

            if ($user->active_session_id && $this->isDeviceGone($user)) {
                $user->forceFill([
                    'active_session_id' => null,
                    'active_login_at' => null,
                ])->save();
            }

            if ($user->active_session_id) {
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun ini masih aktif di perangkat lain.',
                    ]);
            }

            Auth::login($user, true);

            request()->session()->regenerate();

            $user->forceFill([
                'active_session_id' => request()->session()->getId(),
                'active_login_at' => now(),
            ])->save();

            request()->session()->put([
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_name' => $user->name,
            ]);

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'reviewer' => redirect()->route('reviewer.dashboard'),
                'writer' => redirect()->route('writer.dashboard'),
                'operational' => redirect()->route('operational.dashboard'),
                'wbs_admin', 'wbs_officer' => redirect()->route('wbs.admin.dashboard'),
                'pelapor' => redirect()->route('wbs.pelapor.dashboard'),
                default => redirect()->route('wbs.pelapor.dashboard'),
            };
        } catch (Throwable $e) {
            Log::error('Google Login Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Login Google gagal: ' . $e->getMessage(),
                ]);
        }
    }

    private function isDeviceGone(User $user): bool
    {
        if (! $user->active_login_at) {
            return true;
        }

        return $user->active_login_at->lt(now()->subMinutes($this->inactiveLimitMinutes));
    }
}