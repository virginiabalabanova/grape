<?php

namespace App\Http\Controllers;

use App\Services\ThemeService;

abstract class Controller
{
    public function setTheme(ThemeService $themeService, $theme)
    {
        $themeService->setTheme($theme);

        return back();
    }
}
