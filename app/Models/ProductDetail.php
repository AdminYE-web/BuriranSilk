<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $primaryKey = 'product_detail_id';

    protected $fillable = [
        'product_id',
            'short_description',
    'long_description',
        'sample_image',
        'specification_image',
        'detail_content',
        'specification_content',
        'accordion_content',
        'is_active',
    ];

    protected $casts = [
        'detail_content' => 'array',
        'specification_content' => 'array',
        'accordion_content' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
