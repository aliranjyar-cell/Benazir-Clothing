@php
    $variant = $product->activeVariants->first();
    $onSale = $variant && $variant->isOnSale();
    $availableSizes = $product->activeVariants->pluck('size')->filter()->unique()->sort()->values();
    $showDescription = $showDescription ?? true;
    $showSizes = $showSizes ?? true;
    $cardRadius = $cardRadius ?? 'rounded-lg';
    $compact = $compact ?? false;
    $imageAspect = $compact ? 'aspect-[5/6]' : 'aspect-[4/5]';
    $cardPadding = $compact ? 'p-space-sm' : 'p-space-md';
@endphp

<article
    class="group flex h-full min-w-0 flex-col overflow-hidden {{ $cardRadius }} border border-outline-variant/70 bg-surface-container-lowest transition-all duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-[0_14px_34px_rgba(86,25,34,0.12)]">
    <a class="relative block {{ $imageAspect }} overflow-hidden bg-surface-container"
        href="{{ route('store.products.show', $product) }}" aria-label="View {{ $product->name }}">
        @if ($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}"
                class="h-full w-full object-cover object-top transition-transform duration-500 group-hover:scale-105"
                loading="lazy">
        @else
            <div class="flex h-full w-full items-center justify-center text-on-surface-variant/60">
                <i class="fa-solid fa-image text-4xl text-secondary" aria-hidden="true"></i>
            </div>
        @endif
        <span
            class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-primary/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
    </a>

    <div class="flex flex-1 flex-col {{ $cardPadding }}">
        <div class="mb-space-xs min-h-5">
            @if ($product->category)
                <p class="font-label-uppercase text-[0.65rem] uppercase tracking-widest text-secondary">
                    {{ $product->category->name }}</p>
            @endif
        </div>
        <a class="min-h-12" href="{{ route('store.products.show', $product) }}">
            <h3
                class="line-clamp-2 font-title text-title-md text-on-surface transition-colors group-hover:text-primary">
                {{ $product->name }}</h3>
        </a>

        @if ($showDescription && filled($product->description))
            <p class="mt-space-xs line-clamp-2 text-xs leading-relaxed text-on-surface-variant">
                {{ $product->description }}</p>
        @endif
        @if (filled($product->fabric))
            <p class="text-xs text-on-surface-variant"><span class="font-semibold text-on-surface">Fabric:</span>
                {{ $product->fabric }}</p>
        @endif
        @if ($showSizes && $availableSizes->isNotEmpty())
            <p class="text-xs text-on-surface-variant"><span class="font-semibold text-on-surface">Sizes:</span>
                {{ $availableSizes->implode(' · ') }}</p>
        @endif

        <div
            class="mt-auto flex items-center justify-between gap-space-sm border-t border-outline-variant/60 pt-space-md">
            <p class="font-label-uppercase text-label-price text-primary" aria-label="Price">
                @if ($variant)
                    <span class="font-body-md font-semibold text-on-surface">
                        {{ config('storefront.currency_symbol', '₨') }}
                        {{ number_format((float) $variant->price, 0) }}
                    </span>
                    @if ($onSale)
                        <span class="ml-1 text-[11px] text-outline line-through" aria-label="Compare-at price">
                            {{ config('storefront.currency_symbol', '₨') }}
                            {{ number_format((float) $variant->compare_at_price, 0) }}
                        </span>
                    @endif
                @else
                    <span class="text-on-surface-variant/70">Unavailable</span>
                @endif
            </p>

            <a href="{{ route('store.products.show', $product) }}"
                class="inline-flex shrink-0 items-center gap-1 rounded-md bg-primary px-3 py-2 font-label-uppercase text-[0.65rem] uppercase tracking-wider text-on-primary transition-colors hover:bg-primary-container"
                aria-label="View {{ $product->name }}">
                <span>View product</span>
                <i class="fa-solid fa-arrow-right text-base" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>
