<?php

namespace App\Services;

use App\Models\ThemeCustomization;

class ThemeManager
{
    public static function getClass($key, $default = null)
    {
        $themeId = session('theme', 'default');

        $themeCustomization = ThemeCustomization::where('theme_id', $themeId)
            ->where('key', $key)
            ->first();

        return $themeCustomization ? $themeCustomization->value : $default;
    }
}
