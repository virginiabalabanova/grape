<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ThemeHelper
{
    /**
     * Fetches colors from the database and formats them for Tailwind.
     * @param int $themeId
     * @return array
     */
    public static function getThemeColors(int $themeId): array
    {
        return Cache::rememberForever("theme_colors_{$themeId}", function () use ($themeId) {
            return DB::table('theme_theme_color')
                ->join('theme_colors', 'theme_theme_color.theme_color_id', '=', 'theme_colors.id')
                ->where('theme_theme_color.theme_id', $themeId)
                ->pluck('hex', 'name')
                ->mapWithKeys(fn ($hex, $name) => [str_replace(' ', '-', $name) => "--color-".str_replace(' ', '-', $name)])
                ->all();
        });
    }
}
