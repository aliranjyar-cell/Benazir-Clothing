@extends('layouts.admin.app')

@section('title', 'Checkout & Payments Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">Checkout &amp; Payments</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    Checkout &amp; Payments
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Choose how customers pay and what checkout asks them for.
                </p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                All sections
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav active="checkout" />

        <form action="{{ route('admin.settings.checkout.update') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @csrf

            {{-- Payment methods --}}
            <x-admin.settings.panel title="Payment methods"
                description="Only the ticked methods are offered at checkout. At least one must stay enabled.">
                @php
                    $postableMethods = old('payment_methods_enabled', $enabledMethods);
                    if (! is_array($postableMethods)) {
                        $postableMethods = [$postableMethods];
                    }
                    $defaultMethod = old('checkout_default_payment_method', $settings['checkout_default_payment_method']);
                @endphp

                <div class="space-y-3">
                    @foreach ($paymentMethods as $methodKey => $methodLabel)
                        <label
                            class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 px-4 py-3.5 transition has-[:checked]:border-lilac has-[:checked]:bg-lilac/10">
                            <input type="checkbox" name="payment_methods_enabled[]" value="{{ $methodKey }}"
                                @checked(in_array($methodKey, $postableMethods, true))
                                class="mt-1 accent-aubergine">
                            <span class="min-w-0 flex-1">
                                <span class="block text-sm font-semibold text-ink">{{ $methodLabel }}</span>
                                <span class="mt-0.5 block text-xs leading-5 text-muted">
                                    @if ($methodKey === 'cash_on_delivery')
                                        Customer pays in cash when the parcel is delivered; the order stays unpaid until
                                        an admin records it.
                                    @else
                                        Customer transfers to your bank account; the order stays unpaid until an admin
                                        verifies the payment.
                                    @endif
                                </span>
                            </span>
                        </label>
                    @endforeach

                    @error('payment_methods_enabled')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @error('payment_methods_enabled.*')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.select label="Default payment method" name="checkout_default_payment_method"
                        :value="$defaultMethod" :required="true"
                        :options="collect($postableMethods)->filter()->mapWithKeys(fn ($key) => [$key => $paymentMethods[$key] ?? $key])->all()"
                        hint="Pre-selected for every new order." />
                </div>
            </x-admin.settings.panel>

            {{-- Checkout options --}}
            <x-admin.settings.panel title="Checkout options"
                description="Control what the checkout page asks from customers.">
                <div class="space-y-4">
                    <x-admin.settings.switch name="checkout_allow_guest" label="Allow guest checkout"
                        :checked="old('checkout_allow_guest', $settings['checkout_allow_guest']) === '1'"
                        description="Customers can place orders without creating an account. Turn off to require sign-in first." />

                    <x-admin.settings.switch name="checkout_order_notes" label="Delivery notes field"
                        :checked="old('checkout_order_notes', $settings['checkout_order_notes']) === '1'"
                        description="Show the optional &quot;Notes for delivery&quot; box on the checkout form." />
                </div>
            </x-admin.settings.panel>

            {{-- Bank transfer details --}}
            <x-admin.settings.panel title="Bank transfer details"
                description="Shown to customers at checkout when Bank Transfer is the default method.">
                <x-admin.settings.textarea label="Account details" name="checkout_bank_details"
                    :value="$settings['checkout_bank_details']" :rows="4" :maxlength="500"
                    placeholder="Account title, IBAN, bank &amp; branch"
                    hint="Leave blank to hide the bank details block. Plain text — line breaks are kept." />
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