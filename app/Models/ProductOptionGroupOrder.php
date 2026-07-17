<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionGroupOrder extends Model
{
    protected $fillable = [
        'product_id',
        'option_group_id',
        'sort_order',
    ];
}
