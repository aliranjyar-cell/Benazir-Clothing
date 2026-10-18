@extends('layouts.admin.app')

@section('title', 'Shipping & Delivery Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">Shipping &amp; Delivery</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    Shipping &amp; Delivery
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Set what customers pay for delivery and when they can expect their order.
                </p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                All sections
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav active="shipping" />

        <form action="{{ route('admin.settings.shipping.update') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @csrf

            {{-- Delivery rates --}}
            <x-admin.settings.panel title="Delivery rates"
                description="The flat rate applies to every order that doesn't qualify for free shipping.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.input label="Flat delivery fee" name="shipping_flat_fee" type="number"
                        :value="$settings['shipping_flat_fee']" :required="true" min="0" max="1000000"
                        step="0.01" :hint="'Charged on every order in ' . config('storefront.currency_symbol', '₨') . '. Use 0 for free delivery.'" />

                    <x-admin.settings.input label="Free shipping over" name="shipping_free_over" type="number"
                        :value="$settings['shipping_free_over']" min="0" max="1000000" step="0.01"
                        hint="Orders at or above this subtotal ship free. Leave blank to never offer free shipping." />
                </div>

                <div class="rounded-xl border border-primary/15 bg-primary/[0.04] px-4 py-3.5 text-xs leading-5 text-ink">
                    <strong class="font-semibold">How it's applied:</strong>
                    customers see the delivery fee in their checkout summary;
                    when the bag reaches the free-shipping threshold the fee drops to
                    <span class="font-semibold text-primary">Free</span> automatically.
                </div>
            </x-admin.settings.panel>

            {{-- Delivery estimates --}}
            <x-admin.settings.panel title="Delivery estimates"
                description="Shown to customers at checkout so they know what to expect.">
                <x-admin.settings.input label="Estimated delivery time" name="shipping_estimated_days"
                    :value="$settings['shipping_estimated_days']" :required="true" :maxlength="60"
                    placeholder="3–5 business days"
                    hint="Displayed beneath the shipping line in the checkout order summary." />
            </x-admin.settings.panel>

            {{-- Footer actions --}}
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-[#FCFAF8]/60 px-6 py-4">
                <a href="{{ route('admin.settings.index') }}"
                    class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-ink transition hover:bg-ivory">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-xl bg-aubergine px-6 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90">
                    Save settings
                </button>
            </div>
        </form>
    </div>
@endsection