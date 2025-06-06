<?php

namespace App\Helpers;

use App\Services\ThemeService;

class Theme
{
    public static function get($key, $default = null)
    {
        return app(ThemeService::class)->get($key, $default);
    }
}
