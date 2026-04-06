<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentVersion extends Model
{
    protected $fillable = [
        'bundle_id',
        'entity_type',
        'entity_id',
        'locale',
        'payload',
        'created_by',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}