@props(['product'])

@php
    $variant = $product->activeVariants->first();
    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $stock = (int) $product->activeVariants->sum('stock_quantity');
    $onSale = $variant && $variant->isOnSale();
@endphp

<article class="group min-w-0">
    <div class="aurelle-image-wrap relative aspect-[4/5] overflow-hidden bg-[#e8e2e4]">
        @if ($image)
            <img class="aurelle-image size-full object-cover" src="{{ url('storage/' . $image->image_path) }}"
                alt="{{ $image->alt_text ?: $product->name }}" loading="lazy">
        @else
            <div class="grid size-full place-items-center bg-[#e8e2e4] px-6 text-center">
                <div>
                    <span class="aurelle-serif block text-5xl text-aubergine/35">A</span>
                    <span class="mt-2 block text-[9px] uppercase tracking-[.18em] text-aubergine/55">AURELLE</span>
                </div>
            </div>
        @endif
        @if ($onSale)
            <span
                class="absolute left-3 top-3 bg-rose px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[.12em] text-white">Sale</span>
        @endif
        @if ($stock < 1)
            <span
                class="absolute inset-x-0 bottom-0 bg-aubergine/85 px-3 py-2 text-center text-[9px] font-medium uppercase tracking-[.15em] text-white">Currently
                unavailable</span>
        @endif
    </div>

    <div class="pt-3">
        @if ($product->category)
            <p class="mb-1 text-[9px] uppercase tracking-[.15em] text-aubergine/55">{{ $product->category->name }}</p>
        @endif
        <h3 class="aurelle-serif text-[21px] leading-tight text-aubergine">{{ $product->name }}</h3>
        <div class="mt-2 flex flex-wrap items-baseline gap-x-2 gap-y-1 text-xs">
            @if ($variant)
                <span class="font-semibold text-aubergine">PKR {{ number_format((float) $variant->price, 0) }}</span>
                @if ($onSale)
                    <span class="text-aubergine/50 line-through">PKR
                        {{ number_format((float) $variant->compare_at_price, 0) }}</span>
                @endif
            @else
                <span class="text-aubergine/55">Price unavailable</span>
            @endif
        </div>
    </div>
</article>
