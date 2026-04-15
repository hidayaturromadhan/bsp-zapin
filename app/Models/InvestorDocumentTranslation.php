<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestorDocumentTranslation extends Model
{
    protected $fillable = [
        'investor_document_id',
        'locale',
        'title',
        'summary',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(InvestorDocument::class, 'investor_document_id');
    }
}