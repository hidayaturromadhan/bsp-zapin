<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GcgDocumentTranslation extends Model
{
    protected $fillable = [
        'gcg_document_id',
        'locale',
        'title',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(GcgDocument::class, 'gcg_document_id');
    }
}