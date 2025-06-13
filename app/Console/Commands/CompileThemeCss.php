<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThemeCustomization;
use App\Models\ThemeColor;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class CompileThemeCss extends Command
{
    protected $signature = 'theme:compile';
    protected $description = 'Compile theme CSS from database';

    public function handle()
    {
        $this->call('theme:colors');

        $themeId = session('theme', 1);
        $customizations = ThemeCustomization::where('theme_id', $themeId)->get();

        $cssContent = "@import 'tailwindcss';\n";

        $colors = ThemeColor::all();
        $cssContent = "@theme {\n";
            $cssContent .= "  --font-sans: 'Comic Sans';\n";
            $cssContent .= "  --font-semibold: 'Poppins';\n";
            $cssContent .= "  --font-medium: 'Times New Roman';\n";
            $cssContent .= "  --font-normal: 'Verdana';\n";
            $cssContent .= "  --text-sm: 0.875rem;\n";
            $cssContent .= "  --radius-sm: 0.25rem;\n";
            $cssContent .= "  --radius-md: 0.5rem;\n";
            $cssContent .= "  --radius-lg: 1rem;\n";
            $cssContent .= "  --text-sm--line-height: calc(1.25 / 0.875);\n";
            $cssContent .= " --breakpoint-md: 48rem; \n";
            foreach ($colors as $color) {
                $cssContent .= "  --color-{$color->name}: {$color->hex};\n";
            }
        $cssContent .= "}\n";

        foreach ($customizations as $customization) {
            // Temporary fix: Replace incorrect 'radius-sm' with correct 'rounded-sm'
            // The user should fix the data in the database.
            $value = str_replace('radius-sm', 'rounded-sm', $customization->value);
            $value = str_replace('radius-md', 'rounded-md', $customization->value);

            $cssContent .= ".{$customization->key} {\n";
            $cssContent .= "  @apply {$value};\n";
            $cssContent .= "}\n";
        }

        $tempCssPath = storage_path('app/theme-source.css');
        File::put($tempCssPath, $cssContent);

        $publicCssPath = public_path('dynamic-theme.css');

        $command = [
            'npx',
            'tailwindcss',
            '-i',
            $tempCssPath,
            '-o',
            $publicCssPath,
            '--config',
            base_path('tailwind.theme.config.js'),
        ];

        $this->info(implode(' ', $command));

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
        } else {
            $this->info('Theme CSS compiled successfully!');
        }

        File::delete($tempCssPath);
    }
}
