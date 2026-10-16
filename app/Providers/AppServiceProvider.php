<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use App\Services\WishlistService;
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
