<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;

class ThemeManager extends Component
{
    public $themes;
    public $name;
    public $font_primary;
    public $font_secondary;
    public $selectedThemeId;
    public $isEditing = false;

    public function mount()
    {
        $this->loadThemes();
    }

    public function loadThemes()
    {
        $this->themes = Theme::all();
    }

    public function render()
    {
        return view('livewire.theme-manager');
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
        } else {
            Theme::create($data);
        }

        $this->resetForm();
        $this->loadThemes();
    }

    public function editTheme($themeId)
    {
        $theme = Theme::find($themeId);
        $this->selectedThemeId = $theme->id;
        $this->name = $theme->name;
        $this->font_primary = $theme->font_primary;
        $this->font_secondary = $theme->font_secondary;
        $this->isEditing = true;
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
        $this->isEditing = false;
    }
}
