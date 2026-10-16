<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // Slug is required, but prepareForValidation generates it
            // from the name when the field is left blank. Duplicates are
            // unique-suffixed in passedValidation (never rejected), so no
            // "unique" rule is attached here.
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
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
     *
     * The slug is generated (and unique-suffixed) HERE, before
     * validation, so the final value is included in validated() data.
     * Merging in passedValidation() would be too late: the validator has
     * already snapshotted the data.
     */
    public function prepareForValidation(): void
    {
        $data = $this->all();

        // Generate a slug from the name when the user leaves it blank.
        if (empty($data['slug']) || ! is_string($data['slug']) || trim($data['slug']) === '') {
            $slug = Str::slug($data['name'] ?? '');
        } else {
            $slug = Str::slug($data['slug']);
        }

        // Guarantee uniqueness, appending a suffix when the slug is
        // already taken by another category. An empty slug is kept as-is
        // so the required rule can report it.
        if ($slug !== '') {
            $base = $slug;
            $suffix = 2;

            while (Category::query()->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $suffix++;
            }
        }

        $data['slug'] = $slug;

        // Normalise booleans / integers.
        if (isset($data['is_active'])) {
            $data['is_active'] = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($data['sort_order'])) {
            $data['sort_order'] = (int) $data['sort_order'];
        }

        $this->merge($data);
    }
}
