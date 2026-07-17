<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderArtwork extends Model
{
    protected $primaryKey = 'order_artwork_id';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'cart_item_id',

        'file_path',
        'original_name',
        'mime_type',
        'file_size',

        'no_artwork',
        'print_text',
        'font_option',
        'font_other',
        'template_id',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function template()
    {
        return $this->belongsTo(ProductArtworkTemplate::class, 'template_id', 'template_id');
    }
}
