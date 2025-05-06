<div class="space-y-6 p-6">
    {{-- Dynamic Heading --}}
    <h1 class="text-2xl font-bold">{{ $isCreating ? 'Create New Page' : 'Edit Page: ' . $name }}</h1>

    {{-- Meta Information Form --}}
    <div class="space-y-2">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" id="name" wire:model.lazy="name" placeholder="Page Name" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-offset-gray-800 @error('name') border-red-500 @enderror">
            @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
            <input type="text" id="slug" wire:model="slug" placeholder="page-slug" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-offset-gray-800 @error('slug') border-red-500 @enderror">
            @error('slug') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select id="status" wire:model="status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-offset-gray-800 @error('status') border-red-500 @enderror">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
             @error('status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
        {{-- Add parent_id if needed --}}

        {{-- Dynamic Button Text --}}
        <button wire:click="saveMeta" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
            {{ $isCreating ? 'Create Page & Continue' : 'Save Meta' }}
        </button>
        {{-- Show loading state --}}
        <span wire:loading wire:target="saveMeta">Saving...</span>
    </div>

    {{-- Conditionally show GrapesJS editor only when editing an existing page --}}
    @if(!$isCreating && $page->id) {{-- We ensure $pageContent is always set in the component, so only check $page->id --}}
        <hr class="dark:border-gray-600">
        {{-- Default to an empty object for @json if $pageContent is null or empty array, ensuring valid JSON like {} --}}
        <div wire:ignore id="gjs-container" data-content='@json($page->content ?? new stdClass())' data-page-id='{{ $page->id }}'>
            <h2 class="text-xl mb-4">Page Builder</h2>
            <button id="save-content" class="bg-green-600 text-white px-4 py-2 rounded mb-4">Save Content</button>

            <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
                <div id="#gjs-container">
                    <div id="gjs"></div>
                </div>

    @else
        <div class="mt-6 p-4 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-100">
            Please save the page details first to enable the content editor.
        </div>
    @endif
</div>
