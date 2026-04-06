<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
    protected $fillable = [
        'news_id',
        'locale',
        'title',
        'slug',
        'excerpt',
        'content',
        'content_blocks',
    ];

    protected $casts = [
        'content_blocks' => 'array',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}