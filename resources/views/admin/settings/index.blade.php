@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-lilac">ADMINISTRATION</p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    General Store Settings
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Control your store's basic information.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        {{-- Success message, if redirected back with one --}}
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @csrf

            {{-- Store identity --}}
            <div class="border-b border-gray-100 px-6 py-5">
                <h2 class="text-base font-semibold text-ink">
                    Store identity
                </h2>
                <p class="mt-1 text-sm text-muted">
                    Name, branding and tagline used across the storefront.
                </p>
            </div>
            <div class="space-y-6 p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="store_name" class="mb-2 block text-sm font-medium text-ink">
                            Store name *
                        </label>
                        <input id="store_name" name="store_name" type="text" required maxlength="100"
                            value="{{ old('store_name', $settings['store_name']) }}"
                            class="w-full rounded-xl border {{ $errors->has('store_name') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('store_name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="store_tagline" class="mb-2 block text-sm font-medium text-ink">
                            Store tagline
                        </label>
                        <input id="store_tagline" name="store_tagline" type="text" maxlength="200"
                            value="{{ old('store_tagline', $settings['store_tagline']) }}"
                            placeholder="Lahore • London • New York"
                            class="w-full rounded-xl border {{ $errors->has('store_tagline') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('store_tagline')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
            </div>


            {{-- Contact information --}}
            <div class="border-y border-gray-100 bg-[#FCFAF8]/60 px-6 py-5">
                <h2 class="text-base font-semibold text-ink">
                    Contact information
                </h2>
                <p class="mt-1 text-sm text-muted">
                    How customers can reach your team.
                </p>
            </div>

            <div class="space-y-6 p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="contact_email" class="mb-2 block text-sm font-medium text-ink">
                            Contact email *
                        </label>
                        <input id="contact_email" name="contact_email" type="email" required maxlength="190"
                            value="{{ old('contact_email', $settings['contact_email']) }}"
                            class="w-full rounded-xl border {{ $errors->has('contact_email') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('contact_email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_phone" class="mb-2 block text-sm font-medium text-ink">
                            Contact phone
                        </label>
                        <input id="contact_phone" name="contact_phone" type="text" maxlength="40"
                            value="{{ old('contact_phone', $settings['contact_phone']) }}"
                            placeholder="+92 300 0000000"
                            class="w-full rounded-xl border {{ $errors->has('contact_phone') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                        @error('contact_phone')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="mb-2 block text-sm font-medium text-ink">
                        Business address
                    </label>
                    <textarea id="address" name="address" rows="3" maxlength="255"
                        placeholder="Street, city, country"
                        class="w-full rounded-xl border {{ $errors->has('address') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">{{ old('address', $settings['address']) }}</textarea>
                    @error('address')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            {{-- Regional preferences --}}
            <div class="border-y border-gray-100 bg-[#FCFAF8]/60 px-6 py-5">
                <h2 class="text-base font-semibold text-ink">
                    Regional preferences
                </h2>
                <p class="mt-1 text-sm text-muted">
                    Currency and time zone used across the store.
                </p>
            </div>

            <div class="space-y-6 p-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="currency" class="mb-2 block text-sm font-medium text-ink">
                            Currency *
                        </label>
                        <select id="currency" name="currency" required
                            class="w-full rounded-xl border {{ $errors->has('currency') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                            @foreach ($currencies as $code => $symbol)
                                <option value="{{ $code }}"
                                    @selected(old('currency', $settings['currency']) === $code)>
                                    {{ $code }} ({{ $symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('currency')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="timezone" class="mb-2 block text-sm font-medium text-ink">
                            Time zone *
                        </label>
                        <select id="timezone" name="timezone" required
                            class="w-full rounded-xl border {{ $errors->has('timezone') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30">
                            @foreach ($timezones as $timezone)
                                <option value="{{ $timezone }}"
                                    @selected(old('timezone', $settings['timezone']) === $timezone)>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                        @error('timezone')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>


            {{-- Store status --}}
            <div class="border-y border-gray-100 bg-[#FCFAF8]/60 px-6 py-5">
                <h2 class="text-base font-semibold text-ink">
                    Store status
                </h2>
                <p class="mt-1 text-sm text-muted">
                    Open the storefront to customers or take it offline for maintenance.
                </p>
            </div>

            <div class="space-y-4 p-6">
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

            {{-- Footer actions --}}
            <div class="flex justify-end border-t border-gray-100 bg-[#FCFAF8]/60 px-6 py-4">
                <button type="submit"
                    class="rounded-xl bg-aubergine px-6 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90">
                    Save settings
                </button>
            </div>
        </form>
    </div>
@endsection

