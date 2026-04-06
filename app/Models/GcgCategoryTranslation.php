<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GcgCategoryTranslation extends Model
{
    protected $fillable = [
        'gcg_category_id',
        'locale',
        'name',
        'slug',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GcgCategory::class, 'gcg_category_id');
    }
}