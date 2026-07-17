<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceTier extends Model
{
    protected $primaryKey = 'tier_id';

    protected $fillable = [
        'product_id',
        'min_qty',
        'max_qty',
        'unit_price',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
