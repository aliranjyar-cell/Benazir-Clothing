@extends('storefront.layouts.store')

@section('title', 'New Arrivals')

@section('content')
    <section class="relative overflow-hidden bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-2xl lg:py-space-3xl">
            <div class="max-w-2xl">
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-primary">AURELLE · THE NEW EDIT</p>
                <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-lg leading-[1.15] tracking-tight">
                    New Arrivals
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-md leading-relaxed">
                    Fresh pieces added every week. Hand-embroidered organza dupattas, woven
                    suiting, and lawn collections, shipped with love from our atelier.
                </p>
            </div>

            <div class="mt-space-2xl grid gap-space-lg sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $newArrivals = App\Models\Product::query()
                        ->active()
                        ->with(['category:id,name,slug', 'images' => fn ($q) => $q->orderBy('sort_order')->limit(1)])
                        ->orderByDesc('id')
                        ->limit(4)
                        ->get();
                @endphp

                @foreach ($newArrivals as $product)
                    @include('storefront.partials.product-card')
                @endforeach
            </div>
        </div>
    </section>
@endsection
