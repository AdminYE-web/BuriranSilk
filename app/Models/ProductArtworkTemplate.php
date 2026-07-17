<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductArtworkTemplate extends Model
{
    protected $primaryKey = 'template_id';

    protected $fillable = [
        'product_id',
        'template_name',
        'image_path',
        'sort_order',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
