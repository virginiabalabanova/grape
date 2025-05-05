<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\PageController;
use App\Livewire\PageEditor;
// Removed duplicate imports for Appearance, Password, Profile
use App\Models\Page;
use Illuminate\Support\Facades\Route;

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

// Page Routes (grouped for clarity)
Route::middleware(['auth'])->prefix('pages')->name('pages.')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('index'); // Add index route
    Route::post('/{page}/content', [PageController::class, 'saveContent'])->name('content.save');
    Route::get('/{page}/edit', function (Page $page) {
        return view('edit', ['page' => $page]);
    })->name('edit');
    // Define create and destroy routes (store is handled by Livewire component)
    Route::get('/create', [PageController::class, 'create'])->name('create');
    // Route::post('/', [PageController::class, 'store'])->name('store'); // Removed - Handled by PageEditor component
    Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');
});


// Public page view route (must be last to avoid catching other routes)
Route::get('/{page:slug}', function (Page $page) {
    // Ensure only published pages are publicly visible, unless authenticated user
    if ($page->status !== 'published' && !auth()->check()) {
        abort(404);
    }
    return view('pages.show', compact('page'));
})->name('pages.show');

require __DIR__.'/auth.php';
