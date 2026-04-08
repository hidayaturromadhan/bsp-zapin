<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GcgDocument extends Model
{
    protected $fillable = [
        'gcg_category_id',
        'file_path',
        'cover',
        'file_name',
        'file_type',
        'file_size',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(GcgCategory::class, 'gcg_category_id');
    }

    public function translations()
    {
        return $this->hasMany(GcgDocumentTranslation::class, 'gcg_document_id');
    }
}