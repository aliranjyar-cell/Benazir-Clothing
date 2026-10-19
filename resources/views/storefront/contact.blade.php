
@extends('storefront.layouts.store')

@section('title', 'Contact Us | ' . config('storefront.store_name', 'BENAZIR'))

@section('content')
<section class="relative overflow-hidden bg-background">

    {{-- Decorative background --}}
    <div class="pointer-events-none absolute -right-32 top-10 h-96 w-96 rounded-full bg-secondary/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-32 top-96 h-80 w-80 rounded-full bg-primary/5 blur-3xl"></div>

    {{-- Editorial hero --}}
    <div class="relative border-b border-outline-variant/50">
        <div class="mx-auto max-w-7xl px-margin py-14 sm:py-20 lg:px-margin-md lg:py-24">
            <div class="grid items-end gap-10 lg:grid-cols-2 lg:gap-16">

                <div>
                    <div class="mb-6 inline-flex items-center gap-3">
                        <span class="h-px w-10 bg-secondary"></span>
                        <span class="font-label-uppercase text-label-uppercase tracking-[0.25em] text-secondary">
                            THE {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} ATELIER
                        </span>
                    </div>

                    <h1 class="max-w-2xl font-display-hero text-headline-lg leading-[1.08] tracking-tight text-primary sm:text-5xl lg:text-7xl">
                        We would love to
                        <span class="italic text-secondary">hear from you.</span>
                    </h1>

                    <p class="mt-6 max-w-xl text-base leading-8 text-on-surface-variant sm:text-lg">
                        Whether you need help choosing the perfect ensemble, have a question about an order,
                        or wish to discuss a bespoke creation, our team is here to help.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-on-surface-variant">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-secondary">schedule</span>
                            Monday – Saturday
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg text-secondary">mail</span>
                            Response within one business day
                        </div>
                    </div>
                </div>

                {{-- Editorial brand panel --}}
                <div class="relative min-h-[250px] overflow-hidden rounded-2xl border border-outline-variant/50 bg-surface-container-low p-8 sm:p-12 lg:min-h-[330px] lg:p-14">
                    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full border border-secondary/30"></div>
                    <div class="absolute -right-1 -top-1 h-32 w-32 rounded-full border border-secondary/30"></div>
                    <div class="absolute -bottom-16 -left-10 h-48 w-48 rounded-full border border-primary/10"></div>

                    <div class="relative flex h-full min-h-[180px] flex-col justify-between">
                        <span class="material-symbols-outlined text-4xl text-secondary">diamond</span>

                        <div class="my-8">
                            <p class="font-display-hero text-4xl tracking-[0.16em] text-primary sm:text-5xl">
                                {{ config('storefront.store_name', 'BENAZIR') }}
                            </p>
                            <p class="mt-3 text-xs font-medium uppercase tracking-[0.3em] text-secondary">
                                Lahore · London · New York
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="h-px w-12 bg-secondary"></span>
                            <p class="text-sm italic text-on-surface-variant">
                                Thoughtfully made. Beautifully worn.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Contact content --}}
    <div class="relative mx-auto max-w-7xl px-margin py-14 lg:px-margin-md lg:py-20">
        <div class="grid items-start gap-10 lg:grid-cols-12 lg:gap-14">

            {{-- Contact form --}}
            <div class="lg:col-span-7">
                <div class="mb-8">
                    <p class="font-label-uppercase text-label-uppercase tracking-[0.2em] text-secondary">
                        SEND US A MESSAGE
                    </p>
                    <h2 class="mt-3 font-display-hero text-3xl leading-tight text-primary sm:text-4xl">
                        How may we assist you?
                    </h2>
                    <p class="mt-3 max-w-xl leading-7 text-on-surface-variant">
                        Share a few details below, and the right member of our team will get back to you.
                    </p>
                </div>

                {{-- Success message --}}
                @if (session('success'))
                    <div role="status" class="mb-6 flex items-start gap-3 rounded-xl border border-green-700/20 bg-green-50 p-4 text-sm text-green-900">
                        <span class="material-symbols-outlined">check_circle</span>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-800">
                        <p class="font-semibold">Please check the following fields:</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/contact') }}" method="POST"
                    class="rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-5 shadow-sm sm:p-8 lg:p-10">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        {{-- First name --}}
                        <div>
                            <label for="first_name" class="mb-2 block text-sm font-medium text-on-surface">
                                First name <span class="text-primary">*</span>
                            </label>
                            <input
                                id="first_name"
                                name="first_name"
                                type="text"
                                value="{{ old('first_name') }}"
                                autocomplete="given-name"
                                maxlength="80"
                                required
                                placeholder="Your first name"
                                class="w-full rounded-xl border border-outline-variant bg-background px-4 py-3.5 text-sm text-on-surface outline-none transition placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                            @error('first_name')
                                <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Last name --}}
                        <div>
                            <label for="last_name" class="mb-2 block text-sm font-medium text-on-surface">
                                Last name
                            </label>
                            <input
                                id="last_name"
                                name="last_name"
                                type="text"
                                value="{{ old('last_name') }}"
                                autocomplete="family-name"
                                maxlength="80"
                                placeholder="Your last name"
                                class="w-full rounded-xl border border-outline-variant bg-background px-4 py-3.5 text-sm text-on-surface outline-none transition placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                            @error('last_name')
                                <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-on-surface">
                                Email address <span class="text-primary">*</span>
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                maxlength="254"
                                required
                                placeholder="you@example.com"
                                class="w-full rounded-xl border border-outline-variant bg-background px-4 py-3.5 text-sm text-on-surface outline-none transition placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                            @error('email')
                                <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-on-surface">
                                Phone number <span class="text-on-surface-variant">(optional)</span>
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                                maxlength="30"
                                placeholder="+92 ..."
                                class="w-full rounded-xl border border-outline-variant bg-background px-4 py-3.5 text-sm text-on-surface outline-none transition placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                            @error('phone')
                                <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Subject --}}
                        <div class="sm:col-span-2">
                            <label for="subject" class="mb-2 block text-sm font-medium text-on-surface">
                                What can we help you with? <span class="text-primary">*</span>
                            </label>
                            <select
                                id="subject"
                                name="subject"
                                required
                                class="w-full rounded-xl border border-outline-variant bg-background px-4 py-3.5 text-sm text-on-surface outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >
                                <option value="">Select an enquiry type</option>
                                <option value="Order enquiry" @selected(old('subject') === 'Order enquiry')>Order enquiry</option>
                                <option value="Product information" @selected(old('subject') === 'Product information')>Product information</option>
                                <option value="Sizing and fitting" @selected(old('subject') === 'Sizing and fitting')>Sizing and fitting</option>
                                <option value="Bespoke and custom orders" @selected(old('subject') === 'Bespoke and custom orders')>Bespoke and custom orders</option>
                                <option value="Shipping and returns" @selected(old('subject') === 'Shipping and returns')>Shipping and returns</option>
                                <option value="Other" @selected(old('subject') === 'Other')>Other</option>
                            </select>
                            @error('subject')
                                <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div class="sm:col-span-2">
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="message" class="block text-sm font-medium text-on-surface">
                                    Your message <span class="text-primary">*</span>
                                </label>
                                <span class="text-xs text-on-surface-variant">Tell us a little more</span>
                            </div>
                            <textarea
                                id="message"
                                name="message"
                                rows="6"
                                minlength="10"
                                maxlength="5000"
                                required
                                placeholder="Please share your question or how we can help..."
                                class="w-full resize-y rounded-xl border border-outline-variant bg-background px-4 py-3.5 text-sm leading-6 text-on-surface outline-none transition placeholder:text-on-surface-variant/60 focus:border-primary focus:ring-2 focus:ring-primary/10"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-start gap-3 rounded-xl bg-background p-4">
                        <span class="material-symbols-outlined mt-0.5 text-xl text-secondary">lock</span>
                        <p class="text-xs leading-5 text-on-surface-variant">
                            Your details will be used to respond to your enquiry. Please do not include passwords
                            or payment-card information in your message.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="mt-6 inline-flex w-full items-center justify-center gap-3 rounded-xl bg-primary px-6 py-4 text-sm font-semibold tracking-wide text-on-primary shadow-sm transition duration-300 hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 sm:w-auto"
                    >
                        Send your message
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>

                    <p class="mt-4 text-xs leading-5 text-on-surface-variant">
                        Fields marked with <span class="text-primary">*</span> are required.
                    </p>
                </form>
            </div>

            {{-- Contact information --}}
            <aside class="space-y-6 lg:col-span-5">

                <div class="rounded-2xl bg-primary p-7 text-on-primary shadow-sm sm:p-9">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-secondary">
                        HERE FOR YOU
                    </p>
                    <h2 class="mt-4 font-display-hero text-3xl leading-tight sm:text-4xl">
                        Personal service, always.
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-on-primary/80">
                        Every detail matters to us. Reach out for thoughtful guidance on your order,
                        garment care, sizing or a special occasion ensemble.
                    </p>

                    <div class="mt-8 space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10">
                                <span class="material-symbols-outlined text-secondary">schedule</span>
                            </div>
                            <div>
                                <p class="font-medium">Customer care hours</p>
                                <p class="mt-1 text-sm leading-6 text-on-primary/75">
                                    Monday – Saturday<br>
                                    10:00 AM – 6:00 PM
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10">
                                <span class="material-symbols-outlined text-secondary">mail</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium">Email us</p>
                                <a href="mailto:{{ config('storefront.contact.email', 'care@benazir.example') }}"
                                    class="mt-1 inline-block break-all text-sm text-on-primary/75 transition hover:text-white">
                                    {{ config('storefront.contact.email', 'care@benazir.example') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Studio address --}}
                <div class="rounded-2xl border border-outline-variant/60 bg-surface-container-lowest p-7 shadow-sm sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-secondary/10">
                            <span class="material-symbols-outlined text-2xl text-secondary">location_on</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary">
                                OUR STUDIO
                            </p>
                            <h3 class="mt-2 font-display-hero text-2xl text-primary">
                                {{ config('storefront.contact.address', 'Lahore, Pakistan') }}
                            </h3>
                            <p class="mt-3 text-sm leading-7 text-on-surface-variant">
                                {{ config('storefront.contact.address', 'Lahore, Pakistan') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-outline-variant/50 pt-5">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-sm text-on-surface-variant">Phone</span>
                            <a href="tel:{{ config('storefront.contact.phone', '+923000000000') }}"
                                class="text-sm font-medium text-primary hover:text-secondary">
                                {{ config('storefront.contact.phone', '+92 300 0000000') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Quick help --}}
                <div class="rounded-2xl border border-outline-variant/60 bg-surface-container-low p-7 sm:p-8">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-3xl text-secondary">support_agent</span>
                        <div>
                            <h3 class="font-display-hero text-xl text-primary">Need help with an order?</h3>
                            <p class="mt-2 text-sm leading-6 text-on-surface-variant">
                                For a quicker response, include your order number when contacting us about an
                                existing purchase.
                            </p>
                            <a href="{{ url('/shop') }}"
                                class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-secondary">
                                Explore the collection
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </div>

    {{-- Closing statement --}}
    <div class="border-t border-outline-variant/50 bg-surface-container-low">
        <div class="mx-auto max-w-7xl px-margin py-12 text-center lg:px-margin-md lg:py-16">
            <span class="material-symbols-outlined text-3xl text-secondary">auto_awesome</span>
            <p class="mt-4 font-display-hero text-2xl leading-snug text-primary sm:text-3xl">
                Thank you for being part of the {{ config('storefront.store_name', 'BENAZIR') }} story.
            </p>
            <p class="mt-3 text-sm text-on-surface-variant">
                Lahore · London · New York
            </p>
        </div>
    </div>

</section>
@endsection
