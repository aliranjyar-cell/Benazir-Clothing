
@extends('layouts.admin.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-lilac">CUSTOMER MANAGEMENT</p>
            <h1 class="mt-1 text-2xl font-semibold text-ink">Edit Customer</h1>
            <p class="mt-1 text-sm text-muted">
                Update {{ $customer->name }}'s contact information.
            </p>
        </div>

        <a href="{{ route('admin.customers.show', $customer) }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Profile
        </a>
    </div>

    {{-- Form --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-6 py-5">
            <h2 class="text-base font-semibold text-ink">Customer Information</h2>
            <p class="mt-1 text-sm text-muted">
                Update the customer's name, email, and phone number.
            </p>
        </div>

        <form action="{{ route('admin.customers.update', $customer) }}"
              method="POST"
              class="space-y-6 p-6">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-ink">
                    Full Name *
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $customer->name) }}"
                    required
                    autocomplete="name"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('name') border-rose-500 @enderror"
                >
                @error('name')
                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-ink">
                    Email Address *
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $customer->email) }}"
                    required
                    autocomplete="email"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('email') border-rose-500 @enderror"
                >
                @error('email')
                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                @enderror
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
                    value="{{ old('phone', $customer->phone) }}"
                    autocomplete="tel"
                    placeholder="03XXXXXXXXX"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-ink outline-none transition focus:border-lilac focus:ring-2 focus:ring-lilac/30 @error('phone') border-rose-500 @enderror"
                >
                @error('phone')
                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.customers.show', $customer) }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-lilac focus:ring-offset-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
