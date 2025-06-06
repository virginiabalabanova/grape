<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeCustomization extends Model
{
    protected $fillable = [
        'theme_id',
        'key',
        'value',
    ];
}
