@props(['items' => []])

@php
    /**
     * Usage:
     *   <x-admin.breadcrumb :items="[
     *       ['label' => 'Admin', 'url' => route('dashboard')],
     *       ['label' => 'Dashboard'],
     *   ]" />
     * The last item is always rendered as the current page.
     */
    $items = collect($items)->values();
@endphp

@if ($items->isNotEmpty())
    <nav {{ $attributes->class(['min-w-0']) }} aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5 overflow-hidden text-xs">
            @foreach ($items as $index => $item)
                <li class="flex min-w-0 items-center gap-1.5">
                    @if ($index < $items->count() - 1 && ! empty($item['url']))
                        <a
                            href="{{ $item['url'] }}"
                            class="shrink-0 font-medium text-muted transition-colors hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac rounded"
                        >
                            {{ $item['label'] }}
                        </a>
                        <x-admin.icon name="chevron-right" class="h-3.5 w-3.5 shrink-0 text-muted/50" />
                    @else
                        <span aria-current="page" class="truncate font-medium text-ink">
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif