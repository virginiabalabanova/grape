<div>
    <h1>Theme Customizer</h1>

    @foreach ($themeCustomizations as $themeCustomization)
        <div wire:key="{{ $themeCustomization['key'] }}">
            <label for="{{ $themeCustomization['key'] }}">{{ $themeCustomization['key'] }}</label>
            <input
                type="text"
                id="{{ $themeCustomization['key'] }}"
                wire:model.debounce.500ms="themeCustomizations.{{ $loop->index }}.value"
                wire:change="$dispatch('themeCustomizationUpdated', { key: '{{ $themeCustomization['key'] }}', value: $event->target->value })"
            />
        </div>
    @endforeach
</div>
