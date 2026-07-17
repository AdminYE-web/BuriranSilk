<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $primaryKey = 'faq_id';

    protected $fillable = [
        'product_id',
        'language',
        'question',
        'answer',
        'show_main',
        'show_product',
        'status',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
