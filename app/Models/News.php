<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    protected $table = 'news';

    protected $fillable = [
        'news_category_id',
        'status',
        'published_at',
        'is_featured',
        'is_visible',
        'featured_image',
        'reviewed_by',
        'reviewed_at',
        'review_note',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_visible' => 'boolean',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(NewsTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(NewsAuditLog::class)->latest();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function getCanBePublishedByWriterAttribute(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function getCanBeUnpublishedByWriterAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function getTranslationByLocale(string $locale = 'id'): ?NewsTranslation
    {
        if (! $this->relationLoaded('translations')) {
            $this->load('translations');
        }

        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id')
            ?? $this->translations->firstWhere('locale', 'en')
            ?? $this->translations->first();
    }

    public function scopeWithoutTjsl(Builder $query): Builder
    {
        return $query->whereHas('category', function ($q) {
            $q->where('slug', '!=', 'tjsl');
        });
    }

    public function scopePublicPublished(Builder $query): Builder
    {
        return $query
            ->where('is_visible', true)
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeForWriter(Builder $query, int $userId): Builder
    {
        return $query->where('created_by', $userId);
    }
}