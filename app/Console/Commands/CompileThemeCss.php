<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThemeCustomization;
use Illuminate\Support\Facades\File;

class CompileThemeCss extends Command
{
    protected $signature = 'theme:compile-css {theme=default}';
    protected $description = 'Generate Tailwind CSS @apply rules from theme_customizations DB';

    public function handle()
    {
        $theme = $this->argument('theme');
        $customizations = ThemeCustomization::where('theme_id', $theme)->get();

        if ($customizations->isEmpty()) {
            $this->warn("No theme styles found for theme: $theme");
            return;
        }

        $output = "/* Auto-generated theme.css */\n\n";
        foreach ($customizations as $item) {
            $output .= ".{$item->key} {\n";
            $output .= "    @apply {$item->value};\n";
            $output .= "}\n\n";
        }

        $path = resource_path("css/theme.css");
        File::put($path, $output);

        $this->info("Generated CSS for theme [$theme] at: resources/css/theme.css");
    }
}