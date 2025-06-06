<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ThemeCustomization;

class GrapesRenderer
{
    /**
     * Renders the full GrapesJS project data into HTML and CSS.
     *
     * @param array|null $projectData The full data object from editor.getProjectData()
     * @return array ['html' => string, 'css' => string]
     */
    public static function render(?array $projectData): array
    {
        if (empty($projectData)) {
            return ['html' => '', 'css' => ''];
        }

        // Extract the main component structure (adjust path if necessary based on your GrapesJS config)
        // Common path: $projectData['pages'][0]['frames'][0]['component']
        // Fallback to root if pages structure doesn't exist
        $mainComponent = Arr::get($projectData, 'pages.0.frames.0.component', $projectData);

        // Render HTML structure
        $html = self::renderComponent($mainComponent);

        // Extract and render CSS
        $css = self::renderStyles($projectData);

        return ['html' => $html, 'css' => $css];
    }

    /**
     * Recursively renders a GrapesJS component and its children into HTML.
     *
     * @param mixed $component The component data (array or string for text nodes)
     * @return string
     */
    protected static function renderComponent($component): string
    {
        if (is_array($component) && Arr::isList($component)) {
            return collect($component)->map(fn ($comp) => self::renderComponent($comp))->implode('');
        }

        if (is_string($component)) {
            return htmlspecialchars($component);
        }

        if (isset($component['type']) && $component['type'] === 'textnode') {
            return htmlspecialchars($component['content'] ?? '');
        }

        if (!is_array($component)) {
            return '';
        }

        if (($component['type'] ?? null) === 'image') {
            $attrs = $component['attributes'] ?? [];

            $src = $attrs['src'] ?? '';
            $alt = $attrs['alt'] ?? '';

            $attributes = self::renderAttributes($attrs);

            return "<img src=\"{$src}\" alt=\"{$alt}\"{$attributes} />";
        }

        if (($component['type'] ?? null) === 'link') {

            // Step 1: Get attributes
            $attrs = $component['attributes'] ?? [];
        
            // Step 2: Merge classes into attributes
            $componentClassesRaw = $component['classes'] ?? [];
            if (!empty($componentClassesRaw)) {
                $finalClasses = [];
                foreach ($componentClassesRaw as $classEntry) {
                    if (is_string($classEntry)) {
                        $finalClasses[] = $classEntry;
                    } elseif (is_array($classEntry)) {
                        foreach (Arr::flatten($classEntry) as $subClass) {
                            if (is_string($subClass)) {
                                $finalClasses[] = $subClass;
                            }
                        }
                    }
                }
        
                if (!empty($finalClasses)) {
                    if (isset($attrs['class'])) {
                        $attrs['class'] .= ' ' . implode(' ', $finalClasses);
                    } else {
                        $attrs['class'] = implode(' ', $finalClasses);
                    }
                }
            }
        
            // Step 3: Handle href
            $href = $attrs['href'] ?? '#';
            unset($attrs['href']); // Remove href from attributes to avoid duplicate
        
            // Step 4: Render attributes
            $attributes = self::renderAttributes($attrs);
        
            // Step 5: Render content
            $content = '';
            if (!empty($component['components'])) {
                $content = self::renderComponent($component['components']);
            } elseif (!empty($component['content'])) {
                $content .= htmlspecialchars($component['content']);
            }
        
            // Step 6: Return the anchor
            return "<a href=\"{$href}\"{$attributes}>{$content}</a>";
        }
        

        // Normal components
        $tag = $component['tagName'] ?? 'div';
        $componentAttributes = $component['attributes'] ?? [];

        // Handle classes specifically
        $componentClassesRaw = $component['classes'] ?? [];
        if (!empty($componentClassesRaw) && is_array($componentClassesRaw)) {
            $finalClasses = [];
            foreach ($componentClassesRaw as $classEntry) {
                if (is_string($classEntry)) {
                    $finalClasses[] = $classEntry;
                } elseif (is_array($classEntry)) {
                    // If it's an array of strings (e.g., from a bad merge or complex state)
                    // try to flatten and add valid strings.
                    // This is a basic handling; more complex structures might need specific parsing.
                    foreach(Arr::flatten($classEntry) as $subClass) {
                        if (is_string($subClass)) {
                            $finalClasses[] = $subClass;
                        }
                    }
                }
                // Add more sophisticated handling here if classes can be objects like { name: '...', active: true }
                // For now, we only process string entries or flattened arrays of strings.
            }

            if (!empty($finalClasses)) {
                $classString = implode(' ', array_unique($finalClasses)); // Use array_unique to avoid duplicates
                // Merge with existing class attribute or add new one
                if (isset($componentAttributes['class'])) {
                    $componentAttributes['class'] = trim($componentAttributes['class'] . ' ' . $classString);
                } else {
                    $componentAttributes['class'] = $classString;
                }
            }
        } // <-- This closes the "if (!empty($componentClassesRaw)...)" block

        $attributes = self::renderAttributes($componentAttributes);
        $content = '';

        if (!empty($component['components'])) {
            $content = self::renderComponent($component['components']);
        } elseif (!empty($component['content'])) {
            $content .= htmlspecialchars($component['content']);
        }

        return "<{$tag}{$attributes}>{$content}</{$tag}>";
    }

    /**
     * Renders component attributes into an HTML string.
     *
     * @param array $attributes
     * @return string
     */
    protected static function renderAttributes(array $attributes): string
    {
        // Exclude GrapesJS internal attributes like 'data-gjs-*' if needed
        return collect($attributes)
            ->reject(fn ($value, $key) => Str::startsWith($key, 'data-gjs-'))
            ->map(function ($value, $key) {
                // Handle boolean attributes (e.g., disabled, checked)
                if (is_bool($value)) {
                    return $value ? " {$key}" : '';
                }
                return " {$key}=\"" . htmlspecialchars($value, ENT_QUOTES) . "\"";
            })->implode('');
    }

     /**
     * Extracts and formats CSS rules from GrapesJS project data.
     *
     * @param array $projectData
     * @return string
     */
    protected static function renderStyles(array $projectData): string
    {
        $styles = Arr::get($projectData, 'styles', []); // GrapesJS often stores styles here
        if (empty($styles) && isset($projectData['css'])) {
             // Fallback if styles are directly under 'css' key
             return is_string($projectData['css']) ? $projectData['css'] : '';
        }
        if (!is_array($styles)) return '';

        $themeId = session('theme', 'default');
        $themeCustomizations = ThemeCustomization::where('theme_id', $themeId)->get();

        $themeStyles = "<style>\n";
        foreach ($themeCustomizations as $themeCustomization) {
            $themeStyles .= ".{$themeCustomization->key} {\n";
            $themeStyles .= "  @apply {$themeCustomization->value};\n";
            $themeStyles .= "}\n";
        }
        $themeStyles .= "</style>\n";

        $cssString = '';
        foreach ($styles as $styleRule) {
            if (empty($styleRule['selectors']) || empty($styleRule['style'])) {
                continue;
            }

            // Ensure selectors is an array
            $selectors = is_array($styleRule['selectors']) ? $styleRule['selectors'] : [$styleRule['selectors']];

            // Join multiple selectors with a comma
            $selectorString = collect($selectors)->map(function($selector) {
                 // Handle potential object selectors (though less common for CSS rules)
                 return is_string($selector) ? $selector : ($selector['name'] ?? '');
            })->filter()->implode(', ');


            if (empty($selectorString)) continue;

            // Format the style properties
            $styleProperties = collect($styleRule['style'])
                ->map(fn ($value, $key) => Str::kebab($key) . ": " . $value . ";")
                ->implode(' ');

            $cssString .= "{$selectorString} { {$styleProperties} }\n";
        }

        // Look for CSS stored directly in pages (less common but possible)
         $pageCss = Arr::get($projectData, 'pages.0.frames.0.css');
         if (!empty($pageCss) && is_string($pageCss)) {
             $cssString .= "\n" . $pageCss;
         }


        return $cssString;
    }
}
