<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
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
            'force_login' => ['nullable', 'boolean'],
        ]);

        $force = $request->boolean('force_login');

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! $user->is_active || ! Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah, atau akun nonaktif.',
                ])
                ->onlyInput('email');
        }

        $currentSessionId = $request->session()->getId();

        if (
            $user->active_session_id &&
            $user->active_session_id !== $currentSessionId &&
            ! $force
        ) {
            return back()
                ->withErrors([
                    'email' => 'Akun ini masih aktif di perangkat lain.',
                    'force' => 'Silakan masukkan password kembali, lalu klik tombol Paksa Login di Perangkat Ini.',
                ])
                ->withInput([
                    'email' => $data['email'],
                    'remember' => $request->boolean('remember'),
                ]);
        }

        if (
            $user->active_session_id &&
            $user->active_session_id !== $currentSessionId &&
            $force
        ) {
            $user->forceFill([
                'active_session_id' => null,
                'active_login_at' => null,
            ])->save();
        }

        Auth::login($user, $request->boolean('remember'));

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

        $defaultRedirect = match ($user->role) {
            'admin' => route('admin.dashboard'),
            'reviewer' => route('reviewer.dashboard'),
            'writer' => route('writer.dashboard'),
            'operational' => route('operational.dashboard'),
            'wbs_admin', 'wbs_officer' => route('wbs.admin.dashboard'),
            'pelapor' => route('wbs.pelapor.dashboard'),
            default => route('web.home', ['locale' => 'id']),
        };

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

        return redirect()->route('wbs.pelapor.dashboard');
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
}