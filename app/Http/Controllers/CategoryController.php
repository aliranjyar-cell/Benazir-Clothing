<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * List categories with search, filters, sorting and pagination.
     */
    public function index(Request $request): View
    {
        $sortable = ['name', 'created_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'created_at';
        $direction = in_array($request->query('direction'), ['asc', 'desc'], true)
            ? $request->query('direction')
            : ($sort === 'created_at' ? 'desc' : 'asc');

        $categories = Category::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('slug', 'like', '%'.$search.'%')
                        ->orWhereHas('parent', function ($query) use ($search) {
                            $query->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($request->boolean('active_only'), function ($query) {
                $query->active();
            })
            ->when($request->boolean('inactive_only'), function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy($sort, $direction)
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('categories.index', compact('categories', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $parents = Category::query()
            ->where('id', '<>', request('parent_id'))
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);

        return view('categories.create', compact('parents'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $category = Category::create([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'image_path' => $data['image'] ? $data['image']->store('categories', 'public') : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        if ($data['image'] ?? null) {
            $category->update(['image_path' => $data['image']->store('categories', 'public')]);
        }

        return redirect()
            ->route('categories.edit', $category)
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): View
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $parents = Category::query()
            ->where('id', '<>', $category->id)
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);

        return view('categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        $category->update([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? $category->is_active,
        ]);

        if (($upload = $data['image'] ?? null) !== null) {
            $category->update(['image_path' => $upload->store('categories', 'public')]);
        }

        return redirect()
            ->route('categories.edit', $category)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete the specified category.
     *
     * Categories referenced by products cannot be permanently deleted. They are
     * instead unlinked from their products and soft-deactivated.
     */
    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $products = Product::query()->where('category_id', $category->id)->count();

        if ($products > 0) {
            $category->products()->update(['category_id' => null]);

            $category->is_active = false;
            $category->save();

            return redirect()
                ->route('categories.index')
                ->with('success', "Category “{$category->name}” has been deactivated and unlinked from {$products} product(s) because it is in use.");
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Toggle the active state of a category.
     */
    public function toggleStatus(Request $request, Category $category): RedirectResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        $label = $category->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('categories.index')
            ->with('success', "Category “{$category->name}” has been {$label}.");
    }
}
