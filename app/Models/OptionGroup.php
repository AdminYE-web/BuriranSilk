<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
    protected $primaryKey = 'option_group_id';

    protected $fillable = [
        'parent_group_id',
        'group_code',
        'group_name',
        'help_text',
        'display_type',
        'is_required',
        'option_group_main',
        'sort_order',
        'is_active',
        'language',
        'product_type',
        'translation_key',
    ];

    public function parent()
    {
        return $this->belongsTo(OptionGroup::class, 'parent_group_id', 'option_group_id');
    }

    public function children()
    {
        return $this->hasMany(OptionGroup::class, 'parent_group_id', 'option_group_id')
            ->orderBy('sort_order');
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class, 'option_group_id', 'option_group_id')
            ->where('is_active', 1)
            ->orderBy('option_name');
    }
}
