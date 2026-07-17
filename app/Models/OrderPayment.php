<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $primaryKey = 'order_payment_id';

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'payment_status',
        'amount',
        'currency',
        'paid_at',
        'payment_response',
    ];

    protected $casts = [
        'payment_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
