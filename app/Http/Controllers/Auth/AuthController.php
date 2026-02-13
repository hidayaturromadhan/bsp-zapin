<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->is_active || !Hash::check($data['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah, atau akun nonaktif.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);

        return redirect()->route('home');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:190','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        // Default role untuk WBS user umum
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'is_active' => true,
        ]);

        // Auto-login setelah register (boleh kalau mau)
        $request->session()->regenerate();
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_role', $user->role);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'user_role']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
