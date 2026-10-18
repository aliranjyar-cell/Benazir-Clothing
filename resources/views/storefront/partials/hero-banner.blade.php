<div class="bg-surface pt-space-lg px-margin lg:px-margin-md">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-space-md">
        <div>
            <p class="font-label-uppercase text-label-uppercase tracking-widest text-primary">NEW ARRIVALS</p>
            <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-xs leading-[1.15] tracking-tight">
                @if (isset($title))
                    {{ $title }}
                @else
                    Welcome to {{ config('storefront.store_name', 'AURELLE') }}
                @endif
            </h1>
            @if (isset($description))
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-sm leading-relaxed">{{ $description }}</p>
            @endif
        </div>

        <div class="flex items-center gap-space-md">
            <a
                class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-5 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest transition-colors hover:bg-primary-container"
                href="{{ route('store.shop') }}"
            >
                Explore the collection
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>

    <div class="mt-space-2xl grid gap-space-lg sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($products as $product)
            @include('storefront.partials.product-card')
        @endforeach
    </div>

    <div class="mt-space-2xl text-center text-body-sm text-on-surface-variant">
        <a
            class="font-label-uppercase text-label-uppercase tracking-widest text-primary inline-flex items-center gap-space-sm hover:text-secondary"
            href="{{ route('store.shop') }}"
        >
            View all collections
            <span class="material-symbols-outlined text-xs">arrow_forward</span>
        </a>
    </div>
</div>
