<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $lowStockThreshold = 5;

    return view('admin.dashboard', [
        'stats' => [
            'products' => Product::query()->count(),
            'categories' => Category::query()->count(),
            'variants' => ProductVariant::query()->count(),
            'lowStock' => ProductVariant::query()->where('stock_quantity', '<=', $lowStockThreshold)->count(),
            'lowStockThreshold' => $lowStockThreshold,
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('admins', AdminController::class);

    Route::resource('products', ProductController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
        ->name('products.toggle-status');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
