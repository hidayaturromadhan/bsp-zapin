<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    private int $inactiveLimitMinutes = 3;

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            $userId = $request->session()->get('user_id');

            if ($userId) {
                $user = User::find($userId);

                if ($user) {
                    Auth::login($user);
                }
            }
        }

        if (! $user) {
            $this->clearBrowserSession($request);

            return redirect($this->loginUrl($request))
                ->withErrors([
                    'email' => 'Silakan login terlebih dahulu.',
                ]);
        }

        $currentSessionId = $request->session()->getId();

        if ($user->active_session_id && $this->isDeviceGone($user)) {
            $oldSessionId = $user->active_session_id;

            $user->forceFill([
                'active_session_id' => null,
                'active_login_at' => null,
            ])->save();

            if ($oldSessionId === $currentSessionId) {
                $this->clearBrowserSession($request);

                return redirect($this->loginUrl($request))
                    ->withErrors([
                        'email' => 'Sesi Anda berakhir karena perangkat tidak aktif.',
                    ]);
            }
        }

        if ($user->active_session_id && $user->active_session_id !== $currentSessionId) {
            $this->clearBrowserSession($request);

            return redirect($this->loginUrl($request))
                ->withErrors([
                    'email' => 'Akun ini sedang aktif di perangkat lain.',
                ]);
        }

        if (! $user->active_session_id) {
            $user->forceFill([
                'active_session_id' => $currentSessionId,
                'active_login_at' => now(),
            ])->save();
        }

        $user->forceFill([
            'active_login_at' => now(),
        ])->save();

        $request->session()->put([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
        ]);

        $currentRole = $request->session()->get('user_role') ?: $user->role;

        if (! empty($roles) && ! in_array($currentRole, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    private function isDeviceGone(User $user): bool
    {
        if (! $user->active_login_at) {
            return true;
        }

        return $user->active_login_at->lt(now()->subMinutes($this->inactiveLimitMinutes));
    }

    private function clearBrowserSession(Request $request): void
    {
        Auth::logout();

        $request->session()->forget([
            'user_id',
            'user_role',
            'user_name',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    private function loginUrl(Request $request): string
    {
        $locale = $request->route('locale') ?? session('locale', 'id');

        if (! in_array($locale, ['id', 'en'], true)) {
            $locale = 'id';
        }

        if (Route::has('login')) {
            try {
                return route('login', ['locale' => $locale]);
            } catch (\Throwable $e) {
                return route('login');
            }
        }

        return '/login';
    }
}