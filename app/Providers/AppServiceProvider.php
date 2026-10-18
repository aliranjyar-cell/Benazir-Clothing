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
