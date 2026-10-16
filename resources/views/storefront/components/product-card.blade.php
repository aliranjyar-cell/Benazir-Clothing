@props(['product', 'wishlisted' => false])

@php
    $variant = $product->activeVariants->first();
    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $onSale = $variant && $variant->isOnSale();
@endphp

<article
    class="group relative flex min-w-0 flex-col border border-outline-variant/40 bg-surface transition-all duration-300 hover:border-primary/60">
    <div class="relative aspect-[3/4] overflow-hidden bg-surface-container">
        @if ($image)
            <a href="{{ route('store.products.show', $product) }}">
                <img class="h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                    src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?: $product->name }}"
                    loading="lazy">
            </a>
        @else
            <a href="{{ route('store.products.show', $product) }}"
                class="grid h-full place-items-center bg-surface-container-low text-outline">
                <span class="material-symbols-outlined text-4xl">image_not_supported</span>
            </a>
        @endif
        @if ($onSale)
            <span
                class="absolute left-2.5 top-2.5 border border-primary-fixed/30 bg-secondary px-2 py-0.5 text-[10px] uppercase tracking-[0.15em] text-on-secondary shadow-sm">Sale</span>
        @elseif ($product->is_featured)
            <span
                class="absolute left-2.5 top-2.5 border border-primary-fixed/30 bg-secondary px-2 py-0.5 text-[10px] uppercase tracking-[0.15em] text-on-secondary shadow-sm">Featured</span>
        @endif
        <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}" class="absolute right-2.5 top-2.5">
            @csrf
            <button aria-label="{{ $wishlisted ? 'Remove from' : 'Add to' }} wishlist"
                aria-pressed="{{ $wishlisted ? 'true' : 'false' }}"
                class="flex h-8 w-8 items-center justify-center border border-outline-variant/40 bg-surface/90 text-secondary backdrop-blur-sm transition-colors hover:bg-surface"
                type="submit">
                <span class="material-symbols-outlined text-[18px]"
                    style="font-variation-settings: 'FILL' {{ $wishlisted ? 1 : 0 }};">favorite</span>
            </button>
        </form>
    </div>

    <div class="flex flex-1 flex-col justify-between space-y-2 p-3.5">
        <div class="space-y-1">
            @if ($product->category)
                <p class="text-[11px] uppercase tracking-wider text-outline">{{ $product->category->name }}</p>
            @endif
            <a href="{{ route('store.products.show', $product) }}">
                <h3
                    class="line-clamp-2 text-body-md font-headline-sm text-on-surface transition-colors group-hover:text-primary">
                    {{ $product->name }}</h3>
            </a>
        </div>
        <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1 pt-1 text-body-sm">
            @if ($variant)
                <span
                    class="font-body-md font-semibold text-on-surface">{{ config('storefront.currency_symbol', '₨') }}
                    {{ number_format((float) $variant->price, 0) }}</span>
                @if ($onSale)
                    <span class="text-[11px] text-outline line-through"
                        aria-label="Compare-at price">{{ config('storefront.currency_symbol', '₨') }}
                        {{ number_format((float) $variant->compare_at_price, 0) }}</span>
                @endif
            @else
                <span class="text-outline">Price unavailable</span>
            @endif
        </div>
    </div>
</article>
