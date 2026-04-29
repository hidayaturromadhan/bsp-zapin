<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlowGasDailyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'flow_gas_category_id',
        'record_date',
        'mscf',
        'mmbtu',
        'fix',
        'notes',
    ];

    protected $casts = [
        'record_date' => 'date',
        'mscf' => 'decimal:4',
        'mmbtu' => 'decimal:4',
        'fix' => 'decimal:4',
    ];

    public function category()
    {
        return $this->belongsTo(FlowGasCategory::class, 'flow_gas_category_id');
    }
}