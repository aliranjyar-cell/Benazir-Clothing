@extends('layouts.admin.app')

@section('title', 'Customers')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Page heading --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-lilac">
                    Customer relationship management
                </p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight text-ink">
                    Customers
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Understand your customers, their purchases, and their relationship with AURELLE.
                </p>
            </div>

            <div
                class="flex items-center gap-2 rounded-xl border border-line bg-white px-4 py-3 text-sm text-muted shadow-sm">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-ivory text-ink">
                    <i class="fa-solid fa-users"></i>
                </span>
                <span>Customer directory</span>
                <a href="{{ route('admin.customers.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                    <i class="fa-solid fa-user-plus"></i>
                    Add Customer
                </a>
            </div>

        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted">Total customers</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-lilac/20 text-ink">
                        <i class="fa-solid fa-users"></i>
                    </span>
                </div>
                <p class="mt-4 text-2xl font-semibold tabular-nums text-ink">
                    {{ number_format($stats['total']) }}
                </p>
                <p class="mt-1 text-xs text-muted">Registered customer accounts</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted">Customers with orders</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-mint/30 text-ink">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </span>
                </div>
                <p class="mt-4 text-2xl font-semibold tabular-nums text-ink">
                    {{ number_format($stats['with_orders']) }}
                </p>
                <p class="mt-1 text-xs text-muted">Have placed at least one order</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted">Repeat customers</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose/10 text-rose">
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                    </span>
                </div>
                <p class="mt-4 text-2xl font-semibold tabular-nums text-ink">
                    {{ number_format($stats['repeat']) }}
                </p>
                <p class="mt-1 text-xs text-muted">Have placed two or more orders</p>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-muted">Paid order revenue</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-ivory text-ink">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                </div>
                <p class="mt-4 text-2xl font-semibold tabular-nums text-ink">
                    Rs {{ number_format((float) $stats['paid_revenue'], 2) }}
                </p>
                <p class="mt-1 text-xs text-muted">From orders linked to registered customers</p>
            </div>

        </div>

        {{-- Customer directory --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

            <div class="border-b border-line p-5 sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-ink">Customer directory</h2>
                        <p class="mt-1 text-sm text-muted">
                            Search customer details and open a profile to view their order history.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('admin.customers.index') }}"
                        class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">

                        <div class="relative min-w-0 flex-1 sm:min-w-64">
                            <i
                                class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted"></i>
                            <input type="search" name="search" value="{{ $search }}"
                                placeholder="Name, email, or phone"
                                class="w-full rounded-xl border border-line bg-ivory/50 py-2.5 pl-9 pr-3 text-sm text-ink outline-none transition placeholder:text-muted focus:border-lilac focus:ring-2 focus:ring-lilac/20">
                        </div>

                        <select name="type"
                            class="rounded-xl border border-line bg-white px-3 py-2.5 text-sm text-ink outline-none focus:border-lilac focus:ring-2 focus:ring-lilac/20">
                            <option value="all" @selected($type === 'all')>All customers</option>
                            <option value="with_orders" @selected($type === 'with_orders')>With orders</option>
                            <option value="repeat" @selected($type === 'repeat')>Repeat customers</option>
                        </select>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-ink px-4 py-2.5 text-sm font-medium text-white transition hover:opacity-90">
                            <i class="fa-solid fa-filter text-xs"></i>
                            Apply
                        </button>

                        @if ($search !== '' || $type !== 'all')
                            <a href="{{ route('admin.customers.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-line px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left">
                    <thead class="bg-ivory/70">
                        <tr class="border-b border-line">
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Customer</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Contact</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Orders</th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Paid spending
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-muted">Last order
                            </th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-muted">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-line">
                        @forelse($customers as $customer)
                            <tr class="transition hover:bg-ivory/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-lilac/20 text-sm font-semibold text-ink">
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($customer->name ?: 'C', 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-medium text-ink">{{ $customer->name }}</p>
                                            <p class="mt-0.5 text-xs text-muted">
                                                Customer #{{ $customer->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-ink">{{ $customer->email }}</p>
                                    <p class="mt-1 text-xs text-muted">
                                        {{ $customer->phone ?: 'No phone provided' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex min-w-8 items-center justify-center rounded-lg bg-ivory px-2.5 py-1.5 text-sm font-semibold tabular-nums text-ink">
                                        {{ $customer->orders_count }}
                                    </span>
                                    @if ($customer->orders_count >= 2)
                                        <p class="mt-1 text-xs font-medium text-emerald-700">Repeat customer</p>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm font-semibold tabular-nums text-ink">
                                        Rs {{ number_format((float) ($customer->lifetime_spend ?? 0), 2) }}
                                    </p>
                                    <p class="mt-1 text-xs text-muted">Paid orders</p>
                                </td>

                                <td class="px-5 py-4 text-sm text-muted">
                                    @if ($customer->orders_max_created_at)
                                        {{ \Illuminate\Support\Carbon::parse($customer->orders_max_created_at)->format('d M Y') }}
                                    @else
                                        <span class="text-muted">No orders yet</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.customers.show', $customer) }}"
                                        class="inline-flex items-center gap-2 rounded-lg border border-line px-3 py-2 text-sm font-medium text-ink transition hover:border-lilac hover:bg-lilac/10">
                                        View profile
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <span
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-ivory text-xl text-muted">
                                        <i class="fa-solid fa-user-group"></i>
                                    </span>
                                    <h3 class="mt-4 text-base font-semibold text-ink">No customers found</h3>
                                    <p class="mx-auto mt-1 max-w-md text-sm text-muted">
                                        @if ($search !== '' || $type !== 'all')
                                            Try changing your search or filter to find customers.
                                        @else
                                            Registered customers will appear here when customer accounts exist.
                                        @endif
                                    </p>
                                    @if ($search !== '' || $type !== 'all')
                                        <a href="{{ route('admin.customers.index') }}"
                                            class="mt-4 inline-flex items-center rounded-lg bg-ink px-4 py-2 text-sm font-medium text-white hover:opacity-90">
                                            Clear filters
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages() || $customers->total() > 0)
                <div
                    class="flex flex-col gap-3 border-t border-line px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-muted">
                        Showing
                        <span class="font-medium text-ink">{{ $customers->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-medium text-ink">{{ $customers->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-medium text-ink">{{ $customers->total() }}</span>
                        customers
                    </p>

                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
