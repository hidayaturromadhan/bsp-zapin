<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private int $inactiveLimitMinutes = 3;

    public function showLogin(Request $request)
    {
        // Simpan redirect tujuan (kalau ada dari query ?redirect=)
        if ($request->filled('redirect')) {
            $request->session()->put('login_redirect_url', $request->query('redirect'));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! $user->is_active || ! Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah, atau akun nonaktif.',
                ])
                ->onlyInput('email');
        }

        if ($user->active_session_id && $this->isDeviceGone($user)) {
            $user->forceFill([
                'active_session_id' => null,
                'active_login_at' => null,
            ])->save();
        }

        if ($user->active_session_id) {
            return back()
                ->withErrors([
                    'email' => 'Akun ini masih aktif di perangkat lain.',
                ])
                ->onlyInput('email');
        }

        Auth::login($user);

        $request->session()->regenerate();

        $user->forceFill([
            'active_session_id' => $request->session()->getId(),
            'active_login_at' => now(),
        ])->save();

        $request->session()->put([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
        ]);

        // DEFAULT REDIRECT
        $defaultRedirect = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'reviewer' => route('reviewer.dashboard'),
            'writer' => route('writer.dashboard'),
            'operational' => route('operational.dashboard'),
            'wbs_admin', 'wbs_officer' => route('wbs.admin.dashboard'),
            'pelapor' => route('wbs.pelapor.dashboard'),
            default => route('web.home', ['locale' => 'id']),
        };

        // AMBIL REDIRECT DARI SESSION
        $redirectUrl = $request->session()->pull('login_redirect_url');

        if ($redirectUrl && str_starts_with($redirectUrl, url('/'))) {
            return redirect()->to($redirectUrl);
        }

        return redirect()->to($defaultRedirect);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pelapor',
            'is_active' => true,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        $user->forceFill([
            'active_session_id' => $request->session()->getId(),
            'active_login_at' => now(),
        ])->save();

        $request->session()->put([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
        ]);

        return redirect()->intended(route('wbs.pelapor.dashboard'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->active_session_id === $request->session()->getId()) {
            $user->forceFill([
                'active_session_id' => null,
                'active_login_at' => null,
            ])->save();
        }

        Auth::logout();

        $request->session()->forget([
            'user_id',
            'user_role',
            'user_name',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function isDeviceGone(User $user): bool
    {
        if (! $user->active_login_at) {
            return true;
        }

        return $user->active_login_at->lt(now()->subMinutes($this->inactiveLimitMinutes));
    }
}