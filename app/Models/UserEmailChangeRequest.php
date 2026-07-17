<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmailChangeRequest extends Model
{
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'user_id',
        'admin_id',
        'old_email',
        'new_email',
        'token',
        'verified_at',
        'expires_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
