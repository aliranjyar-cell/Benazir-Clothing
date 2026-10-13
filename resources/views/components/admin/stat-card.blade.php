@props([
    'label',
    'value',
    'icon' => null,
    'hint' => null,
    'tone' => 'lilac',
    'placeholder' => false,
])

@php
    $toneClasses = match ($tone) {
        'mint' => 'bg-mint-soft text-mint-deep ring-mint/40',
        'blush' => 'bg-blush-soft text-blush-deep ring-blush/40',
        default => 'bg-lilac-soft text-lilac-deep ring-lilac/40',
    };
@endphp

<div {{ $attributes->class(['rounded-2xl border border-black/[0.06] bg-white p-5 shadow-card sm:p-6']) }}>
    <div class="flex items-start justify-between gap-4">
        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-muted">
            {{ $label }}
        </p>

        @if ($icon)
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl ring-1 {{ $toneClasses }}">
                <x-admin.icon name="{{ $icon }}" class="h-5 w-5" />
            </span>
        @endif
    </div>

    <p class="mt-4 text-2xl font-semibold tracking-tight {{ $placeholder ? 'text-muted' : 'text-ink' }}">
        {{ $value }}
    </p>

    @if (filled($hint))
        <p class="mt-1.5 text-xs leading-relaxed text-muted">
            {{ $hint }}
        </p>
    @endif
</div>