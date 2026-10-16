@extends('storefront.layouts.store')

@section('title', 'Your wishlist')

@section('content')
    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            @if ($products->isEmpty())
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-outline-variant bg-surface-container-lowest py-space-2xl text-center">
                    <span class="material-symbols-outlined text-4xl text-secondary">favorite_border</span>
                    <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-sm">Your wishlist is
                        empty</h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-sm max-w-sm">
                        Products you save for later will appear here.
                    </p>
                    <a href="{{ route('store.shop') }}"
                        class="mt-space-sm inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest hover:bg-primary-container transition-colors">
                        <span class="material-symbols-outlined text-sm">shopping_bag</span>
                        Browse the collection
                    </a>
                </div>
            @else
                <div class="grid gap-space-lg sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <div
                            class="flex flex-col rounded-xl border border-outline-variant bg-surface-container-lowest transition-all hover:border-primary/40">
                            <a href="{{ route('store.products.show', $product) }}">
                                @if ($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                        alt="{{ $product->name }}" class="aspect-[4/5] w-full object-cover"
                                        loading="lazy" />
                                @endif
                            </a>
                            <div class="flex flex-col p-space-md">
                                <a href="{{ route('store.products.show', $product) }}">
                                    <h3
                                        class="font-title text-title-md text-on-surface hover:text-primary transition-colors">
                                        {{ $product->name }}</h3>
                                </a>
                                @if ($product->category)
                                    <p class="font-label-uppercase text-label-uppercase text-secondary mt-space-xs">
                                        {{ $product->category->name }}</p>
                                @endif
                                <div class="mt-auto flex items-center justify-between pt-space-sm">
                                    <p class="font-label-uppercase text-label-price text-primary">
                                        @if ($product->activeVariants->isNotEmpty())
                                            {{ $product->activeVariants->min('price') }} <span
                                                class="text-body-sm text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ config('storefront.currency_symbol', '₨') }}</span>
                                        @endif
                                    </p>
                                    <form method="POST" action="{{ route('store.wishlist.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="material-symbols-outlined text-xl text-error transition-colors hover:text-error-container"
                                            aria-label="Remove from wishlist">
                                            remove_shopping_cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
