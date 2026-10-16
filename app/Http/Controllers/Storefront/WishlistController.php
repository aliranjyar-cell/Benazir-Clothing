<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(private readonly WishlistService $wishlist)
    {
    }

    /**
     * Session-based wishlist (works for guests and customers alike).
     */
    public function index(): View
    {
        return view('storefront.wishlist', [
            'products' => $this->wishlist->products(),
        ]);
    }

    /**
     * Toggle a product in the wishlist. Only active products can be
     * wishlisted.
     */
    public function toggle(int $productId): RedirectResponse
    {
        $product = Product::query()
            ->active()
            ->findOrFail($productId);

        $saved = $this->wishlist->toggle($product->id);

        return back()->with(
            'success',
            $saved
                ? "{$product->name} added to your wishlist."
                : "{$product->name} removed from your wishlist."
        );
    }
}
