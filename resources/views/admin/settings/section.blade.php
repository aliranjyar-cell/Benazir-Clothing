@extends('layouts.admin.app')

@section('title', $section['title'] . ' Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">{{ $section['title'] }}</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    {{ $section['title'] }}
                </h1>
                <p class="mt-1 text-sm text-muted">
                    {{ $section['description'] }}
                </p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                All sections
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav :active="$section['key']" />

        {{-- Coming soon panel (no demo data — this section simply isn't implemented yet) --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col items-start gap-5 px-6 py-10 sm:flex-row sm:items-center sm:px-10">
                <span
                    class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-primary/10 text-primary ring-1 ring-primary/15">
                    <x-admin.icon name="{{ $section['icon'] }}" class="h-7 w-7" />
                </span>

                <div class="min-w-0 flex-1">
                    <span
                        class="inline-block rounded-full border border-secondary-fixed-dim/60 bg-secondary-container/40 px-3 py-1 text-[10px] font-semibold tracking-wider text-on-secondary-container uppercase">
                        Coming soon
                    </span>

                    <h2 class="mt-3 text-lg font-semibold text-ink">
                        {{ $section['title'] }} is on the roadmap
                    </h2>
                    <p class="mt-1.5 max-w-xl text-sm leading-relaxed text-muted">
                        This section is part of the Settings module but hasn't been implemented yet.
                        When it ships, everything you configure here will be stored in the same
                        database-backed settings store and take effect immediately — exactly like the
                        sections that are already live.
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('admin.settings.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-aubergine px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-aubergine/90">
                            Back to all sections
                        </a>
                        <a href="{{ route('admin.settings.general') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                            Configure the live sections
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 bg-[#FCFAF8]/60 px-6 py-4 text-xs text-muted sm:px-10">
                Live now: General Store · Shipping &amp; Delivery · Checkout &amp; Payments · Products &amp; Inventory
            </div>
        </div>
    </div>
@endsection