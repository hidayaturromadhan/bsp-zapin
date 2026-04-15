<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TjslProgram extends Model
{
    protected $fillable = [
        'year',
        'featured_image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(TjslProgramTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TjslProgramImage::class)->orderBy('sort_order');
    }

    public function getTranslation(string $locale = 'id'): ?TjslProgramTranslation
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id')
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }
}