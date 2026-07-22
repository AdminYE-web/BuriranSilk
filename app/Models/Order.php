<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_no',
        'checkout_token',
        'user_id',
        'total_items',
        'total_quantity',
        'qty',
        'base_unit_price',
        'option_total',
        'subtotal',
        'shipping_fee',
        'tax_amount',
        'vat_amount',
        'grand_total',
        'currency',
        'status',
        'order_status',
        'payment_status',
        'shipping_date',
        'payment_date',
        'info_method',
        'signature_text',
        'delivery_option',
        'publish_website',
        'newsletter',
        'notes',
        'checkout_data',
        'confirmation_email_sent_at',
    ];

    protected $casts = [
        'shipping_date' => 'datetime',
        'payment_date' => 'datetime',
        'publish_website' => 'boolean',
        'newsletter' => 'boolean',
        'checkout_data' => 'array',
        'confirmation_email_sent_at' => 'datetime',
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
