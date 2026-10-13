<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

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
     * Store a newly created product with its variants and images.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $uploads = $request->file('images', []);
        $storedPaths = [];

        // #region agent log
        file_put_contents(base_path('debug-d42924.log'), json_encode(['sessionId' => 'd42924', 'hypothesisId' => 'A', 'location' => 'ProductController.php:store', 'message' => 'store received images', 'data' => ['hasFileImages' => $request->hasFile('images'), 'uploadsIsArray' => is_array($uploads), 'uploadCount' => is_array($uploads) ? count($uploads) : 0, 'uploadNames' => is_array($uploads) ? array_map(fn ($f) => $f instanceof UploadedFile ? $f->getClientOriginalName() : null, $uploads) : [], 'primaryRef' => $request->input('primary_image_id')], 'timestamp' => (int) (microtime(true) * 1000), 'runId' => 'post-fix'])."\n", FILE_APPEND);
        // #endregion

        try {
            $product = DB::transaction(function () use ($request, $data, $uploads, &$storedPaths) {
                $product = Product::create([
                    'category_id' => filled($data['category_id'] ?? null) ? (int) $data['category_id'] : null,
                    'name' => $data['name'],
                    'slug' => $this->resolveSlug($data['name'], $data['slug'] ?? null),
                    'description' => $data['description'] ?? null,
                    'fabric' => $data['fabric'] ?? null,
                    'care_instructions' => $data['care_instructions'] ?? null,
                    'is_active' => $request->boolean('is_active'),
                    'is_featured' => $request->boolean('is_featured'),
                ]);

                $this->syncVariants($product, $data['variants'] ?? []);

                if ($uploads !== []) {
                    $this->storeImages(
                        $product,
                        $uploads,
                        $request->input('primary_image_id'),
                        $storedPaths,
                    );
                }

                // #region agent log
                file_put_contents(base_path('debug-d42924.log'), json_encode(['sessionId' => 'd42924', 'hypothesisId' => 'B', 'location' => 'ProductController.php:store:afterTx', 'message' => 'product created image state', 'data' => ['productId' => $product->id, 'skippedStoreImages' => $uploads === [], 'storedPathCount' => count($storedPaths), 'dbImageCount' => $product->images()->count()], 'timestamp' => (int) (microtime(true) * 1000), 'runId' => 'post-fix'])."\n", FILE_APPEND);
                // #endregion

                return $product;
            });
        } catch (Throwable $exception) {
            // Roll back any files written before the failure so no orphans remain.
            Storage::disk('public')->delete(array_values(array_unique($storedPaths)));

            throw $exception;
        }

        if ($request->input('intent') === 'stay') {
            return redirect()
                ->route('products.edit', $product)
                ->with('success', 'Product created. You can keep editing it here.');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show a single product with its gallery and variants.
     */
    public function show(Product $product): View
    {
        $product->load([
            'category:id,name',
            'images' => fn ($query) => $query->orderBy('sort_order'),
            'primaryImage:id,product_id,image_path',
            'variants' => fn ($query) => $query->orderBy('size')->orderBy('color'),
        ])
            ->loadCount('variants')
            ->loadSum(['variants as total_stock' => fn ($query) => $query->where('is_active', true)], 'stock_quantity')
            ->loadMin(['variants as price_min' => fn ($query) => $query->where('is_active', true)], 'price')
            ->loadMax(['variants as price_max' => fn ($query) => $query->where('is_active', true)], 'price');

        // #region agent log
        file_put_contents(base_path('debug-d42924.log'), json_encode(['sessionId' => 'd42924', 'hypothesisId' => 'C', 'location' => 'ProductController.php:show', 'message' => 'show page gallery state', 'data' => ['productId' => $product->id, 'imageCount' => $product->images->count(), 'paths' => $product->images->pluck('image_path')->values()->all(), 'diskExists' => $product->images->map(fn ($img) => Storage::disk('public')->exists($img->image_path))->values()->all()], 'timestamp' => (int) (microtime(true) * 1000), 'runId' => 'post-fix'])."\n", FILE_APPEND);
        // #endregion

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

        $product->load([
            'images' => fn ($query) => $query->orderBy('sort_order'),
            'variants' => fn ($query) => $query->orderBy('id'),
        ]);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product, its variants and its gallery.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        // A blank slug keeps the existing one so public URLs stay stable.
        $requestedSlug = ($data['slug'] ?? null) ?: $product->slug;

        $deletedPaths = [];
        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $data, $product, $requestedSlug, &$deletedPaths, &$storedPaths) {
                $product->update([
                    'category_id' => filled($data['category_id'] ?? null) ? (int) $data['category_id'] : null,
                    'name' => $data['name'],
                    'slug' => $this->resolveSlug($data['name'], $requestedSlug, $product),
                    'description' => $data['description'] ?? null,
                    'fabric' => $data['fabric'] ?? null,
                    'care_instructions' => $data['care_instructions'] ?? null,
                    'is_active' => $request->boolean('is_active'),
                    'is_featured' => $request->boolean('is_featured'),
                ]);

                if (array_key_exists('variants', $data) || $request->boolean('has_variant_editor')) {
                    $this->syncVariants($product, $data['variants'] ?? []);
                }

                $this->applyImageChanges($product, $data, $request->file('images', []), $storedPaths, $deletedPaths);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete(array_values(array_unique($storedPaths)));

            throw $exception;
        }

        // Files are removed only after the transaction has committed and only
        // when no other image row still references the same path.
        $this->deleteImageFiles($deletedPaths);

        if ($request->input('intent') === 'stay') {
            return redirect()
                ->route('products.edit', $product)
                ->with('success', 'Product saved. You can keep editing it here.');
        }

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

    /**
     * Create, update and remove variants so the table mirrors the editor rows.
     *
     * Removal is explicit: any variant absent from the submitted rows is
     * deleted, which the administrator controls through the editor UI.
     */
    private function syncVariants(Product $product, array $rows): void
    {
        $keepIds = [];

        foreach ($rows as $row) {
            $payload = [
                'sku' => $row['sku'],
                'size' => filled($row['size'] ?? null) ? $row['size'] : null,
                'color' => filled($row['color'] ?? null) ? $row['color'] : null,
                'price' => $row['price'],
                'compare_at_price' => filled($row['compare_at_price'] ?? null) ? $row['compare_at_price'] : null,
                'stock_quantity' => (int) $row['stock_quantity'],
                'is_active' => array_key_exists('is_active', $row)
                    ? filter_var($row['is_active'], FILTER_VALIDATE_BOOLEAN)
                    : true,
            ];

            if (! empty($row['id'])) {
                // Ownership is enforced: only this product's variants can be edited.
                $variant = $product->variants()->whereKey($row['id'])->first();

                if ($variant) {
                    $variant->update($payload);
                    $keepIds[] = $variant->id;
                }

                continue;
            }

            $keepIds[] = $product->variants()->create($payload)->id;
        }

        $product->variants()
            ->when($keepIds, fn ($query) => $query->whereNotIn('id', $keepIds))
            ->when($keepIds === [], fn ($query) => $query)
            ->delete();
    }

    /**
     * Persist uploaded images to the public disk and create their rows.
     *
     * @param  array<int, UploadedFile>  $files
     * @param  array<int, string>  $storedPaths  Collected paths for rollback.
     */
    private function storeImages(Product $product, array $files, ?string $primaryReference, array &$storedPaths): void
    {
        $nextOrder = (int) $product->images()->max('sort_order') + 1;
        $createdRows = [];

        foreach (array_values($files) as $index => $file) {
            $path = $file->store('products/'.$product->id, 'public');

            if ($path === false) {
                throw new \RuntimeException('The image could not be stored. Please try again.');
            }

            $storedPaths[] = $path;

            $createdRows[] = [
                'product_id' => $product->id,
                'image_path' => $path,
                'alt_text' => $product->name,
                'sort_order' => $nextOrder + $index,
                'is_primary' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($createdRows !== []) {
            ProductImage::query()->insert($createdRows);
        }

        $this->resolvePrimaryImage($product, $primaryReference);
    }

    /**
     * Apply gallery maintenance for an update: deletion, ordering, new
     * uploads and primary selection — all inside the caller's transaction.
     *
     * @param  array<int, UploadedFile>  $files
     * @param  array<int, string>  $storedPaths
     * @param  array<int, string>  $deletedPaths  Paths queued for removal.
     */
    private function applyImageChanges(Product $product, array $data, array $files, array &$storedPaths, array &$deletedPaths): void
    {
        $deleteIds = array_map('intval', $data['delete_images'] ?? []);

        if ($deleteIds !== []) {
            foreach ($product->images()->whereKey($deleteIds)->get() as $image) {
                $deletedPaths[] = $image->image_path;
                $image->delete();
            }
        }

        foreach (array_values($data['image_order'] ?? []) as $position => $imageId) {
            $product->images()->whereKey((int) $imageId)->update(['sort_order' => $position]);
        }

        if ($files !== []) {
            $this->storeImages($product, $files, $data['primary_image_id'] ?? null, $storedPaths);
        } else {
            $this->resolvePrimaryImage($product, $data['primary_image_id'] ?? null);
        }
    }

    /**
     * Point is_primary at the requested image ("existing id" or "new-N"),
     * falling back to the first image in gallery order.
     */
    private function resolvePrimaryImage(Product $product, ?string $reference): void
    {
        $images = $product->images()->orderBy('sort_order')->get();

        if ($images->isEmpty()) {
            return;
        }

        $primaryId = null;

        if (str_starts_with((string) $reference, 'new-')) {
            $newIndex = (int) substr($reference, 4);
            // Newly inserted rows sort last, so count back from the end.
            $primaryId = $images->reverse()->values()->get($newIndex)?->id;
        } elseif ($reference !== null && $reference !== '') {
            $primaryId = $images->firstWhere('id', (int) $reference)?->id;
        }

        $primaryId ??= $images->first()->id;

        foreach ($images as $image) {
            if ($image->is_primary !== ($image->id === $primaryId)) {
                $image->update(['is_primary' => $image->id === $primaryId]);
            }
        }
    }

    /**
     * Delete files from disk once their rows are gone, skipping any path
     * still referenced by another image record.
     *
     * @param  array<int, string>  $paths
     */
    private function deleteImageFiles(array $paths): void
    {
        foreach (array_unique($paths) as $path) {
            if (blank($path)) {
                continue;
            }

            if (ProductImage::query()->where('image_path', $path)->exists()) {
                continue;
            }

            Storage::disk('public')->delete($path);
        }
    }
}
