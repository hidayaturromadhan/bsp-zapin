<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionHeartbeatController extends Controller
{
    public function ping(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'ok' => false,
                'authenticated' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'ok' => false,
                'authenticated' => false,
                'inactive' => true,
                'message' => 'Akun Anda sedang dinonaktifkan.',
                'redirect' => route('login'),
            ], 403);
        }

        $currentSessionId = $request->session()->getId();

        if (
            !empty($user->active_session_id) &&
            $user->active_session_id !== $currentSessionId
        ) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'ok' => false,
                'authenticated' => false,
                'session_expired' => true,
                'message' => 'Sesi Anda telah berakhir karena akun digunakan di perangkat lain.',
                'redirect' => route('login'),
            ], 419);
        }

        $user->forceFill([
            'active_login_at' => now(),
        ])->save();

        return response()->json([
            'ok' => true,
            'authenticated' => true,
            'message' => 'Session active.',
        ]);
    }
}