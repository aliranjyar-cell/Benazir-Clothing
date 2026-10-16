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
                                Order received
                            @else
                                Order details
                            @endif
                        </p>
                        <h1 class="font-display-hero text-headline-lg text-primary mt-space-xs leading-[1.15]">
                            @if ($confirmation)
                                Thank you for your order
                            @else
                                Order {{ $order->order_number }}
                            @endif
                        </h1>
                        @if ($confirmation)
                            <p class="mt-space-sm max-w-2xl font-body-md text-body-md text-on-surface-variant">
                                We have received your order and will review it shortly. Your pieces are being prepared with
                                care.
                            </p>
                        @endif
                        <div class="mt-space-md border-l-2 border-secondary pl-space-md">
                            <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">Order number
                            </p>
                            <p class="mt-1 font-display-hero text-2xl text-primary">{{ $order->order_number }}</p>
                            <time datetime="{{ $order->created_at->toAtomString() }}"
                                class="mt-1 block font-body-sm text-body-sm text-on-surface-variant">
                                Placed {{ $order->created_at->format('F j, Y · g:i A') }}
                            </time>
                        </div>
                    </div>
                    <span
                        class="inline-flex items-center rounded-full px-3 py-1 text-[0.6rem] font-label-uppercase tracking-widest {{ $order->status === 'delivered' ? 'bg-green-container text-on-green-container' : ($order->status === 'cancelled' ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container') }}">
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
                            {{ $order->shipping_city }}, {{ $order->shipping_state ?? '' }},
                            {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}
                        </p>
                    </div>
                </div>

                <div class="mt-space-md">
                    <p class="font-label-uppercase text-label-uppercase text-secondary">Items</p>
                    <div class="mt-space-sm space-y-space-sm">
                        @foreach ($order->items as $item)
                            <div
                                class="flex items-start justify-between gap-space-md border-b border-outline-variant/40 pb-space-md">
                                <div>
                                    <p class="font-body-sm text-body-sm text-on-surface">{{ $item->product_name }}</p>
                                    <p class="font-label-uppercase text-label-uppercase text-secondary mt-space-xs">
                                        Qty {{ $item->quantity }} ·
                                        {{ $item->size ? $item->size . ' · ' : '' }}{{ $item->color ?: 'Variant' }} ·
                                        {{ $item->sku }}
                                    </p>
                                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                        {{ $order->currency }} {{ number_format((float) $item->unit_price, 2) }} each
                                    </p>
                                </div>
                                <p class="font-display-hero text-headline-lg text-primary">
                                    {{ $order->currency }} {{ number_format((float) $item->line_total, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-space-md grid gap-space-sm text-body-sm">
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant/80">Subtotal</dt>
                        <dd class="font-display-hero text-headline-lg text-primary">
                            {{ $order->currency }} {{ number_format((float) $order->subtotal, 2) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant/80">Discount</dt>
                        <dd class="font-medium text-secondary">
                            − {{ $order->currency }} {{ number_format((float) $order->discount_total, 2) }}
                        </dd>
                    </div>
                    @if ((float) $order->tax_total > 0)
                        <div class="flex justify-between">
                            <dt class="text-on-surface-variant/80">Tax</dt>
                            <dd class="text-on-surface">
                                {{ $order->currency }} {{ number_format((float) $order->tax_total, 2) }}
                            </dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-on-surface-variant/80">Shipping</dt>
                        <dd class="font-display-hero text-headline-lg text-primary">
                            {{ $order->currency }} {{ number_format((float) $order->shipping_total, 2) }}
                        </dd>
                    </div>
                    <div class="flex justify-between border-t border-outline-variant/40 pt-space-md">
                        <dt class="font-label-uppercase text-label-uppercase tracking-widest text-on-surface">Grand total
                        </dt>
                        <dd class="font-display-hero text-headline-lg text-primary">
                            {{ $order->currency }} {{ number_format((float) $order->grand_total, 2) }}
                        </dd>
                    </div>
                </div>

                <section class="mt-space-md border-y border-outline-variant/50 py-space-md" aria-label="Payment method">
                    <div class="flex items-start gap-space-sm">
                        <span class="material-symbols-outlined text-secondary" aria-hidden="true">payments</span>
                        <div>
                            <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">
                                {{ $order->payment_method === 'cash_on_delivery' ? 'Cash on Delivery' : ucwords(str_replace('_', ' ', $order->payment_method ?? 'Not provided')) }}
                            </p>
                            <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                Payment status: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}.
                            </p>
                        </div>
                    </div>
                </section>

                @if (!$confirmation)
                    <div
                        class="mt-space-md rounded-lg border border-outline-variant bg-surface-variant/30 px-space-md py-space-md">
                        <p class="font-label-uppercase text-label-uppercase text-secondary">Status history</p>
                        @if ($order->statusHistories->isNotEmpty())
                            <ol class="mt-space-sm space-y-space-sm">
                                @foreach ($order->statusHistories as $history)
                                    <li
                                        class="flex flex-wrap items-baseline justify-between gap-space-sm border-l-2 border-secondary pl-space-sm">
                                        <p class="font-body-sm text-body-sm text-on-surface">
                                            @if ($history->from_status)
                                                {{ ucfirst(str_replace('_', ' ', $history->from_status)) }} to
                                            @else
                                                Order placed as
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $history->to_status)) }}
                                        </p>
                                        <time datetime="{{ $history->created_at->toAtomString() }}"
                                            class="font-body-sm text-body-sm text-on-surface-variant">
                                            {{ $history->created_at->format('M j, Y · g:i A') }}
                                        </time>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-space-xs">No status updates are
                                available yet.</p>
                        @endif
                    </div>
                @endif

                <div class="mt-space-md flex justify-between">
                    <a href="{{ route('store.shop') }}"
                        class="inline-flex items-center gap-space-sm rounded-lg border border-outline-variant bg-surface-container-lowest px-6 py-2.5 text-label-uppercase text-on-surface font-label-uppercase tracking-widest transition-colors hover:border-primary/40 hover:text-primary">
                        <span class="material-symbols-outlined text-sm">shopping_bag</span>
                        Continue shopping
                    </a>
                    @if ($confirmation && $order->user_id && auth('web')->id() === $order->user_id)
                        <a href="{{ route('store.account.orders.show', $order) }}"
                            class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest transition-colors hover:bg-primary-container">
                            <span class="material-symbols-outlined text-sm">account_circle</span>
                            View order
                        </a>
                    @endif
                    @if (!$confirmation)
                        <a href="{{ route('store.account.orders') }}"
                            class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest transition-colors hover:bg-primary-container">
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">receipt_long</span>
                            My orders
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
