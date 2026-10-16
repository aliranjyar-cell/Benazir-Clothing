@extends('storefront.layouts.app')

@section('content')
    <div class="flex flex-col w-full">
        <!-- SECTION 1: LUXURY HERO BANNER -->
        <section class="relative w-full overflow-hidden bg-background">
            <!-- Ambient Heritage Gold Glow & Architectural Vignette -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-secondary-fixed/40 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute top-1/2 -right-40 w-[30rem] h-[30rem] rounded-full bg-primary-fixed/30 blur-3xl pointer-events-none">
            </div>
            <!-- Architectural Micro-Grid Background Motifs -->
            <div class="absolute inset-0 opacity-[0.035] pointer-events-none"
                style="background-image: radial-gradient(#745a27 1px, transparent 1px); background-size: 28px 28px;">
            </div>
            <div class="relative max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl lg:py-space-3xl">
                <!-- Main Asymmetric Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl lg:gap-gutter items-center">
                    <!-- Left Editorial Story Column (7 Cols on Large Screen) -->
                    <div class="lg:col-span-7 flex flex-col z-10">
                        <!-- Atelier Metadata Bar / Overline -->
                        <div class="flex flex-wrap items-center gap-space-sm mb-space-md">
                            <span
                                class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-surface-container-high text-tertiary font-label-sm text-label-sm tracking-widest uppercase shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                                The Heirloom Edition • 2025 Couture
                            </span>
                            <span
                                class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-tertiary-container/10 text-tertiary-container font-label-sm text-label-sm tracking-wider uppercase">
                                <span class="material-symbols-outlined text-[14px]">stars</span>
                                Artisanal Royal Guild
                            </span>
                        </div>
                        <!-- Headline in Majestic Playfair Display Serif -->
                        <div class="space-y-space-xs mb-space-lg">
                            <p class="font-headline-sm text-headline-sm text-secondary uppercase tracking-widest">
                                Noor • Patiala • Lahore • Amritsar
                            </p>
                            <h1
                                class=" text-[3rem] text-display-lg-mobile lg:text-display-lg text-primary tracking-tight leading-[1.08]">
                                The Poetry of <br class="hidden sm:inline" />
                                <span class="italic font-normal">Punjabi Craftsmanship</span> <br />
                                &amp; Timeless Grace
                            </h1>
                        </div>
                        <!-- Editorial Narrative Subtext -->
                        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mb-space-xl leading-relaxed">
                            Immerse yourself in centuries of royal courtyard splendour, reimagined for the
                            discerning bride.
                            Floor-sweeping silken kurtas, hand-stitched Resham phulkari, and intricate antique Tilla
                            and Zardozi
                            woven into pure Chanderi tissue.
                        </p>
                        <!-- Interactive CTAs -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-space-md mb-space-2xl">
                            <!-- Primary Gold-Accented Wine CTA -->
                            <a class="group relative inline-flex items-center justify-center gap-space-sm px-space-xl py-space-md bg-primary hover:bg-primary-container text-on-primary rounded font-label-lg text-label-lg uppercase tracking-widest shadow-md transition-all duration-300 transform hover:-translate-y-0.5"
                                href="{{ route('store.shop') }}">
                                <span>Explore The Royal Collection</span>
                                <span
                                    class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                                <div class="absolute inset-x-0 bottom-0 h-0.5 bg-secondary-fixed opacity-70"></div>
                            </a>
                            <!-- Secondary Couture Concierge Link -->
                            <a class="group inline-flex items-center justify-center gap-space-sm px-space-lg py-space-md bg-surface-container-lowest hover:bg-surface-container-low text-primary rounded font-label-lg text-label-lg uppercase tracking-wider shadow-sm transition-all duration-300 hover:shadow-md"
                                href="{{ route('store.contact') }}">
                                <span class="material-symbols-outlined text-secondary text-[20px]">calendar_month</span>
                                <span>Private Bespoke Consultation</span>
                            </a>
                        </div>
                        <!-- Trust & Authenticity Pedigree Strip -->
                        <div class="pt-space-lg bg-surface-container-low/60 rounded-xl px-space-lg py-space-md shadow-sm">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-md">
                                <!-- Metric 1 -->
                                <div class="flex items-center gap-space-sm">
                                    <div
                                        class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-secondary shadow-sm">
                                        <span class="material-symbols-outlined text-[20px]">auto_fix</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-label-md text-label-md text-on-surface uppercase tracking-wider">100%
                                            Hand-Crafted</span>
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">420+ Master
                                            Artisans</span>
                                    </div>
                                </div>
                                <!-- Metric 2 -->
                                <div class="flex items-center gap-space-sm">
                                    <div
                                        class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-secondary shadow-sm">
                                        <span class="material-symbols-outlined text-[20px]">texture</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-label-md text-label-md text-on-surface uppercase tracking-wider">Heritage
                                            Silks</span>
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">Pure
                                            Organza &amp; Velvet</span>
                                    </div>
                                </div>
                                <!-- Metric 3 -->
                                <div class="flex items-center gap-space-sm">
                                    <div
                                        class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-secondary shadow-sm">
                                        <span class="material-symbols-outlined text-[20px]">flight_takeoff</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-label-md text-label-md text-on-surface uppercase tracking-wider">Global
                                            Dispatch</span>
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">Insured
                                            White-Glove</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Grand Architectural Visual Canvas (5 Cols on Large Screen) -->
                    <div class="lg:col-span-5 relative flex justify-center lg:justify-end">
                        <!-- Outer Architectural Arch Backdrop Ornament -->
                        <div class="relative w-full max-w-[480px]">
                            <!-- Layered Haveli Jharokha Silhouette Frame -->
                            <div
                                class="relative bg-surface-container-high p-space-sm sm:p-space-md rounded-t-[14rem] rounded-b-2xl shadow-xl transition-all duration-500">
                                <!-- Subtle Inner Inset Arch Border -->
                                <div
                                    class="relative overflow-hidden rounded-t-[13.5rem] rounded-b-xl bg-surface-container-lowest aspect-[3/4] shadow-inner group">
                                    <!-- Main Portrait: Royal Punjabi Bridal Silhouette -->
                                    <img alt="Royal Punjabi Bride dressed in maroon bridal suit with heavy gold zardozi embroidery and heirloom phulkari dupatta in a palace courtyard"
                                        class="w-full h-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-105"
                                        src="{{ asset('images/3wYWtlNhx3i1WvLlQtSUp4VXzxe8p683YEVxZ79C.jpg') }}" />
                                    <!-- Ambient Silk Light Wash Gradient -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/10 to-transparent opacity-60">
                                    </div>
                                    <!-- Corner Filigree Decorative Accents -->
                                    <div class="absolute top-4 left-4 text-secondary-fixed/90 pointer-events-none">
                                        <svg class="opacity-80" fill="none" height="32" viewbox="0 0 32 32"
                                            width="32">
                                            <path d="M0 0H16C16 8.83656 8.83656 16 0 16V0Z" fill="currentColor">
                                            </path>
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 text-secondary-fixed/90 pointer-events-none rotate-90">
                                        <svg class="opacity-80" fill="none" height="32" viewbox="0 0 32 32"
                                            width="32">
                                            <path d="M0 0H16C16 8.83656 8.83656 16 0 16V0Z" fill="currentColor">
                                            </path>
                                        </svg>
                                    </div>
                                    <!-- Bottom Image Badge / Overlay Caption -->
                                    <div class="absolute bottom-6 inset-x-6 z-10 flex flex-col gap-1 text-on-primary">
                                        <span
                                            class="font-label-sm text-label-sm uppercase tracking-widest text-secondary-fixed">Atelier
                                            Showcase</span>
                                        <div
                                            class="font-headline-sm text-headline-sm font-semibold tracking-wide flex items-center justify-between">
                                            <span>{{ $heroProduct?->name ?? "The 'Maharani Jindan' Kurta" }}</span>
                                            <span
                                                class="font-body-sm text-body-sm font-normal text-surface-container-high">Bespoke
                                                2025</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Floating Luxury Atelier Seal (Asymmetric Overlap Left) -->
                                <div
                                    class="absolute -left-6 top-1/3 -translate-y-1/2 hidden sm:flex items-center gap-space-sm bg-surface-container-lowest/95 backdrop-blur-md p-space-sm rounded-xl shadow-lg -rotate-3 transition-transform duration-300 hover:rotate-0">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary-container text-secondary-fixed flex items-center justify-center shadow-inner">
                                        <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                                    </div>
                                    <div class="flex flex-col pr-space-xs">
                                        <span
                                            class="font-label-sm text-label-sm uppercase text-secondary font-bold tracking-widest">Patiala
                                            Atelier</span>
                                        <span class="font-body-sm text-body-sm text-on-surface font-medium">Handloom
                                            Pure Silk</span>
                                    </div>
                                </div>
                                <!-- Quick Swatch / Silhouette Pills (Floating Bottom Right) -->
                                <div
                                    class="absolute -bottom-5 right-4 sm:right-6 bg-surface-container-lowest px-space-md py-space-sm rounded-xl shadow-lg flex items-center gap-space-sm">
                                    <span
                                        class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Silhouettes:</span>
                                    <span
                                        class="px-space-xs py-0.5 rounded bg-surface-container-high text-primary font-label-sm text-label-sm font-medium">Raw
                                        Silk Patiala</span>
                                    <span
                                        class="px-space-xs py-0.5 rounded bg-surface-container-high text-primary font-label-sm text-label-sm font-medium">Chanderi
                                        Phulkari</span>
                                </div>
                            </div>
                            <!-- Hand-drawn Style Architectural Base Pedestal -->
                            <div
                                class="w-3/4 mx-auto h-3 bg-surface-container-highest rounded-full blur-sm mt-3 opacity-60">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- SECTION 3: NEW ARRIVALS ("THE ROYAL NOORANI EDIT '25") -->
        <section class="w-full bg-surface-container-lowest py-space-3xl px-margin lg:px-margin-md" id="new-arrivals">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-space-2xl gap-space-md">
                    <div>
                        <span
                            class="font-label-uppercase text-label-uppercase tracking-[0.2em] text-secondary uppercase block mb-1">Couture
                            Release</span>
                        <h2 class="font-headline-lg text-headline-lg text-primary font-serif">The Royal Noorani
                            Edit '25</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Pristine handwork,
                            authentic vintage
                            brocade motifs, and pure heritage cuts.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="text-xs uppercase font-label-uppercase tracking-wider text-secondary-container bg-tertiary-container px-3 py-1.5 rounded-full">New
                            In Stock</span>
                        <a class="font-label-uppercase text-label-uppercase tracking-widest text-primary hover:text-secondary transition-colors uppercase flex items-center gap-1 py-1 px-3"
                            href="{{ route('store.shop') }}">
                            View All {{ $totalProducts }} Styles <span
                                class="material-symbols-outlined text-sm">north_east</span>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-lg">
                    @forelse ($products as $product)
                        @include('storefront.partials.product-card', ['product' => $product])
                    @empty
                        <p class="col-span-full py-space-xl text-center text-on-surface-variant">No products are
                            available right now.</p>
                    @endforelse
                    {{--
                        <!-- Product 1 -->
                        <div
                            class="group bg-surface rounded-2xl p-3 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300">
                            <div>
                                <div
                                    class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-surface-container mb-space-md">
                                    <img alt="Gulnar Maroon Velvet Zari Suit"
                                        class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8SGT1xT2eZ65quJxNUqyXVD-l-Pef_pTvHXdx7Gt2kywrb59MJk_xcZOM_Fq-RMdHyHpsRl_FetrOZRzGvYDMqnpUvbq2ElcQPxXtLvsHgX8sMlnrm_c1jmMPU2rXF563e3yKs5sdxkAkDIuBhuq14epDHYh54KkLa9cm5sxiffMphbKyXURNsy1ltQIG7vt1JdtOjx44wdiLR-AJrhTYHtesjXIfaqgoXySJ3c9doZ-ppkP3EuBQgA" />
                                    <button aria-label="Add to Wishlist"
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-sm text-primary hover:text-primary-container flex items-center justify-center transition-colors"
                                        type="button">
                                        <span class="material-symbols-outlined text-base">favorite</span>
                                    </button>
                                    <div class="absolute bottom-3 left-3 flex flex-wrap gap-1">
                                        <span
                                            class="px-2 py-0.5 bg-primary-container text-on-primary font-label-uppercase text-[0.6rem] tracking-wider uppercase rounded">Hand-Embroidered</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p
                                        class="font-label-uppercase text-[0.625rem] text-secondary tracking-widest uppercase">
                                        Pure Velvet •
                                        Zardozi</p>
                                    <h3
                                        class="font-headline-sm text-headline-sm text-primary font-serif group-hover:text-secondary transition-colors">
                                        Gulnar Maroon Velvet Zari Suit</h3>
                                    <p class="font-label-price text-label-price text-on-surface">$385 <span
                                            class="text-xs text-on-surface-variant font-normal">USD</span></p>
                                </div>
                            </div>
                            <div class="mt-space-md pt-space-xs">
                                <div
                                    class="flex items-center justify-between text-[0.6875rem] font-label-uppercase text-on-surface-variant mb-2">
                                    <span>Select Size</span>
                                    <span class="text-secondary cursor-pointer hover:underline">Custom Stitching
                                        Available</span>
                                </div>
                                <div class="grid grid-cols-5 gap-1 mb-3">
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">XS</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors font-semibold"
                                        type="button">S</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">M</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">L</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">XL</button>
                                </div>
                                <button
                                    class="w-full py-2.5 bg-primary-container hover:bg-primary text-on-primary font-label-uppercase text-label-uppercase tracking-widest uppercase rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                    type="button">
                                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                    <span>Add to Bag</span>
                                </button>
                            </div>
                        </div>
                        <!-- Product 2 -->
                        <div
                            class="group bg-surface rounded-2xl p-3 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300">
                            <div>
                                <div
                                    class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-surface-container mb-space-md">
                                    <img alt="Nafisa Sage &amp; Ivory Resham Kameez"
                                        class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDqDBlFDlbkRfdcKFivUaa1GpGnmrSotvgchglzBiS7-zpxzdCvmHs-23QpAtcuggyHahgS5oWFrdA4ffW3B0PBw1jAyxDnp5DhTt_QDGzISa0PFNDBTHS62ZGpgvAg3aT7fmaXnqZDIskW4kW92I07vkLk-eT2_Im6_1sN2IUP0r2IhdArTJl-IKzWjjy5ldg9nBulNDvhkmSegYM-ot-k5pEe0GSu0palWDu23MqqhYiLUY9ggyzgag" />
                                    <button aria-label="Add to Wishlist"
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-sm text-primary hover:text-primary-container flex items-center justify-center transition-colors"
                                        type="button">
                                        <span class="material-symbols-outlined text-base">favorite</span>
                                    </button>
                                    <div class="absolute bottom-3 left-3 flex flex-wrap gap-1">
                                        <span
                                            class="px-2 py-0.5 bg-tertiary-container text-on-tertiary-container font-label-uppercase text-[0.6rem] tracking-wider uppercase rounded">Pure
                                            Raw Silk</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p
                                        class="font-label-uppercase text-[0.625rem] text-secondary tracking-widest uppercase">
                                        Resham •
                                        Organza Dupatta</p>
                                    <h3
                                        class="font-headline-sm text-headline-sm text-primary font-serif group-hover:text-secondary transition-colors">
                                        Nafisa Sage &amp; Ivory Resham Kameez</h3>
                                    <p class="font-label-price text-label-price text-on-surface">$320 <span
                                            class="text-xs text-on-surface-variant font-normal">USD</span></p>
                                </div>
                            </div>
                            <div class="mt-space-md pt-space-xs">
                                <div
                                    class="flex items-center justify-between text-[0.6875rem] font-label-uppercase text-on-surface-variant mb-2">
                                    <span>Select Size</span>
                                    <span class="text-secondary cursor-pointer hover:underline">Size Chart</span>
                                </div>
                                <div class="grid grid-cols-5 gap-1 mb-3">
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">XS</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">S</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors font-semibold"
                                        type="button">M</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">L</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">XL</button>
                                </div>
                                <button
                                    class="w-full py-2.5 bg-primary-container hover:bg-primary text-on-primary font-label-uppercase text-label-uppercase tracking-widest uppercase rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                    type="button">
                                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                    <span>Add to Bag</span>
                                </button>
                            </div>
                        </div>
                        <!-- Product 3 -->
                        <div
                            class="group bg-surface rounded-2xl p-3 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300">
                            <div>
                                <div
                                    class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-surface-container mb-space-md">
                                    <img alt="Zeenat Mustard Gold Patiala Ensemble"
                                        class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCPKuumEHiv0YRjZ-7UfeWXDDtLVotp0QGG--3PQMUYo0ELApVYWsAXVU7zyvhCUnETM7rqQgoXqUZjxIggFf0oMY426pPPUk5Y8-2clu6JrdkT-TBQfJ6eXKbKHkd3vQ6LRmpUZF8YAU1MslcvMaUgoBmbleVccXpNn3rvsGYylnm7YxbjHEdW3Pu1dmrpOIkh-KOJKADOiaObIx-w1KhIRRZCAICQuCop4hVz3PQ38C2eP41hu14NkQ" />
                                    <button aria-label="Add to Wishlist"
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-sm text-primary hover:text-primary-container flex items-center justify-center transition-colors"
                                        type="button">
                                        <span class="material-symbols-outlined text-base">favorite</span>
                                    </button>
                                    <div class="absolute bottom-3 left-3 flex flex-wrap gap-1">
                                        <span
                                            class="px-2 py-0.5 bg-secondary text-on-secondary font-label-uppercase text-[0.6rem] tracking-wider uppercase rounded">Limited
                                            Edition</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p
                                        class="font-label-uppercase text-[0.625rem] text-secondary tracking-widest uppercase">
                                        Antique Tilla
                                        • Gota</p>
                                    <h3
                                        class="font-headline-sm text-headline-sm text-primary font-serif group-hover:text-secondary transition-colors">
                                        Zeenat Mustard Gold Patiala</h3>
                                    <p class="font-label-price text-label-price text-on-surface">$440 <span
                                            class="text-xs text-on-surface-variant font-normal">USD</span></p>
                                </div>
                            </div>
                            <div class="mt-space-md pt-space-xs">
                                <div
                                    class="flex items-center justify-between text-[0.6875rem] font-label-uppercase text-on-surface-variant mb-2">
                                    <span>Select Size</span>
                                    <span class="text-secondary cursor-pointer hover:underline">Made to Measure</span>
                                </div>
                                <div class="grid grid-cols-5 gap-1 mb-3">
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">XS</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors font-semibold"
                                        type="button">S</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">M</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">L</button>
                                    <button
                                        class="py-1 text-center font-label-uppercase text-xs rounded bg-surface-container hover:bg-secondary-container transition-colors"
                                        type="button">XL</button>
                                </div>
                                <button
                                    class="w-full py-2.5 bg-primary-container hover:bg-primary text-on-primary font-label-uppercase text-label-uppercase tracking-widest uppercase rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                    type="button">
                                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                    <span>Add to Bag</span>
                                </button>
                            </div>
                        </div>
                        <!-- Product 4 -->
                        <div
                            class="group bg-surface rounded-2xl p-3 flex flex-col justify-between shadow-sm hover:shadow-md transition-all duration-300">
                            <div>
                                <div
                                    class="relative w-full aspect-[3/4] overflow-hidden rounded-xl bg-surface-container mb-space-md">
                                    <img alt="Mehtab Scalloped Banarasi Dupatta"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZ9BrzAAbuBRRaT2aofMmGdAeBifsp0Zco9scQxQthMDGDkFyP0vuJysSRet9zlJt8iVAQdsvXvREoiSHor5Y9ijbG4nNo552gYFd289UNZ5bM04hmBSRj31d3nvo4JrWojMs7qULhWiFNKethivTx10hmI3knq4ElQ8dUiVKrsPoOtahPiLSTYyffrTE1jTQquU9hv6x7jnXLWSHfxU3zYUkpsrViW-ojQlA58y-9a3miSLU1Ku9tjQ" />
                                    <button aria-label="Add to Wishlist"
                                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-sm text-primary hover:text-primary-container flex items-center justify-center transition-colors"
                                        type="button">
                                        <span class="material-symbols-outlined text-base">favorite</span>
                                    </button>
                                    <div class="absolute bottom-3 left-3 flex flex-wrap gap-1">
                                        <span
                                            class="px-2 py-0.5 bg-secondary-container text-on-secondary-container font-label-uppercase text-[0.6rem] tracking-wider uppercase rounded">Pret
                                            Gold</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p
                                        class="font-label-uppercase text-[0.625rem] text-secondary tracking-widest uppercase">
                                        Pure Banarasi
                                        • 2.75m Drape</p>
                                    <h3
                                        class="font-headline-sm text-headline-sm text-primary font-serif group-hover:text-secondary transition-colors">
                                        Mehtab Scalloped Banarasi Dupatta</h3>
                                    <p class="font-label-price text-label-price text-on-surface">$195 <span
                                            class="text-xs text-on-surface-variant font-normal">USD</span></p>
                                </div>
                            </div>
                            <div class="mt-space-md pt-space-xs">
                                <div
                                    class="flex items-center justify-between text-[0.6875rem] font-label-uppercase text-on-surface-variant mb-2">
                                    <span>Standard Length</span>
                                    <span class="text-secondary font-semibold">2.75 Metres</span>
                                </div>
                                <div
                                    class="p-2 mb-3 bg-surface-container rounded text-center text-xs text-on-surface-variant font-body-sm">
                                    Includes matching hand-tasseled silk corners
                                </div>
                                <button
                                    class="w-full py-2.5 bg-primary-container hover:bg-primary text-on-primary font-label-uppercase text-label-uppercase tracking-widest uppercase rounded-lg transition-colors flex items-center justify-center gap-1.5"
                                    type="button">
                                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                    <span>Add to Bag</span>
                                </button>
                            </div>
                        </div>
                        --}}
                </div>
            </div>
        </section>
        <!-- SECTION 4: EDITORIAL SPOTLIGHT & ATELIER HERITAGE -->
        <section class="w-full bg-surface-container-low py-space-3xl px-margin lg:px-margin-md" id="spotlight">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
                    <!-- Left Campaign Feature -->
                    <div class="lg:col-span-7 relative">
                        <div class="aspect-[4/5] rounded-3xl overflow-hidden shadow-xl bg-surface-container relative">
                            <img alt="Artisan Punjab Couture" class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8SGT1xT2eZ65quJxNUqyXVD-l-Pef_pTvHXdx7Gt2kywrb59MJk_xcZOM_Fq-RMdHyHpsRl_FetrOZRzGvYDMqnpUvbq2ElcQPxXtLvsHgX8sMlnrm_c1jmMPU2rXF563e3yKs5sdxkAkDIuBhuq14epDHYh54KkLa9cm5sxiffMphbKyXURNsy1ltQIG7vt1JdtOjx44wdiLR-AJrhTYHtesjXIfaqgoXySJ3c9doZ-ppkP3EuBQgA" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent flex flex-col justify-end p-space-lg md:p-space-xl">
                                <span
                                    class="font-label-uppercase text-label-uppercase tracking-[0.25em] text-secondary-fixed uppercase mb-1">Legacy
                                    of Punjab</span>
                                <blockquote
                                    class="font-display-hero-mobile text-display-hero-mobile md:text-headline-lg font-serif text-on-primary leading-snug">
                                    “Each stitch honors five generations of artisanal hand embroidery from Lahore to
                                    Chandigarh.”
                                </blockquote>
                                <p class="font-label-uppercase text-xs tracking-widest text-on-primary/80 uppercase mt-2">
                                    — Benazir
                                    Atelier Master Karkhana</p>
                            </div>
                        </div>
                        <!-- Decorative Stamp Accent -->
                        <div
                            class="hidden sm:flex absolute -bottom-6 -right-6 w-28 h-28 bg-surface-container-lowest rounded-full p-2 shadow-lg items-center justify-center text-center">
                            <div
                                class="w-full h-full rounded-full border border-dashed border-secondary/50 flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-lg">verified</span>
                                <span
                                    class="font-label-uppercase text-[0.55rem] tracking-wider text-primary uppercase font-bold">100%
                                    Genuine</span>
                                <span
                                    class="font-label-uppercase text-[0.5rem] tracking-tighter text-on-surface-variant">Zari
                                    Work</span>
                            </div>
                        </div>
                    </div>
                    <!-- Right Atelier Details -->
                    <div class="lg:col-span-5 space-y-space-lg">
                        <div>
                            <span
                                class="font-label-uppercase text-label-uppercase tracking-[0.2em] text-secondary uppercase block mb-1">Behind
                                The Loom</span>
                            <h2 class="font-headline-lg text-headline-lg text-primary font-serif">Imperial
                                Textures, Intimate
                                Detailing</h2>
                        </div>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                            Our festive collection is crafted over 180 patient artisan hours. Pure Mulberry silk is
                            hand-dyed with
                            plant-based infusions, followed by intricate dabka and tilla threadwork needle-punched
                            by Punjab’s most
                            revered craftspeople.
                        </p>
                        <div class="space-y-space-md">
                            <div
                                class="flex items-start gap-space-sm p-space-md bg-surface-container-lowest rounded-xl shadow-xs">
                                <span class="material-symbols-outlined text-secondary mt-0.5">diamond</span>
                                <div>
                                    <h4 class="font-title-editorial text-title-editorial text-primary font-serif">
                                        Original Gota Patti
                                    </h4>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Real gold-washed
                                        ribbon appliqué
                                        stitched flat to prevent fraying and ensure a luminous royal gleam.</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-space-sm p-space-md bg-surface-container-lowest rounded-xl shadow-xs">
                                <span class="material-symbols-outlined text-secondary mt-0.5">straighten</span>
                                <div>
                                    <h4 class="font-title-editorial text-title-editorial text-primary font-serif">
                                        Bespoke Couture
                                        Tailoring</h4>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Every order
                                        includes custom neck depth,
                                        sleeve alterations, and trouser hemlines crafted to your personal
                                        measurements.</p>
                                </div>
                            </div>
                        </div>
                        <div class="pt-space-xs flex items-center gap-space-md">
                            <a class="inline-flex items-center px-6 py-3 bg-primary-container hover:bg-primary text-on-primary font-label-uppercase text-label-uppercase tracking-widest uppercase rounded-lg transition-colors shadow-sm"
                                href="{{ route('store.contact') }}">
                                Book A Virtual Atelier Fitting
                            </a>
                            <a class="font-label-uppercase text-label-uppercase tracking-wider text-secondary hover:text-primary transition-colors uppercase underline underline-offset-4"
                                href="#new-arrivals">
                                Explore Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- SECTION 5: BRAND TRUST & CONCIERGE BAR -->
        <section class="w-full bg-surface-container-lowest py-space-2xl px-margin lg:px-margin-md">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-lg">
                    <div
                        class="flex items-start gap-space-md p-space-md rounded-2xl bg-surface-container-low/60 shadow-xs">
                        <div
                            class="w-12 h-12 rounded-xl bg-surface-container-lowest flex items-center justify-center text-secondary shadow-xs shrink-0">
                            <span class="material-symbols-outlined text-2xl">flight_takeoff</span>
                        </div>
                        <div>
                            <h4 class="font-title-editorial text-title-editorial text-primary font-serif">Worldwide
                                Express</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Complimentary DHL
                                tracked express
                                shipping across the US, UK, Canada &amp; UAE on all orders over $250.</p>
                        </div>
                    </div>
                    <div
                        class="flex items-start gap-space-md p-space-md rounded-2xl bg-surface-container-low/60 shadow-xs">
                        <div
                            class="w-12 h-12 rounded-xl bg-surface-container-lowest flex items-center justify-center text-secondary shadow-xs shrink-0">
                            <span class="material-symbols-outlined text-2xl">design_services</span>
                        </div>
                        <div>
                            <h4 class="font-title-editorial text-title-editorial text-primary font-serif">Bespoke
                                Fit Service</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Submit custom
                                measurements for your
                                salwar kameez, lehenga, or gharara before master cutting begins.</p>
                        </div>
                    </div>
                    <div
                        class="flex items-start gap-space-md p-space-md rounded-2xl bg-surface-container-low/60 shadow-xs">
                        <div
                            class="w-12 h-12 rounded-xl bg-surface-container-lowest flex items-center justify-center text-secondary shadow-xs shrink-0">
                            <span class="material-symbols-outlined text-2xl">verified</span>
                        </div>
                        <div>
                            <h4 class="font-title-editorial text-title-editorial text-primary font-serif">Pure Silk
                                Certified</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Ethically sourced
                                100% natural raw
                                silk, chiffon, and velvet certified by traditional Punjabi weaving guilds.</p>
                        </div>
                    </div>
                    <div
                        class="flex items-start gap-space-md p-space-md rounded-2xl bg-surface-container-low/60 shadow-xs">
                        <div
                            class="w-12 h-12 rounded-xl bg-surface-container-lowest flex items-center justify-center text-secondary shadow-xs shrink-0">
                            <span class="material-symbols-outlined text-2xl">support_agent</span>
                        </div>
                        <div>
                            <h4 class="font-title-editorial text-title-editorial text-primary font-serif">Private
                                Concierge</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">One-on-one WhatsApp
                                and Zoom styling
                                consultations with our bridal advisors for international brides.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- SECTION 6: PATRON STORIES & REVIEWS -->
        <section class="w-full bg-surface py-space-3xl px-margin lg:px-margin-md">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col items-center text-center mb-space-2xl">
                    <span
                        class="font-label-uppercase text-label-uppercase tracking-[0.2em] text-secondary uppercase mb-space-xs">Patron
                        Stories</span>
                    <h2 class="font-headline-lg text-headline-lg text-primary font-serif">Words from the Diaspora
                    </h2>
                    <div class="w-12 h-0.5 bg-secondary/50 mt-space-sm rounded-full"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-space-lg">
                    <!-- Review 1 -->
                    <div
                        class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-secondary mb-space-sm">
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                            </div>
                            <p
                                class="font-body-md text-body-md text-on-surface-variant italic leading-relaxed mb-space-md">
                                “The maroon velvet gharara arrived in London wrapped in velvet dust covers with
                                handwritten artisan
                                notes. The fitting on the shoulders was impeccable, like visiting a Lahore couturier
                                in person.”
                            </p>
                        </div>
                        <div class="pt-space-sm flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center font-serif text-primary font-bold">
                                AM
                            </div>
                            <div>
                                <p class="font-title-editorial text-title-editorial text-primary font-semibold">
                                    Ananya Malik</p>
                                <p class="font-label-uppercase text-[0.625rem] text-secondary uppercase tracking-wider">
                                    Mayfair,
                                    London • Verified Bride</p>
                            </div>
                        </div>
                    </div>
                    <!-- Review 2 -->
                    <div
                        class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-secondary mb-space-sm">
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                            </div>
                            <p
                                class="font-body-md text-body-md text-on-surface-variant italic leading-relaxed mb-space-md">
                                “Ordered the Nafisa sage kameez for my brother's Anand Karaj. The resham work is
                                subtle yet so rich
                                under sunlight. Express DHL reached New York in just 4 business days. Benazir is
                                unmatched.”
                            </p>
                        </div>
                        <div class="pt-space-sm flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center font-serif text-primary font-bold">
                                SJ
                            </div>
                            <div>
                                <p class="font-title-editorial text-title-editorial text-primary font-semibold">
                                    Simran Johal</p>
                                <p class="font-label-uppercase text-[0.625rem] text-secondary uppercase tracking-wider">
                                    Manhattan, NY
                                    • Festive Client</p>
                            </div>
                        </div>
                    </div>
                    <!-- Review 3 -->
                    <div
                        class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1 text-secondary mb-space-sm">
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="material-symbols-outlined text-sm"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                            </div>
                            <p
                                class="font-body-md text-body-md text-on-surface-variant italic leading-relaxed mb-space-md">
                                “The Banarasi scalloped dupatta feels like pure liquid gold. So lightweight, yet
                                carries that
                                authentic old-world heirloom weight. I will definitely be wearing this for festive
                                seasons to come.”
                            </p>
                        </div>
                        <div class="pt-space-sm flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center font-serif text-primary font-bold">
                                RP
                            </div>
                            <div>
                                <p class="font-title-editorial text-title-editorial text-primary font-semibold">
                                    Rabia Pirzada</p>
                                <p class="font-label-uppercase text-[0.625rem] text-secondary uppercase tracking-wider">
                                    Toronto,
                                    Canada • Verified Client</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- SECTION 7: ATELIER DIARY & VISUAL ARCHIVE -->
        <section class="w-full bg-surface-container-low py-space-3xl px-margin lg:px-margin-md">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-space-xl gap-space-md">
                    <div>
                        <span
                            class="font-label-uppercase text-label-uppercase tracking-[0.2em] text-secondary uppercase block mb-1">Visual
                            Chronicle</span>
                        <h2 class="font-headline-lg text-headline-lg text-primary font-serif">#BenazirAtelier</h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Tag @BenazirClothes on
                            your celebratory
                            moments to be showcased in our global archive.</p>
                    </div>
                    <div>
                        <a class="inline-flex items-center gap-2 px-5 py-2.5 bg-surface-container-lowest text-primary hover:text-secondary font-label-uppercase text-label-uppercase tracking-widest uppercase rounded-lg shadow-sm transition-colors"
                            href="https://www.instagram.com/BenazirClothes/" target="_blank" rel="noopener noreferrer">
                            <span class="material-symbols-outlined text-base">photo_camera</span>
                            <span>Follow on Instagram</span>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-space-md">
                    <div class="group relative aspect-square rounded-2xl overflow-hidden bg-surface-container shadow-xs">
                        <img alt="Atelier moment 1"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8SGT1xT2eZ65quJxNUqyXVD-l-Pef_pTvHXdx7Gt2kywrb59MJk_xcZOM_Fq-RMdHyHpsRl_FetrOZRzGvYDMqnpUvbq2ElcQPxXtLvsHgX8sMlnrm_c1jmMPU2rXF563e3yKs5sdxkAkDIuBhuq14epDHYh54KkLa9cm5sxiffMphbKyXURNsy1ltQIG7vt1JdtOjx44wdiLR-AJrhTYHtesjXIfaqgoXySJ3c9doZ-ppkP3EuBQgA" />
                        <div
                            class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-on-primary">
                            <span class="material-symbols-outlined text-2xl">favorite</span>
                        </div>
                    </div>
                    <div class="group relative aspect-square rounded-2xl overflow-hidden bg-surface-container shadow-xs">
                        <img alt="Atelier moment 2"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDqDBlFDlbkRfdcKFivUaa1GpGnmrSotvgchglzBiS7-zpxzdCvmHs-23QpAtcuggyHahgS5oWFrdA4ffW3B0PBw1jAyxDnp5DhTt_QDGzISa0PFNDBTHS62ZGpgvAg3aT7fmaXnqZDIskW4kW92I07vkLk-eT2_Im6_1sN2IUP0r2IhdArTJl-IKzWjjy5ldg9nBulNDvhkmSegYM-ot-k5pEe0GSu0palWDu23MqqhYiLUY9ggyzgag" />
                        <div
                            class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-on-primary">
                            <span class="material-symbols-outlined text-2xl">favorite</span>
                        </div>
                    </div>
                    <div class="group relative aspect-square rounded-2xl overflow-hidden bg-surface-container shadow-xs">
                        <img alt="Atelier moment 3"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCPKuumEHiv0YRjZ-7UfeWXDDtLVotp0QGG--3PQMUYo0ELApVYWsAXVU7zyvhCUnETM7rqQgoXqUZjxIggFf0oMY426pPPUk5Y8-2clu6JrdkT-TBQfJ6eXKbKHkd3vQ6LRmpUZF8YAU1MslcvMaUgoBmbleVccXpNn3rvsGYylnm7YxbjHEdW3Pu1dmrpOIkh-KOJKADOiaObIx-w1KhIRRZCAICQuCop4hVz3PQ38C2eP41hu14NkQ" />
                        <div
                            class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-on-primary">
                            <span class="material-symbols-outlined text-2xl">favorite</span>
                        </div>
                    </div>
                    <div class="group relative aspect-square rounded-2xl overflow-hidden bg-surface-container shadow-xs">
                        <img alt="Atelier moment 4"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZ9BrzAAbuBRRaT2aofMmGdAeBifsp0Zco9scQxQthMDGDkFyP0vuJysSRet9zlJt8iVAQdsvXvREoiSHor5Y9ijbG4nNo552gYFd289UNZ5bM04hmBSRj31d3nvo4JrWojMs7qULhWiFNKethivTx10hmI3knq4ElQ8dUiVKrsPoOtahPiLSTYyffrTE1jTQquU9hv6x7jnXLWSHfxU3zYUkpsrViW-ojQlA58y-9a3miSLU1Ku9tjQ" />
                        <div
                            class="absolute inset-0 bg-primary/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-on-primary">
                            <span class="material-symbols-outlined text-2xl">favorite</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('footer')
    <footer class="w-full bg-surface-container-low text-on-surface mt-space-3xl">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md pt-space-2xl pb-space-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-xl">
                <div class="space-y-space-md">
                    <div class="space-y-space-xs"><span
                            class="font-headline-md text-headline-md tracking-wider text-primary font-display-hero uppercase">Benazir</span>
                        <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary uppercase">
                            Haute Couture
                            • Pret-a-Porter</p>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">Preserving the
                        transcendent
                        elegance of Punjab and Pakistan's royal ateliers. Every silhouette is an ode to centuries-old
                        hand zardozi,
                        gota patti, and pure silk wefts meticulously handcrafted by master artisans.</p>
                    <div class="flex items-center gap-space-sm text-secondary"><span
                            class="material-symbols-outlined text-base">verified</span><span
                            class="font-body-sm text-body-sm text-on-surface-variant">Certified Artisanal
                            Craftsmanship</span></div>
                </div>
                <div class="space-y-space-md">
                    <h3 class="font-title-editorial text-title-editorial text-primary tracking-wide font-headline-sm">
                        Customer
                        Atelier Care</h3>
                    <ul class="space-y-space-sm font-body-sm text-body-sm text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" data-path="size-guide"
                                href="{{ route('store.contact') }}">Size &amp; Custom Fit
                                Guide</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="shipping"
                                href="{{ route('store.about') }}">Worldwide Express
                                Shipping</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="bridal-consultation"
                                href="{{ route('store.contact') }}">Custom Bridal
                                Inquiries</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="returns"
                                href="{{ route('store.contact') }}">Returns &amp; Exchanges</a>
                        </li>
                        <li><a class="hover:text-primary transition-colors" data-path="track-order"
                                href="{{ route('store.contact') }}">Track Order Status</a>
                        </li>
                        <li><a class="hover:text-primary transition-colors" data-path="fabric-care"
                                href="{{ route('store.about') }}">Raw Silk &amp; Velvet
                                Care</a></li>
                    </ul>
                </div>
                <div class="space-y-space-md">
                    <h3 class="font-title-editorial text-title-editorial text-primary tracking-wide font-headline-sm">
                        Signature
                        Collections</h3>
                    <ul class="space-y-space-sm font-body-sm text-body-sm text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" data-path="punjabi-suits"
                                href="{{ route('store.shop', ['q' => 'Punjabi']) }}">Classic Punjabi
                                Shalwar Kameez</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="festive-wear"
                                href="{{ route('store.shop', ['q' => 'Gharara']) }}">Raw Silk Ghararas
                                &amp; Shararas</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="dupattas"
                                href="{{ route('store.shop', ['q' => 'Organza']) }}">Hand-embroidered Organza
                                Dupattas</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="festive-wear"
                                href="{{ route('store.shop', ['q' => 'Velvet']) }}">Royal Velvet Festive
                                Edit</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="new-arrivals"
                                href="{{ route('store.shop', ['q' => 'Chiffon']) }}">Lawn &amp; Chiffon
                                Luxury Pret</a></li>
                        <li><a class="hover:text-primary transition-colors" data-path="embroidered-collection"
                                href="{{ route('store.shop', ['q' => 'Tilla']) }}">Heritage
                                Tilla &amp; Resham Work</a></li>
                    </ul>
                </div>
                <div class="space-y-space-md">
                    <h3 class="font-title-editorial text-title-editorial text-primary tracking-wide font-headline-sm">
                        The Atelier
                        Circle</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Join the Benazir Atelier Circle for
                        private
                        festive previews, bespoke trunk show invites, and curated heirloom releases.</p>
                    <form class="space-y-space-xs">
                        <div
                            class="flex rounded-lg overflow-hidden ring-1 ring-secondary/50 focus-within:ring-primary transition-all">
                            <input
                                class="w-full bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm text-body-sm outline-none placeholder:text-on-surface-variant/60"
                                placeholder="Enter your email address" type="email" /><button
                                class="bg-primary-container text-on-primary font-label-uppercase text-label-uppercase px-4 py-2 hover:bg-primary transition-colors tracking-widest uppercase"
                                type="button">Subscribe</button>
                        </div>
                        <p class="font-label-uppercase text-[0.625rem] text-on-surface-variant/80 tracking-wider">By
                            subscribing you
                            agree to our Atelier Terms of Privacy.</p>
                    </form>
                </div>
            </div>
            <div class="mt-space-2xl pt-space-lg flex flex-col md:flex-row items-center justify-between gap-space-md">
                <div
                    class="flex items-center gap-space-sm font-label-uppercase text-label-uppercase tracking-wider text-on-surface-variant">
                    <span class="flex items-center gap-1 bg-surface-container-lowest px-2 py-1 rounded"><span
                            class="material-symbols-outlined text-sm text-secondary">lock</span> Secure Atelier
                        Checkout</span><span class="text-secondary/50">•</span><span>VISA</span><span
                        class="text-secondary/50">•</span><span>MASTERCARD</span><span
                        class="text-secondary/50">•</span><span>AMEX</span><span
                        class="text-secondary/50">•</span><span>APPLE
                        PAY</span>
                </div>
                <p
                    class="font-label-uppercase text-label-uppercase text-on-surface-variant tracking-wider text-center md:text-right">
                    © 2025 Benazir Clothes. All Rights Reserved. Crafted with reverence for Punjabi heritage.</p>
            </div>
        </div>
    </footer>
@endsection
