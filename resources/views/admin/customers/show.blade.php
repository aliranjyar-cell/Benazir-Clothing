@extends('layouts.admin.app')

@section('title', 'Customer Profile')

@section('content')
    @php
        $statusStyles = [
            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            'confirmed' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            'processing' => 'bg-violet-50 text-violet-700 ring-violet-600/20',
            'shipped' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
            'delivered' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        ];

        $paymentStyles = [
            'unpaid' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            'failed' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            'partially_refunded' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
            'refunded' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
        ];

        $totalOrders = $customer->orders_count ?? $customer->orders->count();
        $paidSpending = (float) ($customer->lifetime_spend ?? 0);
        $averagePaidOrder = $totalOrders > 0 ? $paidSpending / $totalOrders : 0;
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Breadcrumb and heading --}}
        <div>
            <a href="{{ route('admin.customers.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-muted transition hover:text-ink">
                <span aria-hidden="true">&larr;</span>
                Back to customers
            </a>



            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-lilac">
                        Customer relationship management
                    </p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight text-ink">
                        Customer profile
                    </h1>
                    <p class="mt-1 text-sm text-muted">
                        Customer #{{ $customer->id }} · Joined
                        {{ $customer->created_at?->format('d M Y') ?? 'Date unavailable' }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.customers.edit', $customer) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit Customer
                    </a>
                    <span
                        class="inline-flex w-fit items-center rounded-full bg-mint/30 px-3 py-1.5 text-xs font-semibold text-ink">
                        Registered customer
                    </span>
                </div>

            </div>
        </div>

        {{-- Customer identity --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="h-2 bg-gradient-to-r from-ink via-lilac to-mint"></div>

            <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:p-7">
                <div
                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-lilac/20 text-2xl font-semibold text-ink">
                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($customer->name ?: 'C', 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <h2 class="break-words text-xl font-semibold text-ink">
                        {{ $customer->name }}
                    </h2>

                    <div class="mt-2 flex flex-col gap-2 text-sm text-muted sm:flex-row sm:flex-wrap sm:gap-x-5">
                        <span class="break-all">{{ $customer->email }}</span>
                        <span>{{ $customer->phone ?: 'No phone provided' }}</span>
                    </div>
                </div>

                <div class="rounded-xl bg-ivory px-4 py-3 sm:min-w-36">
                    <p class="text-xs font-medium text-muted">Lifetime paid spend</p>
                    <p class="mt-1 text-lg font-semibold tabular-nums text-ink">
                        Rs {{ number_format($paidSpending, 2) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Customer metrics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-muted">Total orders</p>
                <p class="mt-3 text-2xl font-semibold tabular-nums text-ink">
                    {{ number_format($totalOrders) }}
                </p>
                <p class="mt-1 text-xs text-muted">All order statuses</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-muted">Paid spending</p>
                <p class="mt-3 text-2xl font-semibold tabular-nums text-ink">
                    Rs {{ number_format($paidSpending, 2) }}
                </p>
                <p class="mt-1 text-xs text-muted">Orders marked as paid</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-muted">Average paid spend</p>
                <p class="mt-3 text-2xl font-semibold tabular-nums text-ink">
                    Rs {{ number_format($averagePaidOrder, 2) }}
                </p>
                <p class="mt-1 text-xs text-muted">Paid spend divided by all orders</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-muted">Most recent order</p>
                <p class="mt-3 text-lg font-semibold text-ink">
                    @if ($customer->orders->isNotEmpty())
                        {{ $customer->orders->first()->created_at?->format('d M Y') ?? '—' }}
                    @else
                        —
                    @endif
                </p>
                <p class="mt-1 text-xs text-muted">Based on linked orders</p>
            </div>
        </div>

        {{-- Contact and account details --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-base font-semibold text-ink">Contact information</h2>
                <p class="mt-1 text-sm text-muted">Customer details on record.</p>

                <dl class="mt-5 space-y-5">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-muted">Full name</dt>
                        <dd class="mt-1 break-words text-sm font-medium text-ink">{{ $customer->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-muted">Email address</dt>
                        <dd class="mt-1 break-all text-sm font-medium text-ink">{{ $customer->email }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-muted">Phone number</dt>
                        <dd class="mt-1 text-sm font-medium text-ink">
                            {{ $customer->phone ?: 'Not provided' }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6 lg:col-span-2">
                <h2 class="text-base font-semibold text-ink">Account information</h2>
                <p class="mt-1 text-sm text-muted">Registration and account activity.</p>

                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="rounded-xl bg-ivory/70 p-4">
                        <p class="text-xs font-medium uppercase tracking-wider text-muted">Customer ID</p>
                        <p class="mt-2 text-lg font-semibold text-ink">#{{ $customer->id }}</p>
                    </div>

                    <div class="rounded-xl bg-ivory/70 p-4">
                        <p class="text-xs font-medium uppercase tracking-wider text-muted">Registration date</p>
                        <p class="mt-2 text-lg font-semibold text-ink">
                            {{ $customer->created_at?->format('d M Y') ?? 'Unavailable' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-ivory/70 p-4">
                        <p class="text-xs font-medium uppercase tracking-wider text-muted">Email verification</p>
                        <p
                            class="mt-2 text-sm font-semibold {{ $customer->email_verified_at ? 'text-emerald-700' : 'text-muted' }}">
                            {{ $customer->email_verified_at ? 'Verified' : 'Not verified' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-ivory/70 p-4">
                        <p class="text-xs font-medium uppercase tracking-wider text-muted">Customer type</p>
                        <p class="mt-2 text-sm font-semibold text-ink">
                            @if ($totalOrders >= 2)
                                Repeat customer
                            @elseif($totalOrders === 1)
                                Existing customer
                            @else
                                No orders yet
                            @endif
                        </p>
                    </div>
                </div>
            </section>
        </div>

        {{-- Order history --}}
        <section class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-line p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <h2 class="text-base font-semibold text-ink">Order history</h2>
                    <p class="mt-1 text-sm text-muted">
                        Orders linked to this registered customer.
                    </p>
                </div>

                <span class="w-fit rounded-lg bg-ivory px-3 py-1.5 text-sm font-medium text-ink">
                    {{ $customer->orders->count() }}
                    {{ \Illuminate\Support\Str::plural('order', $customer->orders->count()) }}
                </span>
            </div>

            @if ($customer->orders->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left">
                        <thead class="bg-ivory/70">
                            <tr class="border-b border-line">
                                <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Order</th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Date</th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Items</th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Order
                                    status</th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Payment
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Total</th>
                                <th
                                    class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-muted">
                                    Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-line">
                            @foreach ($customer->orders as $order)
                                <tr class="transition hover:bg-ivory/40">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-ink">
                                            {{ $order->order_number }}
                                        </p>
                                        <p class="mt-1 text-xs text-muted">ID #{{ $order->id }}</p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-muted">
                                        {{ $order->created_at?->format('d M Y') ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-ink">
                                        {{ $order->items_count ?? $order->items->count() }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize ring-1 ring-inset {{ $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20' }}">
                                            {{ str_replace('_', ' ', $order->status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize ring-1 ring-inset {{ $paymentStyles[$order->payment_status] ?? 'bg-slate-100 text-slate-700 ring-slate-600/20' }}">
                                            {{ str_replace('_', ' ', $order->payment_status) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-sm font-semibold tabular-nums text-ink">
                                        Rs {{ number_format((float) $order->grand_total, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-line px-3 py-2 text-sm font-medium text-ink transition hover:border-lilac hover:bg-lilac/10">
                                            View order
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-14 text-center">
                    <span
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-ivory text-xl text-muted">
                        <span aria-hidden="true">&#8212;</span>
                    </span>
                    <h3 class="mt-4 text-base font-semibold text-ink">No orders yet</h3>
                    <p class="mx-auto mt-1 max-w-md text-sm text-muted">
                        This customer account has no orders linked to it. Guest orders are not included in this profile.
                    </p>
                </div>
            @endif

            <div class="border-t border-line px-5 py-4 sm:px-6">
                <a href="{{ route('admin.customers.index') }}"
                    class="text-sm font-medium text-ink transition hover:text-lilac">
                    &larr; Return to customer directory
                </a>
            </div>
        </section>

    </div>
@endsection
