<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductListBanner extends Model
{
    protected $primaryKey = 'banner_id';

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'image_mobile',
        'link_url',
        'button_text',
        'sort_order',
        'is_active',
    ];
}
