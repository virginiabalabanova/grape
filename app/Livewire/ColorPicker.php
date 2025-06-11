<?php

namespace App\Livewire;

use App\Helpers\ThemeHelper;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ColorPicker extends Component
{
    public string $themeKey; // e.g., 'body-bg' or 'text-color'
    public string $currentValue;
    public array $colors = [];
    public int $themeId;

    public function mount(string $themeKey, int $themeId)
    {
        $this->themeKey = $themeKey;
        $this->themeId = $themeId;
        $this->colors = ThemeHelper::getThemeColors($this->themeId);
        $this->currentValue = DB::table('theme_customizations')
                                ->where('key', $this->themeKey)
                                ->where('theme_id', $this->themeId)
                                ->value('value') ?? 'white';
    }

    public function selectColor(string $colorName)
    {
        // Update the database
        DB::table('theme_customizations')
            ->where('key', $this->themeKey)
            ->where('theme_id', $this->themeId)
            ->update(['value' => $colorName]);

        // Update the component's state
        $this->currentValue = $colorName;

        // Emit an event for Grape.js or other parts of the UI
        $this->dispatch('color-updated', key: $this->themeKey, color: $colorName);
    }

    public function render()
    {
        return <<<'HTML'
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" type="button" class="flex items-center gap-x-2 p-2 border rounded-md">
                <span class="block w-6 h-6 rounded-full"
                      :style="{ backgroundColor: `var(${colors[$currentValue] ?? '--color-white'})` }">
                </span>
                <span>{{ \Illuminate\Support\Str::title($currentValue) }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="open"
                 @click.away="open = false"
                 x-transition
                 class="absolute z-10 top-full mt-2 w-64 p-2 bg-white border rounded-lg shadow-xl"
                 style="display: none;">

                <div class="grid grid-cols-6 gap-2">
                    @foreach($colors as $name => $hex)
                        <div wire:click="selectColor('{{ $name }}')"
                             @click="open = false"
                             class="w-8 h-8 rounded-full cursor-pointer ring-offset-2 ring-blue-500 transition-transform transform hover:scale-110"
                             :class="{ 'ring-2': '{{ $name }}' === '{{ $currentValue }}' }"
                             style="background-color: var({{ $hex }});"
                             title="{{ \Illuminate\Support\Str::title($name) }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        HTML;
    }
}
