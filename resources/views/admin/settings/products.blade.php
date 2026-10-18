@extends('layouts.admin.app')

@section('title', 'Products & Inventory Settings')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">Products &amp; Inventory</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    Products &amp; Inventory
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Decide when stock is considered low and how much of the catalogue shows per page.
                </p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                All sections
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav active="products" />

        <form action="{{ route('admin.settings.products.update') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @csrf

            {{-- Stock alerts --}}
            <x-admin.settings.panel title="Stock alerts"
                description="Variants at or below this count are flagged everywhere stock is monitored.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.input label="Low-stock threshold" name="low_stock_threshold" type="number"
                        :value="$settings['low_stock_threshold']" :required="true" min="1" max="100" step="1"
                        hint="Drives the dashboard \"Low stock\" stat, the inventory filters and the amber stock badges." />
                </div>
            </x-admin.settings.panel>

            {{-- Storefront catalogue --}}
            <x-admin.settings.panel title="Storefront catalogue"
                description="How the shop grid paginates for customers.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-admin.settings.input label="Products per page" name="products_per_page" type="number"
                        :value="$settings['products_per_page']" :required="true" min="4" max="96" step="1"
                        hint="Number of products shown on each shop page before the pager." />
                </div>
            </x-admin.settings.panel>

            {{-- Quick links --}}
            <x-admin.settings.panel title="Where these settings apply"
                description="Save first, then review the pages that use these values.">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3.5 text-sm font-medium text-ink transition hover:border-lilac hover:bg-lilac/10">
                        <x-admin.icon name="home" class="h-5 w-5 shrink-0 text-primary" />
                        Dashboard — Low stock stat
                    </a>
                    <a href="{{ route('admin.inventory.index') }}"
                        class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3.5 text-sm font-medium text-ink transition hover:border-lilac hover:bg-lilac/10">
                        <x-admin.icon name="archive" class="h-5 w-5 shrink-0 text-primary" />
                        Inventory — filters &amp; badges
                    </a>
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