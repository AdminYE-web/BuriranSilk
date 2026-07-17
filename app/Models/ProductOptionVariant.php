<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionVariant extends Model
{
    protected $primaryKey = 'variant_id';

    protected $fillable = [
        'option_id',
        'variant_name',
        'color_code',
        'image_path',
        'additional_price',
        'additional_price_with_tax',
        'sort_order',
        'is_default',
        'is_active',
    ];

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id', 'option_id');
    }
}
