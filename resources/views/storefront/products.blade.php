@extends('storefront.layouts.app')

@section('title', $selectedCategory ? $selectedCategory->name . ' · Products' : 'Products')

@section('content')
    <div class="flex flex-col w-full">
        <!-- SHOP CONTENT WRAPPER -->
        <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-12 py-8 lg:py-12 space-y-10">
            <!-- 1. BREADCRUMBS & EDITORIAL COLLECTION INTRODUCTION -->
            <header class="space-y-6">
                <!-- Breadcrumbs -->
                <nav aria-label="Breadcrumb"
                    class="flex items-center space-x-2 text-on-surface-variant text-body-sm font-body-sm">
                    <a class="hover:text-primary transition-colors" href="{{ route('store.home') }}">Home</a>
                    <span class="text-outline-variant text-[11px]">/</span>
                    <a class="hover:text-primary transition-colors" href="{{ route('store.shop') }}">Products</a>
                    <span class="text-outline-variant text-[11px]">/</span>
                    <span class="text-on-surface font-medium">{{ $selectedCategory?->name ?? 'All Products' }}</span>
                </nav>
                <!-- Editorial Header Block -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end border-b border-outline-variant/40 pb-8">
                    <div class="lg:col-span-8 space-y-3">
                        <div class="flex items-center space-x-3">
                            <span class="text-primary font-label-sm text-label-sm tracking-[0.25em] uppercase">The {{ config('storefront.store_name', 'Benazir') }}
                                Collection</span>
                            <span class="w-12 h-px bg-primary/40"></span>
                        </div>
                        <h1 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">
                            {{ $selectedCategory?->name ?? 'The Art of Punjabi Dressing' }}</h1>
                        <p class="text-body-md font-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                            Discover timeless Punjabi elegance through intricate embroidery, graceful silhouettes, and
                            thoughtfully crafted textiles made for every celebration across Lahore, London, and New York.
                        </p>
                    </div>
                    <div class="lg:col-span-4 flex flex-col lg:items-end justify-between self-stretch pt-4 lg:pt-0">
                        <div
                            class="inline-flex items-center gap-2 bg-surface-container-low px-4 py-2 border border-outline-variant/50">
                            <span class="w-1.5 h-1.5 bg-secondary rounded-full"></span>
                            <span class="text-label-md font-label-md uppercase tracking-[0.16em] text-on-surface">Showing
                                {{ $products->total() }}
                                Handcrafted Creations</span>
                        </div>
                        <span class="text-label-sm font-label-sm text-outline tracking-widest uppercase mt-4 lg:mt-0">Autumn
                            / Winter Atelier 2025</span>
                    </div>
                </div>
                <!-- Editorial Lookbook Banner Frame -->
                <div
                    class="relative w-full overflow-hidden bg-surface-container-low border border-outline-variant/60 shadow-sm">
                    <div class="relative h-[240px] md:h-[320px] lg:h-[380px] w-full">
                        <img alt="{{ config('storefront.store_name', 'BENAZIR') }} Haute Couture Lookbook 2025"
                            class="w-full h-full object-cover object-center filter saturate-[0.9] hover:scale-[1.01] transition-transform duration-700"
                            src="{{ asset('images/pexels-dhanno-27603274.webp') }}" />
                        <div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-on-surface/20 to-transparent">
                        </div>
                        <div
                            class="absolute inset-x-0 bottom-0 p-6 md:p-8 flex flex-col md:flex-row md:items-end justify-between gap-4 text-surface">
                            <div class="space-y-1">
                                <span
                                    class="font-label-sm text-label-sm tracking-[0.22em] uppercase text-primary-fixed">Haute
                                    Couture Lookbook 2025</span>
                                <p class="font-headline-md text-headline-md tracking-tight">Artisanal Zardozi &amp; Phulkari
                                    Heritage</p>
                            </div>
                            <a class="inline-flex items-center gap-2 text-label-md font-label-md tracking-[0.18em] uppercase py-2.5 px-5 bg-surface/90 text-on-surface hover:bg-surface transition-colors"
                                href="#catalogue">
                                Explore Edit
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <!-- 2. ACTIVE FILTER BAR & TOOLBAR CONTROLS -->
            <div class="bg-surface-container-low/60 border-y border-outline-variant/40 py-3.5 px-4 flex flex-col md:flex-row md:items-center justify-between gap-4"
                id="catalogue">
                <!-- Active Pill Badges -->
                <div class="flex items-center flex-wrap gap-2">
                    <span class="text-label-sm font-label-sm uppercase tracking-wider text-outline mr-1">Active
                        Filters:</span>
                    @if ($selectedCategory)
                        <a href="{{ route('store.shop', request()->except(['category', 'page'])) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface border border-outline-variant/60 text-label-sm font-label-sm uppercase text-on-surface">
                            {{ $selectedCategory->name }} <span class="material-symbols-outlined text-[14px]">close</span>
                        </a>
                    @endif
                    @if ($searchTerm)
                        <a href="{{ route('store.shop', request()->except(['q', 'page'])) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface border border-outline-variant/60 text-label-sm font-label-sm uppercase text-on-surface">
                            {{ $searchTerm }} <span class="material-symbols-outlined text-[14px]">close</span>
                        </a>
                    @endif
                    @foreach (['size' => 'Size', 'color' => 'Color', 'fabric' => 'Fabric', 'min_price' => 'Minimum price', 'max_price' => 'Maximum price', 'availability' => 'Availability'] as $filterKey => $filterLabel)
                        @if (request()->filled($filterKey))
                            <a href="{{ route('store.shop', request()->except([$filterKey, 'page'])) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface border border-outline-variant/60 text-label-sm font-label-sm uppercase text-on-surface">
                                {{ $filterLabel }}: {{ request($filterKey) }} <span
                                    class="material-symbols-outlined text-[14px]">close</span>
                            </a>
                        @endif
                    @endforeach
                    @if (
                        !$selectedCategory &&
                            !$searchTerm &&
                            !request()->hasAny(['size', 'color', 'fabric', 'min_price', 'max_price', 'availability']))
                        <span class="text-body-sm text-on-surface-variant">No filters applied</span>
                    @else
                        <a class="text-secondary hover:underline font-label-sm text-label-sm tracking-wider uppercase ml-2"
                            href="{{ route('store.shop') }}">Clear All</a>
                    @endif
                </div>
                <!-- Sorting & Mobile Filter Toggle Trigger -->
                <div class="flex flex-wrap items-center justify-between gap-3 md:justify-end md:gap-4">
                    <!-- Results Counter -->
                    <span
                        class="text-body-sm font-body-sm text-on-surface-variant">{{ $products->total() ? $products->firstItem() . '–' . $products->lastItem() . ' of ' . $products->total() : 'No products found' }}
                        items</span>
                    <!-- Mobile Filter Sheet Button -->
                    <a href="#filters"
                        class="lg:hidden inline-flex items-center gap-2 px-3 py-1.5 border border-outline-variant text-on-surface text-label-md font-label-md tracking-wider uppercase">
                        <span class="material-symbols-outlined text-[16px]">tune</span>
                        Filters
                    </a>
                    <!-- Sort Select -->
                    <form action="{{ route('store.shop') }}" method="GET"
                        class="flex min-w-0 max-w-full items-center gap-2 border border-outline-variant/60 bg-surface px-2 py-1.5 sm:px-3">
                        @foreach (request()->except(['sort', 'page']) as $filterKey => $filterValue)
                            <input type="hidden" name="{{ $filterKey }}" value="{{ $filterValue }}">
                        @endforeach
                        <label for="product-sort"
                            class="text-label-sm font-label-sm uppercase tracking-wider text-outline">Sort By:</label>
                        <select id="product-sort" name="sort" onchange="this.form.submit()"
                            class="min-w-0 max-w-[48vw] bg-transparent text-label-sm font-label-sm uppercase text-on-surface font-medium sm:max-w-none">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>New Arrivals</option>
                            <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name A–Z</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High
                            </option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low
                            </option>
                        </select>
                    </form>
                </div>
            </div>
            <!-- MAIN TWO-COLUMN WORKSPACE: FILTERS & PRODUCTS -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                <!-- 3. PRODUCT FILTERING SIDEBAR (Desktop) -->
                <aside id="filters" aria-label="Filters" class="lg:col-span-3 space-y-8 lg:sticky lg:top-6 scroll-mt-40">
                    <form action="{{ route('store.shop') }}" method="GET" class="space-y-8">
                        <!-- Category Section -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-2">
                                <h2
                                    class="text-label-md font-label-md uppercase tracking-[0.18em] text-on-surface font-semibold">
                                    Category</h2>
                                <span class="material-symbols-outlined text-[16px] text-outline">remove</span>
                            </div>
                            <div class="space-y-2 text-body-sm font-body-sm">
                                <a href="{{ route('store.shop', request()->except(['category', 'page'])) }}"
                                    class="flex items-center justify-between text-on-surface-variant hover:text-primary {{ !$selectedCategory ? 'font-semibold text-secondary' : '' }}">
                                    <span>All products</span><span
                                        class="text-label-sm text-outline">{{ $products->total() }}</span>
                                </a>
                                @foreach ($categories as $category)
                                    <a href="{{ route('store.shop', request()->except(['category', 'page']) + ['category' => $category->slug]) }}"
                                        class="flex items-center justify-between text-on-surface-variant hover:text-primary {{ $selectedCategory?->is($category) ? 'font-semibold text-secondary' : '' }}">
                                        <span>{{ $category->name }}</span><span
                                            class="text-label-sm text-outline">{{ $category->visible_products_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <!-- Price Range Section -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-2">
                                <h2
                                    class="text-label-md font-label-md uppercase tracking-[0.18em] text-on-surface font-semibold">
                                    Price (PKR)</h2>
                                <span class="material-symbols-outlined text-[16px] text-outline">remove</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="text-label-sm font-label-sm text-outline">Min
                                    <input type="number" name="min_price" min="0" step="1"
                                        value="{{ request('min_price') }}"
                                        placeholder="{{ number_format($filterOptions['minPrice']) }}"
                                        class="mt-1 w-full border border-outline-variant/60 bg-surface px-2.5 py-2 text-on-surface">
                                </label>
                                <label class="text-label-sm font-label-sm text-outline">Max
                                    <input type="number" name="max_price" min="0" step="1"
                                        value="{{ request('max_price') }}"
                                        placeholder="{{ number_format($filterOptions['maxPrice']) }}"
                                        class="mt-1 w-full border border-outline-variant/60 bg-surface px-2.5 py-2 text-on-surface">
                                </label>
                            </div>
                        </div>
                        <!-- Size Matrix -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-2">
                                <h2
                                    class="text-label-md font-label-md uppercase tracking-[0.18em] text-on-surface font-semibold">
                                    Size</h2>
                                <span class="material-symbols-outlined text-[16px] text-outline">remove</span>
                            </div>
                            <select name="size"
                                class="w-full border border-outline-variant/60 bg-surface px-3 py-2 text-body-sm text-on-surface">
                                <option value="">All sizes</option>
                                @foreach ($filterOptions['sizes'] as $size)
                                    <option value="{{ $size }}"
                                        {{ request('size') === $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Color Palette Swatches -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-2">
                                <h2
                                    class="text-label-md font-label-md uppercase tracking-[0.18em] text-on-surface font-semibold">
                                    Colour Palette</h2>
                                <span class="material-symbols-outlined text-[16px] text-outline">remove</span>
                            </div>
                            <select name="color"
                                class="w-full border border-outline-variant/60 bg-surface px-3 py-2 text-body-sm text-on-surface">
                                <option value="">All colours</option>
                                @foreach ($filterOptions['colors'] as $color)
                                    <option value="{{ $color }}"
                                        {{ request('color') === $color ? 'selected' : '' }}>{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Availability -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-2">
                                <h2
                                    class="text-label-md font-label-md uppercase tracking-[0.18em] text-on-surface font-semibold">
                                    Availability</h2>
                                <span class="material-symbols-outlined text-[16px] text-outline">remove</span>
                            </div>
                            <select name="availability"
                                class="w-full border border-outline-variant/60 bg-surface px-3 py-2 text-body-sm text-on-surface">
                                <option value="in_stock"
                                    {{ (request('availability') ?? 'in_stock') === 'in_stock' ? 'selected' : '' }}>In stock
                                </option>
                                <option value="out_of_stock"
                                    {{ request('availability') === 'out_of_stock' ? 'selected' : '' }}>Out of stock
                                </option>
                                <option value="all" {{ request('availability') === 'all' ? 'selected' : '' }}>All
                                    availability</option>
                            </select>
                        </div>
                        <!-- Fabric & Craft -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-outline-variant/60 pb-2">
                                <h2
                                    class="text-label-md font-label-md uppercase tracking-[0.18em] text-on-surface font-semibold">
                                    Fabric &amp; Craft</h2>
                                <span class="material-symbols-outlined text-[16px] text-outline">remove</span>
                            </div>
                            <select name="fabric"
                                class="w-full border border-outline-variant/60 bg-surface px-3 py-2 text-body-sm text-on-surface">
                                <option value="">All fabrics</option>
                                @foreach ($filterOptions['fabrics'] as $fabric)
                                    <option value="{{ $fabric }}"
                                        {{ request('fabric') === $fabric ? 'selected' : '' }}>{{ $fabric }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="q" value="{{ $searchTerm }}">
                        <input type="hidden" name="category" value="{{ $selectedCategory?->slug }}">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <button type="submit"
                            class="w-full bg-primary px-4 py-2.5 text-label-sm font-label-sm uppercase tracking-wider text-on-primary transition-colors hover:bg-primary-container">Apply
                            filters</button>
                        <a href="{{ route('store.shop') }}"
                            class="block text-center text-label-sm font-label-sm uppercase tracking-wider text-secondary hover:underline">Reset
                            filters</a>
                    </form>
                </aside>
                <!-- 4. PRODUCT GRID (Desktop 3/4 columns in 9 cols space) -->
                <section aria-label="Product Catalogue" class="col-span-1 lg:col-span-9 space-y-12">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @forelse ($products as $product)
                            @include('storefront.components.product-card', [
                                'product' => $product,
                                'wishlisted' => in_array($product->id, $wishlistIds, true),
                            ])
                        @empty
                            <p class="col-span-full py-16 text-center text-body-md text-on-surface-variant">
                                {{ $searchTerm ? 'No products match your search.' : 'No products are available in this collection yet.' }}
                            </p>
                        @endforelse
                    </div>
                    @if ($products->hasPages())
                        <div class="border-t border-outline-variant/40 pt-8">
                            {{ $products->onEachSide(1)->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
