@extends('storefront.layouts.store')

@section('title', $confirmation ? 'Order confirmation' : 'Order details')

@section('content')
    <div class="bg-background">
        <div class="max-w-4xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-space-md">
                    <div>
                        <p class="font-label-uppercase text-label-uppercase tracking-widest text-primary">
                            @if ($confirmation)
                                Your order is confirmed
                            @else
                                Order details
                            @endif
                        </p>
                        <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-xs leading-[1.15]">
                            @if ($confirmation)
                                Thank you for your order
                            @else
                                Order {{ $order->order_number }}
                            @endif
                        </h1>
                    </div>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-[0.6rem] font-label-uppercase tracking-widest {{ $order->status === 'delivered' ? 'bg-green-container text-on-green-container' : ($order->status === 'cancelled' ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container') }}"
                    >
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>

                <div class="mt-space-md grid gap-space-md sm:grid-cols-2">
                    <div>
                        <p class="font-label-uppercase text-label-uppercase text-secondary">Customer</p>
                        <p class="font-body-sm text-body-sm text-on-surface mt-space-xs">{{ $order->customer_name }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $order->customer_email }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="font-label-uppercase text-label-uppercase text-secondary">Shipping address</p>
                        <p class="font-body-sm text-body-sm text-on-surface mt-space-xs">
                            {{ $order->shipping_address_line_1 }}
                            @if ($order->shipping_address_line_2)
                                <br />{{ $order->shipping_address_line_2 }}
                            @endif
                            <br />
                            {{ $order->shipping_city }}, {{ $order->shipping_state } ?? '' }}, {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}
                        </p>
                    </div>
                </div>

                <div class="mt-space-md">
                    <p class="font-label-uppercase text-label-uppercase text-secondary">Items</p>
                    <div class="mt-space-sm space-y-space-sm">
                        @foreach ($order->items as $item)
                            <div class="flex items-start justify-between gap-space-md border-b border-outline-variant/40 pb-space-md">
                                <div>
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $item->product_name }}</p>
                                    <p class="font-label-uppercase text-label-uppercase text-secondary mt-space-xs">
                                        Qty {{ $item->quantity }} · {{ $item->size ? $item->size . ' · ' : '' }}{{ $item->color ?: 'Variant' }} · {{ $item->sku }}
                                    </p>
                                </div>
                                <p class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                    {{ $item->line_total }} <span class="text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ config('storefront.currency_symbol', '₨') }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-space-md grid gap-space-sm text-body-sm">
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant/80">Subtotal</dt>
                        <dd class="font-display-hero font-headline-lg text-headline-lg text-primary">
                            {{ $order->subtotal }} <span class="text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ config('storefront.currency_symbol', '₨') }}</span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant/80">Shipping</dt>
                        <dd class="font-display-hero font-headline-lg text-headline-lg text-primary">
                            {{ $order->shipping_total }} {{ config('storefront.currency_symbol', '₨') }}
                        </dd>
                    </div>
                    <div class="flex justify-between border-t border-outline-variant/40 pt-space-md">
                        <dt class="font-label-uppercase text-label-uppercase tracking-widest text-on-surface">Grand total</dt>
                        <dd class="font-display-hero font-headline-lg text-headline-lg text-primary">
                            {{ $order->grand_total }} <span class="text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ config('storefront.currency_symbol', '₨') }}</span>
                        </dd>
                    </div>
                </div>

                @if (!$confirmation)
                    <div class="mt-space-md rounded-lg border border-outline-variant bg-surface-variant/30 px-space-md py-space-md">
                        <p class="font-label-uppercase text-label-uppercase text-secondary">Status history</p>
                        <p class="font-body-sm text-body-sm text-on-surface mt-space-xs">
                            Order placed (pending). Our team will review and ship within 1 business day.
                        </p>
                    </div>
                @endif

                <div class="mt-space-md flex justify-between">
                    <a
                        href="{{ route('store.shop') }}"
                        class="inline-flex items-center gap-space-sm rounded-lg border border-outline-variant bg-surface-container-lowest px-6 py-2.5 text-label-uppercase text-on-surface font-label-uppercase tracking-widest transition-colors hover:border-primary/40 hover:text-primary"
                    >
                        <span class="material-symbols-outlined text-sm">shopping_bag</span>
                        Continue shopping
                    </a>
                    @if ($confirmation)
                        <a
                            href="{{ route('store.account') }}"
                            class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest transition-colors hover:bg-primary-container"
                        >
                            <span class="material-symbols-outlined text-sm">account_circle</span>
                            My account
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
