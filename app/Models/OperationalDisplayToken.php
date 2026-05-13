<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OperationalDisplayToken extends Model
{
    protected $fillable = [
        'name',
        'token',
        'is_active',
        'expired_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expired_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expired_at && $this->expired_at->isPast()) {
            return false;
        }

        return true;
    }

    public static function generateSecureToken(): string
    {
        do {
            $token = Str::random(80);
        } while (self::query()->where('token', $token)->exists());

        return $token;
    }

    public function getPublicUrlAttribute(): string
    {
        return route('operational.display.public', $this->token);
    }

    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) {
            return 'Nonaktif';
        }

        if ($this->expired_at && $this->expired_at->isPast()) {
            return 'Kedaluwarsa';
        }

        return 'Aktif';
    }
}