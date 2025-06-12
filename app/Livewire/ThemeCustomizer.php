<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;
use App\Models\ThemeCustomization;
use Illuminate\Support\Arr;

class ThemeCustomizer extends Component
{
    public $theme;
    public $themes = [];
    public $newKey = [];
    public $newValue = [];
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
        $this->themes = Theme::with('colors')->get()->toArray();
        $defaultTheme = collect($this->themes)->where('name', 'default')->first();
        $this->theme = $defaultTheme ? $defaultTheme['id'] : $this->themes[0]['id'];
        
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
                'category' => 'general',
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

    public function switchTheme($themeId)
    {
        $this->theme = $themeId;
        $this->loadRequiredKeys();
        $this->loadCategories();
        $this->customizations = $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    public function updateAllStyles($category)
    {
        foreach ($this->customizations[$category] as $customization) {
            ThemeCustomization::where('id', $customization['id'])->update(['value' => $this->styleValues[$customization['id']]]);
        }

        session()->flash('message', ucfirst($category) . ' styles updated successfully.');
        $this->customizations = $this->loadCustomizations();
        $this->initializeStyleValues();
    }

    public function addStyle($category)
    {
        $this->validate([
            "newKey.{$category}" => 'required|string',
            "newValue.{$category}" => 'required|string',
        ]);

        if (in_array($this->newKey[$category], $this->requiredKeys)) {
            session()->flash('message', 'Cannot add a required key.');
            return;
        }

        ThemeCustomization::create([
            'theme_id' => $this->theme,
            'key' => $this->newKey[$category],
            'value' => $this->newValue[$category],
            'category' => $category,
            'required' => false,
        ]);

        $this->resetInputFields($category);
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

    private function resetInputFields($category)
    {
        $this->newKey[$category] = '';
        $this->newValue[$category] = '';
    }

    public function render()
    {
        return view('livewire.theme-customizer');
    }
}
