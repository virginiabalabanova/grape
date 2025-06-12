<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Manage Themes</h2>
        <a href="/theme-editor" class="text-white py-2 px-4 rounded text-sm" style="background-color: #009ddc; letter-spacing: 0.05em;">
            Theme Editor
        </a>
    </div>

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Primary Font</th>
                <th class="px-4 py-2">Colors</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($themes as $theme)
                <tr>
                    <td class="border px-4 py-2">{{ $theme->name }}</td>
                    <td class="border px-4 py-2">{{ $theme->font_primary }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex flex-wrap gap-1">
                            @foreach($theme->colors as $color)
                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $color->hex }};" title="{{ $color->name }}"></div>
                            @endforeach
                        </div>
                    </td>
                    <td class="border px-4 py-2">
                        <button wire:click="editTheme({{ $theme->id }})" class="text-white py-1 px-2 rounded text-sm" style="background-color: #009b72; letter-spacing: 0.05em;">Edit</button>
                        <button wire:click="deleteTheme({{ $theme->id }})" class="text-white py-1 px-2 rounded text-sm" style="background-color: #f26430; letter-spacing: 0.05em;">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($isEditing)
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-2">Edit Theme</h3>
            <form wire:submit.prevent="saveTheme">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Theme Name">
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="font_primary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Primary Font">
                        @error('font_primary') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            <div class="flex items-center mt-4">
                <button type="submit" class="text-white py-2 px-4 rounded text-sm" style="background-color: #009ddc; letter-spacing: 0.05em;">
                    {{ $isEditing ? 'Update Theme' : 'Create Theme' }}
                </button>
                @if($isEditing)
                    <button wire:click="resetForm" type="button" class="ml-2 text-white py-2 px-4 rounded text-sm" style="background-color: #2a2d34; letter-spacing: 0.05em;">
                        Cancel
                    </button>
                @endif
            </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-2">Assign Colors</h3>
            <div class="flex flex-wrap gap-4">
                @foreach($allColors as $color)
                    <div wire:click="toggleColor({{ $color->id }})"
                         class="w-10 h-10 rounded-full cursor-pointer transition-transform transform hover:scale-110 @if(in_array($color->id, $selectedColors)) ring-2 ring-offset-2 ring-black @endif"
                         style="background-color: {{ $color->hex }};"
                         title="{{ \Illuminate\Support\Str::title($color->name) }}">
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-2">Create Theme</h3>
            <form wire:submit.prevent="saveTheme">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <input type="text" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Theme Name">
                        @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="font_primary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Primary Font">
                        @error('font_primary') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="font_secondary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Secondary Font">
                        @error('font_secondary') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex items-center mt-4">
                    <button type="submit" class="text-white py-2 px-4 rounded text-sm" style="background-color: #009ddc; letter-spacing: 0.05em;">
                        Create Theme
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
