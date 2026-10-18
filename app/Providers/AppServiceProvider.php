<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerStoreSettings();

        // Share cart/wishlist counters and the navigation categories
        // with every storefront view (header and footer use them).
        View::composer('storefront.*', function ($view): void {
            $view->with([
                'cartCount' => app(CartService::class)->count(),
                'wishlistCount' => app(WishlistService::class)->count(),
                'navCategories' => $this->navCategories(),
            ]);
        });
    }

    /**
     * Merge the admin-managed store settings (see Admin\SettingController)
     * over the config/storefront.php defaults, so every view reads the
     * values saved in the admin panel — including the currency symbol
     * helper used across the storefront.
     */
    private function registerStoreSettings(): void
    {
        $settings = Setting::allSettings();

        // Browser titles and any other config('app.name') readers follow
        // the admin-managed store name too.
        Config::set('app.name', $settings['store_name']);
        Config::set('storefront.store_name', $settings['store_name']);
        Config::set('storefront.store_tagline', $settings['store_tagline']);
        Config::set('storefront.store_logo', $settings['store_logo']);
        Config::set('storefront.store_favicon', $settings['store_favicon']);
        Config::set('storefront.notifications.admin_email', $settings['contact_email']);
        Config::set('storefront.contact', [
            'email' => $settings['contact_email'],
            'phone' => $settings['contact_phone'],
            'address' => $settings['address'],
        ]);
        Config::set('storefront.currency', $settings['currency']);
        Config::set('storefront.currency_symbol', Setting::CURRENCY_SYMBOLS[$settings['currency']] ?? '₨');
        Config::set('storefront.checkout.currency', $settings['currency']);
        // Mail sender name follows the admin-managed store name too
        Config::set('mail.from.name', $settings['store_name']);

        // --- Shipping & Delivery (Settings → Shipping & Delivery) ---
        Config::set('storefront.checkout.shipping_flat', (float) $settings['shipping_flat_fee']);
        Config::set('storefront.checkout.shipping_free_over', (float) ($settings['shipping_free_over'] ?: 0));
        Config::set('storefront.shipping.estimated_days', (string) $settings['shipping_estimated_days']);

        // --- Checkout & Payments (Settings → Checkout & Payments) ---
        $enabledMethods = array_values(array_intersect(
            Setting::listValue('payment_methods_enabled', ['cash_on_delivery']),
            array_keys(Setting::PAYMENT_METHODS)
        ));

        if ($enabledMethods === []) {
            $enabledMethods = ['cash_on_delivery'];
        }

        $methodMap = array_intersect_key(Setting::PAYMENT_METHODS, array_flip($enabledMethods));
        $defaultMethod = (string) $settings['checkout_default_payment_method'];

        if (! isset($methodMap[$defaultMethod])) {
            $defaultMethod = (string) array_key_first($methodMap);
        }

        Config::set('storefront.checkout.payment_methods', $methodMap);
        Config::set('storefront.checkout.default_payment_method', $defaultMethod);
        Config::set('storefront.checkout.allow_guest_checkout', Setting::bool('checkout_allow_guest', true));
        Config::set('storefront.checkout.order_notes_enabled', Setting::bool('checkout_order_notes', true));
        Config::set('storefront.checkout.bank_details', (string) $settings['checkout_bank_details']);

        // --- Products & Inventory (Settings → Products & Inventory) ---
        Config::set('storefront.catalog.per_page', max(1, (int) $settings['products_per_page']));

        if (in_array($settings['timezone'], \DateTimeZone::listIdentifiers(), true)) {
            date_default_timezone_set($settings['timezone']);
        }
    }

    private function navCategories()
    {
        static $categories = null;

        if ($categories === null) {
            $categories = Category::query()
                ->active()
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(8)
                ->get(['id', 'name', 'slug']);
        }

        return $categories;
    }
}
