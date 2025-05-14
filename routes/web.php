<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\PageController;
use App\Livewire\PageEditor;
// Removed duplicate imports for Appearance, Password, Profile
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage; // Added for asset uploads

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

use App\Http\Controllers\ThemeController;

Route::get('/api/theme', [ThemeController::class, 'index']);

require __DIR__.'/auth.php';

// Page Routes (grouped for clarity)
Route::middleware(['auth'])->prefix('pages')->name('pages.')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('index'); // Add index route
    // Route::post('/{page}/content', [PageController::class, 'saveContent'])->name('content.save'); // This will be replaced by the API route
    Route::get('/{page}/edit', function (Page $page) {
        return view('edit', ['page' => $page]);
    })->name('edit');
    // Define create and destroy routes (store is handled by Livewire component)
    Route::get('/create', [PageController::class, 'create'])->name('create');
    // Route::post('/', [PageController::class, 'store'])->name('store'); // Removed - Handled by PageEditor component
    Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');
});


use App\Support\GrapesRenderer; // Import the renderer

// Public page view route (must be last to avoid catching other routes)
Route::get('/{page:slug}', function (Page $page) {
    // Ensure only published pages are publicly visible, unless authenticated user
    if ($page->status !== 'published' && !auth()->check()) {
        abort(404);
    }

    // Render the GrapesJS content
    // Assumes $page->content is automatically cast to an array by the Page model
    $renderedContent = GrapesRenderer::render($page->content);

    // Pass the rendered HTML and CSS, along with the page object (for title, etc.)
    return view('pages.show', [
        'page' => $page, // Keep page for title, meta, etc.
        'renderedHtml' => $renderedContent['html'],
        'renderedCss' => $renderedContent['css'],
    ]);
})->name('pages.show');

// API route for saving GrapesJS content (ensure this is before auth.php if it's in web.php)
Route::middleware(['auth'])->post('/api/pages/{page}/save-content', function (Request $request, Page $page) {
    // Basic validation, can be expanded
    $validated = $request->validate([
        'content' => 'required|array' // Ensure content is provided and is an array (GrapesJS project data)
    ]);

    $page->content = $validated['content'];
    $page->save();

    return response()->json(['success' => true, 'message' => 'Content saved successfully.']);
})->name('api.pages.save-content');

// API route for uploading GrapesJS assets (images)
Route::middleware(['auth'])->post('/api/assets/upload', function (Request $request) {
    $request->validate([
        'files' => 'required|array', // GrapesJS asset manager sends files in an array by default with 'files[]' name
        'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
    ]);

    $urls = [];
    if ($request->hasfile('files')) {
        foreach ($request->file('files') as $file) {
            // Store the file in public storage, e.g., 'public/uploads/grapesjs'
            // Ensure 'php artisan storage:link' has been run for public accessibility
            $path = $file->store('uploads/grapesjs', 'public'); // Store in 'storage/app/public/uploads/grapesjs'
            // Get the public URL for the stored file
            $urls[] = Storage::url($path); // Generates URL like '/storage/uploads/grapesjs/filename.jpg'
        }
    }

    // GrapesJS Asset Manager expects a response with a 'data' key containing an array of URLs or objects with 'src'
    // For multiple uploads, it expects an array of objects, each with a 'src' property.
    // For a single upload (if assetManager.uploadFile is used), it might expect just one object or URL.
    // Let's return an array of strings (URLs) as per GrapesJS docs for multiple files.
    // If GrapesJS expects objects like { src: 'url' }, then map $urls: array_map(fn($url) => ['src' => $url], $urls)
    return response()->json(['data' => $urls]);
})->name('api.assets.upload');
