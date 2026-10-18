@extends('layouts.admin.app')

@section('title', 'General Store Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">General Store</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    General Store Settings
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Control your store's basic information.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav active="general" />

        <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @csrf

            {{-- Store identity --}}
            <x-admin.settings.panel title="Store identity"
                description="Name, branding and tagline used across the storefront.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.input label="Store name" name="store_name" :value="$settings['store_name']"
                        :required="true" :maxlength="100" />

                    <x-admin.settings.input label="Store tagline" name="store_tagline"
                        :value="$settings['store_tagline']" :maxlength="200"
                        placeholder="Lahore • London • New York" />
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="store_logo" class="mb-2 block text-sm font-medium text-ink">
                            Store logo
                        </label>
                        <input id="store_logo" name="store_logo" type="file" accept="image/*"
                            class="block w-full text-sm text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-lilac/25 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-aubergine hover:file:bg-lilac/40">
                        <p class="mt-1.5 text-xs text-muted">PNG, JPG or WebP · up to 2 MB. Shown in the storefront header.</p>
                        @if (!empty($settings['store_logo']))
                            <div class="mt-3 flex items-center gap-3">
                                <img src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Current store logo"
                                    class="h-12 w-auto rounded-lg border border-gray-100 bg-ivory p-1 object-contain">
                                <span class="text-xs text-muted">Current logo</span>
                            </div>
                        @endif
                        @error('store_logo')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="store_favicon" class="mb-2 block text-sm font-medium text-ink">
                            Favicon
                        </label>
                        <input id="store_favicon" name="store_favicon" type="file"
                            accept="image/png,image/x-icon,image/svg+xml,image/webp,image/jpeg"
                            class="block w-full text-sm text-muted file:mr-3 file:rounded-lg file:border-0 file:bg-lilac/25 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-aubergine hover:file:bg-lilac/40">
                        <p class="mt-1.5 text-xs text-muted">PNG, ICO, SVG or WebP · up to 512 KB.</p>
                        @if (!empty($settings['store_favicon']))
                            <div class="mt-3 flex items-center gap-3">
                                <img src="{{ asset('storage/' . $settings['store_favicon']) }}" alt="Current favicon"
                                    class="h-8 w-8 rounded border border-gray-100 bg-ivory p-1 object-contain">
                                <span class="text-xs text-muted">Current favicon</span>
                            </div>
                        @endif
                        @error('store_favicon')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.settings.panel>

            {{-- Contact information --}}
            <x-admin.settings.panel title="Contact information"
                description="How customers can reach your team.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.input label="Contact email" name="contact_email" type="email"
                        :value="$settings['contact_email']" :required="true" :maxlength="190"
                        placeholder="contact@example.com" />

                    <x-admin.settings.input label="Contact phone" name="contact_phone"
                        :value="$settings['contact_phone']" :maxlength="40" placeholder="+92 300 0000000" />
                </div>

                <x-admin.settings.textarea label="Business address" name="address" :value="$settings['address']"
                    :rows="3" :maxlength="255" placeholder="Street, city, country" />
            </x-admin.settings.panel>

            {{-- Regional preferences --}}
            <x-admin.settings.panel title="Regional preferences"
                description="Currency and time zone used across the store.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.select label="Currency" name="currency" :value="$settings['currency']"
                        :required="true"
                        :options="collect($currencies)->mapWithKeys(fn ($symbol, $code) => [$code => $code . ' (' . $symbol . ')'])->all()" />

                    <x-admin.settings.select label="Time zone" name="timezone" :value="$settings['timezone']"
                        :required="true" :options="collect($timezones)->flip()->all()" />
                </div>
            </x-admin.settings.panel>

            {{-- Store status --}}
            <x-admin.settings.panel title="Store status"
                description="Open the storefront to customers or take it offline for maintenance.">
                <div class="space-y-4">
                    <label
                        class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 px-4 py-3.5 transition has-[:checked]:border-lilac has-[:checked]:bg-lilac/10">
                        <input type="radio" name="store_status" value="open"
                            @checked(old('store_status', $settings['store_status']) === 'open')
                            class="mt-1 accent-aubergine">
                        <span>
                            <span class="block text-sm font-semibold text-ink">Open</span>
                            <span class="mt-0.5 block text-xs leading-5 text-muted">Customers can browse and place orders as
                                usual.</span>
                        </span>
                    </label>

                    <label
                        class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 px-4 py-3.5 transition has-[:checked]:border-lilac has-[:checked]:bg-lilac/10">
                        <input type="radio" name="store_status" value="maintenance"
                            @checked(old('store_status', $settings['store_status']) === 'maintenance')
                            class="mt-1 accent-aubergine">
                        <span>
                            <span class="block text-sm font-semibold text-ink">Maintenance mode</span>
                            <span class="mt-0.5 block text-xs leading-5 text-muted">Visitors see a "back soon" page.
                                Signed-in admins keep full access.</span>
                        </span>
                    </label>

                    @error('store_status')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
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