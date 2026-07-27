<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUsPage extends Model
{
    protected $primaryKey = 'about_us_page_id';

    protected $fillable = [
        'banner_desktop',
        'banner_mobile',
        'intro_image',
        'intro_content',
        'detail_content',
    ];
}
