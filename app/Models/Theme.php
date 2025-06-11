<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'font_primary',
        'font_secondary',
    ];

    public function colors()
    {
        return $this->belongsToMany(ThemeColor::class, 'theme_theme_color');
    }
}
