@extends('storefront.layouts.store')

@section('title', 'Track Your Order | ' . config('storefront.store_name', 'BENAZIR'))

@section('content')
    @php
        $statusStyles = [
            'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
            'confirmed' => 'bg-blue-50 text-blue-800 border-blue-200',
            'processing' => 'bg-violet-50 text-violet-800 border-violet-200',
            'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
            'delivered' => 'bg-green-50 text-green-800 border-green-200',
            'cancelled' => 'bg-red-50 text-red-800 border-red-200',
        ];

        $formatStatus = fn($status) => ucfirst(str_replace('_', ' ', $status));

        $currentStatus = $order?->status ?? 'pending';

        $statusIcons = [
            'pending' => 'schedule',
            'confirmed' => 'task_alt',
            'processing' => 'inventory_2',
            'shipped' => 'local_shipping',
            'delivered' => 'check_circle',
            'cancelled' => 'cancel',
        ];

        $currency = $order?->currency ?? config('storefront.currency_symbol', '₨');
    @endphp

    <div class="min-h-[65vh] bg-background">

        {{-- Editorial page introduction --}}
        <section class="border-b border-outline-variant/50 bg-surface-container-low">
            <div class="mx-auto max-w-7xl px-margin py-10 sm:py-14 lg:px-margin-md lg:py-16">
                <div class="max-w-3xl">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="h-px w-9 bg-secondary"></span>
                        <p class="font-label-uppercase text-xs tracking-[0.24em] text-secondary">
                            {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} CUSTOMER CARE
                        </p>
                    </div>

                    <h1 class="font-display-hero text-4xl leading-tight tracking-tight text-primary sm:text-5xl lg:text-6xl">
                        Your order, <span class="italic text-secondary">beautifully tracked.</span>
                    </h1>

                    <p class="mt-5 max-w-2xl text-sm leading-7 text-on-surface-variant sm:text-base sm:leading-8">
                        From the moment you place your order to the arrival of your chosen pieces,
                        follow your order's latest status here.
                    </p>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-x-7 gap-y-3 text-xs text-on-surface-variant">
                    <span class="inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-secondary">lock</span>
                        Private order access
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-secondary">support_agent</span>
                        Customer care
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg text-secondary">local_shipping</span>
                        Order status updates
                    </span>
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-7xl px-margin py-10 sm:py-14 lg:px-margin-md lg:py-16">

            @if (!$order)

                {{-- Tracking lookup --}}
                <div class="mx-auto grid max-w-5xl items-start gap-8 lg:grid-cols-[1fr_0.75fr] lg:gap-12">

                    <section
                        class="reveal reveal-up rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-6 shadow-sm sm:p-9 lg:p-10">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary/10">
                            <span class="material-symbols-outlined text-3xl text-secondary">package_2</span>
                        </div>

                        <p class="mt-7 font-label-uppercase text-xs tracking-[0.22em] text-secondary">
                            ORDER LOOKUP
                        </p>

                        <h2 class="mt-3 font-display-hero text-3xl text-primary sm:text-4xl">
                            Track an order
                        </h2>

                        <p class="mt-4 text-sm leading-7 text-on-surface-variant">
                            Enter the private tracking code provided after checkout to view your
                            order details, payment method and current status.
                        </p>

                        @if ($errors->any())
                            <div role="alert"
                                class="mt-6 flex gap-3 rounded-xl border border-error/30 bg-error-container/40 p-4">
                                <span class="material-symbols-outlined shrink-0 text-error">error</span>
                                <p class="text-sm leading-6 text-on-error-container">
                                    {{ $errors->first('tracking_token') }}
                                </p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('store.orders.track.submit') }}" class="mt-8">
                            @csrf

                            <label for="tracking_token"
                                class="mb-2 block text-xs font-semibold uppercase tracking-[0.15em] text-primary">
                                Private tracking code
                            </label>

                            <input id="tracking_token" name="tracking_token" type="text" required maxlength="36"
                                autocomplete="off" autocapitalize="none" spellcheck="false" aria-describedby="tracking-help"
                                placeholder="Enter your tracking code" value="{{ old('tracking_token') }}"
                                class="w-full rounded-xl border border-outline-variant bg-background px-4 py-4 font-mono text-sm text-on-surface outline-none transition placeholder:font-sans placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-4 focus:ring-primary/10">

                            <p id="tracking-help" class="mt-3 text-xs leading-6 text-on-surface-variant">
                                Your tracking code provides access to your order summary.
                                Keep it private and only use it on the official {{ config('storefront.store_name', 'BENAZIR') }} website.
                            </p>

                            <button type="submit"
                                class="mt-6 flex w-full items-center justify-center gap-3 rounded-xl bg-primary px-6 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-on-primary shadow-sm transition hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                Track my order
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        </form>

                        <div class="mt-7 border-t border-outline-variant/50 pt-6">
                            <p class="text-sm font-medium text-primary">Can't find your tracking code?</p>
                            <p class="mt-2 text-sm leading-6 text-on-surface-variant">
                                Contact our team for assistance with your order.
                            </p>
                            <a href="{{ url('/contact') }}"
                                class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-secondary transition hover:text-primary">
                                Contact customer care
                                <span class="material-symbols-outlined text-lg">north_east</span>
                            </a>
                        </div>
                    </section>

                    {{-- Supporting panel --}}
                    <aside class="space-y-5">
                        <div class="reveal reveal-up overflow-hidden rounded-2xl bg-primary p-7 text-on-primary sm:p-9"
                            style="transition-delay: 90ms">
                            <span class="material-symbols-outlined text-4xl text-secondary">redeem</span>

                            <p class="mt-7 font-label-uppercase text-xs tracking-[0.22em] text-secondary">
                                THE {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} EXPERIENCE
                            </p>

                            <h2 class="mt-3 font-display-hero text-3xl leading-tight sm:text-4xl">
                                Every detail matters.
                            </h2>

                            <p class="mt-4 text-sm leading-7 text-on-primary/75">
                                Your order is part of your {{ config('storefront.store_name', 'BENAZIR') }} story. We want every step,
                                from browsing our collection to receiving your pieces, to feel considered.
                            </p>

                            <div class="mt-8 border-t border-white/15 pt-6">
                                <p class="text-xs tracking-[0.2em] text-on-primary/70">
                                    LAHORE · LONDON · NEW YORK
                                </p>
                            </div>
                        </div>

                        <div class="reveal reveal-up rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-6"
                            style="transition-delay: 180ms">
                            <h3 class="font-display-hero text-xl text-primary">How tracking works</h3>

                            <div class="mt-6 space-y-5">
                                <div class="flex items-start gap-4">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-secondary/10 text-secondary">
                                        <span class="material-symbols-outlined">receipt_long</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-primary">1. Find your order</p>
                                        <p class="mt-1 text-xs leading-6 text-on-surface-variant">
                                            Enter the private code provided after checkout.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-secondary/10 text-secondary">
                                        <span class="material-symbols-outlined">inventory_2</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-primary">2. Review its status</p>
                                        <p class="mt-1 text-xs leading-6 text-on-surface-variant">
                                            See the latest recorded order status and available history.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-secondary/10 text-secondary">
                                        <span class="material-symbols-outlined">local_shipping</span>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-primary">3. Prepare for delivery</p>
                                        <p class="mt-1 text-xs leading-6 text-on-surface-variant">
                                            Review your delivery destination and payment method.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            @else
                {{-- Order overview --}}
                <section
                    class="overflow-hidden rounded-2xl border border-outline-variant/60 bg-surface-container-lowest shadow-sm">

                    <div class="border-b border-outline-variant/50 p-6 sm:p-8 lg:p-10">
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-secondary/10">
                                        <span class="material-symbols-outlined text-2xl text-secondary">receipt_long</span>
                                    </span>
                                    <p class="font-label-uppercase text-xs tracking-[0.2em] text-secondary">
                                        ORDER CONFIRMATION
                                    </p>
                                </div>

                                <h2 class="mt-5 break-words font-display-hero text-3xl text-primary sm:text-4xl">
                                    {{ $order->order_number }}
                                </h2>

                                <p class="mt-3 text-sm text-on-surface-variant">
                                    Placed on
                                    <time datetime="{{ $order->created_at->toAtomString() }}">
                                        {{ $order->created_at->format('F j, Y · g:i A') }}
                                    </time>
                                </p>
                            </div>

                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-wider {{ $statusStyles[$currentStatus] ?? 'border-outline-variant bg-surface-container-low text-on-surface-variant' }}">
                                <span class="material-symbols-outlined text-base">
                                    {{ $statusIcons[$currentStatus] ?? 'info' }}
                                </span>
                                {{ $formatStatus($currentStatus) }}
                            </span>
                        </div>
                    </div>

                    {{-- Status summary --}}
                    @if ($currentStatus !== 'cancelled')
                        @php
                            $steps = [
                                'pending' => ['label' => 'Order placed', 'icon' => 'receipt_long'],
                                'confirmed' => ['label' => 'Confirmed', 'icon' => 'task_alt'],
                                'processing' => ['label' => 'Preparing', 'icon' => 'inventory_2'],
                                'shipped' => ['label' => 'Shipped', 'icon' => 'local_shipping'],
                                'delivered' => ['label' => 'Delivered', 'icon' => 'check_circle'],
                            ];

                            $stepOrder = array_keys($steps);
                            $currentIndex = array_search($currentStatus, $stepOrder, true);
                        @endphp

                        <div class="border-b border-outline-variant/50 bg-surface-container-low px-5 py-8 sm:px-8 lg:px-10">
                            <h3 class="font-display-hero text-2xl text-primary">Your order journey</h3>
                            <p class="mt-2 text-sm text-on-surface-variant">
                                Follow the latest recorded stage of your order.
                            </p>

                            <ol class="mt-8 grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-5">
                                @foreach ($steps as $stepStatus => $step)
                                    @php
                                        $stepIndex = array_search($stepStatus, $stepOrder, true);
                                        $isComplete = $currentIndex !== false && $stepIndex <= $currentIndex;
                                        $isCurrent = $stepStatus === $currentStatus;
                                    @endphp

                                    <li class="relative flex items-start gap-3 sm:flex-col sm:items-center sm:text-center">
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border {{ $isComplete ? 'border-primary bg-primary text-on-primary' : 'border-outline-variant bg-background text-on-surface-variant' }}">
                                            <span class="material-symbols-outlined text-xl">
                                                {{ $isComplete ? 'check' : $step['icon'] }}
                                            </span>
                                        </span>

                                        <div class="pt-1 sm:pt-0">
                                            <p
                                                class="text-xs font-semibold {{ $isCurrent ? 'text-primary' : 'text-on-surface-variant' }}">
                                                {{ $step['label'] }}
                                            </p>
                                            @if ($isCurrent)
                                                <p class="mt-1 text-[10px] uppercase tracking-wider text-secondary">
                                                    Current status
                                                </p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @else
                        <div class="border-b border-red-200 bg-red-50 px-6 py-5 sm:px-8">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-2xl text-red-700">info</span>
                                <div>
                                    <h3 class="text-sm font-semibold text-red-900">This order has been cancelled</h3>
                                    <p class="mt-1 text-sm leading-6 text-red-800">
                                        Please contact customer care if you need more information about this order.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Items and order information --}}
                    <div class="grid lg:grid-cols-[minmax(0,1fr)_320px]">
                        <div class="min-w-0 p-6 sm:p-8 lg:p-10">
                            <div class="flex items-end justify-between gap-3">
                                <div>
                                    <p class="font-label-uppercase text-xs tracking-[0.2em] text-secondary">
                                        YOUR SELECTION
                                    </p>
                                    <h3 class="mt-2 font-display-hero text-2xl text-primary">
                                        Order items
                                    </h3>
                                </div>

                                <span class="text-xs text-on-surface-variant">
                                    {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'items' }}
                                </span>
                            </div>

                            <div class="mt-6 divide-y divide-outline-variant/50">
                                @forelse ($order->items as $item)
                                    <article class="reveal reveal-up flex gap-4 py-5 first:pt-0 last:pb-0 sm:gap-5"
                                        style="transition-delay: {{ ($loop->index % 4) * 90 }}ms">
                                        <div
                                            class="flex h-20 w-16 shrink-0 items-center justify-center rounded-lg border border-outline-variant/50 bg-surface-container-low sm:h-24 sm:w-20">
                                            <span
                                                class="material-symbols-outlined text-2xl text-secondary">checkroom</span>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <h4
                                                class="break-words text-sm font-semibold leading-6 text-primary sm:text-base">
                                                {{ $item->product_name }}
                                            </h4>

                                            <p class="mt-1 text-xs leading-5 text-on-surface-variant">
                                                @if ($item->size)
                                                    Size {{ $item->size }}
                                                @endif

                                                @if ($item->size && $item->color)
                                                    <span class="px-1">·</span>
                                                @endif

                                                {{ $item->color ?: (!$item->size ? 'Standard option' : '') }}
                                            </p>

                                            @if ($item->sku)
                                                <p
                                                    class="mt-1 text-[10px] uppercase tracking-wider text-on-surface-variant/80">
                                                    SKU: {{ $item->sku }}
                                                </p>
                                            @endif

                                            <p class="mt-2 text-xs text-on-surface-variant">
                                                Quantity: {{ $item->quantity }}
                                            </p>
                                        </div>

                                        <div class="shrink-0 text-right">
                                            <p class="text-sm font-semibold text-primary">
                                                {{ $currency }} {{ number_format((float) $item->line_total, 2) }}
                                            </p>
                                        </div>
                                    </article>
                                @empty
                                    <div class="py-8 text-center">
                                        <span
                                            class="material-symbols-outlined text-3xl text-on-surface-variant/60">inventory_2</span>
                                        <p class="mt-3 text-sm text-on-surface-variant">
                                            No item details are available for this order.
                                        </p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Status history --}}
                            <div class="mt-10 border-t border-outline-variant/50 pt-8">
                                <p class="font-label-uppercase text-xs tracking-[0.2em] text-secondary">
                                    ORDER UPDATES
                                </p>
                                <h3 class="mt-2 font-display-hero text-2xl text-primary">
                                    Status history
                                </h3>

                                <ol class="mt-6 space-y-0">
                                    @forelse ($order->statusHistories as $history)
                                        <li class="relative flex gap-4 pb-6 last:pb-0">
                                            @if (!$loop->last)
                                                <span
                                                    class="absolute bottom-0 left-[15px] top-8 w-px bg-outline-variant"></span>
                                            @endif

                                            <span
                                                class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-secondary/30 bg-background">
                                                <span
                                                    class="material-symbols-outlined text-base text-secondary">history</span>
                                            </span>

                                            <div class="min-w-0 flex-1 pt-1">
                                                <p class="text-sm font-medium text-primary">
                                                    @if ($history->from_status)
                                                        {{ $formatStatus($history->from_status) }} to
                                                    @endif
                                                    {{ $formatStatus($history->to_status) }}
                                                </p>

                                                <time datetime="{{ $history->created_at->toAtomString() }}"
                                                    class="mt-1 block text-xs text-on-surface-variant">
                                                    {{ $history->created_at->format('M j, Y · g:i A') }}
                                                </time>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="flex gap-4">
                                            <span
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-secondary/30 bg-background">
                                                <span
                                                    class="material-symbols-outlined text-base text-secondary">receipt_long</span>
                                            </span>
                                            <div class="pt-1">
                                                <p class="text-sm font-medium text-primary">Order placed</p>
                                                <time datetime="{{ $order->created_at->toAtomString() }}"
                                                    class="mt-1 block text-xs text-on-surface-variant">
                                                    {{ $order->created_at->format('M j, Y · g:i A') }}
                                                </time>
                                            </div>
                                        </li>
                                    @endforelse
                                </ol>
                            </div>
                        </div>

                        {{-- Sidebar --}}
                        <aside
                            class="border-t border-outline-variant/50 bg-surface-container-low p-6 sm:p-8 lg:border-l lg:border-t-0 lg:p-8">

                            <div>
                                <p class="font-label-uppercase text-xs tracking-[0.2em] text-secondary">
                                    ORDER SUMMARY
                                </p>
                                <h3 class="mt-2 font-display-hero text-2xl text-primary">
                                    Delivery details
                                </h3>
                            </div>

                            <div class="reveal reveal-up mt-6 rounded-xl border border-outline-variant/60 bg-surface-container-lowest p-5">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-xl text-secondary">location_on</span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-primary">
                                            Delivery destination
                                        </p>
                                        <p class="mt-3 text-sm leading-6 text-on-surface">
                                            {{ $order->shipping_city }}
                                            @if ($order->shipping_state)
                                                , {{ $order->shipping_state }}
                                            @endif
                                        </p>
                                        <p class="text-sm leading-6 text-on-surface-variant">
                                            {{ $order->shipping_country }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="reveal reveal-up mt-4 rounded-xl border border-outline-variant/60 bg-surface-container-lowest p-5"
                                style="transition-delay: 90ms">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-xl text-secondary">payments</span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-primary">
                                            Payment method
                                        </p>
                                        <p class="mt-3 text-sm font-medium text-on-surface">
                                            {{ $order->payment_method === 'cash_on_delivery' ? 'Cash on Delivery' : ucwords(str_replace('_', ' ', $order->payment_method ?? 'Not provided')) }}
                                        </p>
                                        <p class="mt-1 text-xs text-on-surface-variant">
                                            Payment status: {{ $formatStatus($order->payment_status ?? 'pending') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="reveal reveal-up mt-4 rounded-xl bg-primary p-5 text-on-primary"
                                style="transition-delay: 180ms">
                                <p class="text-xs uppercase tracking-[0.18em] text-on-primary/70">
                                    Order reference
                                </p>
                                <p class="mt-2 break-words font-display-hero text-xl">
                                    {{ $order->order_number }}
                                </p>
                                <p class="mt-3 text-xs leading-5 text-on-primary/70">
                                    Keep this reference available if you contact our customer care team.
                                </p>
                            </div>

                            <div class="mt-8 border-t border-outline-variant/60 pt-6">
                                <p class="font-label-uppercase text-xs tracking-[0.2em] text-secondary">
                                    NEED ASSISTANCE?
                                </p>
                                <h4 class="mt-2 font-display-hero text-xl text-primary">
                                    We're here to help.
                                </h4>
                                <p class="mt-3 text-sm leading-6 text-on-surface-variant">
                                    Have a question about your delivery or order status? Get in touch with {{ config('storefront.store_name', 'BENAZIR') }}.
                                </p>

                                <a href="{{ url('/contact') }}"
                                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-primary/30 px-4 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-primary transition hover:border-primary hover:bg-primary hover:text-on-primary">
                                    Contact customer care
                                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                </a>
                            </div>
                        </aside>
                    </div>

                    {{-- Footer actions --}}
                    <div
                        class="flex flex-col gap-4 border-t border-outline-variant/50 p-6 sm:flex-row sm:items-center sm:justify-between sm:px-8 lg:px-10">
                        <a href="{{ route('store.home') }}"
                            class="inline-flex items-center justify-center gap-2 text-sm font-medium text-on-surface-variant transition hover:text-primary">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                            Back to the storefront
                        </a>

                        <a href="{{ route('store.orders.track') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-xs font-semibold uppercase tracking-[0.15em] text-on-primary transition hover:bg-primary-container">
                            Track another order
                            <span class="material-symbols-outlined text-lg">search</span>
                        </a>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
