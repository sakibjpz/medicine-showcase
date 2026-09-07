<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MedSource Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/search', [PageController::class, 'search'])->name('search');
Route::get('/search/suggest', [PageController::class, 'suggest'])->name('search.suggest');

// Product Information
Route::get('/products', [PageController::class, 'show'])->defaults('slug', 'products')->name('products');
Route::get('/products/search', [PageController::class, 'show'])->defaults('slug', 'products.search')->name('products.search');
Route::get('/products/documents', [PageController::class, 'show'])->defaults('slug', 'products.documents')->name('products.documents');
Route::get('/products/country-status', [PageController::class, 'show'])->defaults('slug', 'products.country-status')->name('products.country-status');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Explore
Route::get('/therapeutic-areas', [PageController::class, 'show'])->defaults('slug', 'therapeutic-areas')->name('therapeutic-areas');
Route::get('/manufacturers', [PageController::class, 'show'])->defaults('slug', 'manufacturers')->name('manufacturers');
Route::get('/countries-languages', [PageController::class, 'show'])->defaults('slug', 'countries-languages')->name('countries-languages');
Route::get('/how-it-works', [PageController::class, 'show'])->defaults('slug', 'how-it-works')->name('how-it-works');

// Trust & Quality
Route::get('/quality-compliance', [PageController::class, 'show'])->defaults('slug', 'quality-compliance')->name('quality-compliance');
Route::get('/safety-notices', [PageController::class, 'show'])->defaults('slug', 'safety-notices')->name('safety-notices');
Route::get('/official-sources', [PageController::class, 'show'])->defaults('slug', 'official-sources')->name('official-sources');
Route::get('/accessibility', [PageController::class, 'show'])->defaults('slug', 'accessibility')->name('accessibility');

// Resources & Help
Route::get('/articles-updates', [PageController::class, 'show'])->defaults('slug', 'articles-updates')->name('articles-updates');
Route::get('/faq', [PageController::class, 'show'])->defaults('slug', 'faq')->name('faq');
Route::match(['get', 'post'], '/contact-hub', [PageController::class, 'contact'])->name('contact-hub');

// Navigation overview / resources landing
Route::get('/navigation-overview', [PageController::class, 'navigationOverview'])->name('navigation-overview');
Route::get('/resources', [PageController::class, 'navigationOverview'])->name('resources');

// About
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('about');

// Professional Enquiry
Route::match(['get', 'post'], '/professional-enquiry', [PageController::class, 'enquiry'])->name('professional-enquiry');

// Legal
Route::get('/privacy', [PageController::class, 'show'])->defaults('slug', 'privacy')->name('privacy');
Route::get('/terms', [PageController::class, 'show'])->defaults('slug', 'terms')->name('terms');

/*
|--------------------------------------------------------------------------
| Laravel Breeze Auth Routes (kept for future use)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/enquiries', [AdminController::class, 'enquiries'])->name('enquiries');
    Route::get('/enquiries/{enquiry}', [AdminController::class, 'showEnquiry'])->name('enquiries.show');
    Route::patch('/enquiries/{enquiry}/toggle', [AdminController::class, 'toggleEnquiry'])->name('enquiries.toggle');
    Route::delete('/enquiries/{enquiry}', [AdminController::class, 'destroyEnquiry'])->name('enquiries.destroy');

    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('messages.show');
    Route::patch('/messages/{message}/toggle', [AdminController::class, 'toggleMessage'])->name('messages.toggle');
    Route::delete('/messages/{message}', [AdminController::class, 'destroyMessage'])->name('messages.destroy');

    Route::get('/pages', [AdminController::class, 'pages'])->name('pages');
    Route::get('/pages/create', [AdminController::class, 'createPage'])->name('pages.create');
    Route::post('/pages', [AdminController::class, 'storePage'])->name('pages.store');
    Route::get('/pages/{page}/edit', [AdminController::class, 'editPage'])->name('pages.edit');
    Route::patch('/pages/{page}', [AdminController::class, 'updatePage'])->name('pages.update');
    Route::delete('/pages/{page}', [AdminController::class, 'destroyPage'])->name('pages.destroy');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::match(['put', 'patch'], '/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('api/admin')->group(function () {
    Route::get('/products', [AdminProductController::class, 'apiIndex']);
    Route::post('/products', [AdminProductController::class, 'apiStore']);
    Route::get('/products/{product}', [AdminProductController::class, 'apiShow']);
    Route::put('/products/{product}', [AdminProductController::class, 'apiUpdate']);
    Route::delete('/products/{product}', [AdminProductController::class, 'apiDestroy']);
    Route::get('/manufacturers', [AdminProductController::class, 'apiManufacturers']);
    Route::post('/products/validate-slug', [AdminProductController::class, 'validateSlug']);
});

require __DIR__.'/auth.php';
