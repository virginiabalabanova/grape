<div>
    <div x-data="{ notify: false, message: '' }">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <label for="theme" class="block text-gray-700 text-sm font-bold mb-2">Select Theme:</label>
            <select wire:model="theme" id="theme" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:change="switchTheme($event.target.value)">
                <option value="default">Default</option>
                <option value="dark">Dark</option>
                <option value="light">Light</option>
            </select>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-bold mb-2">Add New Style</h2>
            <div class="flex">
                <div class="mr-2">
                    <label for="key" class="block text-gray-700 text-sm font-bold mb-2">Key:</label>
                    <input type="text" wire:model="key" id="key" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('key') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Value:</label>
                    <input type="text" wire:model="value" id="value" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('value') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <button wire:click="addStyle" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Style</button>
        </div>

        <div>
            <h2 class="text-xl font-bold mb-2">Current Styles</h2>
            <ul>
                @foreach ($customizations as $customization)
                    <li class="mb-2 p-2 border rounded flex justify-between items-center">
                        @if ($editingKey === $customization->key)
                            <div class="flex-grow">
                                <label for="editingKey" class="block text-gray-700 text-sm font-bold mb-2">Key:</label>
                                <input type="text" wire:model="editingKey" id="editingKey" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <label for="editingValue" class="block text-gray-700 text-sm font-bold mb-2">Value:</label>
                                <input type="text" wire:model="editingValue" id="editingValue" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <button wire:click="updateStyle({{ $customization->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
                                <button wire:click="cancelEdit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                            </div>
                        @else
                            <span class="flex-grow">{{ $customization->key }}: {{ $customization->value }}</span>
                            <div>
                                <button wire:click="editStyle({{ $customization->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">Edit</button>
                                <button wire:click="deleteStyle({{ $customization->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
