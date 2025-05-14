<?php

namespace App\Services;

use App\Models\Theme;
use App\Models\Page;

class ThemeService
{
    public static function forPage(Page $page = null): array
    {
        $theme = [];

        if ($page) {
            $theme = Theme::where('page_id', $page->id)
                ->pluck('value', 'key')
                ->toArray();
        }

        $globalTheme = Theme::where('global', true)
            ->pluck('value', 'key')
            ->toArray();

        return array_merge($globalTheme, $theme);
    }
}
