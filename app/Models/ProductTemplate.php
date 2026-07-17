<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    protected $primaryKey = 'template_id';

    protected $fillable = [
        'product_id',
        'language',
        'template_size',
        'file_path',
        'original_name',
        'file_type',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
