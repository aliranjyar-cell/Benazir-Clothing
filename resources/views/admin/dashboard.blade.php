@extends('layouts.admin.app')

@section('title', 'Dashboard')

@php
$admin = auth()->user();
$chartMax = max($salesChart->pluck('value')->toArray() ?: [1]);
$currency = config('storefront.currency', 'PKR');
@endphp

@section('breadcrumb')
<x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Dashboard'],
    ]" />
@endsection

@section('content')
<x-admin.page-heading
    title="Welcome back, {{ $admin->first_name }}"
    description="Here is the current state of your Punjabi fashion catalogue — products, stock and orders at a glance.">
    <x-slot:actions>
        <span class="inline-flex items-center gap-2 rounded-full border border-black/[0.06] bg-white px-4 py-2 text-xs font-medium text-muted shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-lilac" aria-hidden="true"></span>
            {{ now()->format('D, d M Y') }}
        </span>
    </x-slot:actions>
</x-admin.page-heading>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 xl:grid-cols-4">
    <x-admin.stat-card
        label="Products"
        :value="$stats['products']"
        icon="cube"
        hint="Catalogue items, active and draft" />

    <x-admin.stat-card
        label="Categories"
        :value="$stats['categories']"
        icon="tag"
        hint="Collections organising your catalogue" />

    <x-admin.stat-card
        label="Low stock"
        :value="$stats['lowStock']"
        icon="alert"
        :tone="$stats['lowStock'] > 0 ? 'blush' : 'mint'"
        hint="Variants with {{ $stats['lowStockThreshold'] }} units or fewer" />

    <x-admin.stat-card
        label="Orders"
        :value="$stats['orders']"
        icon="clipboard"
        hint="Paid revenue: {{ number_format($stats['revenue'], 2) }} {{ $currency }}" />
</div>

<div class="mt-5 grid gap-5 lg:grid-cols-3">
    <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card lg:col-span-2">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold tracking-tight text-ink">Quick actions</h2>
                <p class="mt-1 text-xs leading-relaxed text-muted">
                    Jump straight into your catalogue and store operations.
                </p>
            </div>
            <span class="rounded-full bg-lilac-soft px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-lilac-deep">
                live
            </span>
        </div>

        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach ([
            ['label' => 'Add a product', 'description' => 'Create a new salwar suit, saree or kurti listing', 'icon' => 'plus', 'route' => 'products.create'],
            ['label' => 'Browse products', 'description' => 'Search, edit and manage the catalogue', 'icon' => 'cube', 'route' => 'products.index'],
            ['label' => 'Manage inventory', 'description' => 'Update sizes, colours and stock levels', 'icon' => 'archive', 'route' => 'admin.inventory.index'],
            ['label' => 'Review orders', 'description' => 'Process and fulfil customer orders', 'icon' => 'clipboard', 'route' => 'admin.orders.index'],
            ] as $action)
            @if (! empty($action['route']) && Route::has($action['route']))
            <a
                href="{{ route($action['route']) }}"
                class="group flex items-start gap-3 rounded-xl border border-lilac/40 bg-lilac-soft/50 p-4 transition hover:bg-lilac-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-aubergine ring-1 ring-lilac/30">
                    <x-admin.icon name="{{ $action['icon'] }}" class="h-4 w-4" />
                </span>

                <div class="min-w-0">
                    <p class="flex items-center gap-1.5 text-sm font-medium text-ink">
                        {{ $action['label'] }}
                        <x-admin.icon name="chevron-right" class="h-3.5 w-3.5 text-muted transition-transform group-hover:translate-x-0.5" />
                    </p>
                    <p class="mt-0.5 text-xs leading-relaxed text-muted">
                        {{ $action['description'] }}
                    </p>
                </div>
            </a>
            @else
            <div
                class="flex items-start gap-3 rounded-xl border border-dashed border-black/10 bg-ivory/40 p-4"
                aria-disabled="true">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-muted ring-1 ring-black/5">
                    <x-admin.icon name="{{ $action['icon'] }}" class="h-4 w-4" />
                </span>

                <div class="min-w-0">
                    <p class="flex items-center gap-2 text-sm font-medium text-ink/70">
                        {{ $action['label'] }}
                        <span class="rounded-full bg-white px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-muted ring-1 ring-black/5">
                            Soon
                        </span>
                    </p>
                    <p class="mt-0.5 text-xs leading-relaxed text-muted">
                        {{ $action['description'] }}
                    </p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
        <h2 class="text-sm font-semibold tracking-tight text-ink">Catalogue readiness</h2>
        <p class="mt-1 text-xs leading-relaxed text-muted">
            Live checklist based on your current data.
        </p>

        <ul class="mt-4 space-y-3">
            @foreach ([
            ['label' => 'Category structure created', 'done' => $stats['categories'] > 0],
            ['label' => 'First product added', 'done' => $stats['products'] > 0],
            ['label' => 'Variant stock tracked', 'done' => $stats['variants'] > 0],
            ] as $check)
            <li class="flex items-center gap-3 text-sm">
                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full {{ $check['done'] ? 'bg-mint-soft text-mint-deep ring-1 ring-mint/40' : 'bg-ivory text-muted ring-1 ring-black/5' }}">
                    <x-admin.icon name="{{ $check['done'] ? 'check' : 'chevron-right' }}" class="h-3.5 w-3.5" />
                </span>
                <span class="{{ $check['done'] ? 'text-ink' : 'text-muted' }}">
                    {{ $check['label'] }}
                </span>
            </li>
            @endforeach
        </ul>

        <p class="mt-5 rounded-xl bg-ivory/60 px-4 py-3 text-xs leading-relaxed text-muted">
            Steps complete: {{ collect([[$stats['categories'] > 0], [$stats['products'] > 0], [$stats['variants'] > 0]])->filter(fn ($c) => $c[0])->count() }} of 3
        </p>
    </div>
</div>

<div class="mt-5 grid gap-5 xl:grid-cols-[1.6fr_1fr]">
    <section class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold tracking-tight text-ink">Sales overview</h2>
                <p class="mt-1 text-xs leading-relaxed text-muted">Revenue trend for the last six months</p>
            </div>
            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                {{ $currency }}
            </span>
        </div>

        <div class="mt-6 flex h-48 items-end gap-3">
            @foreach ($salesChart as $point)
            @php
            $height = $chartMax > 0 ? max(12, round(($point['value'] / $chartMax) * 100)) : 0;
            @endphp
            <div class="flex flex-1 flex-col items-center gap-2">
                <div class="flex h-32 w-full items-end justify-center">
                    <span
                        class="block w-full rounded-t-xl bg-gradient-to-t from-lilac to-lilac/60 transition-all duration-300"
                        style="height: {{ $height }}%"
                        title="{{ $point['label'] }}: {{ number_format($point['value'], 2) }} {{ $currency }}"></span>
                </div>
                <span class="text-[10px] font-medium uppercase tracking-wider text-muted">{{ $point['label'] }}</span>
            </div>
            @endforeach
        </div>
    </section>

    <aside class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
        <h2 class="text-sm font-semibold tracking-tight text-ink">Store pulse</h2>
        <div class="mt-4 space-y-4">
            <div class="rounded-xl bg-lilac-soft/70 p-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-muted">Revenue</p>
                <p class="mt-2 text-2xl font-semibold tracking-tight text-ink">{{ number_format($stats['revenue'], 2) }}</p>
                <p class="mt-1 text-xs text-muted">Confirmed paid orders</p>
            </div>
            <div class="rounded-xl bg-amber-50 p-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-muted">Pending</p>
                <p class="mt-2 text-2xl font-semibold tracking-tight text-ink">{{ $stats['pending'] }}</p>
                <p class="mt-1 text-xs text-muted">Orders need attention</p>
            </div>
            <div class="rounded-xl bg-mint-soft p-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-muted">Inventory</p>
                <p class="mt-2 text-2xl font-semibold tracking-tight text-ink">{{ $stats['lowStock'] }}</p>
                <p class="mt-1 text-xs text-muted">Variants at or below threshold</p>
            </div>
        </div>
    </aside>
</div>

<div class="mt-5 rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-sm font-semibold tracking-tight text-ink">Recent activity</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-lilac-deep hover:text-lilac">View all orders</a>
    </div>

    @if ($recentOrders->isNotEmpty())
    <div class="mt-4 overflow-hidden rounded-xl border border-black/[0.06]">
        <table class="min-w-full divide-y divide-black/[0.06] text-left text-sm">
            <thead class="bg-ivory/80 text-[11px] font-semibold uppercase tracking-[0.14em] text-muted">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/[0.06] bg-white">
                @foreach ($recentOrders as $order)
                <tr>
                    <td class="px-4 py-3 font-medium text-ink">{{ $order->order_number }}</td>
                    <td class="px-4 py-3 text-muted">{{ $order->customer_name }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-semibold uppercase tracking-wide ring-1 ring-inset
                                    @switch($order->status)
                                            @case('pending') bg-amber-50 text-amber-700 ring-amber-200 @break
                                            @case('confirmed') bg-violet-50 text-violet-700 ring-violet-200 @break
                                            @case('processing') bg-sky-50 text-sky-700 ring-sky-200 @break
                                            @case('shipped') bg-indigo-50 text-indigo-700 ring-indigo-200 @break
                                            @case('delivered') bg-emerald-50 text-emerald-700 ring-emerald-200 @break
                                            @default bg-rose-50 text-rose-700 ring-rose-200 @endswitch">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-ink">{{ number_format((float) $order->grand_total, 2) }} {{ $currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="flex flex-col items-center justify-center px-4 py-10 text-center">
        <span class="grid h-12 w-12 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
            <x-admin.icon name="sparkle" class="h-6 w-6" />
        </span>

        <h3 class="mt-4 text-sm font-semibold text-ink">No activity yet</h3>
        <p class="mt-1 max-w-sm text-xs leading-relaxed text-muted">
            Product changes, new orders and staff actions will appear here as soon as those modules go live.
        </p>
    </div>
    @endif
</div>
@endsection
