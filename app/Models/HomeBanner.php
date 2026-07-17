<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeBanner extends Model
{
    protected $primaryKey = 'home_banner_id';

    protected $fillable = [
        'title',
        'link_url',
        'image_pc',
        'image_mobile',
        'is_active',
        'sort_order',
    ];
}
