
@extends('storefront.layouts.app')

@section('title', 'AURELLE — The Art of Dressing')

@section('content')

{{-- =========================================================
     HERO SECTION
========================================================= --}}
<section class="relative flex min-h-[680px] items-center overflow-hidden bg-[#35251f] text-white lg:min-h-[820px]">

    @php
        $heroImage = $heroProduct?->images->first();
        $heroTitle = data_get($content, 'hero_title', 'The art of being timeless.');
        $heroDescription = data_get(
            $content,
            'hero_description',
            'Thoughtfully chosen fabrics, considered details, and pieces made to become part of your story.'
        );
        $heroButton = data_get($content, 'hero_button', 'Explore the collection');
    @endphp

    @if ($heroImage)
        <img
            src="{{ asset('storage/' . $heroImage->image_path) }}"
            alt="{{ $heroProduct->name }}"
            fetchpriority="high"
            class="absolute inset-0 h-full w-full object-cover object-center"
        >
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-[#604638] via-[#493128] to-[#241813]"></div>
    @endif

    <div class="absolute inset-0 bg-gradient-to-r from-[#241813]/85 via-[#241813]/55 to-[#241813]/10"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#241813]/30 via-transparent to-transparent"></div>

    <div class="relative z-10 mx-auto w-full max-w-[1500px] px-6 py-36 sm:px-10 lg:px-20">

        <p class="mb-6 text-[10px] uppercase tracking-[0.3em] text-[#e4c2a8] sm:text-xs">
            {{ data_get($content, 'hero_eyebrow', 'The new season · AURELLE') }}
        </p>

        <h1 class="max-w-4xl font-serif text-5xl font-normal leading-[1.08] tracking-tight sm:text-7xl lg:text-8xl xl:text-[104px]">
            {{ data_get($content, 'hero_heading_line_1', 'The art of') }}<br>
            <span class="italic text-[#e4c2a8]">
                {{ data_get($content, 'hero_heading_line_2', 'being timeless.') }}
            </span>
        </h1>

        <p class="mt-7 max-w-md text-sm leading-8 text-white/80 sm:text-base">
            {{ $heroDescription }}
        </p>

        <a
            href="{{ route('store.shop') }}"
            class="mt-9 inline-flex items-center gap-6 bg-[#fffdf9] px-7 py-4 text-[10px] uppercase tracking-[0.16em] text-[#35251f] transition duration-300 hover:-translate-y-1 hover:bg-[#e8d7c6] sm:text-xs"
        >
            {{ $heroButton }}
            <span aria-hidden="true">↗</span>
        </a>

        @if ($heroProduct)
            <a
                href="{{ route('store.products.show', $heroProduct->slug) }}"
                class="mt-5 block text-xs text-white/75 transition hover:text-white"
            >
                Discover {{ $heroProduct->name }} →
            </a>
        @endif

    </div>

    <div class="absolute bottom-6 left-6 right-6 z-10 flex justify-between gap-4 text-[8px] uppercase tracking-[0.16em] text-white/75 sm:left-10 sm:right-10 sm:text-[10px] sm:tracking-[0.2em] lg:left-20 lg:right-20">
        <span>Designed for moments that matter</span>
        <span>Discover AURELLE</span>
    </div>

</section>


{{-- =========================================================
     COLLECTIONS / CATEGORIES
========================================================= --}}
<section id="collections" class="bg-[#fffdf9] px-5 py-20 sm:px-8 lg:px-16 lg:py-28">

    <div class="mx-auto mb-10 flex max-w-[1500px] flex-col justify-between gap-5 md:mb-12 md:flex-row md:items-end">

        <div>
            <p class="text-[10px] uppercase tracking-[0.25em] text-[#a66f53] sm:text-xs">
                {{ data_get($content, 'collections_eyebrow', 'Find your feeling') }}
            </p>

            <h2 class="mt-4 font-serif text-4xl leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                {{ data_get($content, 'collections_heading', 'Dress for your own rhythm.') }}
            </h2>
        </div>

        <p class="max-w-sm text-sm leading-7 text-[#817168]">
            {{ data_get($content, 'collections_description', 'From beautiful unstitched fabrics to ready-to-wear silhouettes, discover pieces that feel like you.') }}
        </p>

    </div>

    <div class="mx-auto grid max-w-[1500px] grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-5">

        @forelse ($categories as $category)

            <a
                href="{{ route('store.shop', ['category' => $category->slug]) }}"
                class="group relative aspect-[3/4] overflow-hidden bg-[#e8d7c6] {{ $loop->even ? 'lg:mt-10' : '' }}"
            >

                @if ($category->image_path)
                    <img
                        src="{{ asset('storage/' . $category->image_path) }}"
                        alt="{{ $category->name }}"
                        loading="lazy"
                        class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                    >
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#e8d7c6] to-[#a66f53]">
                        <span class="px-4 text-center font-serif text-2xl text-[#35251f]">
                            {{ $category->name }}
                        </span>
                    </div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-[#241813]/80 via-[#241813]/10 to-transparent"></div>

                <div class="absolute bottom-5 left-4 right-3 text-white sm:bottom-7 sm:left-7 sm:right-5">

                    <p class="mb-2 text-[8px] uppercase tracking-[0.18em] text-white/75 sm:text-[9px] sm:tracking-[0.2em]">
                        {{ $category->parent?->name ?? 'Explore the collection' }}
                    </p>

                    <h3 class="font-serif text-xl sm:text-2xl lg:text-3xl">
                        {{ $category->name }}
                    </h3>

                    @if ($category->description)
                        <p class="mt-2 hidden text-xs leading-5 text-white/80 sm:block">
                            {{ \Illuminate\Support\Str::limit($category->description, 75) }}
                        </p>
                    @endif

                    <span class="mt-3 inline-block text-sm transition duration-300 group-hover:translate-x-2">
                        Explore ↗
                    </span>

                </div>

            </a>

        @empty

            <div class="col-span-full bg-[#f8f3ed] px-6 py-16 text-center">
                <h3 class="font-serif text-2xl">Our collections are coming soon.</h3>
                <p class="mt-3 text-sm text-[#817168]">
                    Please check back soon for our latest collections.
                </p>
            </div>

        @endforelse

    </div>

</section>


{{-- =========================================================
     BRAND PROMISE
========================================================= --}}
<section class="bg-[#e9ddd1] px-5 py-9 sm:px-8 lg:px-16">

    <div class="mx-auto grid max-w-[1400px] grid-cols-2 gap-x-5 gap-y-7 lg:grid-cols-4">

        @foreach ([
            ['01', 'Thoughtful quality', 'Details worth noticing'],
            ['02', 'Delivery across Pakistan', 'To your doorstep'],
            ['03', 'Secure shopping', 'A simple shopping experience'],
            ['04', 'Here to help', 'Customer care with heart'],
        ] as $promise)

            <div class="border-l border-[#604638]/20 pl-4 sm:pl-6">

                <p class="text-[10px] tracking-[0.2em] text-[#a66f53]">
                    {{ $promise[0] }}
                </p>

                <h3 class="mt-2 font-serif text-base sm:text-xl">
                    {{ $promise[1] }}
                </h3>

                <p class="mt-2 text-[10px] leading-5 text-[#817168] sm:text-xs">
                    {{ $promise[2] }}
                </p>

            </div>

        @endforeach

    </div>

</section>


{{-- =========================================================
     FEATURED PRODUCTS
========================================================= --}}
<section id="products" class="bg-[#f8f3ed] px-5 py-20 sm:px-8 lg:px-16 lg:py-28">

    <div class="mx-auto mb-10 flex max-w-[1500px] flex-col justify-between gap-5 sm:mb-12 sm:flex-row sm:items-end">

        <div>
            <p class="text-[10px] uppercase tracking-[0.25em] text-[#a66f53] sm:text-xs">
                {{ data_get($content, 'products_eyebrow', 'Selected with intention') }}
            </p>

            <h2 class="mt-4 font-serif text-4xl tracking-tight sm:text-5xl lg:text-6xl">
                {{ data_get($content, 'products_heading', 'Pieces to keep close.') }}
            </h2>

            <p class="mt-3 max-w-lg text-sm leading-7 text-[#817168]">
                {{ data_get($content, 'products_description', 'Discover pieces selected for their thoughtful details, beautiful fabrics, and timeless appeal.') }}
            </p>
        </div>

        <a
            href="{{ route('store.shop') }}"
            class="inline-flex items-center gap-3 self-start border-b border-[#a66f53] pb-2 text-[10px] uppercase tracking-[0.15em] sm:self-auto sm:text-xs"
        >
            Explore all pieces <span>↗</span>
        </a>

    </div>

    <div class="mx-auto grid max-w-[1500px] grid-cols-2 gap-x-3 gap-y-9 sm:gap-x-5 sm:gap-y-12 lg:grid-cols-4 lg:gap-x-6">

        @forelse ($products as $product)

            @php
                $variant = $product->activeVariants->first();
                $image = $product->images->first();
            @endphp

            <article class="group min-w-0">

                <a
                    href="{{ route('store.products.show', $product->slug) }}"
                    class="relative block aspect-[3/4] overflow-hidden bg-[#e8d7c6]"
                >

                    @if ($image)
                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $image->alt_text ?: $product->name }}"
                            loading="lazy"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                        >
                    @else
                        <div class="flex h-full flex-col items-center justify-center px-3 text-center text-[#817168]">
                            <span class="font-serif text-xl">{{ $product->name }}</span>
                            <span class="mt-2 text-xs">Image coming soon</span>
                        </div>
                    @endif

                    @if ($product->is_featured)
                        <span class="absolute left-2 top-2 bg-[#fffdf9]/95 px-2 py-2 text-[8px] uppercase tracking-widest sm:left-3 sm:top-3 sm:px-3 sm:text-[9px]">
                            Featured
                        </span>
                    @endif

                </a>

                <div class="flex flex-col gap-2 pt-4 sm:flex-row sm:justify-between">

                    <div class="min-w-0">

                        <h3 class="text-xs font-medium sm:text-sm">
                            <a
                                href="{{ route('store.products.show', $product->slug) }}"
                                class="transition hover:text-[#a66f53]"
                            >
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="mt-1 text-[10px] text-[#817168] sm:text-xs">
                            {{ $product->category?->name ?? 'AURELLE Collection' }}
                        </p>

                    </div>

                    @if ($variant)
                        <p class="text-xs sm:whitespace-nowrap">
                            PKR {{ number_format((float) $variant->price) }}
                        </p>
                    @endif

                </div>

                @if ($product->fabric)
                    <p class="mt-2 text-[10px] text-[#817168]">
                        {{ $product->fabric }}
                    </p>
                @endif

            </article>

        @empty

            <div class="col-span-full py-14 text-center">

                <p class="text-[10px] uppercase tracking-[0.2em] text-[#a66f53]">
                    Coming soon
                </p>

                <h3 class="mt-4 font-serif text-2xl sm:text-3xl">
                    Your next favourite piece is on its way.
                </h3>

                <p class="mt-3 text-sm text-[#817168]">
                    There are no featured products available right now.
                </p>

                <a
                    href="{{ route('store.shop') }}"
                    class="mt-6 inline-flex bg-[#35251f] px-6 py-4 text-xs uppercase tracking-widest text-white transition hover:bg-[#a66f53]"
                >
                    Explore the shop ↗
                </a>

            </div>

        @endforelse

    </div>

</section>


{{-- =========================================================
     BRAND STORY
========================================================= --}}
<section id="story" class="bg-[#fffdf9] px-5 py-20 sm:px-8 lg:px-16 lg:py-28">

    <div class="mx-auto grid max-w-[1500px] overflow-hidden bg-[#eee2d6] md:grid-cols-2">

        <div class="relative min-h-[320px] overflow-hidden bg-[#e8d7c6] sm:min-h-[480px]">

            @if ($storyImage)
                <img
                    src="{{ asset('storage/' . $storyImage) }}"
                    alt="{{ data_get($content, 'story_image_alt', 'AURELLE fabrics and craftsmanship') }}"
                    loading="lazy"
                    class="absolute inset-0 h-full w-full object-cover transition duration-700 hover:scale-105"
                >
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#e8d7c6] to-[#a66f53]">
                    <span class="font-serif text-5xl text-[#35251f]">AURELLE</span>
                </div>
            @endif

        </div>

        <div class="flex flex-col items-start justify-center p-7 sm:p-12 lg:p-20">

            <p class="text-[10px] uppercase tracking-[0.25em] text-[#a66f53] sm:text-xs">
                {{ data_get($content, 'story_eyebrow', 'A little more meaning') }}
            </p>

            <h2 class="mt-5 font-serif text-4xl leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                {{ data_get($content, 'story_heading', 'Made to be remembered.') }}
            </h2>

            <p class="mt-6 text-sm leading-8 text-[#716057]">
                {{ data_get($content, 'story_description', 'We believe getting dressed can be a small, beautiful ritual. AURELLE brings together thoughtful silhouettes, expressive textiles, and details that feel personal — pieces to return to, season after season.') }}
            </p>

            <a
                href="{{ route('store.about') }}"
                class="mt-8 border-b border-[#a66f53] pb-2 text-[10px] uppercase tracking-widest sm:text-xs"
            >
                Discover our story ↗
            </a>

            <p class="mt-10 border-t border-[#604638]/20 pt-5 font-serif text-lg italic text-[#817168]">
                {{ data_get($content, 'story_quote', 'Beauty lives in the details.') }}
            </p>

        </div>

    </div>

</section>


{{-- =========================================================
     SHOP BY STYLE
     Uses actual category images from the database.
========================================================= --}}
<section class="bg-[#35251f] px-5 py-20 text-[#f8f3ed] sm:px-8 lg:px-16 lg:py-28">

    <div class="mx-auto mb-10 max-w-[1500px] sm:mb-12">

        <p class="text-[10px] uppercase tracking-[0.25em] text-[#d7b59e] sm:text-xs">
            {{ data_get($content, 'style_eyebrow', 'A point of view') }}
        </p>

        <h2 class="mt-4 font-serif text-4xl tracking-tight sm:text-5xl lg:text-6xl">
            {{ data_get($content, 'style_heading', 'Find your kind of style.') }}
        </h2>

        <p class="mt-4 max-w-lg text-sm leading-7 text-[#c5b4a9]">
            {{ data_get($content, 'style_description', 'Explore the collections and find pieces that fit your own sense of style.') }}
        </p>

    </div>

    <div class="mx-auto grid max-w-[1500px] grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-5">

        @foreach ($categories->take(4) as $category)

            <a
                href="{{ route('store.shop', ['category' => $category->slug]) }}"
                class="group relative aspect-[3/4] overflow-hidden bg-[#604638]"
            >

                @if ($category->image_path)
                    <img
                        src="{{ asset('storage/' . $category->image_path) }}"
                        alt="{{ $category->name }}"
                        loading="lazy"
                        class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                    >
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#604638] to-[#a66f53]">
                        <span class="px-3 text-center font-serif text-2xl">
                            {{ $category->name }}
                        </span>
                    </div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-[#241813]/80 via-transparent to-transparent"></div>

                <div class="absolute bottom-5 left-4 right-3 sm:bottom-7 sm:left-6">

                    <h3 class="font-serif text-xl sm:text-2xl">
                        {{ $category->name }}
                    </h3>

                    <span class="mt-3 inline-block text-sm text-[#e4c2a8] transition duration-300 group-hover:translate-x-2">
                        Explore ↗
                    </span>

                </div>

            </a>

        @endforeach

    </div>

</section>


{{-- =========================================================
     NEWSLETTER
========================================================= --}}
<section class="bg-[#d7c0ad] px-5 py-16 sm:px-8 lg:px-16 lg:py-20">

    <div class="mx-auto grid max-w-[1250px] gap-8 md:grid-cols-2 md:items-center">

        <div>

            <p class="text-[10px] uppercase tracking-[0.25em] text-[#604638] sm:text-xs">
                {{ data_get($content, 'newsletter_eyebrow', 'A note from us') }}
            </p>

            <h2 class="mt-4 font-serif text-4xl leading-tight tracking-tight sm:text-5xl">
                {{ data_get($content, 'newsletter_heading', 'A little beauty in your inbox.') }}
            </h2>

            <p class="mt-4 max-w-md text-sm leading-7 text-[#6d5547]">
                {{ data_get($content, 'newsletter_description', 'Discover new collections, thoughtful edits, and stories from AURELLE.') }}
            </p>

        </div>

        {{-- Connect this form to your newsletter subscription route before production. --}}
        <form
            action="{{ route('store.newsletter.subscribe') }}"
            method="POST"
            class="flex border-b border-[#35251f]"
        >
            @csrf

            <label for="newsletter-email" class="sr-only">
                Email address
            </label>

            <input
                id="newsletter-email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Your email address"
                required
                class="min-w-0 flex-1 bg-transparent px-1 py-5 text-sm text-[#35251f] outline-none placeholder:text-[#755f52]"
            >

            <button
                type="submit"
                class="px-2 text-[10px] uppercase tracking-widest text-[#35251f] transition hover:text-[#a66f53] sm:px-4 sm:text-xs"
            >
                Join the list ↗
            </button>

        </form>

        @error('email')
            <p class="text-sm text-red-800 md:col-start-2">
                {{ $message }}
            </p>
        @enderror

    </div>

</section>

@endsection
