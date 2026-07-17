<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionAssignment extends Model
{
    protected $primaryKey = 'assignment_id';

    protected $fillable = [
        'product_id',
        'option_id',
        'sort_order',
        'is_default',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id', 'option_id');
    }
}
