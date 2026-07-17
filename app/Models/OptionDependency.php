<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionDependency extends Model
{
    protected $primaryKey = 'dependency_id';

    protected $fillable = [
        'parent_option_id',
        'child_option_id',
        'target_type',
        'action_type',
        'target_group_id',
        'target_option_id',
        'is_active',
        'sort_order',
    ];

    public function parentOption()
    {
        return $this->belongsTo(ProductOption::class, 'parent_option_id', 'option_id');
    }

    public function childOption()
    {
        return $this->belongsTo(ProductOption::class, 'child_option_id', 'option_id');
    }

    public function targetGroup()
    {
        return $this->belongsTo(OptionGroup::class, 'target_group_id', 'option_group_id');
    }

    public function targetOption()
    {
        return $this->belongsTo(ProductOption::class, 'target_option_id', 'option_id');
    }
}
