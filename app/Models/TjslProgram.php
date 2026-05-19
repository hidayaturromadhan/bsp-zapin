<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TjslProgram extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVISION = 'revision';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
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
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVISION => 'Revision',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public static function writerStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVISION => 'Revision',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public static function reviewerStatuses(): array
    {
        return [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVISION => 'Revision',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
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
        return self::statuses()[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_SUBMITTED => 'yellow',
            self::STATUS_REVISION => 'blue',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_PUBLISHED => 'green',
            default => 'gray',
        };
    }

    public function getCanBeEditedByWriterAttribute(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_REVISION,
            self::STATUS_REJECTED,
        ], true);
    }

    public function getCanBeSubmittedByWriterAttribute(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_REVISION,
            self::STATUS_REJECTED,
        ], true);
    }

    public function getCanBePublishedByWriterAttribute(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getCanBeUnpublishedByWriterAttribute(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function getCanBeViewedByReviewerAttribute(): bool
    {
        return in_array($this->status, [
            self::STATUS_SUBMITTED,
            self::STATUS_REVISION,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_PUBLISHED,
        ], true);
    }

    public function getCanBeReviewedAttribute(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
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

    public function scopeSubmitted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeRevision(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REVISION);
    }
}