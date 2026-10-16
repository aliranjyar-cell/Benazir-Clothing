    <header
        class="fixed top-0 left-0 right-0 z-50 bg-surface/95 backdrop-blur-md shadow-[0_1px_8px_rgba(62,51,47,0.06)]">
        <div class="bg-primary-container text-on-primary py-space-xs px-margin">
            <div
                class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-center gap-space-xs">
                <div
                    class="hidden md:flex items-center gap-space-xs font-label-uppercase text-label-uppercase tracking-wider text-secondary-fixed">
                    <span class="material-symbols-outlined text-sm">auto_awesome</span><span>LUXURY ATELIER &amp;
                        BESPOKE
                        COUTURE</span>
                </div>
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-on-primary">Complimentary
                    Worldwide
                    Express Shipping on Orders Over $250 • Festive Pret '25 Live</p>
                <div class="flex items-center gap-space-sm font-label-uppercase text-label-uppercase"><span
                        class="text-secondary-fixed">Currency:</span>
                    <div class="flex items-center gap-space-xs text-on-primary"><button
                            class="hover:text-secondary-fixed transition-colors font-semibold"
                            type="button">USD</button><span>|</span><button
                            class="hover:text-secondary-fixed transition-colors"
                            type="button">GBP</button><span>|</span><button
                            class="hover:text-secondary-fixed transition-colors"
                            type="button">CAD</button><span>|</span><button
                            class="hover:text-secondary-fixed transition-colors" type="button">PKR</button></div>
                </div>
            </div>
        </div>
        <div class="h-28 max-w-7xl mx-auto px-margin lg:px-margin-md flex flex-col justify-between py-space-xs">
            <div class="flex items-center justify-between gap-space-md pt-space-xs">
                <div class="flex-1 max-w-xs relative hidden md:block">
                    <form role="search" action="{{ route('store.shop') }}" method="GET"
                        class="relative flex items-center"><input
                            class="w-full bg-surface-container-lowest/80 text-on-surface placeholder:text-on-surface-variant/70 font-body-sm text-body-sm pl-9 pr-3 py-1.5 rounded-lg outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                            placeholder="Search handcrafted suits, organza dupattas..." type="search" name="q"
                            value="{{ request('q') }}" /><span
                            class="material-symbols-outlined absolute left-2.5 text-secondary text-lg pointer-events-none">search</span>
                    </form>
                </div>
                <div class="flex-1 text-center"><a class="inline-flex flex-col items-center group" data-path="home"
                        href="{{ route('store.home') }}"><span
                            class="font-headline-lg text-headline-lg tracking-widest text-primary font-medium uppercase font-display-hero">Benazir</span><span
                            class="font-label-uppercase text-label-uppercase tracking-[0.25em] text-secondary -mt-1 uppercase">Lahore
                            • London • New York</span></a></div>
                <div class="flex-1 flex items-center justify-end gap-space-md"><a aria-label="Wishlist"
                        class="relative text-on-surface-variant hover:text-primary transition-colors p-1"
                        data-path="wishlist" href="{{ route('store.wishlist.index') }}"><span
                            class="material-symbols-outlined text-xl">favorite</span>
                        @if (($wishlistCount ?? 0) > 0)
                            <span
                                class="absolute -top-1 -right-1.5 bg-primary-container text-on-primary font-label-uppercase text-[0.6rem] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                    <a aria-label="Shopping Bag"
                        class="relative text-on-surface-variant hover:text-primary transition-colors p-1"
                        data-path="cart-drawer" href="{{ route('store.cart.index') }}"><span
                            class="material-symbols-outlined text-xl">shopping_bag</span>
                        @if (($cartCount ?? 0) > 0)
                            <span
                                class="absolute -top-1 -right-1.5 bg-secondary text-on-secondary font-label-uppercase text-[0.6rem] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a aria-label="{{ auth('web')->check() ? 'Customer account' : 'Customer sign in' }}"
                        title="{{ auth('web')->check() ? 'Customer account' : 'Customer sign in' }}"
                        class="text-on-surface-variant hover:text-primary transition-colors p-1"
                        href="{{ auth('web')->check() ? route('store.account') : route('store.login') }}">
                        <span class="material-symbols-outlined text-xl">account_circle</span>
                    </a>
                    @if (auth('web')->check())
                        <div
                            class="hidden lg:flex items-center gap-space-sm font-label-uppercase text-label-uppercase text-on-surface-variant">
                            <a class="hover:text-primary transition-colors"
                                href="{{ route('store.account') }}">Account</a>
                            <form method="POST" action="{{ route('store.logout') }}">
                                @csrf
                                <button class="hover:text-primary transition-colors" type="submit">Sign out</button>
                            </form>
                        </div>
                    @else
                        <div
                            class="hidden sm:flex items-center gap-space-sm font-label-uppercase text-label-uppercase text-on-surface-variant">
                            <a class="hover:text-primary transition-colors" href="{{ route('store.login') }}">Sign
                                in</a>
                            <a class="hover:text-primary transition-colors" href="{{ route('register') }}">Register</a>
                        </div>
                    @endif
                </div>
            </div>
            <nav class="flex items-center justify-center gap-space-lg overflow-x-auto py-1"
                data-active-classes="text-secondary font-semibold border-b-2 border-secondary"><a
                    @if (request()->routeIs('store.home')) aria-current="page" @endif
                    class="{{ request()->routeIs('store.home') ? 'text-secondary font-semibold border-b-2 border-secondary' : 'font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1' }}"
                    data-path="home" href="{{ route('store.home') }}">Home</a><a
                    class="font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1"
                    data-path="new-arrivals" href="{{ route('store.shop') }}">New Arrivals</a>
                <a class="font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1"
                    href="{{ route('store.shop') }}">Products</a>
                @forelse ($navCategories ?? collect() as $navCategory)
                    <a class="font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1"
                        href="{{ route('store.shop', ['category' => $navCategory->slug]) }}">{{ $navCategory->name }}</a>
                @empty
                    <a class="font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1"
                        href="{{ route('store.shop') }}">Punjabi Suits</a>
                    <a class="font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1"
                        href="{{ route('store.shop') }}">Festive Wear</a>
                @endforelse
                <a class="font-label-uppercase text-label-uppercase tracking-widest uppercase text-on-surface-variant hover:text-secondary hover:text-on-surface transition-colors pb-1"
                    data-path="sale" href="{{ route('store.shop') }}">Sale</a>
            </nav>
        </div>
    </header>
