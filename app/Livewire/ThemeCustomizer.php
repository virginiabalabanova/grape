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
    public $styleValues = [];

    private $requiredKeys = []; // Will be populated dynamically

    public $categories = []; // Will be populated dynamically

    private function loadRequiredKeys()
    {
        $this->requiredKeys = ThemeCustomization::where('theme_id', $this->theme)
            ->where('required', true)
            ->pluck('key')
            ->toArray();
    }

    private function loadCategories()
    {
        $categories = ThemeCustomization::where('theme_id', $this->theme)->get();
        $this->categories = [];
        foreach ($categories as $category) {
            if (!isset($this->categories[$category->category])) {
                $this->categories[$category->category] = [];
            }
            $this->categories[$category->category][] = $category->key;
        }
    }

    public function mount()
    {
        $this->loadRequiredKeys();
        $this->loadCategories();
        $this->customizations = $this->loadCustomizations();
        $this->ensureRequiredKeysExist();
        $this->initializeStyleValues();
    }

    private function loadCustomizations()
    {
        return ThemeCustomization::where('theme_id', $this->theme)->get()->groupBy(function ($item) {
            // Assign category based on known categories
            foreach ($this->categories as $category => $keys) {
                if ($item->category == $category) {
                    return $category;
                }
            }
            return 'other';
        })->toArray();
    }

    private function ensureRequiredKeysExist()
    {
        $existingKeys = ThemeCustomization::where('theme_id', $this->theme)->pluck('key')->toArray();
        $missingKeys = array_diff($this->requiredKeys, $existingKeys);

        foreach ($missingKeys as $key) {
            ThemeCustomization::create([
                'theme_id' => $this->theme,
                'key' => $key,
                'value' => '',
            ]);
        }

        $this->customizations = $this->loadCustomizations();
    }

    private function initializeStyleValues()
    {
        $this->styleValues = [];
        foreach ($this->customizations as $category => $styles) {
            foreach ($styles as $customization) {
                $this->styleValues[$customization['id']] = $customization['value'];
            }
        }
    }

    public function switchTheme($theme)
    {
        $this->theme = $theme;
        $this->loadRequiredKeys();
        $this->loadCategories();
        $this->customizations = $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    public function updateAllStyles($category)
    {
        foreach ($this->customizations[$category] as $customization) {
            $model = ThemeCustomization::find($customization['id']);
            if ($model) {
                $model->value = $this->styleValues[$model->id] ?? '';
                $model->save();
            }
        }

        session()->flash('message', ucfirst($category) . ' styles updated successfully.');
        $this->customizations = $this->loadCustomizations();
        $this->initializeStyleValues();
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
        $this->customizations = $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    public function deleteStyle($id)
    {
        $customization = ThemeCustomization::find($id);
        if ($customization && in_array($customization->key, $this->requiredKeys)) {
            session()->flash('message', 'Cannot delete a required key.');
            return;
        }

        ThemeCustomization::destroy($id);
        $this->customizations = $this->loadCustomizations();
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
