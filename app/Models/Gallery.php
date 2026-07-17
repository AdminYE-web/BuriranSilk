<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $primaryKey = 'gallery_id';

    protected $fillable = [
        'title',
        'category_id',
        'material_id',
        'purpose',
        'product_link',
        'gallery_date',
        'cover_image',
        'is_active',
        'sort_order',
        'language',
        'translation_key',
    ];

    protected $casts = [
        'gallery_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'gallery_id', 'gallery_id')
            ->orderBy('sort_order')
            ->orderBy('gallery_image_id');
    }
}
