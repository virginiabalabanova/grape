<?php

namespace App\Livewire;

use App\Models\Page; // Add import for Page model
use Livewire\Component;

use Illuminate\Support\Str; // Import Str for slug generation

class PageEditor extends Component
{
    public Page $page;
    public bool $isCreating; // Flag to indicate if creating or editing

    public $name = '';
    public $slug = '';
    public $status = 'draft'; // Default status
    public $parent_id = null;

    // Updated rules to handle create vs update for slug uniqueness
    public function rules()
    {
        $slugRule = 'required|string|max:255|unique:pages,slug';
        if (!$this->isCreating) {
            // If editing, ignore the current page's ID
            $slugRule .= ',' . $this->page->id;
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => $slugRule,
            'status' => 'required|in:draft,published',
            'parent_id' => 'nullable|exists:pages,id',
        ];
    }

    public function mount(Page $page)
    {
        $this->page = $page;
        $this->isCreating = !$page->exists; // Set flag based on whether the model exists in DB

        // Initialize properties from the model if it exists (editing)
        if (!$this->isCreating) {
            $this->name = $page->name;
            $this->slug = $page->slug;
            $this->status = $page->status;
            $this->parent_id = $page->parent_id;
        }
        // For new pages, properties use their default values ('', '', 'draft', null)
    }

    // Automatically generate slug when name is updated (optional, but helpful)
    public function updatedName($value)
    {
        if ($this->isCreating || empty($this->slug)) { // Only auto-slug if creating or slug is empty
             $this->slug = Str::slug($value);
        }
    }

    public function saveMeta()
    {
        $validatedData = $this->validate();

        if ($this->isCreating) {
            // Create new page
            $this->page = Page::create($validatedData);
            session()->flash('message', 'Page created successfully.');
            // Redirect to the edit route for the newly created page
            return redirect()->route('pages.edit', $this->page);
        } else {
            // Update existing page
            $this->page->update($validatedData);
            session()->flash('message', 'Page meta updated successfully.');
        }
        // No redirect needed for update, stay on the page
    }

    // The saveGrapesContent method and its listener are no longer needed
    // as content saving is now handled by a direct Fetch API call.

    public function render()
    {
        $pageData = $this->page->content;

        // Ensure $pageData is an array or an object for GrapesJS.
        // If it's null or an empty string, default to an empty array
        // to ensure @json($pageData) produces valid JSON ('[]' or '{}') for parsing.
        if (empty($pageData)) {
            $pageData = []; // GrapesJS expects an object for project data,
                           // an empty object {} might be better if loadProjectData([]) causes issues.
                           // For now, [] should be safe for @json and subsequent checks in JS.
        }
        // If $pageData is a JSON string from the DB and not automatically cast, decode it.
        // This depends on your Page model's casts. If 'content' is cast to 'array' or 'object',
        // this json_decode is likely not needed.
        // elseif (is_string($pageData)) {
        //     $decoded = json_decode($pageData, true);
        //     $pageData = (json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
        // }

        return view('livewire.page-editor', [
            'pageContent' => $pageData
        ]);
    }
}
