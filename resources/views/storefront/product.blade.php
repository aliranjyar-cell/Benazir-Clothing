@extends('storefront.layouts.store')

@section('title', $product->name)

@section('content')
    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <div class="grid gap-space-lg lg:grid-cols-2">
                <div class="space-y-space-md">
                    @if ($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}"
                            class="aspect-[3/4] w-full object-cover rounded-xl ring-1 ring-outline-variant/40"
                            loading="lazy" />
                    @else
                        <div
                            class="aspect-[3/4] w-full flex items-center justify-center rounded-xl bg-surface-variant/50 text-on-surface-variant/60">
                            <span class="material-symbols-outlined text-4xl">image_not_supported</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-4 gap-space-xs">
                        @foreach ($product->images->slice(1)->take(3) as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $product->name }}"
                                class="aspect-square w-full object-cover rounded-lg ring-1 ring-outline-variant/30 cursor-pointer hover:ring-primary transition-all"
                                loading="lazy" />
                        @endforeach
                    </div>
                </div>

                <div>
                    <nav aria-label="Breadcrumb" class="text-body-sm text-on-surface-variant/80">
                        <a href="{{ route('store.home') }}">Home</a>
                        <span aria-hidden="true">/</span>
                        @if ($product->category)
                            <a
                                href="{{ route('store.shop.category', $product->category) }}">{{ $product->category->name }}</a>
                        @endif
                        <span aria-hidden="true">/</span>
                        <span class="text-on-surface">{{ $product->name }}</span>
                    </nav>

                    <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-md leading-[1.15]">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-baseline gap-space-md mt-space-sm">
                        @if ($product->activeVariants->isNotEmpty())
                            <p class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                {{ $product->activeVariants->min('price') }}
                                <span
                                    class="text-body-sm font-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">
                                    <?= config('storefront.currency_symbol', '₨') ?>
                                </span>
                            </p>
                        @else
                            <p class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                <span class="text-on-surface-variant/60">Not in stock</span>
                            </p>
                        @endif
                    </div>

                    <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                        {{ $product->description }}
                    </p>

                    <div class="mt-space-xl">
                        @if ($care)
                            <details class="rounded-xl border border-outline-variant bg-surface-container-lowest" open>
                                <summary
                                    class="flex items-center gap-space-sm px-space-md py-space-md font-body-sm text-body-sm text-on-surface">
                                    <span class="material-symbols-outlined text-secondary">spa</span>
                                    <span>Care instructions</span>
                                </summary>
                                <p
                                    class="px-space-md py-space-md font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                    {{ $care }}</p>
                            </details>
                        @endif
                    </div>

                    <ul class="mt-space-xl grid gap-space-sm md:grid-cols-2">
                        @if ($ingredients)
                            <li class="flex gap-space-sm">
                                <span class="material-symbols-outlined text-secondary shrink-0">pest_control</span>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $ingredients }}</p>
                            </li>
                        @endif
                        @if ($product->care_instructions)
                            <li class="flex gap-space-sm">
                                <span class="material-symbols-outlined text-secondary shrink-0">laundry</span>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">
                                    {{ $product->care_instructions }}</p>
                            </li>
                        @endif
                    </ul>

                    <form method="POST" action="{{ route('store.cart.add') }}" class="mt-space-xl">
                        @csrf
                        <input type="hidden" name="variant_id" id="variant-id"
                            value="{{ $product->activeVariants->first()->id ?? '' }}" />
                        <input type="hidden" name="quantity" id="variant-qty" value="1" />

                        <div class="flex flex-wrap items-end gap-space-md">
                            <div class="flex-1 sm:flex-1 min-w-[140px]">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">Quantity</label>
                                <input type="number" name="quantity" id="qty" min="1"
                                    max="{{ $product->activeVariants->first()->stock_quantity ?? 1 }}" value="1"
                                    class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" />
                            </div>
                            <button type="submit"
                                class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest hover:bg-primary-container transition-colors">
                                <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                Add to bag
                            </button>
                        </div>

                        @if ($product->activeVariants->first()->stock_quantity < 1)
                            <p class="mt-space-sm font-label-uppercase text-label-uppercase text-error">
                                This product is currently out of stock.
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        @if (count($similarProducts) > 0)
            <div class="bg-background border-t border-outline-variant/40">
                <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
                    <h2 class="font-display-hero font-headline-lg text-headline-lg text-primary">You may also like</h2>
                    <div class="mt-space-md grid gap-space-lg sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($similarProducts as $product)
                            @include('storefront.partials.product-card')
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
