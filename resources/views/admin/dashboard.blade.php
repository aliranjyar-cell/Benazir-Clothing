@extends('layouts.admin.app')

@section('title', 'Dashboard')

@php
    $admin = auth()->user();
@endphp

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Dashboard'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading
        title="Welcome back, {{ $admin->first_name }}"
        description="Here is the current state of your Punjabi fashion catalogue — products, stock and orders at a glance."
    >
        <x-slot:actions>
            <span class="inline-flex items-center gap-2 rounded-full border border-black/[0.06] bg-white px-4 py-2 text-xs font-medium text-muted shadow-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-lilac" aria-hidden="true"></span>
                {{ now()->format('D, d M Y') }}
            </span>
        </x-slot:actions>
    </x-admin.page-heading>

    {{-- Metric cards — real counts from the catalogue tables (0 is a real value on a fresh store). --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 xl:grid-cols-4">
        <x-admin.stat-card
            label="Products"
            :value="$stats['products']"
            icon="cube"
            hint="Catalogue items, active and draft"
        />

        <x-admin.stat-card
            label="Categories"
            :value="$stats['categories']"
            icon="tag"
            hint="Collections organising your catalogue"
        />

        <x-admin.stat-card
            label="Low stock"
            :value="$stats['lowStock']"
            icon="alert"
            :tone="$stats['lowStock'] > 0 ? 'blush' : 'mint'"
            hint="Variants with {{ $stats['lowStockThreshold'] }} units or fewer"
        />

        <x-admin.stat-card
            label="Orders"
            value="—"
            icon="clipboard"
            :placeholder="true"
            hint="Sales tracking arrives with the orders module"
        />
    </div>

    {{-- Quick actions + catalogue readiness --}}
    <div class="mt-5 grid gap-5 lg:grid-cols-3">
        {{-- Quick actions --}}
        <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card lg:col-span-2">
            <h2 class="text-sm font-semibold tracking-tight text-ink">Quick actions</h2>
            <p class="mt-1 text-xs leading-relaxed text-muted">
                Jump straight into your catalogue and store operations.
            </p>

            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                @foreach ([
                    ['label' => 'Add a product', 'description' => 'Create a new salwar suit, saree or kurti listing', 'icon' => 'plus', 'route' => 'products.create'],
                    ['label' => 'Browse products', 'description' => 'Search, edit and manage the catalogue', 'icon' => 'cube', 'route' => 'products.index'],
                    ['label' => 'Manage inventory', 'description' => 'Update sizes, colours and stock levels', 'icon' => 'archive'],
                    ['label' => 'Review orders', 'description' => 'Process and fulfil customer orders', 'icon' => 'clipboard'],
                ] as $action)
                    @if (! empty($action['route']) && Route::has($action['route']))
                        <a
                            href="{{ route($action['route']) }}"
                            class="group flex items-start gap-3 rounded-xl border border-lilac/40 bg-lilac-soft/50 p-4 transition hover:bg-lilac-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-aubergine ring-1 ring-lilac/30">
                                <x-admin.icon name="{{ $action['icon'] }}" class="h-4 w-4" />
                            </span>

                            <div class="min-w-0">
                                <p class="flex items-center gap-1.5 text-sm font-medium text-ink">
                                    {{ $action['label'] }}
                                    <x-admin.icon name="chevron-right" class="h-3.5 w-3.5 text-muted transition-transform group-hover:translate-x-0.5" />
                                </p>
                                <p class="mt-0.5 text-xs leading-relaxed text-muted">
                                    {{ $action['description'] }}
                                </p>
                            </div>
                        </a>
                    @else
                        <div
                            class="flex items-start gap-3 rounded-xl border border-dashed border-black/10 bg-ivory/40 p-4"
                            aria-disabled="true"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-muted ring-1 ring-black/5">
                                <x-admin.icon name="{{ $action['icon'] }}" class="h-4 w-4" />
                            </span>

                            <div class="min-w-0">
                                <p class="flex items-center gap-2 text-sm font-medium text-ink/70">
                                    {{ $action['label'] }}
                                    <span class="rounded-full bg-white px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-muted ring-1 ring-black/5">
                                        Soon
                                    </span>
                                </p>
                                <p class="mt-0.5 text-xs leading-relaxed text-muted">
                                    {{ $action['description'] }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>



        {{-- Catalogue readiness: real checks against the database --}}
        <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
            <h2 class="text-sm font-semibold tracking-tight text-ink">Catalogue readiness</h2>
            <p class="mt-1 text-xs leading-relaxed text-muted">
                Live checklist based on your current data.
            </p>

            <ul class="mt-4 space-y-3">
                @foreach ([
                    ['label' => 'Category structure created', 'done' => $stats['categories'] > 0],
                    ['label' => 'First product added', 'done' => $stats['products'] > 0],
                    ['label' => 'Variant stock tracked', 'done' => $stats['variants'] > 0],
                ] as $check)
                    <li class="flex items-center gap-3 text-sm">
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full {{ $check['done'] ? 'bg-mint-soft text-mint-deep ring-1 ring-mint/40' : 'bg-ivory text-muted ring-1 ring-black/5' }}">
                            <x-admin.icon name="{{ $check['done'] ? 'check' : 'chevron-right' }}" class="h-3.5 w-3.5" />
                        </span>
                        <span class="{{ $check['done'] ? 'text-ink' : 'text-muted' }}">
                            {{ $check['label'] }}
                        </span>
                    </li>
                @endforeach
            </ul>

            <p class="mt-5 rounded-xl bg-ivory/60 px-4 py-3 text-xs leading-relaxed text-muted">
                Steps complete: {{ collect([[$stats['categories'] > 0], [$stats['products'] > 0], [$stats['variants'] > 0]])->filter(fn ($c) => $c[0])->count() }} of 3
            </p>
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="mt-5 rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
        <h2 class="text-sm font-semibold tracking-tight text-ink">Recent activity</h2>

        <div class="flex flex-col items-center justify-center px-4 py-10 text-center">
            <span class="grid h-12 w-12 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                <x-admin.icon name="sparkle" class="h-6 w-6" />
            </span>

            <h3 class="mt-4 text-sm font-semibold text-ink">No activity yet</h3>
            <p class="mt-1 max-w-sm text-xs leading-relaxed text-muted">
                Product changes, new orders and staff actions will appear here as soon as those modules go live.
            </p>
        </div>
    </div>
@endsection
