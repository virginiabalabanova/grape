<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ThemeCustomization;
use Illuminate\Support\Arr;

class ThemeCustomizer extends Component
{
    public $theme = 'default';
    public $key;
    public $value;
    public $customizations = [];
    private $requiredKeys = ['btn-primary', 'body-background', 'text-color'];
    public $styleValues = [];

    public $categories = [
        'global' => ['body-background', 'text-color'],
        'button' => ['btn-primary'],
        'header' => [],
        'structural' => [],
    ];

    public function mount()
    {
        $this->loadCustomizations();
        $this->ensureRequiredKeysExist();
        $this->initializeStyleValues();
    }

    private function ensureRequiredKeysExist()
    {
        $existingKeys = Arr::flatten(Arr::pluck($this->loadCustomizations(), 'key'));
        $missingKeys = array_diff($this->requiredKeys, $existingKeys);

        foreach ($missingKeys as $key) {
            ThemeCustomization::create([
                'theme_id' => $this->theme,
                'key' => $key,
                'value' => '', // You might want to set a default value here
            ]);
        }

        $this->loadCustomizations();
    }

    private function initializeStyleValues()
    {
        $this->styleValues = [];
        foreach ($this->customizations as $category => $customizations) {
            foreach ($customizations as $customization) {
                $customization = (object) $customization;
                $this->styleValues[$customization->id] = $customization->value;
            }
        }
    }

    public function switchTheme($theme)
    {
        $this->theme = $theme;
        $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    private function loadCustomizations()
    {
        $this->customizations = ThemeCustomization::where('theme_id', $this->theme)->get()->groupBy(function ($item) {
            foreach ($this->categories as $category => $keys) {
                if (in_array($item->key, $keys)) {
                    return $category;
                }
            }
            return 'global'; // Default category
        })->toArray();

        return ThemeCustomization::where('theme_id', $this->theme)->get()->toArray();
    }

    public function addStyle()
    {
        $this->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        if (in_array($this->key, $this->requiredKeys)) {
            session()->flash('message', 'Cannot add a required key.');
            return;
        }

        ThemeCustomization::create([
            'theme_id' => $this->theme,
            'key' => $this->key,
            'value' => $this->value,
        ]);

        $this->resetInputFields();
        $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    public function deleteStyle($id)
    {
        $customization = ThemeCustomization::find($id);
        if (in_array($customization->key, $this->requiredKeys)) {
            session()->flash('message', 'Cannot delete a required key.');
            return;
        }

        ThemeCustomization::destroy($id);
        $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    public function updateAllStyles($category)
    {
        foreach ($this->customizations[$category] as $customization) {
            $customization = ThemeCustomization::find($customization['id']);
            $customization->value = $this->styleValues[$customization->id];
            $customization->save();
        }

        session()->flash('message', ucfirst($category) . ' styles updated successfully.');
        $this->loadCustomizations();
        $this->initializeStyleValues();
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
