<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'news_category_id',
        'status',
        'published_at',
        'is_featured',
        'is_visible',
        'featured_image',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class);
    }

    public function images()
    {
        return $this->hasMany(NewsImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function getTranslationByLocale(string $locale = 'id'): ?NewsTranslation
    {
        if (! $this->relationLoaded('translations')) {
            $this->load('translations');
        }

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id');
    }
}