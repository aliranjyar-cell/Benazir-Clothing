@php
    $inputClass = 'block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition placeholder:text-muted/60 focus:border-lilac focus:ring-lilac';
    $labelClass = 'mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-muted';
    $errorClass = 'mt-1.5 text-xs font-medium text-blush-deep';
@endphp

<div class="space-y-5">
    <div>
        <label for="name" class="{{ $labelClass }}">
            Product name <span class="text-blush-deep" aria-hidden="true">*</span>
        </label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $product->name ?? '') }}"
            placeholder="e.g. Phulkari Anarkali Suit — Rose"
            class="{{ $inputClass }} {{ $errors->has('name') ? 'border-blush ring-blush/40' : '' }}"
            required
            autofocus
        />
        @error('name')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="{{ $labelClass }}">Slug</label>
        <input
            id="slug"
            name="slug"
            type="text"
            value="{{ old('slug', $product->slug ?? '') }}"
            placeholder="e.g. phulkari-anarkali-suit-rose"
            class="{{ $inputClass }} {{ $errors->has('slug') ? 'border-blush ring-blush/40' : '' }}"
        />
        <p class="mt-1.5 text-xs text-muted">
            Optional. Leave blank to generate it from the product name.
            {{ ($product->exists ?? false) ? 'Keeping it blank preserves the current slug.' : '' }}
        </p>
        @error('slug')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="{{ $labelClass }}">Description</label>
        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Describe the silhouette, embroidery, occasion and styling…"
            class="{{ $inputClass }} {{ $errors->has('description') ? 'border-blush ring-blush/40' : '' }}"
        >{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="category_id" class="{{ $labelClass }}">Category</label>
            <select
                id="category_id"
                name="category_id"
                class="{{ $inputClass }} {{ $errors->has('category_id') ? 'border-blush ring-blush/40' : '' }}"
            >
                <option value="">No category</option>
                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="fabric" class="{{ $labelClass }}">Fabric</label>
            <input
                id="fabric"
                name="fabric"
                type="text"
                value="{{ old('fabric', $product->fabric ?? '') }}"
                placeholder="e.g. Georgette, Chanderi silk"
                class="{{ $inputClass }} {{ $errors->has('fabric') ? 'border-blush ring-blush/40' : '' }}"
            />
            @error('fabric')
                <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="care_instructions" class="{{ $labelClass }}">Care instructions</label>
        <textarea
            id="care_instructions"
            name="care_instructions"
            rows="2"
            placeholder="e.g. Dry clean only. Store folded with a muslin cloth."
            class="{{ $inputClass }} {{ $errors->has('care_instructions') ? 'border-blush ring-blush/40' : '' }}"
        >{{ old('care_instructions', $product->care_instructions ?? '') }}</textarea>
        @error('care_instructions')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>