<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;

class ThemeSettings extends Component
{
    public $page;
    public $themeSettings = [];

    public function mount($page = null)
    {
        $this->page = $page;

        $this->themeSettings = Theme::where('page_id', $page ? $page->id : null)
            ->orWhere('global', true)
            ->get();
    }

    public function updateThemeSetting($themeId, $value)
    {
        $theme = Theme::find($themeId);

        if ($theme) {
            $theme->value = $value;
            $theme->save();

            session()->flash('message', 'Theme setting updated successfully.');
        } else {
            session()->flash('error', 'Theme setting not found.');
        }

        $this->mount($this->page);
    }

    public function render()
    {
        return view('livewire.theme-settings');
    }
}
