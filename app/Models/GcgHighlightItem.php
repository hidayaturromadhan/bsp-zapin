<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GcgHighlightItem extends Model
{
    protected $fillable = [
        'label_id',
        'label_en',
        'sort_order',
        'is_active',
    ];
}