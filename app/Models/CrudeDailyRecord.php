<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudeDailyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_date',
        'production',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
        'production' => 'decimal:4',
    ];
}
