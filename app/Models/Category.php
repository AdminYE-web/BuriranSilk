<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_code',
        'category_name',
        'image_path',
        'sort_order',
        'is_active',
        'language',
        'translation_key',
        'product_type',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
