<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCustomer extends Model
{
    protected $primaryKey = 'order_customer_id';

    protected $fillable = [
        'order_id',
        'customer_type',
        'personal_name',
        'personal_name_kana',
        'company_name',
        'company_name_kana',
        'personal_first_name',
        'personal_last_name',
        'personal_phone',
        'personal_email',
        'personal_postcode',
        'personal_province',
        'personal_city',
        'personal_area',
        'same_as_customer',
        'shipping_name',
        'shipping_name_kana',
        'shipping_postcode',
        'shipping_province',
        'shipping_city',
        'shipping_area',
        'shipping_district',
        'shipping_subdistrict',
        'shipping_building_room',
        'shipping_address',
        'billing_address_type',
        'billing_name',
        'billing_name_kana',
        'billing_first_name',
        'billing_last_name',
        'billing_phone',
        'billing_email',
        'billing_postcode',
        'billing_province',
        'billing_city',
        'billing_area',
        'billing_district',
        'billing_subdistrict',
        'billing_building_room',
        'billing_address',
    ];

    protected $casts = [
        'same_as_customer' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
