<?php
namespace App\Http\Requests;
use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('web')->check() && auth()->guard('web')->user()->can('update category');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('categories', 'slug')->ignore($category),
            ],
            'description' => ['nullable', 'string', 'max:10000'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($category) {
                    // A category cannot be its own parent.
                    if ((int) $value === (int) $category->id) {
                        $fail('A category cannot be its own parent.');
                    }

                    // Prevent circular / depth-first relationships. Walk the
                    // ancestor chain of the selected parent and reject the
                    // update if this category appears anywhere above it.
                    $parent = Category::query()->find((int) $value);

                    if ($parent === null) {
                        return;
                    }

                    $ancestor = $parent->parent;

                    while ($ancestor !== null) {
                        if ($ancestor->id === $category->id) {
                            $fail('Changing the parent would create a circular relationship.');
                        }

                        $ancestor = $ancestor->parent;
                    }
                },
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'parent_id' => 'parent category',
            'sort_order' => 'sort order',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $data = $this->all();

        if (empty($data['slug']) || ! is_string($data['slug']) || trim($data['slug']) === '') {
            $data['slug'] = Str::slug($data['name'] ?? '');
        }

        if (isset($data['is_active'])) {
            $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($data['sort_order'])) {
            $data['sort_order'] = (int) $data['sort_order'];
        }

        $this->merge($data);
    }

    /**
     * Handle a successful validation.
     */
    public function passedValidation(): void
    {
        $slugBase = Str::slug($this->input('slug'));

        if ($slugBase === '') {
            $slugBase = 'category';
        }

        $slug = $slugBase;
        $suffix = 2;

        while (Category::query()->where('slug', $slug)->where('id', '!=', $this->route('category')->id)->exists()) {
            $slug = $slugBase.'-'.$suffix++;
        }

        $this->merge(['slug' => $slug]);
    }
}
