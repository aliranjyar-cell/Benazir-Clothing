@extends('layouts.admin.app')

@section('title', 'Orders')

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
        $formatPaymentLabel = fn($order) => $order->payment_method === 'cash_on_delivery' &&
        $order->payment_status === 'unpaid'
            ? 'Pending collection'
            : $formatLabel($order->payment_status);
    @endphp

    <div class="space-y-6">

        {{-- Page heading --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-[0.18em] text-ink/50">
                    <span class="h-1.5 w-1.5 rounded-full bg-lilac"></span>
                    AURELLE / Commerce
                </div>

                <h1 class="text-2xl font-semibold tracking-tight text-ink sm:text-[28px]">
                    Orders
                </h1>

                <p class="mt-1.5 max-w-xl text-sm leading-6 text-ink/60">
                    Manage customer orders, track fulfillment, and keep every order moving.
                </p>
            </div>

            <div
                class="flex items-center gap-2 self-start rounded-xl border border-black/5 bg-white/70 px-3.5 py-2.5 text-sm text-ink/65 shadow-sm sm:self-auto">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-lilac/20 text-ink">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M6 7h12l1 13H5L6 7Z" />
                        <path d="M9 8V6a3 3 0 0 1 6 0v2" />
                    </svg>
                </span>
                <span><span class="font-semibold text-ink">{{ number_format($summary['total']) }}</span> total orders</span>
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div role="status"
                class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8">
                    <path d="m5 12 4 4L19 6" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">We couldn't complete that action.</p>
                <ul class="mt-1 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary --}}
        <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
            <div class="rounded-2xl border border-black/5 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-medium text-ink/55 sm:text-sm">All orders</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-lilac/20 text-ink">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M7 3h10l3 4v14H4V7l3-4Z" />
                            <path d="M4 7h16M9 12h6M9 16h6" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-semibold tracking-tight text-ink">{{ number_format($summary['total']) }}</p>
                <p class="mt-1 text-xs text-ink/45">Orders received</p>
            </div>

            <div class="rounded-2xl border border-amber-200/70 bg-amber-50/60 p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-medium text-ink/60 sm:text-sm">Awaiting confirmation</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/80 text-amber-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 2" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-semibold tracking-tight text-ink">{{ number_format($summary['pending']) }}</p>
                <p class="mt-1 text-xs text-ink/50">Require your attention</p>
            </div>

            <div class="rounded-2xl border border-violet-200/70 bg-violet-50/60 p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-medium text-ink/60 sm:text-sm">In progress</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/80 text-violet-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M3 12h4l3-8 4 16 3-8h4" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-semibold tracking-tight text-ink">{{ number_format($summary['processing']) }}
                </p>
                <p class="mt-1 text-xs text-ink/50">Confirmed or processing</p>
            </div>

            <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50/60 p-4 shadow-sm sm:p-5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-medium text-ink/60 sm:text-sm">Paid revenue</p>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/80 text-emerald-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <path d="M12 2v20M17 6H9a3 3 0 0 0 0 6h6a3 3 0 0 1 0 6H6" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 break-words text-xl font-semibold tracking-tight text-ink sm:text-2xl">
                    {{ number_format($summary['revenue'], 2) }}
                    <span class="text-xs font-medium text-ink/50">PKR</span>
                </p>
                <p class="mt-1 text-xs text-ink/50">Orders marked paid</p>
            </div>
        </div>

        {{-- Orders table --}}
        <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
            <div class="border-b border-black/5 px-4 py-4 sm:px-5">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-ink">Order management</h2>
                        <p class="mt-0.5 text-xs text-ink/50">Search and filter your customer orders.</p>
                    </div>
                    <span class="mt-2 text-xs text-ink/45 sm:mt-0">
                        {{ $orders->total() }} {{ \Illuminate\Support\Str::plural('result', $orders->total()) }}
                    </span>
                </div>

                <form method="GET" action="{{ route('admin.orders.index') }}"
                    class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-12">
                    <div class="relative md:col-span-4">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/40"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m16 16 4 4" />
                        </svg>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}"
                            placeholder="Order no., customer, email or phone"
                            class="w-full rounded-xl border border-black/10 bg-ivory/50 py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-ink/40 focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                    </div>

                    <div class="md:col-span-3">
                        <select name="status"
                            class="w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                            <option value="">All order statuses</option>
                            @foreach (\App\Models\Order::STATUSES as $status)
                                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                    {{ $formatLabel($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <select name="payment_status"
                            class="w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                            <option value="">All payments</option>
                            @foreach (\App\Models\Order::PAYMENT_STATUSES as $paymentStatus)
                                <option value="{{ $paymentStatus }}" @selected(($filters['payment_status'] ?? '') === $paymentStatus)>
                                    {{ $formatLabel($paymentStatus) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2 md:col-span-3">
                        <div>
                            <label for="date_from" class="mb-1 block text-xs font-medium text-ink/55">From</label>
                            <input id="date_from" type="date" name="date_from"
                                value="{{ $filters['date_from'] ?? '' }}"
                                class="w-full min-w-0 rounded-xl border border-black/10 bg-white px-2 py-2.5 text-sm text-ink focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                        </div>
                        <div>
                            <label for="date_to" class="mb-1 block text-xs font-medium text-ink/55">To</label>
                            <input id="date_to" type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                class="w-full min-w-0 rounded-xl border border-black/10 bg-white px-2 py-2.5 text-sm text-ink focus:border-lilac focus:outline-none focus:ring-2 focus:ring-lilac/30">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 md:col-span-12">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-ink px-4 py-2.5 text-sm font-medium text-white transition hover:bg-ink/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                            Filter
                        </button>
                        @if (count(array_filter($filters, fn($value) => filled($value))) > 0)
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-black/10 px-3 py-2.5 text-sm font-medium text-ink/70 transition hover:bg-ivory"
                                aria-label="Clear filters" title="Clear filters">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="M4 4l16 16M20 4 4 20" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($orders->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left">
                        <thead class="bg-ivory/65">
                            <tr
                                class="border-b border-black/5 text-[11px] font-semibold uppercase tracking-[0.12em] text-ink/50">
                                <th class="px-5 py-3.5">Order</th>
                                <th class="px-4 py-3.5">Customer</th>
                                <th class="px-4 py-3.5">Date</th>
                                <th class="px-4 py-3.5">Items</th>
                                <th class="px-4 py-3.5">Total</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5">Payment</th>
                                <th class="px-5 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach ($orders as $order)
                                <tr class="transition hover:bg-ivory/40">
                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="font-semibold text-ink transition hover:text-violet-800">
                                            {{ $order->order_number }}
                                        </a>
                                        <p class="mt-1 text-[11px] text-ink/45">ID #{{ $order->id }}</p>
                                    </td>

                                    <td class="px-4 py-4">
                                        <p class="max-w-[190px] truncate text-sm font-medium text-ink">
                                            {{ $order->customer_name }}</p>
                                        <p class="mt-1 max-w-[190px] truncate text-xs text-ink/50">
                                            {{ $order->customer_email ?: $order->customer_phone }}</p>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        <p class="text-sm text-ink">{{ $order->created_at->format('d M Y') }}</p>
                                        <p class="mt-1 text-xs text-ink/45">{{ $order->created_at->format('h:i A') }}</p>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-ink/70">
                                        {{ $order->items_count }}
                                        <span
                                            class="text-xs text-ink/45">{{ \Illuminate\Support\Str::plural('item', $order->items_count) }}</span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        <p class="text-sm font-semibold tabular-nums text-ink">{{ $order->currency }}
                                            {{ number_format((float) $order->grand_total, 2) }}</p>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-medium capitalize ring-1 ring-inset {{ $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                            {{ $formatLabel($order->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-medium capitalize ring-1 ring-inset {{ $paymentStyles[$order->payment_status] ?? 'bg-slate-50 text-slate-700 ring-slate-200' }}">
                                            {{ $formatPaymentLabel($order) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-black/10 bg-white px-3 py-2 text-xs font-semibold text-ink transition hover:border-lilac hover:bg-lilac/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac">
                                            View order
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path d="M5 12h14m-6-6 6 6-6 6" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="flex flex-col gap-3 border-t border-black/5 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                    <p class="text-xs text-ink/50">
                        Showing <span class="font-semibold text-ink">{{ $orders->firstItem() }}</span>
                        to <span class="font-semibold text-ink">{{ $orders->lastItem() }}</span>
                        of <span class="font-semibold text-ink">{{ $orders->total() }}</span> orders
                    </p>
                    <div class="orders-pagination">
                        {{ $orders->onEachSide(1)->links() }}
                    </div>
                </div>
            @else
                <div class="px-5 py-16 text-center sm:py-20">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-lilac/15 text-ink">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <path d="M7 3h10l3 4v14H4V7l3-4Z" />
                            <path d="M4 7h16M9 12h6M9 16h4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-ink">No orders found</h3>
                    <p class="mx-auto mt-1.5 max-w-sm text-sm leading-6 text-ink/55">
                        There are no orders matching these filters. Try another search or clear the filters.
                    </p>
                    @if (count(array_filter($filters, fn($value) => filled($value))) > 0)
                        <a href="{{ route('admin.orders.index') }}"
                            class="mt-5 inline-flex items-center rounded-xl bg-ink px-4 py-2.5 text-sm font-medium text-white transition hover:bg-ink/90">
                            Clear filters
                        </a>
                    @endif
                </div>
            @endif
        </section>
    </div>
@endsection
