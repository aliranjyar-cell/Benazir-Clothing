@extends('storefront.layouts.store')

@section('title', 'Your account')

@section('content')
    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <div class="max-w-3xl">
                <nav aria-label="Account" class="flex flex-wrap gap-space-md text-body-sm text-on-surface-variant/80">
                    <a
                        href="#profile"
                        class="font-label-uppercase text-label-uppercase tracking-widest rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                    >
                        Your profile
                    </a>
                    <a
                        href="#orders"
                        class="font-label-uppercase text-label-uppercase tracking-widest rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-on-surface transition-colors hover:border-primary/40 hover:text-primary"
                    >
                        Order history
                    </a>
                </nav>

                <div class="mt-space-2xl grid gap-space-xl">
                    <section id="profile" class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                        <h2 class="font-display-hero font-headline-lg text-headline-lg text-primary">Update profile</h2>
                        <form method="POST" action="{{ route('store.account.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="mt-space-md grid gap-space-md">
                                <div class="grid gap-space-sm sm:grid-cols-2">
                                    <div class="space-y-space-xs">
                                        <label class="font-body-sm text-body-sm text-on-surface-variant">Name</label>
                                        <input
                                            name="name"
                                            value="{{ old('name', $customer->name) }}"
                                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        />
                                        @error('name')
                                            <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-space-xs">
                                        <label class="font-body-sm text-body-sm text-on-surface-variant">Email</label>
                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email', $customer->email) }}"
                                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                        />
                                        @error('email')
                                            <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="space-y-space-xs">
                                    <label class="font-body-sm text-body-sm text-on-surface-variant">Phone</label>
                                    <input
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone', $customer->phone) }}"
                                        class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all"
                                    />
                                    @error('phone')
                                        <p class="font-label-uppercase text-label-uppercase text-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-space-md flex justify-end">
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest hover:bg-primary-container transition-colors"
                                >
                                    Update profile
                                    <span class="material-symbols-outlined text-sm">save</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <section id="orders" class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                        <h2 class="font-display-hero font-headline-lg text-headline-lg text-primary">Order history</h2>

                        @if ($orders->isEmpty())
                            <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-sm">You have not placed any orders yet.</p>
                        @else
                            <div class="mt-space-md space-y-space-sm">
                                @foreach ($orders as $order)
                                    <a href="{{ route('store.orders.confirm', $order) }}" class="block border-b border-outline-variant/40 pb-space-md">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-display-hero font-headline-lg text-headline-lg text-primary">#{{$order->order_number}}</p>
                                                <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $order->created_at->format('F d, Y') }}</p>
                                            </div>
                                            <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-[0.6rem] font-label-uppercase tracking-widest {{ $order->status === 'delivered' ? 'bg-green-container text-on-green-container' : ($order->status === 'cancelled' ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container') }}"
                                            >
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </div>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-space-xs">
                                            {{ $order->items_count }} item(s) · {{ $order->grand_total }} {{ config('storefront.currency_symbol', '₨') }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
