<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\InventoryController;
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



    Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
    Route::get('/inventory/{variant}/adjust', [InventoryController::class, 'adjust'])->name('admin.inventory.adjust');
    Route::post('/inventory/{variant}/adjust', [InventoryController::class, 'storeAdjustment'])->name('admin.inventory.adjust.store');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');





    Route::get('/orders', [OrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('admin.orders.show');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.status.update');

    Route::patch('/orders/{order}/payment', [OrderController::class, 'updatePayment'])
        ->name('admin.orders.payment.update');




    Route::get('/customers/create', [CustomerController::class, 'create'])
        ->name('admin.customers.create');

    Route::post('/customers', [CustomerController::class, 'store'])
        ->name('admin.customers.store');

    Route::get('/customers', [CustomerController::class, 'index'])
        ->name('admin.customers.index');

    Route::get('/customers/{customer}', [CustomerController::class, 'show'])
        ->name('admin.customers.show');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])
        ->name('admin.customers.edit');

    Route::put('/customers/{customer}', [CustomerController::class, 'update'])
        ->name('admin.customers.update');

    Route::patch(
        '/orders/{order}/link-customer',
        [OrderController::class, 'linkCustomer']
    )->name('admin.orders.link-customer');
    
});
// Route::middleware('auth')->group(function () {
//     Route::resource('admins', AdminController::class);
// });

require __DIR__ . '/auth.php';
