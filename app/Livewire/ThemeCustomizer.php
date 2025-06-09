<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ThemeCustomization;

class ThemeCustomizer extends Component
{
    public $theme = 'default';
    public $key;
    public $value;
    public $customizations = [];
    public $editingKey;
    public $editingValue;

    public function mount()
    {
        $this->loadCustomizations();
    }

    public function switchTheme($theme)
    {
        $this->theme = $theme;
        $this->loadCustomizations();
    }

    private function loadCustomizations()
    {
        $this->customizations = ThemeCustomization::where('theme_id', $this->theme)->get();
    }

    public function addStyle()
    {
        $this->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        ThemeCustomization::create([
            'theme_id' => $this->theme,
            'key' => $this->key,
            'value' => $this->value,
        ]);

        $this->resetInputFields();
        $this->loadCustomizations();
    }

    public function editStyle($id)
    {
        $customization = ThemeCustomization::find($id);
        $this->editingKey = $customization->key;
        $this->editingValue = $customization->value;
    }

    public function updateStyle($id)
    {
        $this->validate([
            'editingKey' => 'required|string',
            'editingValue' => 'required|string',
        ]);

        $customization = ThemeCustomization::find($id);
        $customization->key = $this->editingKey;
        $customization->value = $this->editingValue;
        $customization->save();

        $this->cancelEdit();
        $this->loadCustomizations();
    }

    public function cancelEdit()
    {
        $this->editingKey = null;
        $this->editingValue = null;
    }

    public function deleteStyle($id)
    {
        ThemeCustomization::destroy($id);
        $this->loadCustomizations();
    }

    private function resetInputFields()
    {
        $this->key = '';
        $this->value = '';
    }

    public function render()
    {
        return view('livewire.theme-customizer');
    }
}
