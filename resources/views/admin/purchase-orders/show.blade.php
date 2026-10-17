@extends('layouts.admin.app')

@section('title', $purchaseOrder->reference)

@section('content')
    @php
        $statusClass = match ($purchaseOrder->status) {
            'received' => 'bg-emerald-50 text-emerald-700',
            'partially_received' => 'bg-amber-50 text-amber-700',
            default => 'bg-lilac/20 text-aubergine',
        };
    @endphp
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-xs font-semibold uppercase tracking-[.18em] text-muted">Catalogue / Restock orders
                </div>
                <a href="{{ route('admin.purchase-orders.index') }}"
                    class="inline-flex items-center gap-2 text-sm text-muted hover:text-aubergine"><span
                        aria-hidden="true">←</span> Back to restock orders</a>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl font-semibold tracking-tight text-ink">{{ $purchaseOrder->reference }}</h1>
                    <span
                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ str($purchaseOrder->status)->replace('_', ' ')->title() }}</span>
                </div>
                <p class="mt-2 text-sm text-muted">Created {{ $purchaseOrder->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">Supplier</p>
                <p class="mt-3 font-semibold text-ink">{{ $purchaseOrder->supplier_name }}</p>
                @if ($purchaseOrder->supplier_email)
                    <p class="mt-1 break-all text-sm text-muted">{{ $purchaseOrder->supplier_email }}</p>
                @endif
            </div>
            <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">Expected delivery</p>
                <p class="mt-3 font-semibold text-ink">{{ $purchaseOrder->expected_at?->format('d M Y') ?? 'Not set' }}</p>
            </div>
            <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">Ordered units</p>
                <p class="mt-3 font-semibold text-ink">{{ number_format($purchaseOrder->items->sum('quantity_ordered')) }}
                </p>
            </div>
            <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">Received units</p>
                <p class="mt-3 font-semibold text-ink">{{ number_format($purchaseOrder->items->sum('quantity_received')) }}
                </p>
            </div>
        </section>

        @if ($purchaseOrder->notes)
            <div class="rounded-xl border border-black/5 bg-white px-5 py-4 text-sm text-muted">{{ $purchaseOrder->notes }}
            </div>
        @endif

        <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
            <div class="border-b border-black/5 px-5 py-4 sm:px-6">
                <h2 class="font-semibold text-ink">Order lines</h2>
                <p class="mt-1 text-sm text-muted">Received quantities update on-hand stock and create an inventory
                    movement.</p>
            </div>
            <form method="POST" action="{{ route('admin.purchase-orders.receive', $purchaseOrder) }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] text-left text-sm">
                        <thead class="bg-[#FCFAF8] text-[10px] font-semibold uppercase tracking-[.16em] text-muted">
                            <tr>
                                <th class="px-6 py-4">Product / SKU</th>
                                <th class="px-4 py-4">Size / Colour</th>
                                <th class="px-4 py-4">Ordered</th>
                                <th class="px-4 py-4">Received</th>
                                <th class="px-4 py-4">Unit cost</th>
                                <th class="px-6 py-4">Receive now</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @foreach ($purchaseOrder->items as $item)
                                @php($remaining = $item->quantity_ordered - $item->quantity_received)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-ink">{{ $item->product_name }}</p>
                                        <p class="mt-1 text-xs text-muted">{{ $item->sku }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-ink">
                                        {{ $item->size ?: 'One Size' }}{{ $item->color ? ' / ' . $item->color : '' }}</td>
                                    <td class="px-4 py-4 text-ink">{{ number_format($item->quantity_ordered) }}</td>
                                    <td class="px-4 py-4 text-ink">{{ number_format($item->quantity_received) }}</td>
                                    <td class="px-4 py-4 text-ink">Rs {{ number_format((float) $item->unit_cost, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @if ($remaining > 0 && $purchaseOrder->status !== 'received')
                                            <input name="items[{{ $item->id }}][quantity]" type="number"
                                                min="1" max="{{ $remaining }}"
                                                value="{{ old('items.' . $item->id . '.quantity') }}"
                                                placeholder="Up to {{ $remaining }}"
                                                aria-label="Quantity received for {{ $item->sku }}"
                                                class="w-36 rounded-xl border border-black/10 px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                                            @error('items.' . $item->id . '.quantity')
                                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                            @enderror
                                        @else
                                            <span class="text-xs font-semibold text-emerald-700">Complete</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('items')
                    <p class="px-6 pt-4 text-sm text-rose-600">{{ $message }}</p>
                @enderror
                @if ($purchaseOrder->status !== 'received')
                    <div
                        class="flex flex-col gap-3 border-t border-black/5 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <p class="text-xs text-muted">Leave lines blank if they are not part of this delivery. Quantities
                            cannot exceed what remains due.</p>
                        <button
                            class="rounded-xl bg-aubergine px-5 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90">Receive
                            selected quantities</button>
                    </div>
                @endif
            </form>
        </section>
    </div>
@endsection
