@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium tracking-[0.18em] text-secondary uppercase">
                    {{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} · Administration
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    Store settings
                </h1>
                <p class="mt-1 max-w-2xl text-sm text-muted">
                    Manage how your store runs — from identity and shipping to payments and stock.
                    Everything you save here is stored in the database and applies immediately.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        {{-- Quick summary --}}
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 rounded-2xl border border-primary/15 bg-primary/[0.04] px-5 py-3.5 text-sm text-ink">
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-mint-deep" aria-hidden="true"></span>
                <strong>{{ count(array_filter($sections, fn ($s) => $s['status'] === 'ready')) }}</strong>
                sections ready to configure
            </span>
            <span class="inline-flex items-center gap-2 text-muted">
                <span class="h-2 w-2 rounded-full bg-secondary-fixed-dim" aria-hidden="true"></span>
                <strong>{{ count(array_filter($sections, fn ($s) => $s['status'] !== 'ready')) }}</strong>
                sections coming soon
            </span>
        </div>

        {{-- Eight clickable sections --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($sections as $section)
                @php($isReady = $section['status'] === 'ready')

                <a href="{{ $isReady
                    ? route($section['route'])
                    : route('admin.settings.section', $section['key']) }}"
                    class="group flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-card transition duration-150 hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                    <div class="flex items-start justify-between gap-3">
                        <span
                            class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary ring-1 ring-primary/15 transition-colors duration-150 group-hover:bg-primary group-hover:text-white">
                            <x-admin.icon name="{{ $section['icon'] }}" class="h-5 w-5" />
                        </span>

                        @if ($isReady)
                            <span
                                class="rounded-full border border-mint/50 bg-mint-soft px-2.5 py-1 text-[10px] font-semibold tracking-wider text-mint-deep uppercase">
                                Ready
                            </span>
                        @else
                            <span
                                class="rounded-full border border-secondary-fixed-dim/60 bg-secondary-container/40 px-2.5 py-1 text-[10px] font-semibold tracking-wider text-on-secondary-container uppercase">
                                Coming soon
                            </span>
                        @endif
                    </div>

                    <h2 class="mt-4 text-[15px] font-semibold text-ink">
                        {{ $section['title'] }}
                    </h2>
                    <p class="mt-1.5 flex-1 text-sm leading-relaxed text-muted">
                        {{ $section['description'] }}
                    </p>

                    <span
                        class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-primary/80 transition-colors group-hover:text-primary">
                        {{ $isReady ? 'Configure' : 'View roadmap' }}
                        <x-admin.icon name="chevron-right"
                            class="h-4 w-4 transition-transform duration-150 group-hover:translate-x-0.5" />
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@endsection