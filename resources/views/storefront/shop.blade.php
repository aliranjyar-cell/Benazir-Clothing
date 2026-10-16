@extends('storefront.layouts.store')

@section('title', $selectedCategory ? 'Shop · ' . $selectedCategory->name : 'Shop')

@section('content')
    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md">
                <div>
                    <p class="font-label-uppercase text-label-uppercase tracking-widest text-primary">AURELLE · ATELIER</p>
                    <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-xs leading-[1.15]">
                        {{ $selectedCategory ? 'Shop · ' . $selectedCategory->name : 'Shop' }}
                    </h1>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-space-sm">
                    <form role="search" action="{{ route('store.shop') }}" method="GET" class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-secondary text-lg pointer-events-none">search</span>
                        <input
                            name="q"
                            value="{{ $searchTerm }}"
                            placeholder="Search handcrafted suits, organza dupattas..."
                            class="w-full sm:w-72 rounded-lg bg-surface-container-lowest py-2 pl-9 pr-3 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                        />
                    </form>

                    <label class="relative flex items-center gap-space-xs rounded-lg bg-surface-container-lowest py-2 pl-3 pr-2">
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Sort</span>
                        <select
                            name="sort"
                            class="appearance-none cursor-pointer rounded-lg bg-surface py-2 pl-2 pr-8 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                        >
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name A–Z</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price — low to high</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price — high to low</option>
                        </select>
                    </label>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-lg">
            <form method="GET" action="{{ route('store.shop.category', $selectedCategory) }}" id="cat-form" class="w-full">
                <div class="flex flex-wrap gap-space-sm">
                    <a
                        href="{{ route('store.shop') }}"
                        class="flex-1 min-w-[140px] rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-center font-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors hover:border-primary/40 hover:text-primary {{ request()->routeIs('store.shop.category') ? 'border-primary bg-primary/5' : '' }}"
                    >
                        All
                    </a>
                    @forelse ($categories as $category)
                        <button
                            type="submit"
                            name="category"
                            value="{{ $category->slug }}"
                            class="flex-1 min-w-[140px] rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-center font-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors {{ request('category') == $category->slug ? 'border-primary bg-primary/5' : '' }}"
                        >
                            {{ $category->name }}
                        </button>
                    @empty
                        <button
                            type="submit"
                            class="flex-1 min-w-[140px] rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-center font-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                        >
                            Classic Punjabi Shalwar Kameez
                        </button>
                        <button
                            type="submit"
                            class="flex-1 min-w-[140px] rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-center font-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                        >
                            Royal Velvet Festive Edit
                        </button>
                    @endforelse
                </div>
            </form>
        </div>

        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">
                        {{ $products->total() ? 'Showing ' . $products->firstItem() . '–' . min($products->lastItem(), $products->total()) . ' of ' . $products->total() . ' items' : 'No products found' }}
                    </p>
                    <h2 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-xs leading-[1.15]">
                        {{ $searchTerm ? 'Search results for “' . e($searchTerm) . '”' : 'All collections' }}
                    </h2>
                </div>
                @if ($products->hasPages())
                    <div class="flex items-center gap-space-md">
                        @if ($products->onFirstPage())
                            <span class="font-label-uppercase text-label-uppercase text-on-surface-variant/60">&laquo;</span>
                        @else
                            <a
                                href="{{ $products->previousPageUrl() }}"
                                class="flex items-center gap-space-sm rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                            >
                                &laquo; Prev
                            </a>
                        @endif
                        @php
                            $pageRange = 3;
                            $start = max(1, $products->currentPage() - $pageRange);
                            $end = min($products->lastPage(), $start + $pageRange * 2);
                            if ($end - $start < $pageRange * 2) {
                                $start = max(1, $end - $pageRange * 2);
                            }
                        @endphp
                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $products->currentPage())
                                <span
                                    class="flex items-center gap-space-sm rounded-lg bg-primary px-4 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest"
                                >
                                    {{ $i }}
                                </span>
                            @else
                                <a
                                    href="{{ $products->url($i) }}"
                                    class="flex items-center gap-space-sm rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                                >
                                    {{ $i }}
                                </a>
                            @endif
                        @endfor
                        @if ($products->hasMorePages())
                            <a
                                href="{{ $products->nextPageUrl() }}"
                                class="flex items-center gap-space-sm rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-label-uppercase text-label-uppercase tracking-widest text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                            >
                                Next &raquo;
                            </a>
                        @else
                            <span class="font-label-uppercase text-label-uppercase text-on-surface-variant/60">&raquo;</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-lg">
            <div class="grid gap-space-lg sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($products as $product)
                    @include('storefront.partials.product-card')
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center rounded-xl border border-outline-variant bg-surface-container-lowest py-space-2xl text-center">
                        <span class="material-symbols-outlined text-4xl text-secondary">search_off</span>
                        <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-sm">
                            {{ $searchTerm ? 'No items match your search.' : 'No products available in this category yet.' }}
                        </p>
                        @if ($searchTerm)
                            <a href="{{ route('store.shop') }}" class="mt-space-sm font-label-uppercase text-label-uppercase text-primary underline-offset-4 hover:underline">Clear search</a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="mt-space-xl border-t border-outline-variant/40 pt-space-lg text-center text-body-sm text-on-surface-variant">
                    <span class="font-label-uppercase text-label-uppercase text-on-surface-variant/70">Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
                </div>
            @endif
        </div>
    </div>
@endsection
