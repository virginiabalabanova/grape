<?php

use App\Services\ThemeManager;

if (! function_exists('theme_class')) {
    function theme_class(string $key, string $default = null): ?string
    {
        return ThemeManager::getClass($key, $default);
    }
}

if (! function_exists('theme')) {
    function theme(string $key, string $default = null): ?string
    {
        return ThemeManager::getClass($key, $default);
    }
}
