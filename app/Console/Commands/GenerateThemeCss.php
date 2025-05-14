<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ThemeService;

class GenerateThemeCss extends Command
{
    protected $signature = 'app:generate-theme-css';
    protected $description = 'Generate theme CSS file before build';

    public function handle(ThemeService $themeService)
    {
        $themeService->generateCssVariables();
        $this->info('Generated CSS: ' . resource_path('css/themes/custom.css'));
    }
}
