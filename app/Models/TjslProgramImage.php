<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TjslProgramImage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tjsl_program_id',
        'image_path',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(TjslProgram::class, 'tjsl_program_id');
    }
}