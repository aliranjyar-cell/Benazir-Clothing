@extends('storefront.layouts.store')

@section('title', 'Shopping Bag')

@section('content')
    @php
        $currency = config('storefront.currency_symbol', '₨');
    @endphp

    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <nav aria-label="Breadcrumb" class="mb-space-lg font-body-sm text-body-sm text-on-surface-variant">
                <a href="{{ route('store.home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="mx-2 text-secondary">/</span>
                <span class="text-on-surface">Shopping Bag</span>
            </nav>

            <div class="border-b border-outline-variant/50 pb-space-lg">
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">BENAZIR Atelier</p>
                <h1 class="mt-space-sm font-display-hero font-headline-lg text-headline-lg text-primary">
                    Your Shopping Bag
                </h1>
                <p class="mt-space-sm font-body-md text-body-md text-on-surface-variant">
                    Your chosen pieces, thoughtfully brought together.
                </p>
            </div>

            @if (session('success'))
                <div class="mt-space-lg rounded-lg border border-green-200 bg-green-50 px-space-md py-space-sm text-green-800"
                    role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-space-lg rounded-lg border border-red-200 bg-red-50 px-space-md py-space-sm text-red-800"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($lines->isNotEmpty())
                <div class="mt-space-xl grid items-start gap-space-xl lg:grid-cols-12">
                    <section class="lg:col-span-8">
                        <div
                            class="hidden border-b border-outline-variant/60 pb-space-sm font-label-uppercase text-label-uppercase tracking-wider text-on-surface-variant sm:grid sm:grid-cols-12 sm:gap-space-md">
                            <div class="sm:col-span-6">Product</div>
                            <div class="text-center sm:col-span-2">Price</div>
                            <div class="text-center sm:col-span-2">Quantity</div>
                            <div class="text-right sm:col-span-2">Subtotal</div>
                        </div>

                        <div class="divide-y divide-outline-variant/50">
                            @foreach ($lines as $cartItem)
                                @php
                                    $variant = $cartItem['variant'];
                                    $product = $cartItem['product'];
                                    $quantity = $cartItem['quantity'];
                                    $price = (float) $variant->price;
                                    $lineTotal = $cartItem['line_total'];
                                    $image =
                                        $product->primaryImage?->image_path ?? $product->images->first()?->image_path;
                                    $stock = $variant->stock_quantity;
                                @endphp

                                <article class="py-space-lg sm:py-space-xl">
                                    <div class="grid gap-space-md sm:grid-cols-12 sm:items-center sm:gap-space-md">
                                        <div class="flex min-w-0 gap-space-md sm:col-span-6">
                                            <div
                                                class="aspect-[3/4] w-24 shrink-0 overflow-hidden rounded-lg bg-surface-variant/50 ring-1 ring-outline-variant/40 sm:w-28">
                                                @if ($image)
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                        alt="{{ $product?->name ?? 'Product image' }}"
                                                        class="h-full w-full object-cover" loading="lazy">
                                                @else
                                                    <div
                                                        class="flex h-full items-center justify-center text-on-surface-variant/60">
                                                        <span
                                                            class="material-symbols-outlined text-3xl">image_not_supported</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <p
                                                    class="font-label-uppercase text-label-uppercase tracking-wider text-secondary">
                                                    {{ $product?->category?->name ?? 'BENAZIR Collection' }}
                                                </p>
                                                <h2 class="mt-1 font-display-hero text-xl text-primary">
                                                    {{ $product?->name ?? 'Product' }}
                                                </h2>

                                                @if ($product?->fabric)
                                                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                                        {{ $product->fabric }}</p>
                                                @endif

                                                @if ($variant?->size)
                                                    <p class="mt-2 font-body-sm text-body-sm text-on-surface-variant">
                                                        Size: <span
                                                            class="font-medium text-on-surface">{{ $variant->size }}</span>
                                                    </p>
                                                @endif

                                                @if ($variant?->color)
                                                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                                        Color: <span
                                                            class="font-medium text-on-surface">{{ $variant->color }}</span>
                                                    </p>
                                                @endif

                                                <div class="mt-space-sm sm:hidden">
                                                    <p class="font-display-hero text-lg text-primary">
                                                        {{ $currency }}{{ number_format($lineTotal, 0) }}</p>
                                                </div>

                                                @if ($stock < 1)
                                                    <p class="mt-2 font-body-sm text-body-sm text-error">Currently out of
                                                        stock.</p>
                                                @endif

                                                <form method="POST" action="{{ route('store.cart.remove') }}"
                                                    class="mt-3">
                                                    @csrf
                                                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                                    <button type="submit"
                                                        class="font-body-sm text-body-sm text-on-surface-variant underline underline-offset-4 hover:text-error">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div
                                            class="hidden text-center font-body-sm text-body-sm text-on-surface sm:col-span-2 sm:block">
                                            {{ $currency }}{{ number_format($price, 0) }}
                                        </div>

                                        <div class="sm:col-span-2">
                                            <form method="POST" action="{{ route('store.cart.update') }}"
                                                class="flex items-center justify-center gap-2">
                                                @csrf
                                                <label class="sr-only" for="quantity-{{ $loop->index }}">Quantity
                                                    for {{ $product->name }}</label>
                                                <input id="quantity-{{ $loop->index }}"
                                                    name="update[{{ $variant->id }}]" type="number" min="0"
                                                    max="{{ $stock }}" value="{{ $quantity }}"
                                                    class="w-16 rounded-lg border border-outline-variant bg-surface-container-lowest px-2 py-2 text-center font-body-sm text-on-surface focus:border-primary focus:outline-none">
                                                <button type="submit"
                                                    class="rounded-lg border border-outline-variant px-3 py-2 font-body-sm text-on-surface hover:bg-surface-variant">
                                                    Update
                                                </button>
                                            </form>
                                        </div>

                                        <div
                                            class="hidden text-right font-display-hero text-lg text-primary sm:col-span-2 sm:block">
                                            {{ $currency }}{{ number_format($lineTotal, 0) }}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-space-lg border-t border-outline-variant/50 pt-space-lg">
                            <a href="{{ route('store.shop') }}"
                                class="inline-flex items-center gap-space-sm font-label-uppercase text-label-uppercase tracking-wider text-primary hover:underline">
                                <span class="material-symbols-outlined text-secondary">arrow_back</span>
                                Continue shopping
                            </a>
                        </div>
                    </section>

                    <aside class="lg:sticky lg:top-8 lg:col-span-4">
                        <div class="rounded-xl border border-outline-variant/60 bg-surface-container-lowest p-space-lg">
                            <h2 class="font-display-hero text-2xl text-primary">Order Summary</h2>

                            <dl
                                class="mt-space-lg space-y-space-md border-b border-outline-variant/50 pb-space-lg font-body-sm text-body-sm">
                                <div class="flex items-center justify-between gap-space-md">
                                    <dt class="text-on-surface-variant">Subtotal</dt>
                                    <dd class="font-medium text-on-surface">
                                        {{ $currency }}{{ number_format($subtotal, 0) }}</dd>
                                </div>
                                <div class="flex items-start justify-between gap-space-md">
                                    <dt class="text-on-surface-variant">Delivery</dt>
                                    <dd class="text-right text-on-surface-variant">Confirmed at checkout</dd>
                                </div>
                            </dl>

                            <div class="flex items-center justify-between gap-space-md py-space-lg">
                                <div>
                                    <p class="font-display-hero text-xl text-primary">Estimated total</p>
                                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Delivery charges may
                                        apply.</p>
                                </div>
                                <p class="font-display-hero text-2xl text-primary">
                                    {{ $currency }}{{ number_format($subtotal, 0) }}</p>
                            </div>

                            <form method="POST" action="{{ route('store.cart.clear') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-space-sm rounded-lg border border-outline-variant px-space-lg py-space-sm font-label-uppercase text-label-uppercase tracking-wider text-on-surface-variant transition-colors hover:border-error/50 hover:text-error">
                                    <span class="material-symbols-outlined text-sm">delete_forever</span>
                                    Clear bag
                                </button>
                            </form>

                            <a href="{{ route('store.checkout.index') }}"
                                class="mt-space-sm inline-flex w-full items-center justify-center gap-space-sm rounded-lg bg-primary px-space-lg py-space-md font-label-uppercase text-label-uppercase tracking-widest text-on-primary transition-colors hover:bg-primary/90">
                                Proceed to checkout
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>

                            <div class="mt-space-lg rounded-lg border border-outline-variant/60 bg-surface p-space-md">
                                <div class="flex items-start gap-space-sm">
                                    <span class="material-symbols-outlined text-secondary">payments</span>
                                    <div>
                                        <p class="font-body-sm text-body-sm font-semibold text-on-surface">Cash on Delivery
                                        </p>
                                        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                            Pay in cash when your order arrives. No bank transfer or online card payment is
                                            required.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <p class="mt-space-md text-center font-body-sm text-body-sm text-on-surface-variant">
                                Carefully packed with care by BENAZIR.
                            </p>
                        </div>

                        <div
                            class="mt-space-md flex items-start gap-space-sm rounded-xl border border-outline-variant/50 bg-surface p-space-md">
                            <span class="material-symbols-outlined text-secondary">support_agent</span>
                            <div>
                                <p class="font-body-sm text-body-sm font-semibold text-on-surface">Need help with sizing?
                                </p>
                                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Contact our team before
                                    placing your order.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            @else
                <div class="mx-auto max-w-2xl py-space-2xl text-center">
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-secondary/30 bg-surface-container-low">
                        <span class="material-symbols-outlined text-4xl text-secondary">shopping_bag</span>
                    </div>
                    <p class="mt-space-lg font-label-uppercase text-label-uppercase tracking-widest text-secondary">BENAZIR
                        Atelier</p>
                    <h2 class="mt-space-sm font-display-hero text-3xl text-primary sm:text-4xl">Your bag is waiting for
                        something beautiful.</h2>
                    <p class="mx-auto mt-space-md max-w-lg font-body-md text-body-md text-on-surface-variant">
                        Explore our collection of timeless Punjabi craftsmanship and find your next favourite piece.
                    </p>
                    <a href="{{ route('store.shop') }}"
                        class="mt-space-lg inline-flex items-center justify-center rounded-lg bg-primary px-space-xl py-space-md font-label-uppercase text-label-uppercase tracking-widest text-on-primary transition-colors hover:bg-primary/90">
                        Explore the collection
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
