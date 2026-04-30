<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WbsReportAttachment extends Model
{
    protected $table = 'wbs_report_attachments';

    protected $fillable = [
        'wbs_report_id',
        'original_name',
        'file_name',
        'file_path',
        'file_disk',
        'mime_type',
        'file_size',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(WbsReport::class, 'wbs_report_id');
    }

    public function getFileUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return asset($this->file_path);
    }

    public function getFileSizeLabelAttribute(): string
    {
        $bytes = (int) $this->file_size;

        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / (1024 * 1024), 2, ',', '.') . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2, ',', '.') . ' KB';
        }

        return $bytes . ' B';
    }
}