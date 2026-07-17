<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceRuleOption extends Model
{
    protected $primaryKey = 'rule_option_id';

    protected $fillable = [
        'rule_id',
        'option_id',
    ];

    public function rule()
    {
        return $this->belongsTo(ProductPriceRule::class, 'rule_id', 'rule_id');
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id', 'option_id');
    }
}
