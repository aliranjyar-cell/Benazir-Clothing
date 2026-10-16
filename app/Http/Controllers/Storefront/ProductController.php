<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Public product detail page, route-model bound by slug.
     * Inactive or out-of-catalogue products return 404.
     */
    public function show(Request $request, Product $product): View
    {
        abort_unless($product->is_active, 404);

        $similar = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereHas('activeVariants', fn($query) => $query->where('stock_quantity', '>', 0))
            ->with([
                'category:id,name,slug',
                'images' => fn($query) => $query->orderBy('sort_order'),
                'activeVariants' => fn($query) => $query->orderBy('price'),
            ])
            ->latest()
            ->limit(4)
            ->get();

        return view('storefront.product', [
            'product' => $product,
            'similarProducts' => $similar,
            'ingredients' => $product->fabric,
            'care' => $product->care_instructions,
        ]);
    }
}
