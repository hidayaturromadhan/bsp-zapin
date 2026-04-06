<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public const CATEGORY_CUSTOMER = 'customer';
    public const CATEGORY_BUSINESS_PARTNER = 'business_partner';

    protected $table = 'partners';

    protected $fillable = [
        'name',
        'category',
        'website_url',
        'sort_order',
        'is_active',
        'logo_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function categoryOptions(): array
    {
        return [
            self::CATEGORY_CUSTOMER => 'Pelanggan',
            self::CATEGORY_BUSINESS_PARTNER => 'Mitra Bisnis',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCustomers(Builder $query): Builder
    {
        return $query->where('category', self::CATEGORY_CUSTOMER);
    }

    public function scopeBusinessPartners(Builder $query): Builder
    {
        return $query->where('category', self::CATEGORY_BUSINESS_PARTNER);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categoryOptions()[$this->category] ?? ucfirst(str_replace('_', ' ', (string) $this->category));
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo_path)) {
            return null;
        }

        return asset($this->logo_path);
    }

    public function hasWebsiteUrl(): bool
    {
        return !empty($this->website_url);
    }
}