<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'title_en',
        'link_url',
        'sort_order',
        'is_active',
        'image_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getTitleByLocale(string $locale = 'id'): ?string
    {
        if ($locale === 'en') {
            return $this->title_en ?: $this->title;
        }

        return $this->title;
    }
}