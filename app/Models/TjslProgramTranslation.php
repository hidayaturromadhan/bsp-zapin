<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TjslProgramTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tjsl_program_id',
        'locale',
        'title',
        'summary',
        'content',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(TjslProgram::class, 'tjsl_program_id');
    }
}