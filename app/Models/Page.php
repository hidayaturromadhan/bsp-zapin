<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'parent_id',
        'menu_group',
        'sort_order',
        'cover_image',
        'is_active',
    ];

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function translation($locale)
    {
        return $this->hasOne(PageTranslation::class)
            ->where('locale', $locale);
    }

    public function getTranslationByLocale(string $locale = 'id'): ?PageTranslation
    {
        if (! $this->relationLoaded('translations')) {
            $this->load('translations');
        }

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id');
    }
}