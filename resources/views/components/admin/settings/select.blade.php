@props([
    'label',
    'name',
    'value' => null,
    'options' => [],
    'required' => false,
    'hint' => null,
])

<div {{ $attributes->merge(['class' => 'block']) }}>
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-ink">
        {{ $label }}@if ($required)<span class="text-blush-deep"> *</span>@endif
    </label>

    <select id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
        class="w-full rounded-xl border {{ $errors->has($name) ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if (filled($hint))
        <p class="mt-1.5 text-xs text-muted">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>