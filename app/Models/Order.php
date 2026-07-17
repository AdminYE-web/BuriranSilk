<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_no',
        'user_id',
        'qty',
        'base_unit_price',
        'option_total',
        'subtotal',
        'vat_amount',
        'shipping_fee',
        'grand_total',
        'status',
        'order_status',
        'payment_status',
           'shipping_date',
        'payment_date',

    ];
       protected $casts = [
        'shipping_date' => 'datetime',
        'payment_date' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(OrderCustomer::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class, 'order_id', 'order_id');
    }

    public function artworks()
    {
        return $this->hasMany(OrderArtwork::class, 'order_id', 'order_id');
    }
}
