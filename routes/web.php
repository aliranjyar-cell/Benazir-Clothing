<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\AccountController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\CustomerAuthController;
use App\Http\Controllers\Storefront\WishlistController;
use App\Http\Controllers\Storefront\OrderController as StorefrontOrderController;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('store.home');

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

    Route::get('/admin/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');


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









Route::get('/', HomeController::class)
    ->name('store.home');


Route::middleware('auth')->group(function () {
    Route::resource('admins', AdminController::class);
});



/*
|--------------------------------------------------------------------------
| Storefront marketing pages
|--------------------------------------------------------------------------
*/
Route::view('/about', 'storefront.about')->name('store.about');
Route::view('/contact', 'storefront.contact')->name('store.contact');

Route::get('/shop', [ShopController::class, 'index'])->name('store.shop');

Route::get('/shop/{category}', [ShopController::class, 'index'])->name('store.shop.category');

Route::get('/products/{product}', [StorefrontProductController::class, 'show'])->name('store.products.show');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('store.wishlist.index');

Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('store.wishlist.toggle');

Route::post('/wishlist', [WishlistController::class, 'store'])->name('store.wishlist.store');

Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('store.wishlist.destroy');

Route::get('/cart', [CartController::class, 'index'])->name('store.cart.index');

Route::post('/cart/add', [CartController::class, 'store'])->name('store.cart.add');

Route::post('/cart/update', [CartController::class, 'update'])->name('store.cart.update');

Route::post('/cart/remove', [CartController::class, 'remove'])->name('store.cart.remove');

Route::post('/cart/clear', [CartController::class, 'clear'])->name('store.cart.clear');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('store.checkout.index');

Route::post('/checkout/place', [CheckoutController::class, 'store'])->name('store.checkout.place');

Route::middleware('guest:web')->group(function () {
    Route::get('/account/login', [CustomerAuthController::class, 'create'])->name('store.login');
    Route::post('/account/login', [CustomerAuthController::class, 'store']);
});

Route::post('/account/logout', [CustomerAuthController::class, 'destroy'])
    ->middleware('auth.customer:web')
    ->name('store.logout');

Route::get('/order-confirmation/{token}', [StorefrontOrderController::class, 'show'])
    ->whereUuid('token')
    ->name('store.orders.show');

// Route::get('/orders/{order}/confirm', [StorefrontOrderController::class, 'confirm'])->name('store.orders.confirm');

Route::middleware('auth.customer:web')->group(function () {
    Route::get('/account', [AccountController::class, 'show'])->name('store.account');
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('store.account.profile');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('store.account.orders');
    Route::patch('/account', [AccountController::class, 'update'])->name('store.account.update');
    Route::get('/account/orders/{order}', [AccountController::class, 'order'])
        ->name('store.account.orders.show');
});


require __DIR__ . '/auth.php';
