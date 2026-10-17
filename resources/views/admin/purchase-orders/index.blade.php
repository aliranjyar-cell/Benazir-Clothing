@extends('layouts.admin.app')

@section('title', 'Restock Orders')

@section('content')
    <div class="space-y-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-xs font-semibold uppercase tracking-[.18em] text-muted">Catalogue / Inventory</div>
                <h1 class="text-3xl font-semibold tracking-tight text-ink">Restock orders</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted">Track supplier orders and add delivered quantities to inventory
                    as they arrive.</p>
            </div>
            <a href="{{ route('admin.purchase-orders.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-aubergine px-4 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90">
                <x-admin.icon name="plus" class="h-4 w-4" /> Create purchase order
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ([['label' => 'Awaiting delivery', 'value' => $summary['open'], 'hint' => 'Orders not fully received'], ['label' => 'Partially received', 'value' => $summary['partial'], 'hint' => 'Orders with quantities still due'], ['label' => 'Completed', 'value' => $summary['received'], 'hint' => 'Fully received orders']] as $card)
                <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">{{ $card['label'] }}</p>
                    <p class="mt-4 text-3xl font-semibold tracking-tight text-ink">{{ number_format($card['value']) }}</p>
                    <p class="mt-2 text-xs leading-5 text-muted">{{ $card['hint'] }}</p>
                </div>
            @endforeach
        </div>

        <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
            <form method="GET" action="{{ route('admin.purchase-orders.index') }}"
                class="grid gap-3 border-b border-black/5 p-5 sm:grid-cols-[minmax(220px,1fr)_200px_auto] sm:items-center">
                <input name="search" value="{{ request('search') }}" placeholder="Search reference or supplier"
                    class="min-w-0 rounded-xl border border-black/10 bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition placeholder:text-muted/70 focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                <select name="status"
                    class="rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                    <option value="">All statuses</option>
                    <option value="ordered" @selected(request('status') === 'ordered')>Ordered</option>
                    <option value="partially_received" @selected(request('status') === 'partially_received')>Partially received</option>
                    <option value="received" @selected(request('status') === 'received')>Received</option>
                </select>
                <button
                    class="rounded-xl bg-aubergine px-4 py-2.5 text-sm font-medium text-white transition hover:bg-aubergine/90">Filter</button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-[#FCFAF8] text-[10px] font-semibold uppercase tracking-[.16em] text-muted">
                        <tr>
                            <th class="px-6 py-4">Reference</th>
                            <th class="px-4 py-4">Supplier</th>
                            <th class="px-4 py-4">Expected</th>
                            <th class="px-4 py-4">Lines</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @forelse ($orders as $order)
                            @php
                                $statusClass = match ($order->status) {
                                    'received' => 'bg-emerald-50 text-emerald-700',
                                    'partially_received' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-lilac/20 text-aubergine',
                                };
                            @endphp
                            <tr class="transition hover:bg-[#FCFAF8]">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.purchase-orders.show', $order) }}"
                                        class="font-semibold text-aubergine hover:underline">{{ $order->reference }}</a>
                                </td>
                                <td class="px-4 py-4 text-ink">{{ $order->supplier_name }}</td>
                                <td class="px-4 py-4 text-ink">{{ $order->expected_at?->format('d M Y') ?? 'Not set' }}</td>
                                <td class="px-4 py-4 text-ink">{{ $order->items_count }}</td>
                                <td class="px-4 py-4"><span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-muted">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <span
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-lilac/20 text-aubergine"><x-admin.icon
                                            name="archive" class="h-6 w-6" /></span>
                                    <h2 class="mt-4 font-semibold text-ink">No restock orders yet</h2>
                                    <p class="mx-auto mt-1 max-w-md text-sm text-muted">Create an order to track supplier
                                        deliveries and receive stock into inventory.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="border-t border-black/5 px-5 py-4 sm:px-6">{{ $orders->links() }}</div>
            @endif
        </section>
    </div>
@endsection
