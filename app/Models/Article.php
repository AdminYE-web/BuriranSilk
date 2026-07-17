<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $primaryKey = 'article_id';

    protected $fillable = [
        'title',
        'category',
        'language',
        'translation_key',
        'article_date',
        'detail',
        'cover_image',
        'description',
        'is_active',
    ];
}
