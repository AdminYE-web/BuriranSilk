<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $primaryKey = 'user_address_id';

    protected $fillable = [
        'user_id',
        'address_type',
        'label',
        'first_name',
        'last_name',
        'phone',
        'company_name',
        'address',
        'apartment',
        'country',
        'city',
        'state',
        'zip_code',
        'is_main',
        'is_active',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
