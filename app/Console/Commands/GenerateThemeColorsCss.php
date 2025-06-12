<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThemeColor;
use Illuminate\Support\Facades\File;

class GenerateThemeColorsCss extends Command
{
    protected $signature = 'theme:colors';
    protected $description = 'Generate a CSS file with theme colors';

    public function handle()
    {
        $colors = ThemeColor::all();
        $cssContent = ":root {\n";
        foreach ($colors as $color) {
            $cssContent .= "  --color-{$color->name}: {$color->hex};\n";
        }
        $cssContent .= "}\n";

        $cssContent .= "\n";

        foreach ($colors as $color) {
            $cssContent .= ".bg-{$color->name} {\n";
            $cssContent .= "  background-color: var(--color-{$color->name});\n";
            $cssContent .= "}\n";
            $cssContent .= ".text-{$color->name} {\n";
            $cssContent .= "  color: var(--color-{$color->name});\n";
            $cssContent .= "}\n";
        }

        $publicCssPath = public_path('dynamic-colors.css');
        File::put($publicCssPath, $cssContent);

        $this->info('Theme colors CSS generated successfully!');
    }
}
