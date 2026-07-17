<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItemOption extends Model
{
    protected $primaryKey = 'quotation_item_option_id';

    protected $fillable = [
        'quotation_item_id',
        'option_group_id',
        'option_id',
        'variant_id',
        'group_name',
        'option_name',
        'variant_name',
        'additional_price',
        'price_type',
    ];
}
