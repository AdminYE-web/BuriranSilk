<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'product_id',

        'product_name',
        'product_name_snapshot',
        'product_image',

        'qty',
        'quantity',

        'price_rule_id',
        'price_rule_name',

        'base_unit_price',
        'unit_price',
        'base_total',

        'product_total',
        'option_total',
        'item_total',

        'options',
        'custom_colors',
    ];

    protected $casts = [
        'options' => 'array',
        'custom_colors' => 'array',
    ];

    public function optionDetails()
    {
        return $this->hasMany(OrderItemOption::class, 'order_item_id', 'order_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
