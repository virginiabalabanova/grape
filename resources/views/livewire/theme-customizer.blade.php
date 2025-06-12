<div class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Theme Customizer</h2>
        <a href="/themes" class="text-white py-2 px-4 rounded text-sm" style="background-color: #009ddc; letter-spacing: 0.05em;">
            Manage Themes
        </a>
    </div>

    <div class="mb-4">
        <label for="theme" class="block text-gray-700 text-sm font-bold mb-2">Select Theme:</label>
        <select wire:model="theme" id="theme" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:change="switchTheme($event.target.value)">
            @foreach($themes as $theme)
                <option value="{{ $theme['id'] }}">{{ $theme['name'] }}</option>
            @endforeach
        </select>
        <div class="flex my-4">
            @if($selectedTheme = collect($themes)->firstWhere('id', $this->theme))
                @foreach($selectedTheme['colors'] as $color)
                    <div class="flex items-center mr-4">
                        <div class="w-6 h-6 rounded-full mr-2" style="background-color: {{ $color['hex'] }};" title="{{ $color['name'] }}"></div>
                        <span>{{ $color['name'] }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    @foreach ($customizations as $category => $styles)
        <div class="mb-8 -ml-4 mt-8">
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
                                <button wire:click="deleteStyle({{ $customization->id }})" class="text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="background-color: #f26430; letter-spacing: 0.05em;">-</button>
                            </div>
                        @else
                            <div class="ml-auto pl-4" style="width: 70px;"></div>
                        @endif
                    </li>
                @endforeach
                <li class="flex justify-end mb-4 items-start">
                    <div class="w-1/4 px-4">
                        <input type="text" wire:model="newKey.{{ $category }}" id="newKey-{{ $category }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error("newKey.{$category}") <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-grow px-4">
                        <input type="text" wire:model="newValue.{{ $category }}" id="newValue-{{ $category }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error("newValue.{$category}") <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="ml-auto pl-4">
                        <button wire:click="addStyle('{{ $category }}')" class="text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline" style="background-color: #009ddc; letter-spacing: 0.05em;">+</button>
                    </div>
                </li>
</ul>

            <div class="flex justify-end">
                <button wire:click="updateAllStyles('{{ $category }}')" class="text-white py-2 px-4 rounded text-sm" style="background-color: #009b72; letter-spacing: 0.05em;">
                    Update All {{ ucfirst($category) }} Styles
                    <span wire:loading wire:target="updateAllStyles('{{ $category }}')">
                        Updating...
                    </span>
                </button>
            </div>
        </div>
    @endforeach
</div>
