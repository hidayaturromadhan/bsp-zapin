<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (
            ! $user ||
            ! $user->is_active ||
            ! Hash::check($data['password'], $user->password)
        ) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah, atau akun nonaktif.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // LOGIN KE GUARD LARAVEL JUGA
        Auth::login($user);

        // SESSION CUSTOM TETAP DIPERTAHANKAN
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_name', $user->name);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'reviewer') {
            return redirect()->route('reviewer.dashboard');
        }

        if ($user->role === 'writer') {
            return redirect()->route('writer.dashboard');
        }

        if ($user->role === 'operational') {
            return redirect()->route('operational.dashboard');
        }

        if ($user->role === 'wbs_officer') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('web.home', [
            'locale' => 'id',
        ]);
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
            'role' => 'user',
            'is_active' => true,
        ]);

        $request->session()->regenerate();

        Auth::login($user);

        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);
        $request->session()->put('user_name', $user->name);

        return redirect()->route('web.home', [
            'locale' => 'id',
        ]);
    }

    public function logout(Request $request)
    {
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