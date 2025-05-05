<?php

namespace App\Http\Controllers;

use App\Models\Page; // Import the Page model
use Illuminate\Http\Request;
use Illuminate\View\View; // Import View for type hinting
use Illuminate\Http\JsonResponse; // Import JsonResponse for type hinting
use Illuminate\Http\RedirectResponse; // Import RedirectResponse for type hinting

class PageController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index(): View
    {
        return view('pages.index', [
            'pages' => Page::orderBy('name')->get() // Fetch pages, ordered by name
        ]);
    }

    /**
     * Show the form for creating a new page (using the edit view with a new model).
     */
    public function create(): View
    {
        // Return the 'edit' view (which loads the Livewire component)
        // Pass a new, unsaved Page instance.
        return view('edit', ['page' => new Page()]);
    }

    // store() method is now handled by the PageEditor Livewire component

    // edit() method is handled by the route closure pointing to the 'edit' view

    // update() method is now handled by the PageEditor Livewire component

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        // Add authorization check if needed
        $page->delete();
        // Optionally add a success message
        session()->flash('message', 'Page deleted successfully.');
        return redirect()->route('pages.index'); // Redirect to index after delete
    }

    /**
     * Save the GrapesJS content for a page.
     * Note: This is still handled by the controller, but could potentially be moved
     * into the Livewire component as well if preferred.
     */
    public function saveContent(Request $request, Page $page): JsonResponse
    {
        $page->update([
            'content' => $request->validate(['content' => 'required|array'])['content'],
        ]);

        return response()->json(['status' => 'saved']);
    }
}
