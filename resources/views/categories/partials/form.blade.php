@php
    $inputClass =
        'block w-full rounded-lg border-black/10 bg-white text-sm text-ink shadow-sm transition placeholder:text-muted/60 focus:border-lilac focus:ring-lilac';
    $labelClass = 'mb-2 block text-sm font-medium text-ink';
    $errorClass = 'mt-1.5 text-xs font-medium text-blush-deep';
@endphp

<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="{{ $labelClass }}">
            Category name <span class="text-blush-deep" aria-hidden="true">*</span>
        </label>
        <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}"
            placeholder="e.g. Turbans & Dupattas"
            class="{{ $inputClass }} {{ $errors->has('name') ? 'border-blush ring-blush/40' : '' }}" required
            autofocus />
        @error('name')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="slug" class="{{ $labelClass }}">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $category->slug ?? '') }}"
            placeholder="auto-generated from name"
            class="{{ $inputClass }} {{ $errors->has('slug') ? 'border-blush ring-blush/40' : '' }}" />
        <p class="mt-1.5 text-xs text-muted">
            @if ($category && $category->exists && filled($category->slug))
                Auto-generated from the category name when left blank.
            @else
                Leave blank to generate it automatically.
            @endif
        </p>
        @error('slug')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="{{ $labelClass }}">Description</label>
        <textarea id="description" name="description" rows="3"
            placeholder="Optional. Describe the category — styling, occasions, or assortment."
            class="{{ $inputClass }} {{ $errors->has('description') ? 'border-blush ring-blush/40' : '' }}">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2 grid gap-5 sm:grid-cols-2">
        <div>
            <label for="parent_id" class="{{ $labelClass }}">Parent category</label>
            <select id="parent_id" name="parent_id"
                class="{{ $inputClass }} {{ $errors->has('parent_id') ? 'border-blush ring-blush/40' : '' }}">
                <option value="">None</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected((string) old('parent_id', $category->parent_id ?? '') === (string) $parent->id)>
                        {{ $parent->parent ? '— ' . ($parent->parent->name ?? '') : '' }} {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')
                <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sort_order" class="{{ $labelClass }}">Sort order</label>
            <input id="sort_order" name="sort_order" type="number" min="0" step="1"
                value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                class="{{ $inputClass }} {{ $errors->has('sort_order') ? 'border-blush ring-blush/40' : '' }}" />
            @error('sort_order')
                <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="image" class="{{ $labelClass }}">Category image</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif"
            class="{{ $inputClass }} {{ $errors->has('image') ? 'border-blush ring-blush/40' : '' }}" />
        @error('image')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror

        @if ($category && $category->exists && filled($category->image_path))
            <div class="mt-3 flex items-center gap-3 rounded-lg border border-black/[0.06] bg-ivory/50 px-4 py-3">
                <span
                    class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-white/80 text-lilac-deep ring-1 ring-black/5">
                    <x-admin.icon name="image" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-ink">{{ basename($category->image_path) }}</p>
                    <p class="text-xs text-muted">Upload a new image to replace it.</p>
                </div>
            </div>
        @endif
    </div>

    <div class="sm:col-span-2">
        <label class="flex cursor-pointer items-start gap-3">
            <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $category->is_active ?? true))
                class="mt-0.5 h-4 w-4 rounded border-black/20 text-lilac focus:ring-lilac" />
            <span>
                <span class="block text-sm font-medium text-ink">Active</span>
                <span class="mt-0.5 block text-xs text-muted">Visible on the storefront once the shop goes live.</span>
            </span>
        </label>
        @error('is_active')
            <p class="{{ $errorClass }}" role="alert">{{ $message }}</p>
        @enderror
    </div>
</div>
