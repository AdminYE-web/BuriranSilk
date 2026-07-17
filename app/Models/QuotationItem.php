<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $primaryKey = 'quotation_item_id';

    protected $fillable = [
        'quotation_id',
        'product_id',
        'product_name_snapshot',
        'product_code_snapshot',
        'quantity',
        'unit_price',
        'option_total',
        'item_total',
        'price_rule_snapshot',
    ];

    protected $casts = [
        'price_rule_snapshot' => 'array',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'quotation_id');
    }

    public function options()
    {
        return $this->hasMany(QuotationItemOption::class, 'quotation_item_id', 'quotation_item_id');
    }
}
