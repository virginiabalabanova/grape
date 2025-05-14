<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form>
        @foreach ($themeSettings as $theme)
            <div class="mb-4">
                <label for="{{ $theme->key }}" class="block text-gray-700 text-sm font-bold mb-2">{{ $theme->name }} (<code>.{{ $theme->key }}</code>)</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="{{ $theme->key }}" wire:model.debounce.500ms="theme.value" wire:change="updateThemeSetting({{ $theme->id }}, $theme->value)">
                <p class="text-gray-500 text-xs italic">Enter the Tailwind CSS classes to apply to <code>.{{ $theme->key }}</code></p>
            </div>
        @endforeach
    </form>
</div>
