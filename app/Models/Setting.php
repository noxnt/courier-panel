<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['key', 'value'];

    public static function getBool(string $key, bool $default = false): bool
    {
        $value = static::find($key)?->value;

        return $value === null ? $default : (bool)$value;
    }
}
