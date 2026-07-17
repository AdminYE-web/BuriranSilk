<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionPriceRuleTier extends Model
{
    protected $table = 'product_option_price_rule_tiers';

    protected $primaryKey = 'option_price_rule_tier_id';

    protected $fillable = [
        'option_price_rule_id',
        'min_qty',
        'max_qty',
        'additional_price',
        'additional_price_with_tax',
        'is_active',
    ];

    public function rule()
    {
        return $this->belongsTo(
            ProductOptionPriceRule::class,
            'option_price_rule_id',
            'option_price_rule_id'
        );
    }
}
