<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TjslProgram extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'year',
        'featured_image',
        'sort_order',
        'is_active',
        'status',
        'created_by',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
        'published_at',
        'rejected_at',
        'review_note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TjslProgramTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TjslProgramImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getTranslation(string $locale): ?TjslProgramTranslation
    {
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        return $translations->firstWhere('locale', $locale)
            ?: $translations->firstWhere('locale', 'id')
            ?: $translations->firstWhere('locale', 'en');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getCanBeEditedByWriterAttribute(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
        ], true);
    }

    public function getCanBePublishedByWriterAttribute(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function getCanBeUnpublishedByWriterAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function getCanBeViewedByReviewerAttribute(): bool
    {
        return true;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->where('is_active', true);
    }

    public function scopeForWriter(Builder $query, int $userId): Builder
    {
        return $query->where('created_by', $userId);
    }
}