<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | GET
    |--------------------------------------------------------------------------
    */

    public static function getSetting(
        string $key,
        mixed $default = null
    ): mixed {

        return static::where('key', $key)
            ->first()
            ?->value ?? $default;
    }

    /*
    |--------------------------------------------------------------------------
    | SET
    |--------------------------------------------------------------------------
    */

    public static function setSetting(
        string $key,
        mixed $value
    ): void {

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}