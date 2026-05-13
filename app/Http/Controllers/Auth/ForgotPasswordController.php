<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:190'],
        ]);

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        | Jika email tidak ditemukan / akun nonaktif, tetap tampilkan pesan sukses.
        | Tujuannya agar orang lain tidak bisa menebak email mana yang terdaftar.
        |--------------------------------------------------------------------------
        */
        if (! $user || ! $user->is_active) {
            return back()->with(
                'success',
                'Jika email terdaftar, link reset password akan dikirim ke email tersebut.'
            );
        }

        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($plainToken),
            'created_at' => now(),
        ]);

        $resetUrl = route('password.reset.form', [
            'token' => $plainToken,
            'email' => $user->email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Queue Email
        |--------------------------------------------------------------------------
        | Email reset password masuk ke tabel jobs dan diproses oleh queue worker.
        |--------------------------------------------------------------------------
        */
        Mail::to($user->email)->queue(new ForgotPasswordMail($user, $resetUrl));

        return back()->with(
            'success',
            'Jika email terdaftar, link reset password akan dikirim ke email tersebut.'
        );
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = trim((string) $request->query('email'));

        if ($email === '') {
            return redirect()
                ->route('password.forgot.form')
                ->withErrors([
                    'email' => 'Link reset password tidak valid.',
                ]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:190'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->first();

        if (! $resetRecord) {
            return back()
                ->withInput([
                    'email' => $data['email'],
                ])
                ->withErrors([
                    'email' => 'Token reset password tidak valid atau sudah kedaluwarsa.',
                ]);
        }

        $createdAt = $resetRecord->created_at
            ? now()->parse($resetRecord->created_at)
            : null;

        if (! $createdAt || $createdAt->lt(now()->subMinutes(60))) {
            DB::table('password_reset_tokens')
                ->where('email', $data['email'])
                ->delete();

            return redirect()
                ->route('password.forgot.form')
                ->withErrors([
                    'email' => 'Link reset password sudah kedaluwarsa. Silakan minta link baru.',
                ]);
        }

        if (! Hash::check($data['token'], $resetRecord->token)) {
            return back()
                ->withInput([
                    'email' => $data['email'],
                ])
                ->withErrors([
                    'email' => 'Token reset password tidak valid.',
                ]);
        }

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if (! $user) {
            return redirect()
                ->route('password.forgot.form')
                ->withErrors([
                    'email' => 'Akun tidak ditemukan.',
                ]);
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),

            /*
            |--------------------------------------------------------------------------
            | Reset Session Aktif
            |--------------------------------------------------------------------------
            | Karena sistem kamu memakai one account one device,
            | setelah password diganti sesi lama wajib dibersihkan.
            |--------------------------------------------------------------------------
            */
            'active_session_id' => null,
            'active_login_at' => null,
        ])->save();

        DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->delete();

        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login menggunakan password baru.');
    }
}