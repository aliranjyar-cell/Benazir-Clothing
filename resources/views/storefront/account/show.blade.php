@extends('storefront.layouts.store')

@section('title',
    match ($page) {
    'profile' => 'My Profile',
    'orders' => 'My Orders',
    default => 'My Account',
    })

@section('content')
    <div class="bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-xl">
            <div class="flex flex-col gap-space-xl lg:flex-row">
                <aside class="lg:w-60 lg:shrink-0">
                    <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ strtoupper(config('storefront.store_name', 'BENAZIR')) }}</p>
                    <h1 class="mt-space-xs font-display-hero text-headline-lg text-primary">My Account</h1>
                    <nav aria-label="Account navigation" class="mt-space-md grid gap-1">
                        @foreach ([['dashboard', 'My Account', 'store.account'], ['profile', 'My Profile', 'store.account.profile'], ['orders', 'My Orders', 'store.account.orders']] as [$key, $label, $route])
                            <a href="{{ route($route) }}" @if ($page === $key) aria-current="page" @endif
                                class="rounded-lg px-space-md py-3 font-body-sm text-body-sm transition-colors {{ $page === $key ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:bg-surface-container-lowest hover:text-primary' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                    <a href="{{ route('store.shop') }}"
                        class="mt-space-md inline-flex items-center gap-space-xs font-label-uppercase text-label-uppercase tracking-widest text-secondary hover:text-primary">
                        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
                        Continue shopping
                    </a>
                    <form method="POST" action="{{ route('store.logout') }}" class="mt-space-sm">
                        @csrf
                        <button type="submit"
                            class="font-label-uppercase text-label-uppercase tracking-widest text-on-surface-variant underline underline-offset-4 hover:text-primary">
                            Sign out
                        </button>
                    </form>
                </aside>

                <main class="min-w-0 flex-1">
                    @if ($page === 'profile')
                        <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                            <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">Your details
                            </p>
                            <h2 class="mt-space-xs font-headline-lg text-headline-lg text-primary">My Profile</h2>
                            <form method="POST" action="{{ route('store.account.update') }}" class="mt-space-md">
                                @csrf
                                @method('PATCH')
                                <div class="grid gap-space-md sm:grid-cols-2">
                                    <div class="space-y-space-xs">
                                        <label for="name"
                                            class="font-body-sm text-body-sm text-on-surface-variant">Name</label>
                                        <input id="name" name="name" autocomplete="name" required
                                            value="{{ old('name', $customer->name) }}"
                                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" />
                                        @error('name')
                                            <p class="font-body-sm text-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-space-xs">
                                        <label for="email"
                                            class="font-body-sm text-body-sm text-on-surface-variant">Email</label>
                                        <input id="email" type="email" name="email" autocomplete="email" required
                                            value="{{ old('email', $customer->email) }}"
                                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" />
                                        @error('email')
                                            <p class="font-body-sm text-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-space-xs sm:col-span-2">
                                        <label for="phone"
                                            class="font-body-sm text-body-sm text-on-surface-variant">Phone</label>
                                        <input id="phone" type="tel" name="phone" autocomplete="tel"
                                            value="{{ old('phone', $customer->phone) }}"
                                            class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2.5 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" />
                                        @error('phone')
                                            <p class="font-body-sm text-error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-space-md flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-6 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest hover:bg-primary-container transition-colors">
                                        Save profile <span class="material-symbols-outlined text-sm"
                                            aria-hidden="true">save</span>
                                    </button>
                                </div>
                            </form>
                        </section>
                    @elseif ($page === 'orders')
                        <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                            <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">Your
                                purchases</p>
                            <h2 class="mt-space-xs font-headline-lg text-headline-lg text-primary">My Orders</h2>
                            @if ($orders->isEmpty())
                                <p class="mt-space-md font-body-sm text-body-sm text-on-surface-variant">You have not placed
                                    any orders yet.</p>
                            @else
                                <div class="mt-space-md divide-y divide-outline-variant/50">
                                    @foreach ($orders as $order)
                                        <article
                                            class="grid gap-space-sm py-space-md sm:grid-cols-[1fr_auto] sm:items-center">
                                            <div>
                                                <p
                                                    class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">
                                                    #{{ $order->order_number }}</p>
                                                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                                    {{ $order->created_at->format('F j, Y') }}</p>
                                                <p class="mt-1 font-body-sm text-body-sm text-on-surface">
                                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Not provided')) }}
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between gap-space-md sm:justify-end">
                                                <div class="text-right">
                                                    <p class="font-display-hero text-lg text-primary">
                                                        {{ $order->currency }}
                                                        {{ number_format((float) $order->grand_total, 2) }}</p>
                                                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                                </div>
                                                <a href="{{ route('store.account.orders.show', $order) }}"
                                                    class="inline-flex items-center gap-space-xs rounded-lg border border-outline-variant px-3 py-2 font-label-uppercase text-label-uppercase tracking-widest text-primary hover:border-secondary"
                                                    aria-label="View order {{ $order->order_number }}">
                                                    View <span class="material-symbols-outlined text-base"
                                                        aria-hidden="true">arrow_forward</span>
                                                </a>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                                <div class="mt-space-md">{{ $orders->links() }}</div>
                            @endif
                        </section>
                    @else
                        <div class="flex flex-wrap items-end justify-between gap-space-md">
                            <div>
                                <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">Welcome
                                    back</p>
                                <h2 class="mt-space-xs font-headline-lg text-headline-lg text-primary">
                                    {{ $customer->name }}</h2>
                            </div>
                            <a href="{{ route('store.account.profile') }}"
                                class="font-label-uppercase text-label-uppercase tracking-widest text-secondary underline underline-offset-4 hover:text-primary">Edit
                                profile</a>
                        </div>
                        <dl
                            class="mt-space-md grid gap-px overflow-hidden rounded-xl border border-outline-variant bg-outline-variant sm:grid-cols-2">
                            <div class="bg-surface-container-lowest p-space-md">
                                <dt
                                    class="font-label-uppercase text-label-uppercase tracking-widest text-on-surface-variant">
                                    Orders placed</dt>
                                <dd class="mt-space-xs font-display-hero text-3xl text-primary">{{ $orderCount }}</dd>
                            </div>
                            <div class="bg-surface-container-lowest p-space-md">
                                <dt
                                    class="font-label-uppercase text-label-uppercase tracking-widest text-on-surface-variant">
                                    Account email</dt>
                                <dd class="mt-space-xs break-all font-body-sm text-body-sm text-primary">
                                    {{ $customer->email }}</dd>
                            </div>
                        </dl>
                        <section
                            class="mt-space-xl rounded-xl border border-outline-variant bg-surface-container-lowest p-space-xl">
                            <div class="flex flex-wrap items-center justify-between gap-space-sm">
                                <h3 class="font-headline-lg text-headline-lg text-primary">Recent orders</h3>
                                <a href="{{ route('store.account.orders') }}"
                                    class="font-label-uppercase text-label-uppercase tracking-widest text-secondary underline underline-offset-4">View
                                    all</a>
                            </div>
                            @if ($orders->isEmpty())
                                <p class="mt-space-md font-body-sm text-body-sm text-on-surface-variant">You have not placed
                                    any orders yet.</p>
                            @else
                                <div class="mt-space-md divide-y divide-outline-variant/50">
                                    @foreach ($orders as $order)
                                        <article
                                            class="grid gap-space-sm py-space-md sm:grid-cols-[1fr_auto] sm:items-center">
                                            <div>
                                                <p
                                                    class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">
                                                    #{{ $order->order_number }}</p>
                                                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                                                    {{ $order->created_at->format('F j, Y') }}</p>
                                                <p class="mt-1 font-body-sm text-body-sm text-on-surface">
                                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Not provided')) }}
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between gap-space-md sm:justify-end">
                                                <div class="text-right">
                                                    <p class="font-display-hero text-lg text-primary">
                                                        {{ $order->currency }}
                                                        {{ number_format((float) $order->grand_total, 2) }}</p>
                                                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                                </div>
                                                <a href="{{ route('store.account.orders.show', $order) }}"
                                                    class="inline-flex items-center gap-space-xs rounded-lg border border-outline-variant px-3 py-2 font-label-uppercase text-label-uppercase tracking-widest text-primary hover:border-secondary"
                                                    aria-label="View order {{ $order->order_number }}">
                                                    View <span class="material-symbols-outlined text-base"
                                                        aria-hidden="true">arrow_forward</span>
                                                </a>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    @endif
                </main>
            </div>
        </div>
    </div>
@endsection
