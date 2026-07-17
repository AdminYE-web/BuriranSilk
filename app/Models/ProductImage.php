<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $primaryKey = 'image_id';

    protected $fillable = [
        'product_id',
        'image_path',
        'image_alt',
        'image_type',
        'is_main',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
