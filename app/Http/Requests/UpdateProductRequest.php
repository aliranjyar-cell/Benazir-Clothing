<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($this->route('product')),
            ],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:10000'],
            'fabric' => ['nullable', 'string', 'max:255'],
            'care_instructions' => ['nullable', 'string', 'max:10000'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],

            // Media — newly appended images plus gallery maintenance actions.
            'images' => ['sometimes', 'array', 'max:12'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
            'primary_image_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('product_images', 'id')->where('product_id', $productId),
            ],
            'delete_images' => ['sometimes', 'array'],
            'delete_images.*' => [
                'integer',
                Rule::exists('product_images', 'id')->where('product_id', $productId),
            ],
            'image_order' => ['sometimes', 'array', 'max:12'],
            'image_order.*' => [
                'integer',
                Rule::exists('product_images', 'id')->where('product_id', $productId),
            ],

            // Variants — existing rows keep their id so ownership and the SKU
            // uniqueness check can be resolved per row. The sentinel flag marks
            // an empty editor so all variants can be intentionally removed.
            'has_variant_editor' => ['sometimes', 'boolean'],
            'variants' => ['sometimes', 'array', 'max:60'],
            'variants.*.id' => [
                'nullable',
                'integer',
                Rule::exists('product_variants', 'id')->where('product_id', $productId),
            ],
            'variants.*.sku' => [
                'required',
                'string',
                'max:255',
                'distinct',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $rowId = $this->input("variants.{$index}.id");

                    $taken = ProductVariant::query()
                        ->where('sku', $value)
                        ->when($rowId, fn ($query) => $query->where('id', '!=', $rowId))
                        ->exists();

                    if ($taken) {
                        $fail('This SKU is already used by another variant.');
                    }
                },
            ],
            'variants.*.size' => ['nullable', 'string', 'max:50'],
            'variants.*.color' => ['nullable', 'string', 'max:50'],
            'variants.*.price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'variants.*.compare_at_price' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'variants.*.stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'variants.*.is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category does not exist.',
            'slug.unique' => 'This slug is already used by another product.',
            'images.*.image' => 'Every upload must be a valid image file.',
            'images.*.mimes' => 'Images must be a JPG, PNG, WEBP or AVIF file.',
            'images.*.max' => 'Each image may not be larger than 4 MB.',
            'primary_image_id.exists' => 'The selected primary image does not belong to this product.',
            'delete_images.*.exists' => 'One of the images you tried to remove no longer exists.',
            'variants.*.sku.required' => 'Every variant needs a SKU.',
            'variants.*.sku.distinct' => 'Each variant SKU must be different from the others.',
            'variants.*.sku.unique' => 'This SKU is already used by another variant.',
            'variants.*.price.required' => 'Every variant needs a price.',
            'variants.*.price.min' => 'Price cannot be negative.',
            'variants.*.compare_at_price.min' => 'Compare-at price cannot be negative.',
            'variants.*.stock_quantity.required' => 'Every variant needs a stock quantity.',
            'variants.*.stock_quantity.min' => 'Stock cannot be negative.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'images.*' => 'image',
            'variants.*.sku' => 'SKU',
            'variants.*.price' => 'price',
            'variants.*.compare_at_price' => 'compare-at price',
            'variants.*.stock_quantity' => 'stock',
        ];
    }

    /**
     * Reject duplicate size/color combinations inside the submitted rows.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function ($validator) {
                $seen = [];

                foreach ($this->input('variants', []) as $index => $row) {
<<<<<<< HEAD
                    $key = mb_strtolower(trim(($row['size'] ?? '').'|'.($row['color'] ?? '')));
=======
                    $key = strtolower(trim(($row['size'] ?? '').'|'.($row['color'] ?? '')));
>>>>>>> 377bc8b (fixed mb_strtolower)

                    if (array_key_exists($key, $seen)) {
                        $validator->errors()->add(
                            "variants.{$index}.color",
                            'This size and colour combination is already listed below.'
                        );
                    } else {
                        $seen[$key] = $index;
                    }
                }
            },
        ];
    }
}
