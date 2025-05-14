<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use DB;

class ExportTailwindTheme extends Command
{
    protected $signature = 'tailwind:export-theme';

    public function handle()
    {
        $utilities = DB::table('themes')
            ->pluck('value', 'key');

        $formatted = [];

        foreach ($utilities as $key => $value) {
            $formatted[".$key"] = ['@apply ' . $value => []];
        }

        $formatted['.test-class'] = ['@apply bg-blue-500' => []];

        File::put(resource_path('js/theme-utilities.js'), 'export default ' . json_encode($formatted, JSON_PRETTY_PRINT) . ';');

        $this->info('Theme utilities exported for Tailwind.');
    }
}
