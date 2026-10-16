@extends('storefront.layouts.store')

@section('title', 'Checkout')

@section('content')
    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <nav aria-label="Checkout" class="flex flex-wrap gap-space-md text-body-sm text-on-surface-variant/80">
                <a href="{{ route('store.cart.index') }}"
                    class="font-label-uppercase text-label-uppercase tracking-widest rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-on-surface transition-colors hover:border-primary/40 hover:text-primary">
                    Your bag
                </a>
                <a href="#details"
                    class="font-label-uppercase text-label-uppercase tracking-widest rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-on-surface transition-colors hover:border-primary/40 hover:text-primary">
                    Delivery details
                </a>
                <a href="#summary"
                    class="font-label-uppercase text-label-uppercase tracking-widest rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-on-surface transition-colors hover:border-primary/40 hover:text-primary">
                    Order summary
                </a>
            </nav>

            <div class="mt-space-2xl max-w-4xl">
                <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary">Checkout</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-sm">
                    Complete your order securely. A confirmation email will be sent.
                </p>

                <div class="mt-space-2xl grid gap-space-xl md:grid-cols-2">
                    <section id="details"
                        class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                        <h2 class="font-display-hero font-headline-lg text-headline-lg text-primary">Delivery details</h2>

                        @if (session('validation_error'))
                            <div role="alert"
                                class="mt-space-md rounded-lg border border-error/30 bg-error-container/10 px-space-md py-space-md font-body-sm text-body-sm text-error">
                                {{ session('validation_error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('store.checkout.place') }}"
                            class="mt-space-md grid gap-space-md">
                            @csrf
                            <input type="hidden" name="payment_method" value="{{ $defaultPaymentMethod }}">

                            <div class="grid gap-space-md sm:grid-cols-2">
                                <div class="space-y-space-xs">
                                    <label class="font-body-sm text-body-sm text-on-surface-variant">Full name</label>
                                    <input name="customer_name" value="{{ old('customer_name') }}"
                                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        placeholder="Aziz Ahmed" />
                                    @error('customer_name')
                                        <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-space-xs">
                                    <label class="font-body-sm text-body-sm text-on-surface-variant">Email</label>
                                    <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        placeholder="you@example.com" />
                                    @error('customer_email')
                                        <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="space-y-space-xs">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">Phone</label>
                                <input name="customer_phone" value="{{ old('customer_phone') }}"
                                    class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                    placeholder="+92 300 0000000" />
                                @error('customer_phone')
                                    <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-space-xs">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">Address line 1</label>
                                <input name="shipping_address_line_1" value="{{ old('shipping_address_line_1') }}"
                                    class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                    placeholder="House no., street" />
                                @error('shipping_address_line_1')
                                    <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid gap-space-md sm:grid-cols-3">
                                <div class="space-y-space-xs">
                                    <label class="font-body-sm text-body-sm text-on-surface-variant">City</label>
                                    <input name="shipping_city" value="{{ old('shipping_city') }}"
                                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        placeholder="Lahore" />
                                    @error('shipping_city')
                                        <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-space-xs">
                                    <label class="font-body-sm text-body-sm text-on-surface-variant">State / region</label>
                                    <input name="shipping_state" value="{{ old('shipping_state') }}"
                                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        placeholder="Punjab" />
                                </div>
                                <div class="space-y-space-xs">
                                    <label class="font-body-sm text-body-sm text-on-surface-variant">Postal code</label>
                                    <input name="shipping_postal_code" value="{{ old('shipping_postal_code') }}"
                                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        placeholder="54000" />
                                </div>
                            </div>

                            <div class="space-y-space-xs">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">Country</label>
                                <input name="shipping_country" value="{{ old('shipping_country') ?? 'Pakistan' }}"
                                    class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" />
                                @error('shipping_country')
                                    <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-space-xs">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">Notes for delivery
                                    (optional)</label>
                                <textarea name="customer_note" rows="3"
                                    class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                    placeholder="Leave at the front desk...">{{ old('customer_note') }}</textarea>
                            </div>

                            <button type="submit"
                                class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest hover:bg-primary-container transition-colors">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Confirm order
                            </button>
                        </form>
                    </section>

                    <section id="summary"
                        class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                        <h2 class="font-display-hero font-headline-lg text-headline-lg text-primary">Order summary</h2>

                        <div class="mt-space-md max-h-48 overflow-y-auto">
                            @foreach ($lines as $line)
                                @php($variant = $line['variant'])
                                <div
                                    class="flex items-start justify-between gap-space-md border-b border-outline-variant/40 pb-space-md">
                                    <div>
                                        <p class="font-body-sm text-body-sm text-on-surface">{{ $line['product']->name }}
                                        </p>
                                        <p class="font-label-uppercase text-label-uppercase text-secondary mt-space-xs">
                                            Qty {{ $line['quantity'] }} ·
                                            {{ $variant->size ? $variant->size . ' · ' : '' }}{{ $variant->color ?: 'Variant' }}
                                        </p>
                                    </div>
                                    <p class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                        {{ $line['line_total'] }}
                                        <span
                                            class="text-body-sm text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">
                                            {{ config('storefront.currency_symbol', '₨') }}
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <dl class="mt-space-md grid gap-space-sm text-body-sm">
                            <div class="flex justify-between">
                                <dt class="text-on-surface-variant/80">Subtotal</dt>
                                <dd class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                    {{ $subtotal }} <span
                                        class="text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ config('storefront.currency_symbol', '₨') }}</span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-on-surface-variant/80">Shipping</dt>
                                <dd class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                    {{ $shippingTotal }} {{ config('storefront.currency_symbol', '₨') }}
                                </dd>
                            </div>
                            <div class="flex justify-between border-t border-outline-variant/40 pt-space-md">
                                <dt class="font-label-uppercase text-label-uppercase tracking-widest text-on-surface">Grand
                                    total</dt>
                                <dd class="font-display-hero font-headline-lg text-headline-lg text-primary">
                                    {{ $grandTotal }} <span
                                        class="text-body-sm text-body-sm text-body-sm text-body-sm text-on-surface-variant/70 font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ config('storefront.currency_symbol', '₨') }}</span>
                                </dd>
                            </div>
                        </dl>

                        <p class="mt-space-md text-label-uppercase text-label-uppercase text-secondary">
                            Cash on delivery only. Payment is due when your order is delivered.
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
