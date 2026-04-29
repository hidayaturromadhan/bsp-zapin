<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitolRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'quantity',
        'unit',
        'fee_rate',
        'commission',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'quantity' => 'decimal:4',
        'fee_rate' => 'decimal:4',
        'commission' => 'decimal:4',
    ];

    public function getMonthLabelAttribute(): string
    {
        return match ((int) $this->month) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => '-',
        };
    }
}
