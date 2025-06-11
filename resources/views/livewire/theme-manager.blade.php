<div class="p-4">
    <h2 class="text-2xl font-bold mb-4">Manage Themes</h2>

    <div class="mb-4">
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
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ $isEditing ? 'Update Theme' : 'Create Theme' }}
                </button>
                @if($isEditing)
                    <button wire:click="resetForm" type="button" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    @if($isEditing)
    <div class="mb-4" x-data="{ selectedColors: @entangle('selectedColors') }">
        <h3 class="text-lg font-semibold mb-2">Assign Colors</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($allColors as $color)
                <div wire:click="toggleColor({{ $color->id }})"
                     class="w-8 h-8 rounded-full cursor-pointer transition-transform transform hover:scale-110 border-2"
                     :class="selectedColors.includes({{ $color->id }}) ? 'border-blue-500' : 'border-gray-200'"
                     style="background-color: {{ $color->hex }};"
                     title="{{ \Illuminate\Support\Str::title($color->name) }}">
                </div>
            @endforeach
        </div>
    </div>
@endif

    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Primary Font</th>
                <th class="px-4 py-2">Secondary Font</th>
                <th class="px-4 py-2">Colors</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($themes as $theme)
                <tr>
                    <td class="border px-4 py-2">{{ $theme->name }}</td>
                    <td class="border px-4 py-2">{{ $theme->font_primary }}</td>
                    <td class="border px-4 py-2">{{ $theme->font_secondary }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex flex-wrap gap-1">
                            @foreach($theme->colors as $color)
                                <div class="w-6 h-6 rounded-full" style="background-color: {{ $color->hex }};" title="{{ $color->name }}"></div>
                            @endforeach
                        </div>
                    </td>
                    <td class="border px-4 py-2">
                        <button wire:click="editTheme({{ $theme->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Edit</button>
                        <button wire:click="deleteTheme({{ $theme->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
