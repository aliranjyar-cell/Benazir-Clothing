<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInventoryAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Keep this route inside your existing authenticated admin middleware.
        // Add your project's specific permission check here if applicable.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['received', 'correction', 'damaged', 'returned'])],
            'quantity_change' => ['required', 'integer', 'not_in:0', 'between:-100000,100000'],
            'reference' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('quantity_change', 'min:1', fn ($input) => in_array($input->type, ['received', 'returned'], true));
        $validator->sometimes('quantity_change', 'max:-1', fn ($input) => in_array($input->type, ['damaged'], true));
    }
}
