<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GcgCategory extends Model
{
    protected $fillable = [
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(GcgCategoryTranslation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(GcgDocument::class)->orderBy('id');
    }

    public function activeDocuments(): HasMany
    {
        return $this->hasMany(GcgDocument::class)
            ->where('is_active', true)
            ->orderBy('id');
    }

    public function getTranslation(string $locale = 'id'): ?GcgCategoryTranslation
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id');
    }
}