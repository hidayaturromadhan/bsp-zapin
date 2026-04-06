<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GcgDocument extends Model
{
    protected $fillable = [
        'gcg_category_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GcgCategory::class, 'gcg_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(GcgDocumentTranslation::class);
    }

    public function getTranslation(string $locale = 'id'): ?GcgDocumentTranslation
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'id');
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getFileIconAttribute(): string
    {
        return match (strtolower($this->file_type)) {
            'pdf'        => 'bi-file-earmark-pdf text-danger',
            'doc', 'docx'=> 'bi-file-earmark-word text-primary',
            'xls', 'xlsx'=> 'bi-file-earmark-excel text-success',
            'ppt', 'pptx'=> 'bi-file-earmark-ppt text-warning',
            default      => 'bi-file-earmark text-secondary',
        };
    }
}