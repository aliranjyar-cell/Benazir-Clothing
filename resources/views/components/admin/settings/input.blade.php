@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'maxlength' => null,
    'min' => null,
    'max' => null,
    'step' => null,
])

@php($current = old($name, $value))

<div {{ $attributes->merge(['class' => 'block']) }}>
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-ink">
        {{ $label }}@if ($required)<span class="text-blush-deep"> *</span>@endif
    </label>

    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
        @if ($required) required @endif
        @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
        @if ($maxlength !== null) maxlength="{{ $maxlength }}" @endif
        @if ($min !== null) min="{{ $min }}" @endif
        @if ($max !== null) max="{{ $max }}" @endif
        @if ($step !== null) step="{{ $step }}" @endif
        value="{{ $current }}"
        class="w-full rounded-xl border {{ $errors->has($name) ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">

    @if (filled($hint))
        <p class="mt-1.5 text-xs text-muted">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>