
@extends('storefront.layouts.store')

@section('title', 'Our Story | ' . config('storefront.store_name', 'BENAZIR'))

@section('content')
<section class="relative overflow-hidden bg-background">

    {{-- Hero --}}
    <div class="relative">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-margin py-14 sm:py-20 lg:grid-cols-2 lg:gap-20 lg:px-margin-md lg:py-24">

            <div class="relative z-10">
                <div class="mb-6 flex items-center gap-3">
                    <span class="h-px w-10 bg-secondary"></span>
                    <p class="font-label-uppercase text-label-uppercase tracking-[0.24em] text-secondary">
                        THE {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} STORY
                    </p>
                </div>

                <h1 class="font-display-hero text-headline-lg leading-[1.08] tracking-tight text-primary sm:text-5xl lg:text-7xl">
                    Heritage in every thread.
                    <span class="mt-2 block italic text-secondary">Elegance in every detail.</span>
                </h1>

                <p class="mt-7 max-w-xl text-base leading-8 text-on-surface-variant sm:text-lg">
                    {{ config('storefront.store_name', 'BENAZIR') }} brings the artistry of Pakistan's ateliers into clothing made for modern life.
                    Our collections celebrate thoughtful detail, expressive colour, and the generations
                    of craft behind every stitch.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-5">
                    <a href="{{ route('store.shop') }}"
                       class="inline-flex items-center gap-3 rounded-lg bg-primary px-6 py-3.5 text-sm font-semibold tracking-wide text-on-primary shadow-sm transition hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        Discover the collection
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>

                    <a href="{{ url('/contact') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-secondary">
                        Get to know us
                        <span class="material-symbols-outlined text-lg">north_east</span>
                    </a>
                </div>

                <div class="mt-12 border-t border-outline-variant/60 pt-6">
                    <p class="font-label-uppercase text-xs tracking-[0.22em] text-on-surface-variant">
                        OUR WORLD
                    </p>
                    <p class="mt-3 font-display-hero text-xl tracking-[0.16em] text-primary sm:text-2xl">
                        LAHORE <span class="text-secondary">·</span> LONDON <span class="text-secondary">·</span> NEW YORK
                    </p>
                </div>
            </div>

            {{-- Editorial visual panel --}}
            <div class="relative mx-auto w-full max-w-xl lg:max-w-none">
                <div class="absolute -right-4 -top-4 h-28 w-28 rounded-full border border-secondary/40 sm:-right-6 sm:-top-6 sm:h-40 sm:w-40"></div>

                <div class="relative overflow-hidden rounded-t-full rounded-b-2xl border border-outline-variant/50 bg-surface-container-low p-3 shadow-sm">
                    {{-- Replace this decorative panel with a real campaign photograph when available --}}
                    <div class="flex min-h-[380px] flex-col items-center justify-center rounded-t-full rounded-b-xl bg-gradient-to-b from-surface-container-low to-surface-container-high px-6 py-12 text-center sm:min-h-[520px]">
                        <span class="material-symbols-outlined text-5xl text-secondary">spa</span>

                        <p class="mt-8 font-display-hero text-4xl tracking-[0.15em] text-primary sm:text-5xl">
                            {{ config('storefront.store_name', 'BENAZIR') }}
                        </p>

                        <span class="my-5 h-px w-16 bg-secondary"></span>

                        <p class="max-w-xs text-sm leading-7 text-on-surface-variant sm:text-base">
                            A celebration of heritage, artistry and the beauty of dressing with intention.
                        </p>

                        <p class="mt-8 text-xs uppercase tracking-[0.25em] text-secondary">
                            Rooted in tradition · Made for today
                        </p>
                    </div>
                </div>

                <div class="absolute -bottom-5 -left-3 rounded-xl border border-outline-variant/60 bg-surface-container-lowest px-5 py-4 shadow-md sm:-left-6 sm:px-7">
                    <p class="font-display-hero text-xl text-primary sm:text-2xl">
                        Crafted with care.
                    </p>
                    <p class="mt-1 text-xs tracking-wide text-on-surface-variant">
                        Inspired by our heritage
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Brand introduction --}}
    <div class="border-y border-outline-variant/50 bg-surface-container-low">
        <div class="mx-auto max-w-4xl px-margin py-16 text-center sm:py-20 lg:px-margin-md lg:py-24">
            <span class="material-symbols-outlined text-3xl text-secondary">auto_awesome</span>

            <p class="mt-5 font-label-uppercase text-xs tracking-[0.25em] text-secondary">
                MORE THAN WHAT WE WEAR
            </p>

            <h2 class="mt-5 font-display-hero text-3xl leading-tight text-primary sm:text-4xl lg:text-5xl">
                Clothing that carries a story.
            </h2>

            <p class="mx-auto mt-6 max-w-3xl text-base leading-8 text-on-surface-variant sm:text-lg">
                We believe clothing can be both deeply personal and beautifully expressive.
                From the first impression of a fabric to the smallest finishing detail,
                every element contributes to how a garment feels, moves and becomes part of your story.
            </p>

            <p class="mx-auto mt-5 max-w-3xl text-base leading-8 text-on-surface-variant">
                Our vision brings the richness of South Asian design into a contemporary wardrobe,
                creating pieces to be treasured, styled in your own way and worn on the occasions that matter.
            </p>
        </div>
    </div>

    {{-- Brand values --}}
    <div class="mx-auto max-w-7xl px-margin py-16 sm:py-20 lg:px-margin-md lg:py-24">
        <div class="mx-auto mb-12 max-w-2xl text-center">
            <p class="font-label-uppercase text-xs tracking-[0.24em] text-secondary">
                THE {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} DIFFERENCE
            </p>

            <h2 class="mt-4 font-display-hero text-3xl leading-tight text-primary sm:text-4xl lg:text-5xl">
                Our philosophy, in every detail.
            </h2>

            <p class="mt-4 leading-7 text-on-surface-variant">
                The principles that shape our approach to design, craftsmanship and personal style.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Value 1 --}}
            <article class="reveal reveal-up group rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-7 transition duration-300 hover:-translate-y-1 hover:border-secondary/60 hover:shadow-lg sm:p-8">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary/10 transition group-hover:bg-secondary/15">
                    <span class="material-symbols-outlined text-3xl text-secondary">history_edu</span>
                </div>

                <p class="mt-7 text-xs font-semibold uppercase tracking-[0.2em] text-secondary">
                    01 / HERITAGE
                </p>

                <h3 class="mt-3 font-display-hero text-2xl text-primary">
                    Rooted in tradition
                </h3>

                <p class="mt-4 text-sm leading-7 text-on-surface-variant">
                    We draw inspiration from the colours, textiles, silhouettes and artistic traditions
                    that make South Asian clothing so distinctive.
                </p>
            </article>

            {{-- Value 2 --}}
            <article class="reveal reveal-up group rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-7 transition duration-300 hover:-translate-y-1 hover:border-secondary/60 hover:shadow-lg sm:p-8">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary/10 transition group-hover:bg-secondary/15">
                    <span class="material-symbols-outlined text-3xl text-secondary">draw</span>
                </div>

                <p class="mt-7 text-xs font-semibold uppercase tracking-[0.2em] text-secondary">
                    02 / CRAFT
                </p>

                <h3 class="mt-3 font-display-hero text-2xl text-primary">
                    Thoughtful details
                </h3>

                <p class="mt-4 text-sm leading-7 text-on-surface-variant">
                    From expressive embellishments to considered finishing touches, we believe the
                    smallest details can make a garment feel extraordinary.
                </p>
            </article>

            {{-- Value 3 --}}
            <article class="reveal reveal-up group rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-7 transition duration-300 hover:-translate-y-1 hover:border-secondary/60 hover:shadow-lg sm:col-span-2 sm:p-8 lg:col-span-1">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary/10 transition group-hover:bg-secondary/15">
                    <span class="material-symbols-outlined text-3xl text-secondary">styler</span>
                </div>

                <p class="mt-7 text-xs font-semibold uppercase tracking-[0.2em] text-secondary">
                    03 / PERSONAL STYLE
                </p>

                <h3 class="mt-3 font-display-hero text-2xl text-primary">
                    Elegance your way
                </h3>

                <p class="mt-4 text-sm leading-7 text-on-surface-variant">
                    Our collections are designed to inspire individual expression, allowing each
                    wearer to make every piece their own.
                </p>
            </article>

        </div>
    </div>

    {{-- Heritage and modern life --}}
    <div class="border-y border-outline-variant/50 bg-surface-container-low">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-margin py-16 lg:grid-cols-2 lg:gap-16 lg:px-margin-md lg:py-20">

            <div class="relative overflow-hidden rounded-2xl border border-outline-variant/50 bg-background p-8 sm:p-12">
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full border border-secondary/30"></div>

                <div class="relative">
                    <p class="font-label-uppercase text-xs tracking-[0.22em] text-secondary">
                        OUR INSPIRATION
                    </p>

                    <h2 class="mt-5 font-display-hero text-3xl leading-tight text-primary sm:text-4xl">
                        From our heritage to your wardrobe.
                    </h2>

                    <p class="mt-5 leading-8 text-on-surface-variant">
                        Pakistani fashion carries a remarkable visual language: rich colour,
                        graceful silhouettes, intricate surface details and a deep connection
                        to celebration.
                    </p>

                    <p class="mt-4 leading-8 text-on-surface-variant">
                        {{ config('storefront.store_name', 'BENAZIR') }} embraces that inspiration while looking toward the way women dress
                        today: with confidence, individuality and a sense of occasion.
                    </p>

                    <div class="mt-8 flex items-center gap-3">
                        <span class="h-px w-10 bg-secondary"></span>
                        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-primary">
                            Heritage meets modernity
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <p class="font-label-uppercase text-xs tracking-[0.24em] text-secondary">
                    OUR PROMISE
                </p>

                <h2 class="mt-4 font-display-hero text-3xl leading-tight text-primary sm:text-4xl">
                    A more personal way to dress.
                </h2>

                <p class="mt-5 leading-8 text-on-surface-variant">
                    Finding something beautiful should feel just as special as wearing it.
                    We want your experience with {{ config('storefront.store_name', 'BENAZIR') }} to feel considered, welcoming and
                    easy to navigate, from exploring the collection to choosing the details
                    that suit you.
                </p>

                <div class="mt-8 space-y-5">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined mt-1 text-2xl text-secondary">check_circle</span>
                        <div>
                            <h3 class="font-semibold text-primary">A considered collection</h3>
                            <p class="mt-1 text-sm leading-6 text-on-surface-variant">
                                Discover designs selected for their character, colour and detail.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined mt-1 text-2xl text-secondary">check_circle</span>
                        <div>
                            <h3 class="font-semibold text-primary">Guidance when you need it</h3>
                            <p class="mt-1 text-sm leading-6 text-on-surface-variant">
                                Our contact page provides a way to ask about sizing, products and orders.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined mt-1 text-2xl text-secondary">check_circle</span>
                        <div>
                            <h3 class="font-semibold text-primary">Style with individuality</h3>
                            <p class="mt-1 text-sm leading-6 text-on-surface-variant">
                                Make each ensemble your own, whether dressing for everyday moments
                                or a special celebration.
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ url('/contact') }}"
                   class="mt-9 inline-flex items-center gap-3 rounded-lg border border-primary/30 px-6 py-3.5 text-sm font-semibold text-primary transition hover:border-primary hover:bg-primary hover:text-on-primary">
                    Connect with our team
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </a>
            </div>

        </div>
    </div>

    {{-- Final call to action --}}
    <div class="relative overflow-hidden bg-primary">
        <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full border border-white/10"></div>
        <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full border border-white/10"></div>

        <div class="relative mx-auto max-w-4xl px-margin py-16 text-center sm:py-20 lg:px-margin-md lg:py-24">
            <span class="material-symbols-outlined text-4xl text-secondary">flare</span>

            <p class="mt-5 font-label-uppercase text-xs tracking-[0.25em] text-secondary">
                YOUR {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} STORY STARTS HERE
            </p>

            <h2 class="mt-5 font-display-hero text-3xl leading-tight text-on-primary sm:text-4xl lg:text-5xl">
                Find the pieces that feel like you.
            </h2>

            <p class="mx-auto mt-5 max-w-2xl leading-7 text-on-primary/75">
                Explore a collection inspired by heritage and designed for your own expression
                of elegance.
            </p>

            <a href="{{ route('store.shop') }}"
               class="mt-8 inline-flex items-center gap-3 rounded-lg bg-secondary px-7 py-4 text-sm font-semibold tracking-wide text-white transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary">
                Explore the collection
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>

            <p class="mt-8 text-xs tracking-[0.24em] text-on-primary/60">
                LAHORE · LONDON · NEW YORK
            </p>
        </div>
    </div>

</section>
@endsection
