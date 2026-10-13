<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * List products with search, filters, sorting and pagination.
     */
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $sortable = ['name', 'created_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'created_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc'], true)
            ? $request->query('direction')
            : ($sort === 'created_at' ? 'desc' : 'asc');

        $status = $request->query('status');

        $products = Product::query()
            ->with(['category:id,name', 'primaryImage:id,product_id,image_path'])
            ->withCount('variants')
            ->withSum(['variants as total_stock' => fn ($query) => $query->where('is_active', true)], 'stock_quantity')
            ->withMin(['variants as price_min' => fn ($query) => $query->where('is_active', true)], 'price')
            ->withMax(['variants as price_max' => fn ($query) => $query->where('is_active', true)], 'price')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.trim($request->string('search')->toString()).'%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', (int) $request->input('category'));
            })
            ->when(in_array($status, ['active', 'inactive'], true), function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy($sort, $direction)
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'categories', 'sort', 'direction'));
    }

    /**
     * Show the create product form.
     */
    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data) {
            Product::create([
                'category_id' => $data['category_id'] ?? null,
                'name' => $data['name'],
                'slug' => $this->resolveSlug($data['name'], $data['slug'] ?? null),
                'description' => $data['description'] ?? null,
                'fabric' => $data['fabric'] ?? null,
                'care_instructions' => $data['care_instructions'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show a single product.
     */
    public function show(Product $product): View
    {
        $product->load('category:id,name', 'primaryImage:id,product_id,image_path')
            ->loadCount('variants')
            ->loadSum(['variants as total_stock' => fn ($query) => $query->where('is_active', true)], 'stock_quantity')
            ->loadMin(['variants as price_min' => fn ($query) => $query->where('is_active', true)], 'price')
            ->loadMax(['variants as price_max' => fn ($query) => $query->where('is_active', true)], 'price');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the edit product form.
     */
    public function edit(Product $product): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        // A blank slug keeps the existing one so public URLs stay stable.
        $requestedSlug = ($data['slug'] ?? null) ?: $product->slug;

        DB::transaction(function () use ($request, $data, $product, $requestedSlug) {
            $product->update([
                'category_id' => $data['category_id'] ?? null,
                'name' => $data['name'],
                'slug' => $this->resolveSlug($data['name'], $requestedSlug, $product),
                'description' => $data['description'] ?? null,
                'fabric' => $data['fabric'] ?? null,
                'care_instructions' => $data['care_instructions'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ]);
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Activate or deactivate a product without touching its data.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with(
            'success',
            $product->is_active
                ? "\"{$product->name}\" is now active."
                : "\"{$product->name}\" has been deactivated."
        );
    }

    /**
     * Build a unique slug from the requested value or the product name.
     */
    private function resolveSlug(string $name, ?string $requestedSlug, ?Product $ignore = null): string
    {
        $base = Str::slug($requestedSlug ?: $name);

        if ($base === '') {
            $base = 'product';
        }

        $slug = $base;
        $suffix = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
                ->exists()
        ) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
