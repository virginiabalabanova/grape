<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $themeId = 1; // Assuming theme ID 1 for now
            $colors = self::getThemeColors($themeId);

            $rootStyles = ':root {';
            foreach ($colors as $name => $hex) {
                $rootStyles .= "--color-{$name}: {$hex};";
            }
            $rootStyles .= '}';

            file_put_contents(public_path('dynamic-theme.css'), $rootStyles);
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it)
            error_log("Error generating dynamic theme CSS: " . $e->getMessage());
        }
    }

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
                ->mapWithKeys(fn ($hex, $name) => [str_replace(' ', '-', $name) => $hex])
                ->all();
        });
    }
}
