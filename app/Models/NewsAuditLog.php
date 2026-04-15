<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsAuditLog extends Model
{
    protected $fillable = [
        'news_id',
        'user_id',
        'action',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}