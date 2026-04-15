<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestorDocument extends Model
{
    protected $fillable = [
        'year',
        'file_path',
        'cover',
        'file_name',
        'file_type',
        'file_size',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(InvestorDocumentTranslation::class, 'investor_document_id');
    }

    public function getTranslation(string $locale = 'id'): ?InvestorDocumentTranslation
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id')
            ?? $this->translations->first();
    }
}