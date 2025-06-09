<?php

namespace App\Services;

use App\Models\ThemeCustomization;
use Illuminate\Support\Facades\Cache;

class ThemeManager
{
    public static function getClass($key, $default = null)
    {
        $themeId = session('theme', 'default');

        $themeCustomization = ThemeCustomization::where('theme_id', $themeId)
            ->where('key', $key)
            ->first();

        dd($themeCustomization);    

        return $themeCustomization ? $themeCustomization->value : $default;
    }

    public static function all()
    {
        $themeId = session('theme', 'default');
        $cacheKey = "theme.{$themeId}.all";

        return Cache::rememberForever($cacheKey, function () use ($themeId) {
            return ThemeCustomization::where('theme_id', $themeId)->get()->pluck('value', 'key')->toArray();
        });
    }
}
