<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscript extends Model
{
    protected $table = 'email_subscript';

    protected $fillable = [
        'email',
        'is_subscript',
    ];

    protected $casts = [
        'is_subscript' => 'boolean',
    ];
}
