@extends('layouts.admin.app')

@section('title', 'Adjust Inventory')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <div class="mb-2 text-xs font-semibold uppercase tracking-[.18em] text-muted">Catalogue / Inventory</div>
        <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 text-sm text-muted hover:text-aubergine"><i class="fa-solid fa-arrow-left"></i> Back to inventory</a>
        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-ink">Adjust stock</h1>
        <p class="mt-2 text-sm text-muted">Record a stock change with a reason so your inventory history stays traceable.</p>
    </div>

    <div class="grid gap-5 lg:grid-cols-[1fr_300px]">
        <form method="POST" action="{{ route('admin.inventory.adjust.store', $variant) }}" class="space-y-5 rounded-2xl border border-black/5 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            <div>
                <h2 class="font-semibold text-ink">Adjustment details</h2>
                <p class="mt-1 text-sm text-muted">Only submit a change that reflects a real stock event.</p>
            </div>

            <div>
                <label for="type" class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Reason <span class="text-rose-600">*</span></label>
                <select id="type" name="type" required class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                    <option value="received" @selected(old('type') === 'received')>Stock received</option>
                    <option value="correction" @selected(old('type') === 'correction')>Stock correction</option>
                    <option value="damaged" @selected(old('type') === 'damaged')>Damaged / lost</option>
                    <option value="returned" @selected(old('type') === 'returned')>Customer return</option>
                </select>
                @error('type')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="quantity_change" class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Quantity change <span class="text-rose-600">*</span></label>
                <input id="quantity_change" name="quantity_change" type="number" step="1" required value="{{ old('quantity_change') }}" placeholder="e.g. 10 or -2" class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink placeholder:text-muted/70 focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                <p class="mt-1.5 text-xs text-muted">Use a positive number to add stock and a negative number to remove stock. Stock cannot become negative.</p>
                @error('quantity_change')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="reference" class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Reference (optional)</label>
                <input id="reference" name="reference" value="{{ old('reference') }}" placeholder="e.g. supplier invoice or return reference" class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink placeholder:text-muted/70 focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                @error('reference')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="notes" class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Notes (optional)</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Add context for this adjustment..." class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink placeholder:text-muted/70 focus:border-lilac focus:ring-2 focus:ring-lilac/30">{{ old('notes') }}</textarea>
                @error('notes')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-black/5 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.inventory.index') }}" class="rounded-xl border border-black/10 px-5 py-3 text-center text-sm font-medium text-ink hover:bg-stone-50">Cancel</a>
                <button class="rounded-xl bg-aubergine px-5 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90"><i class="fa-solid fa-check mr-2"></i>Save adjustment</button>
            </div>
        </form>

        <aside class="space-y-4">
            <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[.14em] text-muted">Selected variant</p>
                <h2 class="mt-3 text-lg font-semibold text-ink">{{ $variant->product?->name ?? 'Product unavailable' }}</h2>
                <p class="mt-1 text-sm text-muted">{{ $variant->sku }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-lilac/20 px-3 py-1 text-xs font-medium text-aubergine">{{ $variant->size ?: 'One Size' }}</span>
                    @if($variant->color)<span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-700">{{ $variant->color }}</span>@endif
                </div>
                <div class="mt-5 border-t border-black/5 pt-4">
                    <p class="text-xs text-muted">Current stock</p>
                    <p class="mt-1 text-3xl font-semibold text-ink">{{ number_format((int) $variant->stock_quantity) }} <span class="text-sm font-normal text-muted">units</span></p>
                    @if((int) $variant->stock_quantity === 0)
                        <p class="mt-2 text-xs font-medium text-rose-600">Out of stock</p>
                    @elseif((int) $variant->stock_quantity <= $lowStockThreshold)
                        <p class="mt-2 text-xs font-medium text-amber-700">Low stock</p>
                    @else
                        <p class="mt-2 text-xs font-medium text-emerald-700">Healthy stock level</p>
                    @endif
                </div>
            </div>
            <div class="rounded-2xl border border-lilac/30 bg-lilac/10 p-5">
                <div class="flex items-center gap-2 font-semibold text-aubergine"><i class="fa-solid fa-shield-halved"></i> Stock safety</div>
                <p class="mt-2 text-sm leading-6 text-aubergine/80">This adjustment is recorded in the movement history. The system prevents stock from dropping below zero.</p>
            </div>
        </aside>
    </div>
</div>
@endsection
