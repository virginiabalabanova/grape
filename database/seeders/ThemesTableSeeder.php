<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('themes')->insert([
            [
                'name' => 'Global Primary Color',
                'key' => 'primary-color',
                'value' => 'text-blue-600',
                'global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Global Button Style',
                'key' => 'btn-primary',
                'value' => 'bg-blue-500 text-white px-4 py-2 rounded',
                'global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Global Heading Style',
                'key' => 'heading-style',
                'value' => 'text-3xl font-bold',
                'global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Global Font Family',
                'key' => 'font-sans',
                'value' => 'font-sans',
                'global' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
