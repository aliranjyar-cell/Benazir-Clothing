@props(['label', 'name', 'checked' => false, 'description' => null])

@php($isChecked = (bool) old($name, $checked))

{{-- Toggle backed by a hidden "0" input so disabled switches always post a value. --}}
<label
    class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 px-4 py-3.5 transition has-[:checked]:border-lilac has-[:checked]:bg-lilac/10">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked($isChecked)
        class="mt-1 accent-aubergine">

    <span class="min-w-0 flex-1">
        <span class="block text-sm font-semibold text-ink">{{ $label }}</span>

        @if (filled($description))
            <span class="mt-0.5 block text-xs leading-5 text-muted">{{ $description }}</span>
        @endif
    </span>
</label>

@error($name)
    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
@enderror