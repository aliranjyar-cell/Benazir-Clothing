@props(['tone' => 'neutral'])

@php
    $toneClasses = match ($tone) {
        'mint' => 'bg-mint-soft text-mint-deep ring-mint/40',
        'blush' => 'bg-blush-soft text-blush-deep ring-blush/40',
        'lilac' => 'bg-lilac-soft text-aubergine ring-lilac/40',
        default => 'bg-ivory text-muted ring-black/10',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider ring-1', $toneClasses]) }}>
    {{ $slot }}
</span>