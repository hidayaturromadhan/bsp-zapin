<?php

namespace App\Http\Controllers\Wbs;

use App\Http\Controllers\Controller;
use App\Models\WbsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function open(WbsNotification $notification)
    {
        abort_if((int) $notification->user_id !== (int) Auth::id(), 403, 'Anda tidak memiliki akses ke notifikasi ini.');

        if (! $notification->read_at) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        return redirect($notification->url ?: $this->fallbackUrl());
    }

    public function markAllRead(Request $request)
    {
        WbsNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }

    private function fallbackUrl(): string
    {
        $role = Auth::user()?->role;

        return match ($role) {
            'wbs_admin', 'wbs_officer' => route('wbs.admin.dashboard'),
            'pelapor' => route('wbs.pelapor.dashboard'),
            default => route('login'),
        };
    }
}