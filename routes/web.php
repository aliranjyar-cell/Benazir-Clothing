<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
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
            'lowStock' => ProductVariant::query()
                ->where('stock_quantity', '<=', $lowStockThreshold)
                ->count(),
            'lowStockThreshold' => $lowStockThreshold,
        ],
    ]);
})->middleware('auth:admin')->name('dashboard');


Route::middleware('auth:admin')->group(function () {
    // Keep all your existing admin, category, product and profile routes here.

    Route::resource('categories', CategoryController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update'])
        ->except(['destroy'])
        ->names([
            'index' => 'categories.index',
            'create' => 'categories.create',
            'store' => 'categories.store',
            'show' => 'categories.show',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
        ]);

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
        ->name('categories.toggle-status');

    Route::resource('products', ProductController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
        ->name('products.toggle-status');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::middleware('auth')->group(function () {
//     Route::resource('admins', AdminController::class);
// });

require __DIR__ . '/auth.php';
