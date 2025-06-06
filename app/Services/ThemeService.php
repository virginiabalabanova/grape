<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\ThemeCustomization;

class ThemeService
{
    protected $themeId;

    public function __construct()
    {
        $this->themeId = config('theme.active', session('theme', 'default'));
    }

    public function get($key, $default = null)
    {
        $cacheKey = "theme.{$this->themeId}.{$key}";

        return Cache::rememberForever($cacheKey, function () use ($key, $default) {
            $themeCustomization = ThemeCustomization::where('theme_id', $this->themeId)
                ->where('key', $key)
                ->first();

            return $themeCustomization ? $themeCustomization->value : $default;
        });
    }

    public function setTheme($themeId)
    {
        session(['theme' => $themeId]);
        config(['theme.active' => $themeId]);
        $this->themeId = $themeId;
        Cache::flush();
    }

    public function getThemeId()
    {
        return $this->themeId;
    }
}
