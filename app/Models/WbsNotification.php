<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbsNotification extends Model
{
    protected $table = 'wbs_notifications';

    protected $fillable = [
        'user_id',
        'actor_id',
        'wbs_report_id',
        'title',
        'message',
        'url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(WbsReport::class, 'wbs_report_id');
    }

    public function getIsReadAttribute(): bool
    {
        return ! is_null($this->read_at);
    }
}