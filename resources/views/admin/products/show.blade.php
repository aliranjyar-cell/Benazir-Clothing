@extends('layouts.admin.app')

@section('title', $product->name)

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Products', 'url' => route('products.index')],
        ['label' => $product->name],
    ]" />
@endsection

@section('content')
    @php
        $stock = (int) ($product->total_stock ?? 0);
        $hasVariants = $product->variants_count > 0;
        $galleryImages = $product->images->values();
        $initialImage = $product->images->firstWhere('is_primary', true) ?? $galleryImages->first();
    @endphp

    <x-admin.page-heading
        title="{{ $product->name }}"
        description="Slug: /{{ $product->slug }}{{ $product->category ? ' · '.$product->category->name : '' }}"
    >
        <x-slot:actions>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
            >
                <x-admin.icon name="chevron-right" class="h-4 w-4 rotate-180" />
                Back to products
            </a>

            <a
                href="{{ route('products.edit', $product) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-lilac px-4 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                Edit product
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <div class="grid items-start gap-5 lg:grid-cols-12">
        {{-- Main column --}}
        <div class="space-y-5 lg:col-span-8">
            {{-- Gallery --}}
            <div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
                <div class="flex items-center justify-between border-b border-black/5 px-6 py-4">
                    <h2 class="text-sm font-semibold tracking-tight text-ink">Images</h2>
                    <span class="rounded-full bg-ivory px-2.5 py-0.5 text-[11px] font-semibold tabular-nums text-muted ring-1 ring-black/10">
                        {{ $galleryImages->count() }}
                    </span>
                </div>

                <div class="px-6 py-5" x-data="{ activeUrl: @js($initialImage ? asset('storage/'.$initialImage->image_path) : null) }">
                    @if ($galleryImages->isNotEmpty())
                        <div class="overflow-hidden rounded-xl bg-ivory/40 ring-1 ring-black/5">
                            <img
                                :src="activeUrl"
                                alt="{{ $product->name }}"
                                class="aspect-square w-full object-cover"
                            />
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2.5">
                            @foreach ($galleryImages as $image)
                                <button
                                    type="button"
                                    @click="activeUrl = @js(asset('storage/'.$image->image_path))"
                                    class="relative h-16 w-16 overflow-hidden rounded-lg ring-1 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                    :class="activeUrl === @js(asset('storage/'.$image->image_path)) ? 'ring-2 ring-lilac' : 'ring-black/10 hover:ring-black/25'"
                                    aria-label="View image {{ $loop->iteration }}"
                                >
                                    <img src="{{ asset('storage/'.$image->image_path) }}" alt="" class="h-full w-full object-cover" loading="lazy" />

                                    @if ($image->is_primary)
                                        <span class="absolute bottom-0.5 left-0.5 rounded bg-aubergine/85 px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider text-white">
                                            Primary
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center rounded-xl border border-dashed border-black/10 bg-ivory/40 px-4 py-12 text-center">
                            <span class="grid h-12 w-12 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                                <x-admin.icon name="upload" class="h-6 w-6" />
                            </span>
                            <p class="mt-4 text-sm font-semibold text-ink">No images yet</p>
                            <p class="mt-1 max-w-xs text-xs leading-relaxed text-muted">
                                Add photos of this piece — front view, embroidery close-ups and fabric detail.
                            </p>
                            <a
                                href="{{ route('products.edit', $product) }}"
                                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-lilac px-4 py-2 text-xs font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                            >
                                <x-admin.icon name="plus" class="h-3.5 w-3.5" />
                                Add images
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Variants --}}
            <div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
                <div class="flex items-center justify-between border-b border-black/5 px-6 py-4">
                    <h2 class="text-sm font-semibold tracking-tight text-ink">Variants</h2>
                    <span class="rounded-full bg-ivory px-2.5 py-0.5 text-[11px] font-semibold tabular-nums text-muted ring-1 ring-black/10">
                        {{ $product->variants_count }}
                    </span>
                </div>

                @if ($hasVariants)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-black/5 text-sm">
                            <thead>
                                <tr class="border-b border-black/5 bg-ivory/40">
                                    <th scope="col" class="px-6 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">SKU</th>
                                    <th scope="col" class="px-6 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Size</th>
                                    <th scope="col" class="px-6 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Colour</th>
                                    <th scope="col" class="px-6 py-3 text-end text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Price</th>
                                    <th scope="col" class="px-6 py-3 text-end text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Stock</th>
                                    <th scope="col" class="px-6 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5">
                                @foreach ($product->variants as $variant)
                                    <tr class="transition-colors hover:bg-ivory/30">
                                        <td class="px-6 py-3.5 font-medium text-ink">{{ $variant->sku }}</td>
                                        <td class="px-6 py-3.5 text-muted">{{ $variant->size ?: 'One Size' }}</td>
                                        <td class="px-6 py-3.5 text-muted">{{ $variant->color ?: '—' }}</td>
                                        <td class="px-6 py-3.5 text-end tabular-nums">
                                            <span class="font-medium text-ink">₹{{ number_format((float) $variant->price, 2) }}</span>
                                            @if ($variant->isOnSale())
                                                <span class="ms-1.5 text-xs text-muted line-through">₹{{ number_format((float) $variant->compare_at_price, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3.5 text-end tabular-nums {{ $variant->stock_quantity > 0 ? 'text-ink' : 'font-medium text-blush-deep' }}">
                                            {{ $variant->stock_quantity }}
                                        </td>
                                        <td class="px-6 py-3.5">
                                            @if ($variant->is_active)
                                                <x-admin.badge tone="mint">Active</x-admin.badge>
                                            @else
                                                <x-admin.badge tone="blush">Inactive</x-admin.badge>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center px-6 py-10 text-center">
                        <p class="text-sm font-medium text-ink">No variants yet</p>
                        <p class="mt-1 max-w-xs text-xs leading-relaxed text-muted">
                            Sizes, colours and pricing can be added from the edit page.
                        </p>
                        <a
                            href="{{ route('products.edit', $product) }}"
                            class="mt-4 rounded text-xs font-semibold text-lilac-deep underline-offset-2 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                        >
                            Add variants
                        </a>
                    </div>
                @endif
            </div>

            {{-- Description & details --}}
            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Description</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-muted">
                    {{ $product->description ?: 'No description has been added yet.' }}
                </p>

                <dl class="mt-5 grid gap-x-6 gap-y-4 border-t border-black/5 pt-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Category</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $product->category?->name ?: 'Uncategorised' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Fabric</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $product->fabric ?: '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Care instructions</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm leading-relaxed text-ink">{{ $product->care_instructions ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Created</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $product->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Last updated</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $product->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Side column --}}
        <div class="space-y-5 lg:col-span-4">
            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Status</h2>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @if ($product->is_active)
                        <x-admin.badge tone="mint">Active</x-admin.badge>
                    @else
                        <x-admin.badge tone="blush">Inactive</x-admin.badge>
                    @endif

                    @if ($product->is_featured)
                        <x-admin.badge tone="lilac">Featured</x-admin.badge>
                    @else
                        <x-admin.badge>Not featured</x-admin.badge>
                    @endif
                </div>

                <form
                    method="POST"
                    action="{{ route('products.toggle-status', $product) }}"
                    class="mt-4"
                    onsubmit="return confirm('{{ $product->is_active ? 'Deactivate' : 'Reactivate' }} &quot;{{ $product->name }}&quot;?')"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac {{ $product->is_active ? 'border-blush/40 bg-blush-soft text-blush-deep hover:bg-blush hover:text-white' : 'border-mint/40 bg-mint-soft text-mint-deep hover:bg-mint hover:text-aubergine' }}"
                    >
                        {{ $product->is_active ? 'Deactivate product' : 'Reactivate product' }}
                    </button>
                </form>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Inventory</h2>

                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Total stock</dt>
                        <dd class="font-medium tabular-nums {{ $stock > 0 ? 'text-ink' : 'text-blush-deep' }}">{{ $stock }} units</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Variants</dt>
                        <dd class="font-medium tabular-nums text-ink">{{ $product->variants_count }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Price range</dt>
                        <dd class="font-medium tabular-nums text-ink">
                            @if (! $hasVariants)
                                —
                            @elseif ((float) $product->price_min === (float) $product->price_max)
                                ₹{{ number_format((float) $product->price_min, 2) }}
                            @else
                                ₹{{ number_format((float) $product->price_min, 2) }} – ₹{{ number_format((float) $product->price_max, 2) }}
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Actions</h2>

                <div class="mt-4 flex flex-col gap-2.5">
                    <a
                        href="{{ route('products.edit', $product) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-lilac px-4 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                    >
                        Edit product
                    </a>
                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                    >
                        Back to products
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

