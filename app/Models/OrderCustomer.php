<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomer extends Model
{
    protected $primaryKey = 'order_customer_id';

    protected $fillable = [
        'order_id',

        'personal_first_name',
        'personal_last_name',
        'personal_phone',
        'personal_email',

        'shipping_postcode',
        'shipping_province',
        'shipping_district',
        'shipping_subdistrict',
        'shipping_building_room',
        'shipping_address',

        'billing_first_name',
        'billing_last_name',
        'billing_phone',
        'billing_email',
        'billing_postcode',
        'billing_province',
        'billing_district',
        'billing_subdistrict',
        'billing_building_room',
        'billing_address',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
