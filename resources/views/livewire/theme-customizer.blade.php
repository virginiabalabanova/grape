<div class="p-4">
    <h2 class="text-2xl font-bold mb-4">Theme Customizer</h2>

    <div class="mb-4">
        <label for="theme" class="block text-gray-700 text-sm font-bold mb-2">Select Theme:</label>
        <select wire:model="theme" id="theme" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:change="switchTheme($event.target.value)">
            <option value="default">Default</option>
            <option value="dark">Dark</option>
            <option value="light">Light</option>
        </select>
    </div>

    @foreach ($customizations as $category => $styles)
        <div class="mb-8 -ml-4">
            <h3 class="text-xl font-semibold mb-4 ml-4">{{ ucfirst($category) }} Styles</h3>

            <div class="grid grid-cols-3 gap-4 mb-2">
                <div class="w-1/5 px-4" style="width: 250px;">Key</div>
                <div class="w-3/5 flex-grow px-4">Value</div>
                <div class="w-1/5"></div>
            </div>

            <ul>
                @foreach ($styles as $customization)
                    @php
                        $customization = (object) $customization;
                    @endphp
                    <li class="flex justify-end mb-4 items-start">
                        <div class="w-1/4 px-4 w-250px">
                            @if(in_array($customization->key, $this->requiredKeys))
                                <span class="block text-gray-500 py-2">{{ $customization->key }}</span>
                            @else
                                <input type="text" id="key-{{ $customization->id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline py-2" value="{{ $customization->key }}" disabled>
                            @endif
                        </div>
                        <div class="flex-grow px-4">
                            <input type="text" wire:model="styleValues.{{ $customization->id }}" id="value-{{ $customization->id }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        @if(!in_array($customization->key, $this->requiredKeys))
                            <div class="ml-auto pl-4 w-250px">
                                <button wire:click="deleteStyle({{ $customization->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">-</button>
                            </div>
                        @else
                            <div class="ml-auto pl-4" style="width: 70px;"></div>
                        @endif
                    </li>
                @endforeach
                <li class="flex justify-end mb-4 items-start">
                    <div class="w-1/4 px-4">
                        <input type="text" wire:model="key" id="newKey-{{ $category }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('key') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-grow px-4">
                        <input type="text" wire:model="value" id="newValue-{{ $category }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('value') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="ml-auto pl-4">
                        <button wire:click="addStyle" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">+</button>
                    </div>
                </li>
</ul>

            <div class="flex justify-end">
                <button wire:click="updateAllStyles('{{ $category }}')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update All {{ ucfirst($category) }} Styles
                    <span wire:loading wire:target="updateAllStyles('{{ $category }}')">
                        Updating...
                    </span>
                </button>
            </div>
        </div>
    @endforeach
</div>
