<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ThemeCustomization;

class ThemeCustomizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'btn-primary-bg-color',
            'value' => 'bg-blue-500',
            'category' => 'cta',
            'required' => true,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'btn-secondary-bg-color',
            'value' => 'bg-red-500',
            'category' => 'cta',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'btn-tertiary-bg-color',
            'value' => 'bg-green-500',
            'category' => 'cta',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'heading-h1-font-size',
            'value' => 'text-3xl',
            'category' => 'typography',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'heading-h2-font-size',
            'value' => 'text-2xl',
            'category' => 'typography',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'heading-h3-font-size',
            'value' => 'text-xl',
            'category' => 'typography',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'container-max-width',
            'value' => 'max-w-7xl',
            'category' => 'layout',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'column-width',
            'value' => 'w-full',
            'category' => 'layout',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'gap-size',
            'value' => 'space-x-4',
            'category' => 'layout',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'body-bg-color',
            'value' => 'bg-white',
            'category' => 'global',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'text-color',
            'value' => 'text-black',
            'category' => 'global',
            'required' => false,
        ]);

        ThemeCustomization::create([
            'theme_id' => 1,
            'key' => 'color',
            'value' => 'red',
            'category' => 'global',
            'required' => false,
        ]);
    }
}
