<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeCustomizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $theme = 'default';

        $defaultTheme = config('themes.default');

        foreach ($defaultTheme as $key => $value) {
            \Illuminate\Support\Facades\DB::table('theme_customizations')->insert([
                'theme_id' => $theme,
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        \Illuminate\Support\Facades\DB::table('theme_customizations')->insert([
            'theme_id' => $theme,
            'key' => 'body',
            'value' => 'bg-red-500',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
