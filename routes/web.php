<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Auth\LoginController;

// Authentication Routes with Rate Limiting
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [LoginController::class, 'show'])->name('profile');
    Route::put('/profile', [LoginController::class, 'updateProfile'])->name('profile.update');
});

Route::get('/', function () {
    $featuredProducts = Product::with(['images'])
        ->where('featured', true)
        ->take(4)
        ->get();
        
    $allProducts = Product::with(['images'])
        ->orderBy('name')
        ->get();
        
    $categories = Category::where('is_active', true)
        ->orderBy('name')
        ->get();
        
    return Inertia::render('Home', [
        'featuredProducts' => $featuredProducts,
        'allProducts' => $allProducts,
        'categories' => $categories
    ]);
});

// Admin Routes with Rate Limiting
Route::prefix('admin')->middleware(['web', 'auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');
    
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->middleware('throttle:10,1')->name('admin.products.store');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->middleware('throttle:10,1')->name('admin.products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->middleware('throttle:10,1')->name('admin.products.destroy');
    
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->middleware('throttle:10,1')->name('admin.categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->middleware('throttle:10,1')->name('admin.categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->middleware('throttle:10,1')->name('admin.categories.destroy');
});

Route::get('/products/{slug}', function ($slug) {
    $product = \App\Models\Product::with('images')->where('slug', $slug)->firstOrFail();
    return Inertia::render('ProductDetail', [
        'product' => $product
    ]);
})->name('products.show');
