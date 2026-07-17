<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    public static function getValue(string $key, $default = null)
    {
        return static::where('setting_key', $key)->value('setting_value') ?? $default;
    }

    public static function setValue(string $key, $value, string $type = 'string'): void
    {
        static::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_type' => $type,
            ]
        );
    }
}
