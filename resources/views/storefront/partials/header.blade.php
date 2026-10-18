
<header class="fixed inset-x-0 top-0 z-50 bg-surface/95 shadow-[0_2px_12px_rgba(62,51,47,0.06)] backdrop-blur-md">

    {{-- ANNOUNCEMENT BAR --}}
    <div class="bg-primary-container px-3 py-1 text-on-primary">
        <div class="mx-auto flex max-w-7xl items-center justify-center gap-2 text-center text-[10px] font-semibold uppercase tracking-wider sm:text-xs">

            <span class="material-symbols-outlined hidden text-sm text-secondary-fixed sm:inline" aria-hidden="true">
                auto_awesome
            </span>

            <p class="leading-relaxed">
                Complimentary Worldwide Express Shipping on Orders Over $250
                <span class="hidden sm:inline"> · Festive Pret '25 Live</span>
            </p>
        </div>
    </div>

    {{-- MAIN HEADER ROW --}}
    <div class="mx-auto max-w-7xl px-3 sm:px-5 lg:px-8">

        <div class="flex h-[68px] items-center justify-between gap-3 sm:h-20">

            {{-- MOBILE MENU --}}
            <details class="group relative shrink-0 md:hidden">

                <summary
                    aria-label="Open navigation menu"
                    class="flex h-10 w-10 cursor-pointer list-none items-center justify-center text-on-surface-variant transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary [&::-webkit-details-marker]:hidden">

                    <span class="material-symbols-outlined text-2xl group-open:hidden" aria-hidden="true">
                        menu
                    </span>

                    <span class="material-symbols-outlined hidden text-2xl group-open:block" aria-hidden="true">
                        close
                    </span>
                </summary>

                {{-- MOBILE NAVIGATION DRAWER --}}
                <div class="absolute left-[-12px] top-full z-50 mt-3 max-h-[calc(100dvh-125px)] w-[min(90vw,360px)] overflow-y-auto border border-outline-variant/30 bg-surface shadow-xl">

                    <div class="border-b border-outline-variant/30 px-5 py-4">
                        <p class="font-headline-sm text-xl text-primary">
                            Explore {{ config('storefront.store_name', 'Benazir') }}
                        </p>
                        <p class="mt-1 text-xs text-on-surface-variant">
                            Discover timeless Punjabi craftsmanship.
                        </p>
                    </div>

                    <nav aria-label="Mobile navigation" class="p-3">

                        <a
                            href="{{ route('store.home') }}"
                            @if (request()->routeIs('store.home')) aria-current="page" @endif
                            class="mb-1 flex items-center justify-between px-3 py-3 text-sm transition-colors hover:bg-surface-container-low hover:text-primary {{ request()->routeIs('store.home') ? 'font-semibold text-secondary' : 'text-on-surface' }}">

                            Home
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">chevron_right</span>
                        </a>

                        <a
                            href="{{ route('store.shop') }}"
                            @if (request()->routeIs('store.shop')) aria-current="page" @endif
                            class="mb-1 flex items-center justify-between px-3 py-3 text-sm transition-colors hover:bg-surface-container-low hover:text-primary {{ request()->routeIs('store.shop') ? 'font-semibold text-secondary' : 'text-on-surface' }}">

                            All Products
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">chevron_right</span>
                        </a>

                        <a href="{{ route('store.shop') }}"
                            class="mb-1 flex items-center justify-between px-3 py-3 text-sm text-on-surface transition-colors hover:bg-surface-container-low hover:text-primary">
                            New Arrivals
                            <span class="material-symbols-outlined text-lg" aria-hidden="true">chevron_right</span>
                        </a>

                        <div class="my-3 border-t border-outline-variant/30"></div>

                        <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-outline">
                            Shop by collection
                        </p>

                        @forelse ($navCategories ?? collect() as $navCategory)
                            <a
                                href="{{ route('store.shop', ['category' => $navCategory->slug]) }}"
                                class="block px-3 py-2.5 text-sm text-on-surface transition-colors hover:bg-surface-container-low hover:text-primary">

                                {{ $navCategory->name }}
                            </a>
                        @empty
                            <a href="{{ route('store.shop') }}"
                                class="block px-3 py-2.5 text-sm text-on-surface hover:text-primary">
                                Punjabi Suits
                            </a>

                            <a href="{{ route('store.shop') }}"
                                class="block px-3 py-2.5 text-sm text-on-surface hover:text-primary">
                                Festive Wear
                            </a>
                        @endforelse

                        <a href="{{ route('store.shop') }}"
                            class="block px-3 py-2.5 text-sm text-secondary hover:text-primary">
                            Sale
                        </a>

                        <div class="my-3 border-t border-outline-variant/30"></div>

                        <a href="{{ route('store.about') }}"
                            class="block px-3 py-2.5 text-sm text-on-surface hover:text-primary">
                            About Us
                        </a>

                        <a href="{{ route('store.contact') }}"
                            class="block px-3 py-2.5 text-sm text-on-surface hover:text-primary">
                            Contact Us
                        </a>

                        <a href="{{ route('store.orders.track') }}"
                            class="block px-3 py-2.5 text-sm text-on-surface hover:text-primary">
                            Track Order
                        </a>

                        <div class="my-3 border-t border-outline-variant/30"></div>

                        @if (auth('web')->check())
                            <a href="{{ route('store.account') }}"
                                class="flex items-center gap-3 px-3 py-3 text-sm text-on-surface hover:text-primary">
                                <span class="material-symbols-outlined" aria-hidden="true">account_circle</span>
                                My Account
                            </a>

                            <form method="POST" action="{{ route('store.logout') }}" class="px-3 py-2">
                                @csrf
                                <button type="submit"
                                    class="text-sm text-secondary hover:text-primary">
                                    Sign Out
                                </button>
                            </form>
                        @else
                            <div class="grid grid-cols-2 gap-2 p-2">
                                <a href="{{ route('store.login') }}"
                                    class="border border-outline-variant/50 px-3 py-3 text-center text-sm text-on-surface hover:border-primary hover:text-primary">
                                    Sign In
                                </a>

                                <a href="{{ route('register') }}"
                                    class="bg-primary px-3 py-3 text-center text-sm text-on-primary hover:bg-secondary">
                                    Register
                                </a>
                            </div>
                        @endif
                    </nav>
                </div>
            </details>

            {{-- BRAND LOGO --}}
            <a
                href="{{ route('store.home') }}"
                class="flex min-w-0 flex-1 flex-col items-center justify-center text-center md:flex-none"
                aria-label="{{ config('storefront.store_name', 'Benazir') }} home">

                @if (config('storefront.store_logo'))
                    <img
                        src="{{ asset('storage/' . config('storefront.store_logo')) }}"
                        alt="{{ config('storefront.store_name', 'Benazir') }}"
                        class="mb-0.5 h-8 max-w-[150px] object-contain sm:h-10 sm:max-w-[220px]">
                @endif

                <span class="font-headline-lg text-xl font-medium uppercase tracking-[0.12em] text-primary sm:text-2xl md:text-3xl">
                    {{ config('storefront.store_name', 'Benazir') }}
                </span>

                <span class="mt-0.5 text-[8px] uppercase tracking-[0.16em] text-secondary sm:text-[9px] sm:tracking-[0.23em]">
                    {{ config('storefront.store_tagline', 'Lahore • London • New York') }}
                </span>
            </a>

            {{-- DESKTOP SEARCH --}}
            <div class="hidden min-w-0 flex-1 md:block md:max-w-xs">
                <form role="search" action="{{ route('store.shop') }}" method="GET" class="relative">
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search handcrafted suits..."
                        aria-label="Search products"
                        class="w-full rounded-lg bg-surface-container-lowest/80 py-2 pl-10 pr-3 text-sm text-on-surface ring-1 ring-secondary/30 transition focus:outline-none focus:ring-2 focus:ring-primary-container">

                    <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-secondary" aria-hidden="true">
                        search
                    </span>
                </form>
            </div>

            {{-- SHOPPING ACTIONS --}}
            <div class="flex shrink-0 items-center justify-end gap-1 sm:gap-3">

                <a
                    href="{{ route('store.wishlist.index') }}"
                    aria-label="Wishlist{{ ($wishlistCount ?? 0) > 0 ? ', ' . $wishlistCount . ' items' : '' }}"
                    class="relative flex h-10 w-9 items-center justify-center text-on-surface-variant transition-colors hover:text-primary sm:w-10">

                    <span class="material-symbols-outlined text-[21px]" aria-hidden="true">favorite</span>

                    @if (($wishlistCount ?? 0) > 0)
                        <span class="absolute right-0 top-0 flex h-4 min-w-4 items-center justify-center rounded-full bg-primary-container px-1 text-[9px] font-bold text-on-primary">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('store.cart.index') }}"
                    aria-label="Shopping bag{{ ($cartCount ?? 0) > 0 ? ', ' . $cartCount . ' items' : '' }}"
                    class="relative flex h-10 w-9 items-center justify-center text-on-surface-variant transition-colors hover:text-primary sm:w-10">

                    <span class="material-symbols-outlined text-[21px]" aria-hidden="true">shopping_bag</span>

                    @if (($cartCount ?? 0) > 0)
                        <span class="absolute right-0 top-0 flex h-4 min-w-4 items-center justify-center rounded-full bg-secondary px-1 text-[9px] font-bold text-on-secondary">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- ACCOUNT ICON --}}
                <a
                    href="{{ auth('web')->check() ? route('store.account') : route('store.login') }}"
                    aria-label="{{ auth('web')->check() ? 'My account' : 'Sign in' }}"
                    class="hidden h-10 w-9 items-center justify-center text-on-surface-variant transition-colors hover:text-primary sm:flex sm:w-10">

                    <span class="material-symbols-outlined text-[22px]" aria-hidden="true">account_circle</span>
                </a>

                {{-- DESKTOP ACCOUNT LINKS --}}
                @if (auth('web')->check())
                    <div class="hidden items-center gap-3 text-xs text-on-surface-variant lg:flex">
                        <a href="{{ route('store.account') }}" class="hover:text-primary">Account</a>

                        <form method="POST" action="{{ route('store.logout') }}">
                            @csrf
                            <button type="submit" class="hover:text-primary">Sign out</button>
                        </form>
                    </div>
                @else
                    <div class="hidden items-center gap-3 text-xs text-on-surface-variant lg:flex">
                        <a href="{{ route('store.login') }}" class="hover:text-primary">Sign in</a>
                        <a href="{{ route('register') }}" class="hover:text-primary">Register</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- MOBILE SEARCH --}}
        <div class="pb-3 md:hidden">
            <form role="search" action="{{ route('store.shop') }}" method="GET" class="relative">
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search suits, dupattas, and more..."
                    aria-label="Search products"
                    class="w-full rounded-md border border-outline-variant/40 bg-surface-container-lowest py-2.5 pl-10 pr-3 text-sm text-on-surface placeholder:text-on-surface-variant/70 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">

                <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-lg text-secondary" aria-hidden="true">
                    search
                </span>
            </form>
        </div>

        {{-- DESKTOP NAVIGATION --}}
        <nav aria-label="Main navigation" class="hidden items-center justify-center gap-5 overflow-x-auto border-t border-outline-variant/20 py-3 md:flex lg:gap-7">

            <a
                href="{{ route('store.home') }}"
                @if (request()->routeIs('store.home')) aria-current="page" @endif
                class="whitespace-nowrap border-b-2 pb-1 text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('store.home') ? 'border-secondary font-semibold text-secondary' : 'border-transparent text-on-surface-variant hover:border-secondary hover:text-secondary' }}">
                Home
            </a>

            <a href="{{ route('store.shop') }}"
                class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                New Arrivals
            </a>

            <a
                href="{{ route('store.shop') }}"
                @if (request()->routeIs('store.shop')) aria-current="page" @endif
                class="whitespace-nowrap border-b-2 pb-1 text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('store.shop') ? 'border-secondary font-semibold text-secondary' : 'border-transparent text-on-surface-variant hover:border-secondary hover:text-secondary' }}">
                Products
            </a>

            <a href="{{ route('store.about') }}"
                class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                About
            </a>

            <a href="{{ route('store.contact') }}"
                class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                Contact
            </a>

            <a href="{{ route('store.orders.track') }}"
                class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                Track Order
            </a>

            @forelse ($navCategories ?? collect() as $navCategory)
                <a
                    href="{{ route('store.shop', ['category' => $navCategory->slug]) }}"
                    class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">

                    {{ $navCategory->name }}
                </a>
            @empty
                <a href="{{ route('store.shop') }}"
                    class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                    Punjabi Suits
                </a>

                <a href="{{ route('store.shop') }}"
                    class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                    Festive Wear
                </a>
            @endforelse

            <a href="{{ route('store.shop') }}"
                class="whitespace-nowrap border-b-2 border-transparent pb-1 text-xs uppercase tracking-wider text-on-surface-variant transition-colors hover:border-secondary hover:text-secondary">
                Sale
            </a>
        </nav>
    </div>
</header>
