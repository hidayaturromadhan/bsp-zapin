<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorHighlightItem extends Model
{
    protected $fillable = [
        'label_id',
        'label_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}