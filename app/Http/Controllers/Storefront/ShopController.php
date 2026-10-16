<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ShopController extends Controller
{
    /**
     * Public storefront listing. Supports search, category filter,
     * sorting (latest / name / price) and pagination.
     */
    public function index(Request $request, WishlistService $wishlist): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'string', 'in:latest,name,price_asc,price_desc'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:100'],
            'fabric' => ['nullable', 'string', 'max:255'],
            'availability' => ['nullable', 'in:in_stock,out_of_stock,all'],
        ]);

        $term = trim($filters['q'] ?? '');
        $sort = $filters['sort'] ?? 'latest';
        $categorySlug = $filters['category'] ?? $request->route('category');
        $availability = $filters['availability'] ?? 'in_stock';

        $variantFilters = function ($query) use ($filters, $availability) {
            return $query
                ->when(filled($filters['min_price'] ?? null), fn($query) => $query->where('price', '>=', $filters['min_price']))
                ->when(filled($filters['max_price'] ?? null), fn($query) => $query->where('price', '<=', $filters['max_price']))
                ->when(filled($filters['size'] ?? null), fn($query) => $query->where('size', $filters['size']))
                ->when(filled($filters['color'] ?? null), fn($query) => $query->where('color', $filters['color']))
                ->when($availability === 'in_stock', fn($query) => $query->where('stock_quantity', '>', 0))
                ->when($availability === 'out_of_stock', fn($query) => $query->where('stock_quantity', '<=', 0));
        };

        $selectedCategory = filled($categorySlug)
            ? Category::active()->where('slug', $categorySlug)->first()
            : null;

        $products = Product::active()
            ->whereHas('activeVariants', $variantFilters)
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('fabric', 'like', "%{$term}%")
                        ->orWhereHas('category', fn($category) => $category->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('variants', fn($variant) => $variant->where('sku', 'like', "%{$term}%"));
                });
            })
            ->when(filled($filters['fabric'] ?? null), fn($query) => $query->where('fabric', $filters['fabric']))
            ->when(filled($categorySlug) && ! $selectedCategory, fn($query) => $query->whereRaw('1 = 0'))
            ->when($selectedCategory, fn($query) => $query->where('category_id', $selectedCategory->id))
            ->with([
                'category:id,name,slug',
                'images' => fn($query) => $query->orderBy('sort_order'),
                'activeVariants' => fn($query) => $variantFilters($query)->orderBy('price'),
            ])
            ->when($sort === 'name', fn($query) => $query->orderBy('name'))
            ->when($sort === 'price_asc', function ($query) use ($variantFilters) {
                $query->withMin(['activeVariants as price_min' => $variantFilters], 'price')->orderBy('price_min');
            })
            ->when($sort === 'price_desc', function ($query) use ($variantFilters) {
                $query->withMax(['activeVariants as price_max' => $variantFilters], 'price')->orderByDesc('price_max');
            })
            ->when($sort === 'latest', fn($query) => $query->latest())
            ->paginate(config('storefront.catalog.per_page'))
            ->withQueryString();

        $categories = Category::active()
            ->withCount(['products as visible_products_count' => fn($query) => $query->active()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'parent_id']);

        $filterOptions = [
            'sizes' => ProductVariant::query()->active()->whereNotNull('size')->where('size', '<>', '')
                ->distinct()->orderBy('size')->pluck('size'),
            'colors' => ProductVariant::query()->active()->whereNotNull('color')->where('color', '<>', '')
                ->distinct()->orderBy('color')->pluck('color'),
            'fabrics' => Product::query()->active()->whereNotNull('fabric')->where('fabric', '<>', '')
                ->distinct()->orderBy('fabric')->pluck('fabric'),
            'minPrice' => ProductVariant::query()->active()->min('price') ?? 0,
            'maxPrice' => ProductVariant::query()->active()->max('price') ?? 0,
        ];

        return view('storefront.products', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'searchTerm' => $term,
            'sort' => $sort,
            'wishlistIds' => $wishlist->ids(),
            'filterOptions' => $filterOptions,
        ]);
    }
}
