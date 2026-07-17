<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $primaryKey = 'order_item_option_id';

    protected $fillable = [
        'order_item_id',
        'option_group_id',
        'option_id',
        'group_name_snapshot',
        'option_name_snapshot',
        'additional_price',
        'price_type',
        'custom_value',
        'total_price',
    ];
}
