<?php

namespace App\Support;

class GrapesRenderer
{
    public static function render(array $components): string
    {
        return self::renderComponent($components);
    }

    protected static function renderComponent($component): string
    {
        if (is_array($component) && isset($component[0])) {
            // Multiple components
            return collect($component)->map(function ($comp) {
                return self::renderComponent($comp);
            })->implode('');
        }

        if (!isset($component['type'])) {
            return '';
        }

        $tag = $component['tagName'] ?? 'div';
        $attributes = self::renderAttributes($component['attributes'] ?? []);
        $content = '';

        // If the component has inner components
        if (!empty($component['components'])) {
            $content = self::renderComponent($component['components']);
        }

        // If the component has raw content (like a text node)
        if (!empty($component['content'])) {
            $content .= $component['content'];
        }

        return "<{$tag}{$attributes}>{$content}</{$tag}>";
    }

    protected static function renderAttributes(array $attributes): string
    {
        return collect($attributes)->map(function ($value, $key) {
            return " {$key}=\"" . htmlspecialchars($value, ENT_QUOTES) . "\"";
        })->implode('');
    }
}
