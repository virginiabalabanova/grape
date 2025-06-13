<?php

namespace App\Livewire;

use App\Models\Theme;
use Livewire\Component;
use Illuminate\Support\Arr;
use App\Jobs\CompileThemeCss;
use App\Models\ThemeCustomization;
use Illuminate\Support\Facades\Artisan;

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
        return ThemeCustomization::where('theme_id', $this->theme)
            ->orderBy('required', 'desc')
            ->get()
            ->groupBy('category')
            ->toArray();
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
                $this->styleValues[$customization['id']] = [
                    'key' => $customization['key'],
                    'value' => $customization['value'],
                ];
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
            $model = ThemeCustomization::find($customization['id']);
            if ($model) {
                $model->key = $this->styleValues[$model->id]['key'];
                $model->value = $this->styleValues[$model->id]['value'];
                $model->save();
            }
        }

        Artisan::call('theme:compile');
        //dd(Artisan::output());

        session()->flash('message', 'Theme styles saved! The CSS is now being recompiled in the background.');
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
