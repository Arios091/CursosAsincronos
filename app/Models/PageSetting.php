<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    use HasFactory;

    protected $table = 'page_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function getAll(): array
    {
        return self::all()->pluck('value', 'key')->toArray();
    }
}
