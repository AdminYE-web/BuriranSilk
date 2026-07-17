<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceRuleTier extends Model
{
    protected $primaryKey = 'tier_id';

    protected $fillable = [
        'rule_id',
        'min_qty',
        'max_qty',
        'unit_price',
        'is_active',
        'is_display',
        'unit_price_with_tax',
    ];

    public function rule()
    {
        return $this->belongsTo(ProductPriceRule::class, 'rule_id', 'rule_id');
    }
}
