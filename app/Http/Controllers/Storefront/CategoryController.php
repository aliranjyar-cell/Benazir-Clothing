<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Public category page. Bound by slug so the admin resource route
     * "categories/{category}" (bound by id) keeps working untouched.
     */
    public function show(Request $request, Category $category): View
    {
        abort_unless($category->is_active, 404);

        $filters = $request->validate([
            'sort' => ['nullable', 'string', 'in:latest,name,price_asc,price_desc'],
        ]);

        $sort = $filters['sort'] ?? 'latest';

        // Include products from child categories as well.
        $categoryIds = $category->children()->active()->pluck('id')
            ->push($category->id);

        $products = $category->products()
            ->active()
            ->whereIn('category_id', $categoryIds)
            ->whereHas('activeVariants', fn ($query) => $query->where('stock_quantity', '>', 0))
            ->with([
                'category:id,name,slug',
                'images' => fn ($query) => $query->orderBy('sort_order'),
                'activeVariants' => fn ($query) => $query->orderBy('price'),
            ])
            ->when($sort === 'name', fn ($query) => $query->orderBy('name'))
            ->when($sort === 'price_asc', function ($query) {
                $query->withMin(['activeVariants as price_min'], 'price')->orderBy('price_min');
            })
            ->when($sort === 'price_desc', function ($query) {
                $query->withMax(['activeVariants as price_max'], 'price')->orderByDesc('price_max');
            })
            ->when($sort === 'latest', fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $children = $category->children()->active()->orderBy('sort_order')->get(['id', 'name', 'slug']);

        return view('storefront.category', [
            'category' => $category,
            'products' => $products,
            'children' => $children,
            'sort' => $sort,
        ]);
    }
}
