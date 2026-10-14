
@extends('layouts.admin.app')

@section('title', 'Customers')
@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-lilac">CUSTOMER MANAGEMENT</p>
            <h1 class="mt-1 text-2xl font-semibold text-ink">
                Add Customer
            </h1>
            <p class="mt-1 text-sm text-muted">
                Create a customer profile for AURELLE.
            </p>
        </div>

        <a href="{{ route('admin.customers.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Customers
        </a>
    </div>

    {{-- Success message, if redirected back with one --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <h2 class="text-base font-semibold text-ink">
                Customer Information
            </h2>
            <p class="mt-1 text-sm text-muted">
                Fields marked with * are required.
            </p>
        </div>

        <form action="{{ route('admin.customers.store') }}"
              method="POST"
              class="space-y-6 p-6">
            @csrf

            {{-- Name and email --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-ink">
                        Full Name *
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        placeholder="e.g. Ayesha Khan"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('name') border-rose-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-ink">
                        Email Address *
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="customer@example.com"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('email') border-rose-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="mb-2 block text-sm font-medium text-ink">
                    Phone Number
                </label>
                <input
                    id="phone"
                    name="phone"
                    type="tel"
                    value="{{ old('phone') }}"
                    autocomplete="tel"
                    placeholder="03XXXXXXXXX"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('phone') border-rose-500 @enderror"
                >
                @error('phone')
                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-sm font-semibold text-ink">Account Password</h3>
                <p class="mt-1 text-sm text-muted">
                    Set a password for the customer's account.
                </p>
            </div>

            {{-- Password fields --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-ink">
                        Password *
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="At least 8 characters"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('password') border-rose-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-ink">
                        Confirm Password *
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Enter password again"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30"
                    >
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.customers.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-lilac focus:ring-offset-2">
                    <i class="fa-solid fa-user-plus"></i>
                    Create Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
