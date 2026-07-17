<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryBanner extends Model
{
    protected $primaryKey = 'gallery_banner_id';

    protected $fillable = [
        'title',
        'link_url',
        'image_pc',
        'image_mobile',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
