<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;
use App\Models\ThemeColor;

class ThemeManager extends Component
{
    public $themes;
    public $name;
    public $font_primary;
    public $font_secondary;
    public $selectedThemeId;
    public $isEditing = false;
    public $allColors;
    public $selectedColors = [];

    public function mount()
    {
        $this->loadThemes();
        $this->allColors = ThemeColor::all();
    }

    public function loadThemes()
    {
        $this->themes = Theme::with('colors')->get();
    }

    public function render()
    {
        return view('livewire.theme-manager');
    }

    public function editTheme($themeId)
    {
        $theme = Theme::with('colors')->find($themeId);
        $this->selectedThemeId = $theme->id;
        $this->name = $theme->name;
        $this->font_primary = $theme->font_primary;
        $this->font_secondary = $theme->font_secondary;
        $this->selectedColors = $theme->colors->pluck('id')->toArray();
        $this->isEditing = true;

        $this->allColors = $this->allColors->sortBy(function ($color) {
            return array_search($color->id, $this->selectedColors) === false;
        });
    }

    public function deleteTheme($themeId)
    {
        Theme::find($themeId)->delete();
        $this->loadThemes();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->font_primary = '';
        $this->font_secondary = '';
        $this->selectedThemeId = null;
        $this->selectedColors = [];
        $this->isEditing = false;
    }

    public function saveTheme()
    {
        $this->validate([
            'name' => 'required|string|unique:themes,name,' . $this->selectedThemeId,
            'font_primary' => 'nullable|string',
            'font_secondary' => 'nullable|string',
        ]);

        $data = [
            'name' => $this->name,
            'font_primary' => $this->font_primary,
            'font_secondary' => $this->font_secondary,
        ];

        if ($this->isEditing) {
            $theme = Theme::find($this->selectedThemeId);
            $theme->update($data);
            $theme->colors()->sync($this->selectedColors);
        } else {
            $theme = Theme::create($data);
            $theme->colors()->sync($this->selectedColors);
        }

        $this->resetForm();
        $this->loadThemes();
    }
}
