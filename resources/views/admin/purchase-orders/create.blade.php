@extends('layouts.admin.app')

@section('title', 'Create Purchase Order')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div>
            <div class="mb-2 text-xs font-semibold uppercase tracking-[.18em] text-muted">Catalogue / Restock orders</div>
            <a href="{{ route('admin.purchase-orders.index') }}"
                class="inline-flex items-center gap-2 text-sm text-muted hover:text-aubergine"><span
                    aria-hidden="true">←</span> Back to restock orders</a>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-ink">Create purchase order</h1>
            <p class="mt-2 text-sm text-muted">Record the supplier and variants being ordered. Stock changes only when
                quantities are received.</p>
        </div>

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <p class="font-semibold">Please check the order details.</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.purchase-orders.store') }}" class="space-y-6">
            @csrf
            <section class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm sm:p-7">
                <h2 class="font-semibold text-ink">Supplier details</h2>
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="supplier_name"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Supplier name
                            <span class="text-rose-600">*</span></label>
                        <input id="supplier_name" name="supplier_name" required maxlength="120"
                            value="{{ old('supplier_name') }}"
                            class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('supplier_name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="supplier_email"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Supplier
                            email</label>
                        <input id="supplier_email" name="supplier_email" type="email" maxlength="254"
                            value="{{ old('supplier_email') }}"
                            class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('supplier_email')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expected_at"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Expected
                            delivery</label>
                        <input id="expected_at" name="expected_at" type="date" value="{{ old('expected_at') }}"
                            class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('expected_at')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="notes"
                            class="mb-2 block text-xs font-semibold uppercase tracking-[.12em] text-muted">Notes</label>
                        <textarea id="notes" name="notes" rows="2" maxlength="2000"
                            class="w-full rounded-xl border border-black/10 bg-white px-3.5 py-3 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
                <div
                    class="flex flex-col gap-3 border-b border-black/5 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div>
                        <h2 class="font-semibold text-ink">Variants and quantities</h2>
                        <p class="mt-1 text-sm text-muted">Each variant is a separate purchase-order line. Unit cost is
                            optional.</p>
                    </div>
                    <button type="button" id="add-purchase-order-line"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-lilac/60 bg-lilac/15 px-4 py-2.5 text-sm font-semibold text-aubergine transition hover:bg-lilac/30">
                        <x-admin.icon name="plus" class="h-4 w-4" /> Add variant
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-[#FCFAF8] text-[10px] font-semibold uppercase tracking-[.16em] text-muted">
                            <tr>
                                <th class="px-6 py-4">Product variant</th>
                                <th class="px-4 py-4">Quantity</th>
                                <th class="px-4 py-4">Unit cost (PKR)</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="purchase-order-lines" class="divide-y divide-black/5">
                            @php($oldItems = old('items', [['variant_id' => '', 'quantity_ordered' => 1, 'unit_cost' => '']]))
                            @foreach ($oldItems as $lineIndex => $line)
                                <tr class="purchase-order-line">
                                    <td class="px-6 py-3">
                                        <select name="items[{{ $lineIndex }}][variant_id]" required
                                            class="w-full min-w-[300px] rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                                            <option value="">Select a product / SKU</option>
                                            @foreach ($variants as $variant)
                                                <option value="{{ $variant->id }}" @selected((string) ($line['variant_id'] ?? '') === (string) $variant->id)>
                                                    {{ $variant->product?->name ?? 'Product unavailable' }} ·
                                                    {{ $variant->sku }} ·
                                                    {{ $variant->size ?: 'One Size' }}{{ $variant->color ? ' · ' . $variant->color : '' }}
                                                    · {{ $variant->stock_quantity }} in stock
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3"><input name="items[{{ $lineIndex }}][quantity_ordered]"
                                            type="number" min="1" max="100000" required
                                            value="{{ $line['quantity_ordered'] ?? 1 }}"
                                            class="w-28 rounded-xl border border-black/10 px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                                    </td>
                                    <td class="px-4 py-3"><input name="items[{{ $lineIndex }}][unit_cost]"
                                            type="number" min="0" max="99999999.99" step="0.01"
                                            value="{{ $line['unit_cost'] ?? '' }}" placeholder="Optional"
                                            class="w-36 rounded-xl border border-black/10 px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                                    </td>
                                    <td class="px-6 py-3 text-right"><button type="button"
                                            class="remove-purchase-order-line rounded-lg p-2 text-muted transition hover:bg-rose-50 hover:text-rose-700"
                                            aria-label="Remove variant"><x-admin.icon name="trash"
                                                class="h-4 w-4" /></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('items')
                    <p class="px-6 pb-4 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.purchase-orders.index') }}"
                    class="rounded-xl border border-black/10 px-5 py-3 text-center text-sm font-medium text-ink hover:bg-stone-50">Cancel</a>
                <button
                    class="rounded-xl bg-aubergine px-5 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90">Create
                    purchase order</button>
            </div>
        </form>
    </div>

    <template id="purchase-order-line-template">
        <tr class="purchase-order-line">
            <td class="px-6 py-3">
                <select name="items[__INDEX__][variant_id]" required
                    class="w-full min-w-[300px] rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                    <option value="">Select a product / SKU</option>
                    @foreach ($variants as $variant)
                        <option value="{{ $variant->id }}">{{ $variant->product?->name ?? 'Product unavailable' }} ·
                            {{ $variant->sku }} ·
                            {{ $variant->size ?: 'One Size' }}{{ $variant->color ? ' · ' . $variant->color : '' }} ·
                            {{ $variant->stock_quantity }} in stock</option>
                    @endforeach
                </select>
            </td>
            <td class="px-4 py-3"><input name="items[__INDEX__][quantity_ordered]" type="number" min="1"
                    max="100000" required value="1"
                    class="w-28 rounded-xl border border-black/10 px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
            </td>
            <td class="px-4 py-3"><input name="items[__INDEX__][unit_cost]" type="number" min="0"
                    max="99999999.99" step="0.01" placeholder="Optional"
                    class="w-36 rounded-xl border border-black/10 px-3 py-2.5 text-sm text-ink focus:border-lilac focus:ring-2 focus:ring-lilac/30">
            </td>
            <td class="px-6 py-3 text-right"><button type="button"
                    class="remove-purchase-order-line rounded-lg p-2 text-muted transition hover:bg-rose-50 hover:text-rose-700"
                    aria-label="Remove variant"><x-admin.icon name="trash" class="h-4 w-4" /></button></td>
        </tr>
    </template>

    @push('scripts')
        <script>
            const lines = document.getElementById('purchase-order-lines');
            const lineTemplate = document.getElementById('purchase-order-line-template');
            let nextLineIndex = lines.querySelectorAll('.purchase-order-line').length;

            document.getElementById('add-purchase-order-line').addEventListener('click', () => {
                const row = lineTemplate.content.cloneNode(true);
                row.querySelectorAll('[name]').forEach((field) => {
                    field.name = field.name.replaceAll('__INDEX__', nextLineIndex);
                });
                lines.append(row);
                nextLineIndex += 1;
            });

            lines.addEventListener('click', (event) => {
                const removeButton = event.target.closest('.remove-purchase-order-line');
                if (removeButton) removeButton.closest('tr').remove();
            });
        </script>
    @endpush
@endsection
