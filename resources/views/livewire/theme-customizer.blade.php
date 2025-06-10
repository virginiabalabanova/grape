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
            @foreach ($customizations as $category => $styles)
                <h3 class="text-lg font-semibold mb-2">{{ ucfirst($category) }}</h3>
                <div class="flex items-center mb-2">
                    <div class="w-1/4 mr-2">Key</div>
                    <div class="flex-grow mr-2">Value</div>
                    <div></div>
                </div>
                <ul>
                    @foreach ($styles as $customization)
                        @php
                            $customization = (object) $customization;
                        @endphp
                        <li class="p-2 border rounded flex items-center mb-4">
                            <div class="w-1/4">
                                @if(in_array($customization->key, $this->requiredKeys))
                                    <span class="block text-gray-500">{{ $customization->key }}</span>
                                @else
                                    <input type="text" id="key-{{ $customization->id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $customization->key }}" disabled>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <input type="text" wire:model="styleValues.{{ $customization->id }}" id="value-{{ $customization->id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </div>
                            @if(!in_array($customization->key, $this->requiredKeys))
                                <div class="ml-auto">
                                    <button wire:click="deleteStyle({{ $customization->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <button wire:click="updateAllStyles('{{ $category }}')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update All {{ ucfirst($category) }} Styles</button>
            @endforeach
        </div>
    </div>
</div>
