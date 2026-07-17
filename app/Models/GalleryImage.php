<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $primaryKey = 'gallery_image_id';

    protected $fillable = [
        'gallery_id',
        'image_path',
        'original_name',
        'sort_order',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id', 'gallery_id');
    }
}
