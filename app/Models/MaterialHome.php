<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialHome extends Model
{
    protected $primaryKey = 'material_home_id';

    protected $fillable = [
        'material_id',
         'product_type',
        'title',
        'description',
        'image_path',
        'is_active',
        'sort_order',
        'language',
        'translation_key',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }
}
