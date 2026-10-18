
@props([
    'product',
    'showDescription' => true,
    'showSizes' => true,
    'cardRadius' => 'rounded-xl',
    'compact' => false,
])

@php
    $variant = $product->activeVariants->first();
    $onSale = $variant && $variant->isOnSale();

    $image = $product->primaryImage;
    $productUrl = route('store.products.show', $product);

    $availableSizes = $product->activeVariants
        ->pluck('size')
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $imageAspect = $compact ? 'aspect-[5/6]' : 'aspect-[4/5]';
    $currency = config('storefront.currency_symbol', '₨');
@endphp

<article
    class="group relative flex h-full min-w-0 flex-col overflow-hidden {{ $cardRadius }}
           border border-outline-variant/40 bg-surface-container-lowest
           shadow-sm transition-all duration-300 ease-out
           hover:-translate-y-1 hover:border-primary/35
           hover:shadow-[0_10px_28px_rgba(86,25,34,0.10)]
           motion-reduce:transform-none motion-reduce:transition-none">

    {{-- PRODUCT IMAGE --}}
    <div class="relative {{ $imageAspect }} shrink-0 overflow-hidden bg-surface-container">

        <a
            href="{{ $productUrl }}"
            aria-label="View {{ $product->name }}"
            class="block h-full w-full focus-visible:outline-none
                   focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary">

            @if ($image)
                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    alt="{{ $image->alt_text ?: $product->name }}"
                    loading="lazy"
                    decoding="async"
                    class="h-full w-full object-cover object-top
                           transition-transform duration-700 ease-out
                           group-hover:scale-[1.04]
                           motion-reduce:transform-none motion-reduce:transition-none">
            @else
                <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-outline">
                    <span class="material-symbols-outlined text-4xl" aria-hidden="true">
                        image_not_supported
                    </span>
                    <span class="text-xs">Image coming soon</span>
                </div>
            @endif

            {{-- Subtle image overlay --}}
            <span
                aria-hidden="true"
                class="pointer-events-none absolute inset-0 bg-gradient-to-t
                       from-primary/10 via-transparent to-transparent
                       opacity-60 transition-opacity duration-300
                       group-hover:opacity-100">
            </span>
        </a>

        {{-- PRODUCT STATUS --}}
        @if ($onSale)
            <span
                class="absolute left-3 top-3 border border-secondary/20
                       bg-secondary px-2.5 py-1 text-[10px] font-semibold
                       uppercase tracking-[0.14em] text-on-secondary shadow-sm">
                Sale
            </span>
        @elseif ($product->is_featured)
            <span
                class="absolute left-3 top-3 border border-primary/15
                       bg-surface-container-lowest/95 px-2.5 py-1
                       text-[10px] font-semibold uppercase tracking-[0.14em]
                       text-primary shadow-sm">
                Featured
            </span>
        @endif

        {{-- QUICK VIEW / PRODUCT DETAILS LINK --}}
        <div
            class="absolute inset-x-0 bottom-0 p-3
                   translate-y-1 opacity-0
                   transition-all duration-300 ease-out
                   group-hover:translate-y-0 group-hover:opacity-100
                   group-focus-within:translate-y-0 group-focus-within:opacity-100
                   motion-reduce:transform-none motion-reduce:transition-none">

            <a
                href="{{ $productUrl }}"
                class="flex min-h-10 w-full items-center justify-center gap-2
                       rounded-md border border-white/30 bg-primary/95
                       px-3 py-2.5 text-[10px] font-semibold uppercase
                       tracking-[0.14em] text-on-primary shadow-md
                       backdrop-blur-sm transition-colors duration-200
                       hover:bg-secondary
                       focus-visible:outline-none focus-visible:ring-2
                       focus-visible:ring-white">

                View Product
                <span class="material-symbols-outlined text-base" aria-hidden="true">
                    arrow_forward
                </span>
            </a>
        </div>
    </div>

    {{-- PRODUCT INFORMATION --}}
    <div class="flex flex-1 flex-col p-3 sm:p-4">

        {{-- CATEGORY --}}
        <div class="mb-1.5 min-h-4">
            @if ($product->category)
                <p class="text-[10px] font-medium uppercase tracking-[0.16em] text-secondary">
                    {{ $product->category->name }}
                </p>
            @endif
        </div>

        {{-- PRODUCT NAME --}}
        <h3
            class="line-clamp-2 min-h-[2.75rem] font-title text-title-md
                   leading-snug text-on-surface transition-colors duration-200
                   group-hover:text-primary">

            <a
                href="{{ $productUrl }}"
                class="rounded-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">

                {{ $product->name }}
            </a>
        </h3>

        {{-- DESCRIPTION --}}
        @if ($showDescription && filled($product->description))
            <p class="mt-2 line-clamp-2 text-xs leading-relaxed text-on-surface-variant">
                {{ $product->description }}
            </p>
        @endif

        {{-- FABRIC --}}
        @if (filled($product->fabric))
            <p class="mt-2 line-clamp-1 text-xs text-on-surface-variant">
                <span class="font-medium text-on-surface">Fabric:</span>
                {{ $product->fabric }}
            </p>
        @endif

        {{-- AVAILABLE SIZES --}}
        @if ($showSizes && $availableSizes->isNotEmpty())
            <p class="mt-1 line-clamp-1 text-xs text-on-surface-variant">
                <span class="font-medium text-on-surface">Sizes:</span>
                {{ $availableSizes->implode(' · ') }}
            </p>
        @endif

        {{-- PRICE AND ACTION --}}
        <div class="mt-auto pt-3">
            <div class="mb-3 border-t border-outline-variant/40"></div>

            <div class="flex flex-wrap items-center justify-between gap-x-2 gap-y-2">

                <div class="flex min-w-0 flex-wrap items-baseline gap-x-2 gap-y-1">

                    @if ($variant)
                        <span class="text-sm font-semibold tabular-nums text-primary sm:text-base">
                            {{ $currency }} {{ number_format((float) $variant->price, 0) }}
                        </span>

                        @if ($onSale)
                            <span
                                class="text-[11px] tabular-nums text-outline line-through"
                                aria-label="Original price">

                                {{ $currency }}
                                {{ number_format((float) $variant->compare_at_price, 0) }}
                            </span>
                        @endif
                    @else
                        <span class="text-xs text-outline">
                            Price unavailable
                        </span>
                    @endif
                </div>

                {{-- Always-visible action on touch devices --}}
                <a
                    href="{{ $productUrl }}"
                    aria-label="View {{ $product->name }}"
                    class="inline-flex min-h-9 shrink-0 items-center justify-center
                           gap-1.5 rounded-md border border-primary/15
                           bg-primary px-3 py-2 text-[10px] font-semibold
                           uppercase tracking-wider text-on-primary
                           transition-colors duration-200
                           hover:border-secondary hover:bg-secondary
                           focus-visible:outline-none focus-visible:ring-2
                           focus-visible:ring-primary focus-visible:ring-offset-2">

                    <span>View</span>
                    <span class="material-symbols-outlined text-base" aria-hidden="true">
                        arrow_forward
                    </span>
                </a>
            </div>
        </div>
    </div>
</article>
