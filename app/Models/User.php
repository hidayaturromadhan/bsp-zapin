<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATIONAL = 'operational';
    public const ROLE_WRITER = 'writer';
    public const ROLE_REVIEWER = 'reviewer';
    public const ROLE_PELAPOR = 'pelapor';
    public const ROLE_WBS_ADMIN = 'wbs_admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'is_active',
        'active_session_id',
        'active_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public static function roleOptions(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_OPERATIONAL => 'Operational',
            self::ROLE_WRITER => 'Writer',
            self::ROLE_REVIEWER => 'Reviewer',
            self::ROLE_PELAPOR => 'Pelapor',
            self::ROLE_WBS_ADMIN => 'WBS Admin',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }

    public function roleLabel(): string
    {
        return self::roleOptions()[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role ?? '-'));
    }
}