<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $selectedCategory = Category::query()
            ->active()
            ->where('slug', $filters['category'] ?? '')
            ->first();

        $hasFilters = filled($filters['q'] ?? null) || filled($filters['category'] ?? null);

        $products = Product::query()
            ->active()
            ->whereHas('activeVariants')
            ->when(! $hasFilters, fn($query) => $query->featured())
            ->when(filled($filters['category'] ?? null) && ! $selectedCategory, fn($query) => $query->whereRaw('1 = 0'))
            ->when($selectedCategory, fn($query) => $query->where('category_id', $selectedCategory->id))
            ->when(filled($filters['q'] ?? null), function ($query) use ($filters) {
                $term = trim($filters['q']);

                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhereHas('category', fn($category) => $category->where('name', 'like', "%{$term}%"));
                });
            })
            ->with([
                'category:id,name,slug',
                'images' => fn($query) => $query->orderBy('sort_order'),
                'activeVariants' => fn($query) => $query->orderBy('price'),
            ])
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::query()
            ->active()
            ->whereHas('products', fn($query) => $query->active()->whereHas('activeVariants'))
            ->with('parent:id,name')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $heroProduct = Product::query()
            ->active()
            ->featured()
            ->whereHas('activeVariants')
            ->whereHas('images')
            ->with(['images' => fn($query) => $query->orderBy('sort_order')])
            ->latest()
            ->first();

        $storyImage = $categories->first(fn($category) => filled($category->image_path))?->image_path
            ?? $products->skip(1)->first()?->images->first()?->image_path;

        $unstitchedCategory = $categories->first(fn($category) => str_contains(
            strtolower($category->name . ' ' . $category->slug),
            'unstitched'
        ));

        $readyToWearCategory = $categories->first(fn($category) => str_contains(
            strtolower($category->name . ' ' . $category->slug),
            'ready'
        ) || str_contains(strtolower($category->name . ' ' . $category->slug), 'stitched'));

        return view('storefront.home', [
            'announcement' => config('storefront.announcement'),
            'content' => config('storefront.home'),
            'categories' => $categories,
            'products' => $products,
            'heroProduct' => $heroProduct,
            'storyImage' => $storyImage,
            'selectedCategory' => $selectedCategory,
            'searchTerm' => $filters['q'] ?? '',
            'unstitchedCategory' => $unstitchedCategory,
            'readyToWearCategory' => $readyToWearCategory,
        ]);
    }
}
