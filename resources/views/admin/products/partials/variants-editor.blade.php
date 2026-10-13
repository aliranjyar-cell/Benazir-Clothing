@php
    $fieldClass = 'block w-full rounded-lg border-black/10 text-sm text-ink shadow-sm transition placeholder:text-muted/60 focus:border-lilac focus:ring-lilac py-2';
    $labelClass = 'mb-1 block text-[11px] font-semibold uppercase tracking-[0.08em] text-muted';

    // Per-row validation errors, keyed as "index.field" for Alpine lookups.
    $variantErrors = [];
    foreach ($errors->get('variants') as $index => $rowErrors) {
        foreach ($rowErrors as $field => $messages) {
            $variantErrors[$index.'.'.Illuminate\Support\Str::afterLast($field, '.')] = Illuminate\Support\Arr::first($messages);
        }
    }
@endphp

<div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black/5 px-6 py-4">
        <div class="flex items-center gap-2.5">
            <h2 class="text-sm font-semibold tracking-tight text-ink">Variants</h2>
            <span
                class="rounded-full bg-ivory px-2.5 py-0.5 text-[11px] font-semibold tabular-nums text-muted ring-1 ring-black/10"
                x-text="variants.length"
                x-cloak
            ></span>
        </div>

        <button
            type="button"
            @click="addVariant()"
            class="inline-flex items-center gap-1.5 rounded-lg border border-lilac/50 bg-lilac-soft px-3 py-1.5 text-xs font-semibold text-aubergine transition hover:bg-lilac hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
        >
            <x-admin.icon name="plus" class="h-3.5 w-3.5" />
            Add variant
        </button>
    </div>

    <div class="px-6 py-5">
        @if ($errors->has('variants'))
            <div class="mb-4 rounded-xl border border-blush/40 bg-blush-soft px-4 py-3" role="alert">
                <p class="text-xs font-semibold text-blush-deep">Please fix the variant errors below.</p>
            </div>
        @endif

        <div class="space-y-4">
            <template x-for="(variant, index) in variants" :key="variant.id ?? 'new-' + index">
                <div class="rounded-xl border border-black/[0.06] bg-ivory/30 p-4">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">
                            Variant <span class="tabular-nums" x-text="index + 1"></span>
                            <span x-show="variant.id" class="text-mint-deep">· saved</span>
                        </p>

                        <button
                            type="button"
                            @click="removeVariant(index)"
                            class="rounded-lg p-1.5 text-blush-deep transition hover:bg-blush-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                            :aria-label="'Remove variant ' + (index + 1)"
                        >
                            <x-admin.icon name="x-mark" class="h-4 w-4" />
                        </button>
                    </div>

                    <input
                        x-show="variant.id"
                        type="hidden"
                        :name="'variants[' + index + '][id]'"
                        :value="variant.id ?? ''"
                    />

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="{{ $labelClass }}" :for="'variant-sku-' + index">
                                SKU <span class="text-blush-deep">*</span>
                            </label>
                            <input
                                :id="'variant-sku-' + index"
                                type="text"
                                :name="'variants[' + index + '][sku]'"
                                x-model="variant.sku"
                                placeholder="AUR-ANN-ROSE-M"
                                class="{{ $fieldClass }}"
                            />
                            <p class="mt-1 hidden text-xs font-medium text-blush-deep" x-show="variantErrors[index + '.sku']" x-text="variantErrors[index + '.sku']"></p>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" :for="'variant-size-' + index">Size</label>
                            <input
                                :id="'variant-size-' + index"
                                type="text"
                                :name="'variants[' + index + '][size]'"
                                x-model="variant.size"
                                list="common-sizes"
                                placeholder="M or One Size"
                                class="{{ $fieldClass }}"
                            />
                            <p class="mt-1 hidden text-xs font-medium text-blush-deep" x-show="variantErrors[index + '.size']" x-text="variantErrors[index + '.size']"></p>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" :for="'variant-color-' + index">Colour</label>
                            <input
                                :id="'variant-color-' + index"
                                type="text"
                                :name="'variants[' + index + '][color]'"
                                x-model="variant.color"
                                placeholder="e.g. Rose"
                                class="{{ $fieldClass }}"
                            />
                            <p class="mt-1 hidden text-xs font-medium text-blush-deep" x-show="variantErrors[index + '.color']" x-text="variantErrors[index + '.color']"></p>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" :for="'variant-price-' + index">
                                Price (₹) <span class="text-blush-deep">*</span>
                            </label>
                            <input
                                :id="'variant-price-' + index"
                                type="number"
                                min="0"
                                step="0.01"
                                :name="'variants[' + index + '][price]'"
                                x-model="variant.price"
                                placeholder="2499"
                                class="{{ $fieldClass }} tabular-nums"
                            />
                            <p class="mt-1 hidden text-xs font-medium text-blush-deep" x-show="variantErrors[index + '.price']" x-text="variantErrors[index + '.price']"></p>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" :for="'variant-compare-' + index">Compare-at (₹)</label>
                            <input
                                :id="'variant-compare-' + index"
                                type="number"
                                min="0"
                                step="0.01"
                                :name="'variants[' + index + '][compare_at_price]'"
                                x-model="variant.compare_at_price"
                                placeholder="3499"
                                class="{{ $fieldClass }} tabular-nums"
                            />
                            <p class="mt-1 hidden text-xs font-medium text-blush-deep" x-show="variantErrors[index + '.compare_at_price']" x-text="variantErrors[index + '.compare_at_price']"></p>
                        </div>

                        <div>
                            <label class="{{ $labelClass }}" :for="'variant-stock-' + index">
                                Stock <span class="text-blush-deep">*</span>
                            </label>
                            <input
                                :id="'variant-stock-' + index"
                                type="number"
                                min="0"
                                step="1"
                                :name="'variants[' + index + '][stock_quantity]'"
                                x-model="variant.stock_quantity"
                                placeholder="0"
                                class="{{ $fieldClass }} tabular-nums"
                            />
                            <p class="mt-1 hidden text-xs font-medium text-blush-deep" x-show="variantErrors[index + '.stock_quantity']" x-text="variantErrors[index + '.stock_quantity']"></p>
                        </div>
                    </div>

                    <label class="mt-3 inline-flex items-center gap-2.5">
                        <input type="hidden" :name="'variants[' + index + '][is_active]'" value="0" />
                        <input
                            type="checkbox"
                            :name="'variants[' + index + '][is_active]'"
                            value="1"
                            x-model.boolean="variant.is_active"
                            class="rounded border-black/20 text-lilac focus:ring-lilac"
                        />
                        <span class="text-xs font-medium text-ink">Variant is active and available for sale</span>
                    </label>
                </div>
            </template>

            <div
                x-show="variants.length === 0"
                x-cloak
                class="flex flex-col items-center rounded-xl border border-dashed border-black/10 bg-ivory/30 px-4 py-8 text-center"
            >
                <span class="grid h-10 w-10 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                    <x-admin.icon name="archive" class="h-5 w-5" />
                </span>
                <p class="mt-3 text-sm font-medium text-ink">No variants yet</p>
                <p class="mt-1 max-w-xs text-xs leading-relaxed text-muted">
                    Add sizes, colours and pricing so customers can order. One-size pieces only need a single row.
                </p>
            </div>
        </div>
    </div>

    <datalist id="common-sizes">
        <option value="XXS"></option>
        <option value="XS"></option>
        <option value="S"></option>
        <option value="M"></option>
        <option value="L"></option>
        <option value="XL"></option>
        <option value="XXL"></option>
        <option value="One Size"></option>
    </datalist>
</div>
