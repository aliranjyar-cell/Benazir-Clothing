    <footer class="w-full bg-surface-container-low text-on-surface mt-space-3xl">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md pt-space-2xl pb-space-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-xl">
                <div class="space-y-space-md">
                    <div class="space-y-space-xs"><span
                            class="font-headline-md text-headline-md tracking-wider text-primary font-display-hero uppercase">{{ config('storefront.store_name', 'Benazir') }}</span>
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
                        <li><a class="hover:text-primary transition-colors" href="{{ route('store.contact') }}">Contact
                                the Atelier</a></li>
                        <li><a class="hover:text-primary transition-colors"
                                href="{{ route('store.orders.track') }}">Track Order Status</a></li>
                    </ul>
                </div>
                <div class="space-y-space-md">
                    <h3 class="font-title-editorial text-title-editorial text-primary tracking-wide font-headline-sm">
                        Signature
                        Collections</h3>
                    <ul class="space-y-space-sm font-body-sm text-body-sm text-on-surface-variant">
                        @forelse ($navCategories ?? collect() as $navCategory)
                            <li><a class="hover:text-primary transition-colors"
                                    href="{{ route('store.shop', ['category' => $navCategory->slug]) }}">{{ $navCategory->name }}</a>
                            </li>
                        @empty
                            <li><a class="hover:text-primary transition-colors" href="{{ route('store.shop') }}">Classic
                                    Punjabi
                                    Shalwar Kameez</a></li>
                            <li><a class="hover:text-primary transition-colors" href="{{ route('store.shop') }}">Royal
                                    Velvet Festive
                                    Edit</a></li>
                        @endforelse
                        <li><a class="hover:text-primary transition-colors" data-path="new-arrivals"
                                href="{{ route('store.shop') }}">New
                                Arrivals</a></li>
                    </ul>
                </div>
                <div class="space-y-space-md">
                    <h3 class="font-title-editorial text-title-editorial text-primary tracking-wide font-headline-sm">
                        The Atelier
                        Circle</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Join the
                        {{ config('storefront.store_name', 'Benazir') }} Atelier Circle for
                        private
                        festive previews, bespoke trunk show invites, and curated heirloom releases.</p>
                    <form class="space-y-space-xs" action="{{ route('store.newsletter.subscribe') }}" method="POST">
                    @csrf
                        <div
                            class="flex rounded-lg overflow-hidden ring-1 ring-secondary/50 focus-within:ring-primary transition-all">
                            <label for="newsletter-email" class="sr-only">Email address</label>
                            <input
                                class="w-full bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm text-body-sm outline-none placeholder:text-on-surface-variant/60"
                                id="newsletter-email" placeholder="Enter your email address" type="email" /><button
                                class="bg-primary-container text-on-primary font-label-uppercase text-label-uppercase px-4 py-2 hover:bg-primary transition-colors tracking-widest uppercase"
                                type="submit">Subscribe</button>
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
                    © 2025 {{ config('storefront.store_name', 'Benazir Clothes') }}. All Rights Reserved. Crafted with
                    reverence for Punjabi heritage.</p>
            </div>
        </div>

        {{-- Developer credit: display only when approved and enabled --}}
        @if (config('developer.enabled'))
            <div class="border-t border-outline-variant/30 px-4 py-4 text-center">
                <p class="text-xs leading-6 text-on-surface-variant">
                    Website crafted by
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('developer.whatsapp_number')) }}?text={{ rawurlencode(config('developer.whatsapp_message')) }}"
                        target="_blank" rel="noopener noreferrer"
                        class="font-medium text-primary underline-offset-4 transition-colors hover:text-secondary hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                        aria-label="Contact {{ config('developer.name') }} about web development on WhatsApp">
                        {{ config('developer.name') }}
                    </a>
                    <span class="mx-1 text-outline" aria-hidden="true">·</span>
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('developer.whatsapp_number')) }}?text={{ rawurlencode(config('developer.whatsapp_message')) }}"
                        target="_blank" rel="noopener noreferrer"
                        class="text-primary transition-colors hover:text-secondary hover:underline">
                        Web Development Services
                    </a>
                </p>
            </div>
        @endif

    </footer>
