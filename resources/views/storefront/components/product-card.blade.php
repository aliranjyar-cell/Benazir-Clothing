@props(['product', 'wishlisted' => false])

@php
    $variant = $product->activeVariants->first();
    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();

    $onSale = $variant && $variant->isOnSale();
    $productUrl = route('store.products.show', $product);
    $currency = config('storefront.currency_symbol', '₨');
    $availableSizes = $product->activeVariants->pluck('size')->filter()->unique()->sort()->values();
@endphp

<article
    class="group relative flex h-full min-w-0 flex-col justify-between border border-outline-variant/30 bg-surface-container-low transition-all duration-300 hover:border-tertiary/50 hover:shadow-xl">
    {{-- PRODUCT IMAGE --}}
    <div class="relative aspect-[3/4] w-full overflow-hidden bg-surface-container-high">

        @if ($image)
            <a href="{{ $productUrl }}"
                class="block h-full w-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-tertiary"
                aria-label="View {{ $product->name }}">

                <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?: $product->name }}"
                    loading="lazy" decoding="async"
                    class="h-full w-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100">
            </a>
        @else
            <a href="{{ $productUrl }}"
                class="flex h-full w-full flex-col items-center justify-center gap-2 bg-surface-container-high text-outline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-tertiary">

                <i class="fa-solid fa-image text-4xl" aria-hidden="true"></i>

                <span class="text-xs tracking-wide">View product</span>
            </a>
        @endif

        {{-- STATUS BADGE --}}
        @if ($onSale)
            <span
                class="pointer-events-none absolute left-3 top-3 z-10 border border-tertiary/60 bg-surface-container-lowest/90 px-2.5 py-1 text-[10px] font-medium uppercase tracking-widest text-tertiary">
                Sale
            </span>
        @elseif ($product->is_featured)
            <span
                class="pointer-events-none absolute left-3 top-3 z-10 border border-tertiary/60 bg-surface-container-lowest/90 px-2.5 py-1 text-[10px] font-medium uppercase tracking-widest text-tertiary">
                Featured
            </span>
        @endif

        {{-- FLOATING WISHLIST --}}
        <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}"
            class="absolute right-3 top-3 z-20">

            @csrf

            <button type="submit"
                aria-label="{{ $wishlisted ? 'Remove from' : 'Add to' }} wishlist: {{ $product->name }}"
                aria-pressed="{{ $wishlisted ? 'true' : 'false' }}"
                class="flex h-9 w-9 items-center justify-center border border-outline-variant/50 bg-surface-container-lowest/90 text-outline backdrop-blur-sm transition-colors duration-200 hover:border-tertiary hover:text-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-tertiary focus-visible:ring-offset-2 focus-visible:ring-offset-surface motion-reduce:transition-none">

                <span class="material-symbols-outlined text-[18px]" aria-hidden="true"
                    style="font-variation-settings: 'FILL' {{ $wishlisted ? 1 : 0 }};">
                    favorite
                </span>
            </button>
        </form>

        {{-- QUICK VIEW HOVER ACTION --}}
        <div
            class="absolute inset-x-0 bottom-0 z-10 bg-gradient-to-t from-background/90 via-background/60 to-transparent p-3 opacity-0 translate-y-2 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:translate-y-0 group-focus-within:opacity-100 motion-reduce:transform-none motion-reduce:transition-none">

            <a href="{{ $productUrl }}"
                class="flex w-full items-center justify-center border border-tertiary/50 bg-primary-container px-3 py-2.5 text-center text-[10px] font-medium uppercase tracking-[0.16em] text-tertiary transition-colors hover:bg-tertiary hover:text-on-tertiary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-tertiary">

                Quick View · Tailor
            </a>
        </div>
    </div>

    {{-- PRODUCT DETAILS --}}
    <div class="flex flex-1 flex-col justify-between space-y-2 p-4">

        <div class="space-y-1">
            {{-- CATEGORY / PRODUCT COLLECTION --}}
            <span class="block text-[10px] font-medium uppercase tracking-[0.14em] text-secondary">

                {{ $product->category?->name ?? 'BENAZIR Collection' }}
            </span>

            {{-- PRODUCT NAME --}}
            <h3
                class="line-clamp-2 min-h-[2.75rem] break-words font-headline-sm text-headline-sm leading-snug text-on-surface transition-colors duration-200 group-hover:text-tertiary motion-reduce:transition-none">

                <a href="{{ $productUrl }}"
                    class="rounded-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-tertiary">

                    {{ $product->name }}
                </a>
            </h3>

            @if (filled($product->description))
                <p class="line-clamp-2 text-xs leading-relaxed text-on-surface-variant">
                    {{ $product->description }}</p>
            @endif
            @if (filled($product->fabric))
                <p class="text-xs text-on-surface-variant"><span class="font-medium text-on-surface">Fabric:</span>
                    {{ $product->fabric }}</p>
            @endif
            @if ($availableSizes->isNotEmpty())
                <p class="text-xs text-on-surface-variant"><span class="font-medium text-on-surface">Sizes:</span>
                    {{ $availableSizes->implode(' · ') }}</p>
            @endif
        </div>

        {{-- PRICE --}}
        <div
            class="flex flex-wrap items-center justify-between gap-x-2 gap-y-2 border-t border-outline-variant/20 pt-3">
            <div class="flex min-w-0 flex-wrap items-baseline gap-x-2 gap-y-1">
                @if ($variant)
                    <span class="font-body-md text-sm font-semibold tabular-nums text-tertiary">
                        {{ $currency }} {{ number_format((float) $variant->price, 0) }}
                    </span>

                    @if ($onSale)
                        <span class="text-xs tabular-nums text-outline line-through" aria-label="Original price">
                            {{ $currency }} {{ number_format((float) $variant->compare_at_price, 0) }}
                        </span>
                    @endif
                @else
                    <span class="text-xs text-outline">Price unavailable</span>
                @endif
            </div>

            <i class="fa-solid fa-gem shrink-0 text-[17px] text-tertiary/70" aria-hidden="true"></i>
        </div>
    </div>
</article>
