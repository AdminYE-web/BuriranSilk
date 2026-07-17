<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionImage extends Model
{
    protected $primaryKey = 'image_id';

    protected $fillable = [
        'option_id',
        'image_path',
        'image_alt',
        'is_main',
        'sort_order',
    ];

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id', 'option_id');
    }
}
