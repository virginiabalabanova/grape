<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('theme_colors')->insert([
            ['name' => 'black', 'hex' => '#231F20'],
            ['name' => 'orange', 'hex' => '#FFAC00'],
            ['name' => 'dark green', 'hex' => '#157640'],
            ['name' => 'medium gray', 'hex' => '#ABABAB'],
            ['name' => 'gray', 'hex' => '#EDEDED'],
            ['name' => 'white', 'hex' => '#FFFFFF'],
            ['name' => 'dark blue', 'hex' => '#0D255C'],
            ['name' => 'red', 'hex' => '#FF553A'],
            ['name' => 'green', 'hex' => '#6BE234'],
            ['name' => 'pink', 'hex' => '#FF4CC6'],
            ['name' => 'blue', 'hex' => '#11B4FF'],
        ]);
    }
}
