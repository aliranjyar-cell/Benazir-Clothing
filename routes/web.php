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
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\AccountController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\CustomerAuthController;
use App\Http\Controllers\Storefront\WishlistController;
use App\Http\Controllers\Storefront\OrderController as StorefrontOrderController;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('store.home');

Route::get('/dashboard', function () {
    $lowStockThreshold = max(1, (int) Setting::get('low_stock_threshold', 5));

    $salesChart = collect(range(5, 0))->map(function ($offset) {
        $month = now()->copy()->subMonths($offset)->startOfMonth();

        return [
            'label' => $month->translatedFormat('M'),
            'value' => (float) Order::query()
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->where('payment_status', Order::PAYMENT_PAID)
                ->sum('grand_total'),
        ];
    });

    $recentOrders = Order::query()
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', [
        'stats' => [
            'products' => Product::query()->count(),
            'categories' => Category::query()->count(),
            'variants' => ProductVariant::query()->count(),
            'orders' => Order::query()->count(),
            'revenue' => (float) Order::query()
                ->where('payment_status', Order::PAYMENT_PAID)
                ->sum('grand_total'),
            'pending' => Order::query()->where('status', Order::STATUS_PENDING)->count(),
            'lowStock' => ProductVariant::query()
                ->where('stock_quantity', '<=', $lowStockThreshold)
                ->count(),
            'lowStockThreshold' => $lowStockThreshold,
        ],
        'salesChart' => $salesChart,
        'recentOrders' => $recentOrders,
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

    Route::resource('purchase-orders', PurchaseOrderController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->names('admin.purchase-orders');

    Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])
        ->name('admin.purchase-orders.receive');








    Route::get('/orders', [OrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('admin.orders.show');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.status.update');

    Route::patch('/orders/{order}/payment', [OrderController::class, 'updatePayment'])
        ->name('admin.orders.payment.update');


    Route::get('/settings', [SettingController::class, 'index'])
        ->name('admin.settings.index');

    // Legacy alias kept so anything posting to the old endpoint keeps working.
    Route::post('/settings', [SettingController::class, 'updateGeneral'])
        ->name('admin.settings.update');

    // --- Settings module: sections ---
    Route::get('/settings/general', [SettingController::class, 'general'])
        ->name('admin.settings.general');
    Route::post('/settings/general', [SettingController::class, 'updateGeneral'])
        ->name('admin.settings.general.update');

    Route::get('/settings/products', [SettingController::class, 'products'])
        ->name('admin.settings.products');
    Route::post('/settings/products', [SettingController::class, 'updateProducts'])
        ->name('admin.settings.products.update');

    Route::get('/settings/shipping', [SettingController::class, 'shipping'])
        ->name('admin.settings.shipping');
    Route::post('/settings/shipping', [SettingController::class, 'updateShipping'])
        ->name('admin.settings.shipping.update');

    Route::get('/settings/checkout', [SettingController::class, 'checkout'])
        ->name('admin.settings.checkout');
    Route::post('/settings/checkout', [SettingController::class, 'updateCheckout'])
        ->name('admin.settings.checkout.update');

    Route::get('/settings/account-security', [SettingController::class, 'accountSecurity'])
        ->name('admin.settings.account-security');
    Route::post('/settings/account-security/password', [SettingController::class, 'updatePassword'])
        ->name('admin.settings.account-security.password');

    Route::get('/settings/orders-notifications', [SettingController::class, 'ordersNotifications'])
        ->name('admin.settings.orders-notifications');
    Route::post('/settings/orders-notifications/{notification}/read', [SettingController::class, 'markNotificationRead'])
        ->name('admin.settings.orders-notifications.read');

    // Sections listed on the overview but not implemented yet → roadmap page.
    Route::get('/settings/{section}', [SettingController::class, 'section'])
        ->where('section', 'storefront-branding|policies-support')
        ->name('admin.settings.section');




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

Route::middleware('auth:web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});







Route::get('/', HomeController::class)
    ->name('store.home');


Route::middleware('auth')->group(function () {
    Route::resource('admins', AdminController::class);
});



/*
|--------------------------------------------------------------------------
| Storefront marketing pages $heroProduct?->images->first()?->image_path ? asset('storage/' . $heroProduct->images->first()->image_path) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuA_mA04qlOe4S_eeZFybxNgEqKVXmbuluMX5DOtB34oaFlV6fd9uxjY3glLNqnMUyuL0lsYxhr643GEd3EUNuDkv-75v2aqPnlWn2wvRaE26QER84dta_7HbkupM0f7bk3k_xWSsyVwkbD_avGzWIoUzBYmRYNzHzitr9XioywDKUP1c9Ylc-2bI_0-Uymu4IsXDmvk7-K7yaQ3w_CDfFQNnw4-NI9HBdjyzrZE9Yz9HDh2OYK0lNLe'
|--------------------------------------------------------------------------
*/
Route::view('/about', 'storefront.about')->name('store.about');
Route::view('/contact', 'storefront.contact')->name('store.contact');
Route::post('/contact', [\App\Http\Controllers\Storefront\ContactController::class, 'store'])
    ->name('store.contact.submit');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('store.newsletter.subscribe');

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

Route::get('/order-tracking', [StorefrontOrderController::class, 'trackingForm'])
    ->name('store.orders.track');

Route::post('/order-tracking', [StorefrontOrderController::class, 'track'])
    ->middleware('throttle:10,1')
    ->name('store.orders.track.submit');

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
