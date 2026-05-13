<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrudeDailyRecord extends Model
{
    protected $fillable = [
        'record_date',
        'vacuum_truck',
        'road_tank',
        'production',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
        'vacuum_truck' => 'decimal:4',
        'road_tank' => 'decimal:4',
        'production' => 'decimal:4',
    ];
}