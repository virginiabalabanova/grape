<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ThemeService
{
    private $theme;

    public function __construct()
    {
        $this->theme = $this->loadTheme();
        $this->generateCssVariables();
    }

    private function loadTheme()
    {
        $themeName = Config::get('app.theme', 'default');
        $defaultTheme = Config::get("themes.$themeName");
        $themeCustomizations = DB::table('theme_customizations')
            ->where('theme_id', $themeName)
            ->pluck('value', 'key')
            ->toArray();

        \Illuminate\Support\Facades\Log::info('Default Theme:', [$defaultTheme]);
        \Illuminate\Support\Facades\Log::info('Theme Customizations:', [$themeCustomizations]);

        return array_merge($defaultTheme, $themeCustomizations);
    }

    public function generateCssVariables()
    {
        $cssVariables = '';
        foreach ($this->theme as $key => $value) {
            $cssVariables .= "--$key: $value;\n";
        }

        $filePath = resource_path('css/themes/custom.css');
        File::put($filePath, ":root {\n" . $cssVariables . "}\n");
    }

    public function get(string $key)
    {
        return $this->theme[$key] ?? null;
    }
}
