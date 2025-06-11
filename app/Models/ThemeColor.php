<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex',
    ];

    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'theme_theme_color');
    }
}
