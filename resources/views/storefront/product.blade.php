
@extends('storefront.layouts.store')

@section('title', $product->name)

@section('content')
@php
    $variants = $product->activeVariants;
    $firstVariant = $variants->first();
    $availableVariants = $variants->filter(fn ($variant) => $variant->stock_quantity > 0);
    $galleryImages = $product->images->sortBy('sort_order')->values();
    $primaryImage = $galleryImages->firstWhere('is_primary', true) ?? $galleryImages->first();
    $currency = config('storefront.currency_symbol', '₨');
    $hasStock = $availableVariants->isNotEmpty();
@endphp

<div class="bg-background">

    {{-- Breadcrumbs --}}
    <div class="border-b border-outline-variant/40">
        <div class="mx-auto max-w-7xl px-margin py-4 lg:px-margin-md">
            <nav aria-label="Breadcrumb"
                class="flex flex-wrap items-center gap-2 text-xs tracking-wide text-on-surface-variant sm:text-sm">
                <a href="{{ route('store.home') }}" class="transition hover:text-primary">Home</a>
                <span class="text-secondary">/</span>

                @if ($product->category)
                    <a href="{{ route('store.shop.category', $product->category) }}"
                        class="transition hover:text-primary">
                        {{ $product->category->name }}
                    </a>
                    <span class="text-secondary">/</span>
                @endif

                <span aria-current="page" class="font-medium text-primary">
                    {{ $product->name }}
                </span>
            </nav>
        </div>
    </div>

    {{-- Product details --}}
    <section class="mx-auto max-w-7xl px-margin py-8 sm:py-12 lg:px-margin-md lg:py-16">
        <div class="grid items-start gap-10 lg:grid-cols-12 lg:gap-14">

            {{-- Product gallery --}}
            <div class="lg:col-span-7">
                <div class="grid gap-3 sm:grid-cols-[90px_minmax(0,1fr)] lg:grid-cols-[100px_minmax(0,1fr)]">

                    {{-- Thumbnails --}}
                    @if ($galleryImages->isNotEmpty())
                        <div class="order-2 flex gap-3 overflow-x-auto sm:order-1 sm:flex-col">
                            @foreach ($galleryImages as $image)
                                <button
                                    type="button"
                                    class="product-thumbnail group w-[76px] shrink-0 overflow-hidden rounded-lg border border-outline-variant/60 bg-surface-container-low transition hover:border-secondary focus:outline-none focus:ring-2 focus:ring-primary sm:w-full"
                                    data-image="{{ asset('storage/' . $image->image_path) }}"
                                    data-alt="{{ $product->name }}"
                                    aria-label="View product image {{ $loop->iteration }}"
                                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                    <img
                                        src="{{ asset('storage/' . $image->image_path) }}"
                                        alt="{{ $product->name }} view {{ $loop->iteration }}"
                                        class="aspect-[3/4] w-full object-cover transition duration-300 group-hover:scale-105"
                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                </button>
                            @endforeach
                        </div>

                        {{-- Main image --}}
                        <div class="order-1 min-w-0 sm:order-2">
                            <div class="group relative overflow-hidden rounded-xl border border-outline-variant/40 bg-surface-container-low shadow-sm">
                                <div class="absolute left-4 top-4 z-10">
                                    @if ($product->is_featured)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-background/95 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-primary shadow-sm backdrop-blur">
                                            <span class="material-symbols-outlined text-sm text-secondary">auto_awesome</span>
                                            Featured
                                        </span>
                                    @endif
                                </div>

                                @if ($primaryImage)
                                    <img
                                        id="main-product-image"
                                        src="{{ asset('storage/' . $primaryImage->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="aspect-[3/4] w-full object-cover object-top transition duration-700 group-hover:scale-[1.02]"
                                        fetchpriority="high">
                                @else
                                    <div class="flex aspect-[3/4] w-full flex-col items-center justify-center gap-3 text-on-surface-variant/60">
                                        <span class="material-symbols-outlined text-5xl">image_not_supported</span>
                                        <p class="text-sm">Product image coming soon</p>
                                    </div>
                                @endif

                                <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/10 to-transparent"></div>
                            </div>

                            <p class="mt-3 text-center text-[10px] uppercase tracking-[0.2em] text-on-surface-variant">
                                Designed to be treasured
                            </p>
                        </div>
                    @else
                        <div class="sm:col-span-2">
                            <div class="flex aspect-[4/3] flex-col items-center justify-center gap-3 rounded-xl border border-outline-variant/40 bg-surface-container-low text-on-surface-variant/60">
                                <span class="material-symbols-outlined text-5xl">image_not_supported</span>
                                <p class="text-sm">Product images are not available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Product information --}}
            <div class="lg:sticky lg:top-8 lg:col-span-5">
                <div class="border-b border-outline-variant/50 pb-6">
                    <p class="font-label-uppercase text-[10px] tracking-[0.25em] text-secondary sm:text-xs">
                        BENAZIR COLLECTION
                    </p>

                    <h1 class="mt-4 font-display-hero text-3xl leading-[1.15] tracking-tight text-primary sm:text-4xl lg:text-5xl">
                        {{ $product->name }}
                    </h1>

                    @if ($product->category)
                        <p class="mt-3 text-sm text-on-surface-variant">
                            {{ $product->category->name }}
                        </p>
                    @endif

                    <div class="mt-6 flex flex-wrap items-baseline gap-3">
                        @if ($hasStock)
                            <p id="product-price" class="font-display-hero text-3xl text-primary sm:text-4xl">
                                {{ $currency }}{{ number_format((float) $availableVariants->min('price'), 0) }}
                            </p>
                            @if ($availableVariants->count() > 1)
                                <span class="text-xs text-on-surface-variant">Starting from</span>
                            @endif
                        @else
                            <p class="font-display-hero text-2xl text-on-surface-variant">
                                Currently unavailable
                            </p>
                        @endif
                    </div>

                    <div class="mt-5 flex items-center gap-2">
                        @if ($hasStock)
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-600 opacity-30"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-green-700"></span>
                            </span>
                            <span class="text-xs font-medium text-on-surface-variant">Available to order</span>
                        @else
                            <span class="h-2 w-2 rounded-full bg-error"></span>
                            <span class="text-xs font-medium text-error">Out of stock</span>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <div class="border-b border-outline-variant/50 py-6">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.18em] text-primary">
                        About this piece
                    </h2>

                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-on-surface-variant sm:text-base">
                        {{ $product->description ?: 'Discover the details of this piece from the BENAZIR collection.' }}
                    </p>

                    @if ($product->fabric)
                        <div class="mt-5 flex items-start gap-3">
                            <span class="material-symbols-outlined text-xl text-secondary">texture</span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-primary">Fabric</p>
                                <p class="mt-1 text-sm text-on-surface-variant">{{ $product->fabric }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Add to cart --}}
                <div class="py-6">
                    <form method="POST" action="{{ route('store.cart.add') }}" id="add-to-cart-form">
                        @csrf

                        <input type="hidden" name="variant_id" id="variant-id"
                            value="{{ $firstVariant?->id }}">

                        @if ($variants->isNotEmpty())
                            <div class="mb-6">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <label for="variant-select" class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">
                                        Select an option
                                    </label>
                                    <span class="text-xs text-on-surface-variant">Required</span>
                                </div>

                                <select
                                    id="variant-select"
                                    class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3.5 text-sm text-on-surface outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/10"
                                    {{ !$hasStock ? 'disabled' : '' }}>
                                    @foreach ($variants as $variant)
                                        <option
                                            value="{{ $variant->id }}"
                                            data-price="{{ $variant->price }}"
                                            data-stock="{{ $variant->stock_quantity }}"
                                            @selected($variant->id === $firstVariant?->id)
                                            {{ $variant->stock_quantity < 1 ? 'disabled' : '' }}>
                                            {{ $variant->size ?? 'Standard' }}{{ !empty($variant->color) ? ' · ' . $variant->color : '' }}{{ $variant->stock_quantity < 1 ? ' — Out of stock' : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <p id="variant-stock-message" class="mt-2 text-xs text-on-surface-variant">
                                    @if ($firstVariant && $firstVariant->stock_quantity > 0)
                                        {{ $firstVariant->stock_quantity }} available
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div class="mb-6">
                            <div class="mb-3 flex items-center justify-between">
                                <label for="qty" class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">
                                    Quantity
                                </label>
                                <span class="text-xs text-on-surface-variant">Choose your quantity</span>
                            </div>

                            <div class="flex max-w-[180px] items-center rounded-lg border border-outline-variant bg-surface-container-lowest">
                                <button type="button" id="qty-minus"
                                    class="flex h-12 w-12 items-center justify-center text-primary transition hover:bg-surface-container-low disabled:cursor-not-allowed disabled:opacity-40"
                                    aria-label="Decrease quantity">
                                    <span class="material-symbols-outlined text-lg">remove</span>
                                </button>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="qty"
                                    min="1"
                                    max="{{ $firstVariant?->stock_quantity ?? 1 }}"
                                    value="1"
                                    required
                                    class="h-12 min-w-0 w-full border-x border-outline-variant bg-transparent text-center text-sm font-semibold text-primary outline-none"
                                    {{ !$hasStock ? 'disabled' : '' }}>

                                <button type="button" id="qty-plus"
                                    class="flex h-12 w-12 items-center justify-center text-primary transition hover:bg-surface-container-low disabled:cursor-not-allowed disabled:opacity-40"
                                    aria-label="Increase quantity">
                                    <span class="material-symbols-outlined text-lg">add</span>
                                </button>
                            </div>
                            @error('quantity')
                                <p class="mt-2 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            id="add-to-bag-button"
                            class="flex w-full items-center justify-center gap-3 rounded-lg bg-primary px-6 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-on-primary shadow-sm transition duration-300 hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            {{ !$hasStock ? 'disabled' : '' }}>
                            <span class="material-symbols-outlined text-lg">shopping_bag</span>
                            <span id="add-to-bag-label">{{ $hasStock ? 'Add to bag' : 'Currently unavailable' }}</span>
                        </button>
                    </form>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 rounded-lg bg-surface-container-low p-3">
                            <span class="material-symbols-outlined text-xl text-secondary">local_shipping</span>
                            <span class="text-xs leading-5 text-on-surface-variant">Delivery details at checkout</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-surface-container-low p-3">
                            <span class="material-symbols-outlined text-xl text-secondary">payments</span>
                            <span class="text-xs leading-5 text-on-surface-variant">Cash on Delivery</span>
                        </div>
                    </div>
                </div>

                {{-- Care and details --}}
                <div class="border-t border-outline-variant/50">
                    @if ($product->fabric)
                        <details class="group border-b border-outline-variant/50">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-5 text-sm font-medium text-primary">
                                <span class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-xl text-secondary">texture</span>
                                    Fabric & composition
                                </span>
                                <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                            </summary>
                            <p class="pb-5 pl-9 text-sm leading-7 text-on-surface-variant">
                                {{ $product->fabric }}
                            </p>
                        </details>
                    @endif

                    @if ($care || $product->care_instructions)
                        <details class="group border-b border-outline-variant/50">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-5 text-sm font-medium text-primary">
                                <span class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-xl text-secondary">laundry</span>
                                    Care instructions
                                </span>
                                <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                            </summary>
                            <div class="space-y-3 pb-5 pl-9 text-sm leading-7 text-on-surface-variant">
                                @if ($care)
                                    <p>{{ $care }}</p>
                                @endif
                                @if ($product->care_instructions)
                                    <p>{{ $product->care_instructions }}</p>
                                @endif
                            </div>
                        </details>
                    @endif

                    <div class="border-b border-outline-variant/50 py-5">
                        <a href="{{ url('/contact') }}"
                            class="flex items-center justify-between gap-4 text-sm text-primary transition hover:text-secondary">
                            <span class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-xl text-secondary">support_agent</span>
                                Need help choosing?
                            </span>
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- You may also like --}}
    @if (count($similarProducts) > 0)
        <section class="border-t border-outline-variant/50 bg-surface-container-low">
            <div class="mx-auto max-w-7xl px-margin py-14 lg:px-margin-md lg:py-20">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="font-label-uppercase text-xs tracking-[0.22em] text-secondary">
                            COMPLETE THE LOOK
                        </p>
                        <h2 class="mt-3 font-display-hero text-3xl text-primary sm:text-4xl">
                            You may also like
                        </h2>
                        <p class="mt-3 text-sm leading-6 text-on-surface-variant">
                            Discover more pieces from the BENAZIR collection.
                        </p>
                    </div>

                    <a href="{{ route('store.shop') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-secondary">
                        View all pieces
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
                    @foreach ($similarProducts as $product)
                        @include('storefront.partials.product-card')
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>

{{-- Gallery and quantity interactions --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.product-thumbnail');

        thumbnails.forEach(function (thumbnail) {
            thumbnail.addEventListener('click', function () {
                if (!mainImage) return;

                mainImage.src = thumbnail.dataset.image;
                mainImage.alt = thumbnail.dataset.alt || '';

                thumbnails.forEach(function (item) {
                    item.classList.remove('border-secondary');
                    item.classList.add('border-outline-variant/60');
                    item.setAttribute('aria-pressed', 'false');
                });

                thumbnail.classList.remove('border-outline-variant/60');
                thumbnail.classList.add('border-secondary');
                thumbnail.setAttribute('aria-pressed', 'true');
            });
        });

        const variantSelect = document.getElementById('variant-select');
        const variantId = document.getElementById('variant-id');
        const quantityInput = document.getElementById('qty');
        const minusButton = document.getElementById('qty-minus');
        const plusButton = document.getElementById('qty-plus');
        const priceElement = document.getElementById('product-price');
        const stockMessage = document.getElementById('variant-stock-message');
        const submitButton = document.getElementById('add-to-bag-button');
        const submitLabel = document.getElementById('add-to-bag-label');

        function updateVariant() {
            if (!variantSelect || !variantId || !quantityInput) return;

            const option = variantSelect.options[variantSelect.selectedIndex];
            if (!option || !option.value) return;

            const stock = Number(option.dataset.stock || 0);
            const price = Number(option.dataset.price || 0);

            variantId.value = option.value;
            quantityInput.max = String(Math.max(stock, 1));

            if (Number(quantityInput.value) > stock) {
                quantityInput.value = String(Math.max(stock, 1));
            }

            if (stock < 1) {
                quantityInput.value = '1';
                quantityInput.disabled = true;
                submitButton.disabled = true;
                submitLabel.textContent = 'Currently unavailable';
                stockMessage.textContent = 'This option is out of stock.';
            } else {
                quantityInput.disabled = false;
                submitButton.disabled = false;
                submitLabel.textContent = 'Add to bag';
                stockMessage.textContent = stock + (stock === 1 ? ' available' : ' available');
            }

            if (priceElement) {
                priceElement.textContent = @json($currency) + price.toLocaleString(undefined, {
                    maximumFractionDigits: 2
                });
            }

            updateQuantityButtons();
        }

        function updateQuantityButtons() {
            if (!quantityInput) return;

            const min = Number(quantityInput.min || 1);
            const max = Number(quantityInput.max || 1);
            const value = Number(quantityInput.value || min);

            if (minusButton) minusButton.disabled = value <= min || quantityInput.disabled;
            if (plusButton) plusButton.disabled = value >= max || quantityInput.disabled;
        }

        if (variantSelect) {
            variantSelect.addEventListener('change', updateVariant);
        }

        if (minusButton && quantityInput) {
            minusButton.addEventListener('click', function () {
                const min = Number(quantityInput.min || 1);
                quantityInput.value = String(Math.max(min, Number(quantityInput.value || min) - 1));
                updateQuantityButtons();
            });
        }

        if (plusButton && quantityInput) {
            plusButton.addEventListener('click', function () {
                const max = Number(quantityInput.max || 1);
                quantityInput.value = String(Math.min(max, Number(quantityInput.value || 1) + 1));
                updateQuantityButtons();
            });
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', function () {
                const min = Number(quantityInput.min || 1);
                const max = Number(quantityInput.max || 1);
                let value = Number(quantityInput.value || min);

                value = Math.max(min, Math.min(max, value));
                quantityInput.value = String(value);

                updateQuantityButtons();
            });

            quantityInput.addEventListener('change', updateQuantityButtons);
        }

        if (variantSelect) {
            updateVariant();
        } else {
            updateQuantityButtons();
        }
    });
</script>
@endsection
