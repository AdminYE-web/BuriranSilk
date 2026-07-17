<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $primaryKey = 'quotation_id';

    protected $fillable = [
        'quotation_no',
        'quotation_date',
        'customer_name',
        'customer_email',
        'customer_address',
        'note',
        'subtotal',
        'discount_amount',
        'shipping_fee',
        'vat_amount',
        'grand_total',
        'status',
        'language',
        
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'quotation_id');
    }
}
