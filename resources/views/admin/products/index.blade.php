@extends('layouts.admin.app')

@section('title', 'Products')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Products'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading
        title="Products"
        description="Manage your Punjabi fashion catalogue — names, categories, pricing and visibility."
    >
        <x-slot:actions>
            <a
                href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-lilac px-4 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                <x-admin.icon name="plus" class="h-4 w-4" />
                Add Product
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    {{-- Search & filters --}}
    <form
        action="{{ route('products.index') }}"
        method="GET"
        class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-card sm:p-5"
    >
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_auto_auto_auto]">
            <div>
                <label for="search" class="sr-only">Search products by name</label>
                <input
                    id="search"
                    name="search"
                    type="search"
                    value="{{ request('search') }}"
                    placeholder="Search by product name…"
                    class="block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition placeholder:text-muted/60 focus:border-lilac focus:ring-lilac"
                />
            </div>

            <div>
                <label for="filter-category" class="sr-only">Filter by category</label>
                <select
                    id="filter-category"
                    name="category"
                    class="block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-48"
                >
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter-status" class="sr-only">Filter by status</label>
                <select
                    id="filter-status"
                    name="status"
                    class="block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-40"
                >
                    <option value="">Any status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-aubergine px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                >
                    Filter
                </button>

                @if (request()->filled('search') || request()->filled('category') || request()->filled('status'))
                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($products->total() > 0)
        @php
            $nameDir = $sort === 'name' ? ($direction === 'asc' ? 'desc' : 'asc') : 'asc';
            $createdDir = $sort === 'created_at' ? ($direction === 'desc' ? 'asc' : 'desc') : 'desc';
        @endphp

        <div class="mt-5 overflow-hidden rounded-2xl border border-black/[0.06] bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/5 text-sm">
                    <thead>
                        <tr class="border-b border-black/5 bg-ivory/40">
                            <th scope="col" class="px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">
                                <a
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $nameDir]) }}"
                                    class="inline-flex items-center gap-1 rounded transition-colors hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                >
                                    Product
                                    <x-admin.icon name="chevron-down" class="h-3 w-3 {{ $sort === 'name' && $direction === 'desc' ? 'rotate-180' : '' }} {{ $sort === 'name' ? '' : 'opacity-0' }}" />
                                </a>
                            </th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted md:table-cell">Category</th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted lg:table-cell">Price</th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted lg:table-cell">Stock</th>
                            <th scope="col" class="px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Status</th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted xl:table-cell">Featured</th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted xl:table-cell">
                                <a
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => $createdDir]) }}"
                                    class="inline-flex items-center gap-1 rounded transition-colors hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                >
                                    Created
                                    <x-admin.icon name="chevron-down" class="h-3 w-3 {{ $sort === 'created_at' && $direction === 'asc' ? 'rotate-180' : '' }} {{ $sort === 'created_at' ? '' : 'opacity-0' }}" />
                                </a>
                            </th>
                            <th scope="col" class="px-5 py-3.5 text-end text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach ($products as $product)
                            @php
                                $stock = (int) ($product->total_stock ?? 0);
                                $hasVariants = $product->variants_count > 0;
                            @endphp

                            <tr class="transition-colors hover:bg-ivory/30">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($product->primaryImage?->image_path)
                                            <img
                                                src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                                                alt=""
                                                class="h-11 w-11 shrink-0 rounded-xl object-cover ring-1 ring-black/5"
                                                loading="lazy"
                                            />
                                        @else
                                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                                                <x-admin.icon name="cube" class="h-5 w-5" />
                                            </span>
                                        @endif

                                        <div class="min-w-0">
                                            <a
                                                href="{{ route('products.show', $product) }}"
                                                class="block truncate rounded font-medium text-ink transition-colors hover:text-lilac-deep focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                            >
                                                {{ $product->name }}
                                            </a>
                                            <p class="mt-0.5 truncate text-xs text-muted">/{{ $product->slug }}</p>
                                        </div>
                                    </div>
                                </td>


                                <td class="hidden px-5 py-4 md:table-cell">
                                    @if ($product->category)
                                        <span class="text-ink">{{ $product->category->name }}</span>
                                    @else
                                        <span class="text-xs text-muted">Uncategorised</span>
                                    @endif
                                </td>

                                <td class="hidden px-5 py-4 lg:table-cell">
                                    @if (! $hasVariants)
                                        <span class="text-xs text-muted">No variants yet</span>
                                    @elseif ((float) $product->price_min === (float) $product->price_max)
                                        <span class="font-medium tabular-nums text-ink">₹{{ number_format((float) $product->price_min, 2) }}</span>
                                    @else
                                        <span class="font-medium tabular-nums text-ink">
                                            ₹{{ number_format((float) $product->price_min, 2) }} – ₹{{ number_format((float) $product->price_max, 2) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="hidden px-5 py-4 lg:table-cell">
                                    @if ($stock === 0)
                                        <span class="font-medium tabular-nums text-blush-deep">Out of stock</span>
                                    @elseif ($stock <= 5)
                                        <span class="font-medium tabular-nums text-blush-deep">{{ $stock }} unit{{ $stock === 1 ? '' : 's' }}</span>
                                    @else
                                        <span class="tabular-nums text-ink">{{ $stock }} units</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if ($product->is_active)
                                        <x-admin.badge tone="mint">Active</x-admin.badge>
                                    @else
                                        <x-admin.badge tone="blush">Inactive</x-admin.badge>
                                    @endif
                                </td>

                                <td class="hidden px-5 py-4 xl:table-cell">
                                    @if ($product->is_featured)
                                        <x-admin.badge tone="lilac">Featured</x-admin.badge>
                                    @else
                                        <span class="text-xs text-muted" aria-label="Not featured">—</span>
                                    @endif
                                </td>

                                <td class="hidden px-5 py-4 text-xs text-muted xl:table-cell">
                                    {{ $product->created_at->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a
                                            href="{{ route('products.show', $product) }}"
                                            class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-muted transition-colors hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="{{ route('products.edit', $product) }}"
                                            class="rounded-lg bg-lilac-soft px-2.5 py-1.5 text-xs font-semibold text-aubergine transition-colors hover:bg-lilac hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('products.toggle-status', $product) }}"
                                            onsubmit="return confirm('{{ $product->is_active ? 'Deactivate' : 'Reactivate' }} &quot;{{ $product->name }}&quot;?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac {{ $product->is_active ? 'text-blush-deep hover:bg-blush-soft' : 'text-mint-deep hover:bg-mint-soft' }}"
                                            >
                                                {{ $product->is_active ? 'Deactivate' : 'Reactivate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-black/5 px-5 py-4">
                {{ $products->links() }}
            </div>
        </div>
    @else
        {{-- Empty state --}}
        <div class="mt-5 rounded-2xl border border-black/[0.06] bg-white px-6 py-16 text-center shadow-card">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                <x-admin.icon name="cube" class="h-7 w-7" />
            </span>

            <h2 class="mt-5 text-base font-semibold tracking-tight text-ink">
                @if (request()->filled('search') || request()->filled('category') || request()->filled('status'))
                    No products match your filters
                @else
                    No products yet
                @endif
            </h2>

            <p class="mx-auto mt-1.5 max-w-sm text-sm leading-relaxed text-muted">
                @if (request()->filled('search') || request()->filled('category') || request()->filled('status'))
                    Try a different search term, or clear the filters to see the full catalogue.
                @else
                    Start building your catalogue by adding your first Punjabi ensemble — names, fabrics and pricing all live here.
                @endif
            </p>

            @if (request()->filled('search') || request()->filled('category') || request()->filled('status'))
                <a
                    href="{{ route('products.index') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                >
                    Clear filters
                </a>
            @else
                <a
                    href="{{ route('products.create') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-lilac px-5 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                >
                    <x-admin.icon name="plus" class="h-4 w-4" />
                    Add your first product
                </a>
            @endif
        </div>
    @endif
@endsection
