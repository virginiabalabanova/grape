<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ThemeCustomization;
use Livewire\Attributes\On;

class ThemeCustomizer extends Component
{
    public $themeId;
    public $key;
    public $value;
    public $themeCustomizations = [];

    public function mount()
    {
        $this->themeId = config('theme.active', session('theme', 'default'));
        $this->loadThemeCustomizations();
    }

    public function render()
    {
        return view('livewire.theme-customizer');
    }

    #[On('themeCustomizationUpdated')]
    public function themeCustomizationUpdated($key, $value)
    {
        $themeCustomization = ThemeCustomization::where('theme_id', $this->themeId)
            ->where('key', $key)
            ->first();

        if ($themeCustomization) {
            $themeCustomization->value = $value;
            $themeCustomization->save();
        } else {
            ThemeCustomization::create([
                'theme_id' => $this->themeId,
                'key' => $key,
                'value' => $value,
            ]);
        }

        cache()->forget("theme.{$this->themeId}.{$key}");
        session()->flash('message', 'Theme customization updated successfully.');
        $this->loadThemeCustomizations();
    }

    private function loadThemeCustomizations()
    {
        $this->themeCustomizations = ThemeCustomization::where('theme_id', $this->themeId)->get()->toArray();
    }
}
