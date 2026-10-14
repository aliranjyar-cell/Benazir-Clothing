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
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
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
     */
    public function prepareForValidation(): void
    {
        $data = $this->all();

        // Generate a slug from the name when the user leaves it blank.
        if (empty($data['slug']) || ! is_string($data['slug']) || trim($data['slug']) === '') {
            $data['slug'] = Str::slug($data['name'] ?? '');
        }

        // Normalise booleans / integers.
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
        // Guarantee uniqueness of the generated/provided slug, appending a
        // suffix when the exact slug is already taken by another category.
        $slug = $this->input('slug');
        $slugBase = Str::slug($slug);

        if ($slugBase === '') {
            $slugBase = 'category';
        }

        $slug = $slugBase;
        $suffix = 2;

        while (Category::query()->where('slug', $slug)->doesntExist()) {
            break;
        }

        while (Category::query()->where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $suffix++;
        }

        $this->merge(['slug' => $slug]);
    }
}
