<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceRule extends Model
{
    protected $primaryKey = 'rule_id';

    protected $fillable = [
        'product_id',
        'rule_name',
        'is_active',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function ruleOptions()
    {
        return $this->hasMany(ProductPriceRuleOption::class, 'rule_id', 'rule_id');
    }

    public function options()
    {
        return $this->belongsToMany(
            ProductOption::class,
            'product_price_rule_options',
            'rule_id',
            'option_id',
            'rule_id',
            'option_id'
        );
    }

    public function tiers()
    {
        return $this->hasMany(ProductPriceRuleTier::class, 'rule_id', 'rule_id')
            ->where('is_active', 1)
            ->orderBy('min_qty');
    }
}
