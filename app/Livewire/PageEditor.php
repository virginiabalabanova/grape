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

    #[\Livewire\Attributes\On('saveGrapesContent')]
    public function saveContent($content)
    {
        // The event sends { content: contentJson }, so we access it via $content['content']
        // Ensure content is treated as an array/object for JSON storage
        if (isset($content['content'])) {
            $this->page->update(['content' => (array) $content['content']]);
            session()->flash('message', 'Page content saved.'); // Add feedback for content save
        } else {
             session()->flash('error', 'Failed to save content: Invalid data format received.'); // Add error feedback
             \Log::error('Invalid content data received in PageEditor:', ['data' => $content]); // Log the error
        }
    }

    public function render()
    {
        return view('livewire.page-editor');
    }
}
