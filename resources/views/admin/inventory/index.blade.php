@extends('layouts.admin.app')

@section('title', 'Inventory')

@section('content')
<div class="space-y-7">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 text-xs font-semibold uppercase tracking-[.18em] text-muted">Catalogue / Operations</div>
            <h1 class="text-3xl font-semibold tracking-tight text-ink">Inventory</h1>
            <p class="mt-2 max-w-2xl text-sm text-muted">Monitor stock across every size and colour, and record adjustments with a clear audit trail.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Tracked variants', 'value' => number_format($summary['variants']), 'hint' => 'Individual size and colour combinations', 'icon' => 'fa-cubes', 'tone' => 'lilac'],
            ['label' => 'Units on hand', 'value' => number_format($summary['units']), 'hint' => 'Current total across variants', 'icon' => 'fa-boxes-stacked', 'tone' => 'mint'],
            ['label' => 'Low stock', 'value' => number_format($summary['low_stock']), 'hint' => 'Between 1 and 5 units', 'icon' => 'fa-triangle-exclamation', 'tone' => 'rose'],
            ['label' => 'Out of stock', 'value' => number_format($summary['out_of_stock']), 'hint' => 'Variants with zero units', 'icon' => 'fa-circle-exclamation', 'tone' => 'rose'],
        ] as $card)
            <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">{{ $card['label'] }}</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $card['tone'] === 'mint' ? 'bg-mint/30 text-emerald-700' : ($card['tone'] === 'rose' ? 'bg-rose-50 text-rose-600' : 'bg-lilac/25 text-aubergine') }}">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-semibold tracking-tight text-ink">{{ $card['value'] }}</p>
                <p class="mt-2 text-xs leading-5 text-muted">{{ $card['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
        <div class="border-b border-black/5 p-5 sm:p-6">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <h2 class="font-semibold text-ink">Stock by variant</h2>
                    <p class="mt-1 text-sm text-muted">Each row represents one SKU, size and colour combination.</p>
                </div>
                <form method="GET" action="{{ route('admin.inventory.index') }}" class="grid gap-2 sm:grid-cols-[minmax(200px,1fr)_150px_140px_auto]">
                    <input name="search" value="{{ request('search') }}" placeholder="Search product, SKU, size or colour" class="min-w-0 rounded-xl border border-black/10 bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition placeholder:text-muted/70 focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                    <select name="stock" class="rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        <option value="">All stock levels</option>
                        <option value="out" @selected(request('stock') === 'out')>Out of stock</option>
                        <option value="low" @selected(request('stock') === 'low')>Low stock (1–5)</option>
                        <option value="in" @selected(request('stock') === 'in')>In stock (6+)</option>
                    </select>
                    <select name="status" class="rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                    <button class="rounded-xl bg-aubergine px-4 py-2.5 text-sm font-medium text-white transition hover:bg-aubergine/90">Filter</button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-[#FCFAF8] text-[10px] font-semibold uppercase tracking-[.16em] text-muted">
                    <tr>
                        <th class="px-6 py-4">Product / SKU</th>
                        <th class="px-4 py-4">Size</th>
                        <th class="px-4 py-4">Colour</th>
                        <th class="px-4 py-4">Price</th>
                        <th class="px-4 py-4">Stock</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($query as $variant)
                        @php
                            $stock = (int) $variant->stock_quantity;
                            $stockClass = $stock === 0 ? 'bg-rose-50 text-rose-700' : ($stock <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700');
                        @endphp
                        <tr class="transition hover:bg-[#FCFAF8]">
                            <td class="px-6 py-4">
                                <div class="font-medium text-ink">{{ $variant->product?->name ?? 'Product unavailable' }}</div>
                                <div class="mt-1 text-xs text-muted">{{ $variant->sku }}</div>
                            </td>
                            <td class="px-4 py-4 text-ink">{{ $variant->size ?: 'One Size' }}</td>
                            <td class="px-4 py-4 text-ink">{{ $variant->color ?: '—' }}</td>
                            <td class="px-4 py-4 font-medium text-ink">Rs {{ number_format((float) $variant->price, 2) }}</td>
                            <td class="px-4 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $stockClass }}">{{ number_format($stock) }} units</span></td>
                            <td class="px-4 py-4">
                                @if ($variant->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.inventory.adjust', $variant) }}" class="inline-flex items-center gap-2 rounded-lg border border-lilac/60 bg-lilac/15 px-3 py-2 text-xs font-semibold text-aubergine transition hover:bg-lilac/30">
                                    <i class="fa-solid fa-sliders"></i> Adjust stock
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-lilac/20 text-aubergine"><i class="fa-solid fa-box-open text-lg"></i></span>
                                <h3 class="mt-4 font-semibold text-ink">No inventory variants found</h3>
                                <p class="mx-auto mt-1 max-w-md text-sm text-muted">Add variants to your products first, or adjust the search and filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($query->hasPages())
            <div class="border-t border-black/5 px-5 py-4 sm:px-6">{{ $query->links() }}</div>
        @endif
    </section>

    <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
        <div class="border-b border-black/5 px-5 py-4 sm:px-6">
            <h2 class="font-semibold text-ink">Recent stock movements</h2>
            <p class="mt-1 text-sm text-muted">The latest recorded manual adjustments.</p>
        </div>
        <div class="divide-y divide-black/5">
            @forelse ($movements as $movement)
                <div class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <p class="text-sm font-medium text-ink">{{ $movement->variant?->product?->name ?? 'Product unavailable' }} <span class="font-normal text-muted">· {{ $movement->variant?->sku ?? 'SKU unavailable' }}</span></p>
                        <p class="mt-1 text-xs text-muted">{{ str($movement->type)->replace('_', ' ')->title() }}@if($movement->user) · by {{ $movement->user->name }}@endif @if($movement->reference) · Ref {{ $movement->reference }}@endif</p>
                        @if($movement->notes)<p class="mt-1 text-xs text-muted">{{ $movement->notes }}</p>@endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold {{ $movement->quantity_change > 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }} units</span>
                        <span class="text-xs text-muted">{{ $movement->created_at?->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-sm text-muted">No stock movements recorded yet. Your adjustment history will appear here.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
