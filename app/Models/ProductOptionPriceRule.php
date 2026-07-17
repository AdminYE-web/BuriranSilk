<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionPriceRule extends Model
{
    protected $table = 'product_option_price_rules';

    protected $primaryKey = 'option_price_rule_id';

    protected $fillable = [
        'product_id',
        'target_option_id',
        'rule_name',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function targetOption()
    {
        return $this->belongsTo(ProductOption::class, 'target_option_id', 'option_id');
    }

    public function options()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'product_option_price_rule_options',
            'option_price_rule_id',
            'option_id'
        );
    }

    public function tiers()
    {
        return $this->hasMany(
            ProductOptionPriceRuleTier::class,
            'option_price_rule_id',
            'option_price_rule_id'
        )
            ->where('is_active', 1)
            ->orderBy('min_qty');
    }
}
