@extends('layouts.admin.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
    @php
        $statusStyles = [
            'pending' => 'bg-amber-50 text-amber-800 ring-amber-200',
            'confirmed' => 'bg-violet-50 text-violet-800 ring-violet-200',
            'processing' => 'bg-sky-50 text-sky-800 ring-sky-200',
            'shipped' => 'bg-indigo-50 text-indigo-800 ring-indigo-200',
            'delivered' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
            'cancelled' => 'bg-rose-50 text-rose-800 ring-rose-200',
        ];

        $paymentStyles = [
            'unpaid' => 'bg-amber-50 text-amber-800 ring-amber-200',
            'paid' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
            'failed' => 'bg-rose-50 text-rose-800 ring-rose-200',
            'partially_refunded' => 'bg-orange-50 text-orange-800 ring-orange-200',
            'refunded' => 'bg-slate-100 text-slate-700 ring-slate-200',
        ];

        $formatLabel = fn($value) => str($value)->replace('_', ' ')->title();

        $transitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ];

        $nextStatuses = $transitions[$order->status] ?? [];

        $canUpdatePayment = in_array($order->payment_status, ['unpaid', 'failed'], true);
    @endphp

    <div class="space-y-6">

        {{-- Breadcrumb and heading --}}
        <div class="space-y-4">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-ink/55 transition hover:text-ink">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m15 18-6-6 6-6" />
                    <path d="M9 12h11" />
                </svg>
                Back to orders
            </a>

            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-[0.16em] text-ink/45">
                        Order management
                        <span class="text-ink/25">/</span>
                        {{ $order->order_number }}
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-semibold tracking-tight text-ink sm:text-[28px]">
                            {{ $order->order_number }}
                        </h1>

                        <span
                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize ring-1 ring-inset {{ $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                            {{ $formatLabel($order->status) }}
                        </span>
                    </div>

                    <p class="mt-1.5 text-sm text-ink/55">
                        Placed {{ $order->created_at->format('d M Y') }} at {{ $order->created_at->format('h:i A') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-2 rounded-xl border border-black/5 bg-white px-3.5 py-2.5 text-sm text-ink/65 shadow-sm">
                        <span
                            class="h-2 w-2 rounded-full {{ $order->payment_status === 'paid' ? 'bg-emerald-500' : ($order->payment_status === 'failed' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                        Payment: <span class="font-semibold text-ink">{{ $formatLabel($order->payment_status) }}</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Feedback --}}
        {{-- @if (session('success'))
            <div role="status"
                class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <path d="m5 12 4 4L19 6" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif --}}

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Please review the following.</p>
                <ul class="mt-1 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main grid --}}
        <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-3">

            {{-- Left column --}}
            <div class="space-y-5 xl:col-span-2">

                {{-- Items --}}
                <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-black/5 px-4 py-4 sm:px-5">
                        <div>
                            <h2 class="text-base font-semibold text-ink">Order items</h2>
                            <p class="mt-0.5 text-xs text-ink/50">{{ $order->items->count() }}
                                {{ \Illuminate\Support\Str::plural('line item', $order->items->count()) }}</p>
                        </div>
                        <span
                            class="rounded-lg bg-ivory px-2.5 py-1.5 text-xs font-medium text-ink/65">{{ $order->currency }}</span>
                    </div>

                    @if ($order->items->isNotEmpty())
                        <div class="divide-y divide-black/5">
                            @foreach ($order->items as $item)
                                <div class="flex gap-3 px-4 py-4 sm:gap-4 sm:px-5">
                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-black/5 bg-ivory text-ink/45">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path d="M6 8h12l1 12H5L6 8Z" />
                                            <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="break-words text-sm font-semibold text-ink">{{ $item->product_name }}</p>

                                        <div class="mt-1.5 flex flex-wrap gap-x-3 gap-y-1 text-xs text-ink/50">
                                            @if ($item->sku)
                                                <span>SKU: {{ $item->sku }}</span>
                                            @endif
                                            @if ($item->size)
                                                <span>Size: {{ $item->size }}</span>
                                            @endif
                                            @if ($item->color)
                                                <span>Color: {{ $item->color }}</span>
                                            @endif
                                        </div>

                                        @if ($item->product_variant_id && !$item->variant)
                                            <p class="mt-1.5 text-xs text-rose-700">The linked variant is no longer
                                                available.</p>
                                        @endif

                                        <p class="mt-2 text-xs text-ink/60">
                                            {{ number_format((float) $item->unit_price, 2) }} × {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <div class="shrink-0 text-right">
                                        <p class="text-sm font-semibold tabular-nums text-ink">
                                            {{ number_format((float) $item->line_total, 2) }}
                                        </p>
                                        <p class="mt-1 text-[11px] text-ink/45">Line total</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-10 text-center">
                            <p class="text-sm font-medium text-ink">No items are attached to this order.</p>
                            <p class="mt-1 text-xs text-ink/50">Review the order data before changing its status.</p>
                        </div>
                    @endif

                    {{-- Totals --}}
                    <div class="border-t border-black/5 bg-ivory/45 px-4 py-4 sm:px-5">
                        <div class="ml-auto max-w-sm space-y-2.5 text-sm">
                            <div class="flex justify-between gap-4 text-ink/60">
                                <span>Subtotal</span>
                                <span class="tabular-nums">{{ $order->currency }}
                                    {{ number_format((float) $order->subtotal, 2) }}</span>
                            </div>

                            @if ((float) $order->discount_total > 0)
                                <div class="flex justify-between gap-4 text-emerald-800">
                                    <span>Discount</span>
                                    <span class="tabular-nums">− {{ $order->currency }}
                                        {{ number_format((float) $order->discount_total, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between gap-4 text-ink/60">
                                <span>Shipping</span>
                                <span class="tabular-nums">{{ $order->currency }}
                                    {{ number_format((float) $order->shipping_total, 2) }}</span>
                            </div>

                            @if ((float) $order->tax_total > 0)
                                <div class="flex justify-between gap-4 text-ink/60">
                                    <span>Tax</span>
                                    <span class="tabular-nums">{{ $order->currency }}
                                        {{ number_format((float) $order->tax_total, 2) }}</span>
                                </div>
                            @endif

                            <div
                                class="flex justify-between gap-4 border-t border-black/10 pt-3 text-base font-semibold text-ink">
                                <span>Grand total</span>
                                <span class="tabular-nums">{{ $order->currency }}
                                    {{ number_format((float) $order->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Customer and shipping --}}
                <section class="rounded-2xl border border-black/5 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-lilac/20 text-ink">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21v-2a8 8 0 0 1 16 0v2" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-base font-semibold text-ink">Customer & shipping</h2>
                            <p class="mt-0.5 text-xs text-ink/50">Contact details and delivery address</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-ink/45">Customer</p>
                            <p class="mt-2 text-sm font-semibold text-ink">{{ $order->customer_name }}</p>

                            @if ($order->customer_email)
                                <p class="mt-1 break-all text-sm text-ink/60">{{ $order->customer_email }}</p>
                            @endif

                            <p class="mt-1 text-sm text-ink/60">{{ $order->customer_phone }}</p>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-ink/45">Shipping address
                            </p>
                            <address class="mt-2 space-y-0.5 text-sm not-italic leading-6 text-ink/70">
                                <p>{{ $order->shipping_address_line_1 }}</p>
                                @if ($order->shipping_address_line_2)
                                    <p>{{ $order->shipping_address_line_2 }}</p>
                                @endif
                                <p>
                                    {{ $order->shipping_city }}
                                    @if ($order->shipping_state)
                                        , {{ $order->shipping_state }}
                                    @endif
                                    @if ($order->shipping_postal_code)
                                        {{ $order->shipping_postal_code }}
                                    @endif
                                </p>
                                <p>{{ $order->shipping_country }}</p>
                            </address>
                        </div>
                    </div>

                    @if ($order->customer_note)
                        <div class="mt-5 rounded-xl border border-black/5 bg-ivory/60 p-3.5">
                            <p class="text-xs font-semibold text-ink">Customer note</p>
                            <p class="mt-1.5 whitespace-pre-line text-sm leading-6 text-ink/65">{{ $order->customer_note }}
                            </p>
                        </div>
                    @endif

                    @if ($order->internal_note)
                        <div class="mt-3 rounded-xl border border-violet-200/70 bg-violet-50/50 p-3.5">
                            <p class="text-xs font-semibold text-ink">Internal note</p>
                            <p class="mt-1.5 whitespace-pre-line text-sm leading-6 text-ink/65">{{ $order->internal_note }}
                            </p>
                        </div>
                    @endif
                </section>

                {{-- Order timeline --}}
                <section class="rounded-2xl border border-black/5 bg-white p-4 shadow-sm sm:p-5">
                    <div>
                        <h2 class="text-base font-semibold text-ink">Order activity</h2>
                        <p class="mt-0.5 text-xs text-ink/50">A record of fulfillment status changes</p>
                    </div>

                    <div class="mt-5 space-y-0">
                        <div class="relative flex gap-3 pb-5">
                            <div class="relative flex w-5 shrink-0 justify-center">
                                <span class="absolute top-5 h-full w-px bg-black/10"></span>
                                <span class="relative mt-1 h-2.5 w-2.5 rounded-full bg-lilac ring-4 ring-lilac/15"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-ink">Order created</p>
                                <p class="mt-1 text-xs text-ink/50">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>

                        @forelse ($order->statusHistories as $history)
                            <div class="relative flex gap-3 pb-5 last:pb-0">
                                <div class="relative flex w-5 shrink-0 justify-center">
                                    @if (!$loop->last)
                                        <span class="absolute top-5 h-full w-px bg-black/10"></span>
                                    @endif
                                    <span
                                        class="relative mt-1 h-2.5 w-2.5 rounded-full bg-ink/25 ring-4 ring-ink/5"></span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-ink">
                                        @if ($history->from_status)
                                            {{ $formatLabel($history->from_status) }} <span
                                                class="font-normal text-ink/40">→</span>
                                        @endif
                                        {{ $formatLabel($history->to_status) }}
                                    </p>

                                    <p class="mt-1 text-xs text-ink/50">
                                        {{ $history->created_at?->format('d M Y, h:i A') ?? 'Time unavailable' }}
                                        @if ($history->admin)
                                            <span> · {{ $history->admin->first_name }}
                                                {{ $history->admin->last_name }}</span>
                                        @endif
                                    </p>

                                    @if ($history->note)
                                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-ink/65">
                                            {{ $history->note }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-black/10 bg-ivory/40 px-4 py-3">
                                <p class="text-sm text-ink/55">No status changes have been recorded yet.</p>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- Guest order linking --}}
                @if (session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
                        role="status">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
                        role="alert">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700"
                        role="alert">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="mb-4">
                        <h2 class="text-base font-semibold text-ink">Customer</h2>
                        <p class="mt-1 text-sm text-muted">
                            Link this order to a registered customer profile.
                        </p>
                    </div>

                    @if ($order->user)
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-medium text-ink">{{ $order->user->name }}</p>
                                <p class="text-sm text-muted">{{ $order->user->email }}</p>
                                <p class="mt-1 text-xs text-green-700">Linked customer</p>
                            </div>

                            <a href="{{ route('admin.customers.show', $order->user) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-ink transition hover:bg-ivory">
                                <i class="fa-solid fa-user"></i>
                                View Customer
                            </a>
                        </div>
                    @else
                        <div class="mb-4 rounded-lg bg-ivory p-3 text-sm text-muted">
                            This is a guest order. Linking it will add it to the selected customer's order history.
                        </div>

                        <form action="{{ route('admin.orders.link-customer', $order) }}" method="POST"
                            class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="customer_id" class="mb-2 block text-sm font-medium text-ink">
                                    Select Customer
                                </label>

                                <select id="customer_id" name="customer_id" required
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                                    <option value="">Choose a customer</option>

                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
                                            {{ $customer->name }} — {{ $customer->email }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('customer_id')
                                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-lilac focus:ring-offset-2">
                                <i class="fa-solid fa-link"></i>
                                Link Customer
                            </button>
                        </form>
                    @endif
                </div>

            </div>

            {{-- Right column --}}
            <aside class="space-y-5 xl:sticky xl:top-5">

                {{-- Fulfillment status --}}
                <section class="rounded-2xl border border-black/5 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-ink">Fulfillment</h2>
                            <p class="mt-1 text-xs leading-5 text-ink/50">Manage the order's next step.</p>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-lilac/20 text-ink">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7">
                                <path d="M3 7h18M6 3h12M6 11h12M6 15h8M6 19h5" />
                            </svg>
                        </span>
                    </div>

                    <div class="mt-4 rounded-xl bg-ivory/70 p-3.5">
                        <p class="text-[11px] font-medium uppercase tracking-[0.1em] text-ink/45">Current status</p>
                        <p class="mt-1.5 text-sm font-semibold text-ink">{{ $formatLabel($order->status) }}</p>

                        @if ($order->stock_deducted_at)
                            <p class="mt-2 text-xs leading-5 text-emerald-800">
                                Stock deducted {{ $order->stock_deducted_at->format('d M Y, h:i A') }}.
                            </p>
                        @else
                            <p class="mt-2 text-xs leading-5 text-ink/50">
                                Stock has not been deducted for this order.
                            </p>
                        @endif

                        @if ($order->stock_restored_at)
                            <p class="mt-1 text-xs leading-5 text-ink/55">
                                Stock restored {{ $order->stock_restored_at->format('d M Y, h:i A') }}.
                            </p>
                        @endif
                    </div>

                    @if (count($nextStatuses))
                        <form method="POST" action="{{ route('admin.orders.status.update', $order) }}"
                            class="mt-4 space-y-3">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="mb-1.5 block text-xs font-medium text-ink/70">Move order
                                    to</label>
                                <select id="status" name="status" required
                                    class="w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                                    <option value="">Choose next status</option>
                                    @foreach ($nextStatuses as $nextStatus)
                                        <option value="{{ $nextStatus }}" @selected(old('status') === $nextStatus)>
                                            {{ $formatLabel($nextStatus) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="note" class="mb-1.5 block text-xs font-medium text-ink/70">
                                    Note <span class="font-normal text-ink/40">(optional)</span>
                                </label>
                                <textarea id="note" name="note" rows="3" maxlength="1000" placeholder="Add a short internal note…"
                                    class="w-full resize-y rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink placeholder:text-ink/35 focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">{{ old('note') }}</textarea>
                                @error('note')
                                    <p class="mt-1 text-xs text-rose-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-ink/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                                Update status
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M5 12h14m-6-6 6 6-6 6" />
                                </svg>
                            </button>

                            @if (in_array('confirmed', $nextStatuses, true))
                                <p class="text-xs leading-5 text-ink/50">
                                    Confirming this order checks availability and deducts stock in a database transaction.
                                </p>
                            @endif

                            @if (in_array('cancelled', $nextStatuses, true))
                                <p class="text-xs leading-5 text-ink/50">
                                    Cancellation restores stock only when this order previously deducted it. Enter a reason
                                    in the note field.
                                </p>
                            @endif
                        </form>
                    @else
                        <div class="mt-4 rounded-xl border border-black/5 bg-ivory/50 px-3.5 py-3">
                            <p class="text-sm font-medium text-ink">
                                {{ $order->status === 'delivered' ? 'Order completed' : 'Order closed' }}
                            </p>
                            <p class="mt-1 text-xs leading-5 text-ink/55">
                                No further fulfillment transitions are available.
                            </p>
                        </div>
                    @endif
                </section>

                {{-- Payment --}}
                <section class="rounded-2xl border border-black/5 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-ink">Payment</h2>
                            <p class="mt-1 text-xs leading-5 text-ink/50">Record payment verification.</p>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-800">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.7">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="M3 10h18M7 15h3" />
                            </svg>
                        </span>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 rounded-xl border border-black/5 px-3.5 py-3">
                        <div>
                            <p class="text-xs text-ink/50">Payment status</p>
                            <span
                                class="mt-1.5 inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize ring-1 ring-inset {{ $paymentStyles[$order->payment_status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                {{ $formatLabel($order->payment_status) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-ink/50">Order total</p>
                            <p class="mt-1 text-sm font-semibold tabular-nums text-ink">{{ $order->currency }}
                                {{ number_format((float) $order->grand_total, 2) }}</p>
                        </div>
                    </div>

                    @if ($order->payment_method)
                        <div class="mt-3 flex justify-between gap-3 text-xs">
                            <span class="text-ink/50">Method</span>
                            <span class="text-right font-medium text-ink">{{ $order->payment_method }}</span>
                        </div>
                    @endif

                    @if ($order->payment_reference)
                        <div class="mt-2 flex justify-between gap-3 text-xs">
                            <span class="text-ink/50">Reference</span>
                            <span
                                class="max-w-[60%] break-all text-right font-medium text-ink">{{ $order->payment_reference }}</span>
                        </div>
                    @endif

                    @if ($order->paid_at)
                        <div class="mt-2 flex justify-between gap-3 text-xs">
                            <span class="text-ink/50">Paid at</span>
                            <span class="text-right text-ink/70">{{ $order->paid_at->format('d M Y, h:i A') }}</span>
                        </div>
                    @endif

                    @if ($canUpdatePayment)
                        <form method="POST" action="{{ route('admin.orders.payment.update', $order) }}"
                            class="mt-4 space-y-3 border-t border-black/5 pt-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="payment_status" class="mb-1.5 block text-xs font-medium text-ink/70">Record
                                    payment as</label>
                                <select id="payment_status" name="payment_status" required
                                    class="w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                                    <option value="paid" @selected(old('payment_status') === 'paid')>Paid / verified</option>
                                    <option value="failed" @selected(old('payment_status') === 'failed')>Failed</option>
                                </select>
                            </div>

                            <div>
                                <label for="payment_method" class="mb-1.5 block text-xs font-medium text-ink/70">Payment
                                    method</label>
                                <input id="payment_method" name="payment_method"
                                    value="{{ old('payment_method', $order->payment_method) }}" maxlength="50"
                                    placeholder="e.g. Cash on delivery"
                                    class="w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink placeholder:text-ink/35 focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                            </div>

                            <div>
                                <label for="payment_reference"
                                    class="mb-1.5 block text-xs font-medium text-ink/70">Payment reference <span
                                        class="font-normal text-ink/40">(optional)</span></label>
                                <input id="payment_reference" name="payment_reference"
                                    value="{{ old('payment_reference', $order->payment_reference) }}" maxlength="255"
                                    placeholder="Transaction or receipt reference"
                                    class="w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink placeholder:text-ink/35 focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-semibold text-ink transition hover:border-lilac hover:bg-lilac/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                                Save payment status
                            </button>

                            <p class="text-xs leading-5 text-ink/50">
                                Verify the payment externally before marking it paid. This action does not change the
                                fulfillment status.
                            </p>
                        </form>
                    @else
                        <div class="mt-4 rounded-xl bg-ivory/60 px-3.5 py-3">
                            <p class="text-xs leading-5 text-ink/60">
                                This payment has already been processed. Refunds and payment corrections should use a
                                separate audited workflow.
                            </p>
                        </div>
                    @endif
                </section>

                {{-- Inventory record --}}
                <section class="rounded-2xl border border-black/5 bg-white p-4 shadow-sm sm:p-5">
                    <h2 class="text-sm font-semibold text-ink">Inventory record</h2>
                    <p class="mt-1 text-xs leading-5 text-ink/50">Stock changes associated with this order.</p>

                    <div class="mt-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ $order->stock_deducted_at ? 'bg-emerald-100 text-emerald-800' : 'bg-ivory text-ink/40' }}">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="m5 12 4 4L19 6" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-ink">Stock deducted</p>
                                <p class="mt-0.5 text-xs text-ink/50">
                                    {{ $order->stock_deducted_at ? $order->stock_deducted_at->format('d M Y, h:i A') : 'Not deducted' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ $order->stock_restored_at ? 'bg-violet-100 text-violet-800' : 'bg-ivory text-ink/40' }}">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M4 12a8 8 0 1 0 2.3-5.7L4 8" />
                                    <path d="M4 4v4h4" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-ink">Stock restored</p>
                                <p class="mt-0.5 text-xs text-ink/50">
                                    {{ $order->stock_restored_at ? $order->stock_restored_at->format('d M Y, h:i A') : 'Not restored' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

            </aside>
        </div>
    </div>
@endsection
