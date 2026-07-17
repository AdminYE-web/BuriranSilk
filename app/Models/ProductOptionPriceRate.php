<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionPriceRate extends Model
{
    protected $table = 'product_option_price_rates';
    protected $primaryKey = 'rate_id';

    protected $fillable = [
        'option_id',
        'min_qty',
        'additional_price',
        'additional_price_with_tax',
    ];
}