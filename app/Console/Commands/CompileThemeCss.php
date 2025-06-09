<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThemeCustomization;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class CompileThemeCss extends Command
{
    protected $signature = 'theme:compile';
    protected $description = 'Compile theme CSS from database';

    public function handle()
    {
        $themeId = config('theme.active', 'default');
        $customizations = ThemeCustomization::where('theme_id', $themeId)->get();

        $cssContent = "@reference 'tailwindcss';\n";
        foreach ($customizations as $customization) {
            $cssContent .= ".{$customization->key} {\n";
            $cssContent .= "  @apply {$customization->value};\n";
            $cssContent .= "}\n";
        }

        $tempCssPath = storage_path('app/theme-source.css');
        File::put($tempCssPath, $cssContent);

        $publicCssPath = public_path('dynamic-theme.css');

        $process = new Process([
            'npx',
            '@tailwindcss/cli',
            '-i',
            $tempCssPath,
            '-o',
            $publicCssPath,
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error($process->getErrorOutput());
        } else {
            $this->info('Theme CSS compiled successfully!');
        }

        File::delete($tempCssPath);
    }
}
