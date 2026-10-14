@extends('layouts.admin.app')

@section('title', 'Add Category')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Categories', 'url' => route('categories.index')],
        ['label' => 'Add Category'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading
        title="Add category"
        description="Create a new category to organise your Punjabi fashion catalogue."
    >
        <x-slot:actions>
            <a
                href="{{ route('categories.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
            >
                <x-admin.icon name="chevron-left" class="h-4 w-4" />
                Back to categories
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <form
        action="{{ route('categories.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="mt-5 grid gap-5 rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card sm:p-7"
    >
        @csrf

        @include('categories.partials.form', ['category' => null, 'parents' => $parents])

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-aubergine px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                <x-admin.icon name="plus" class="h-4 w-4" />
                Create category
            </button>

            <a
                href="{{ route('categories.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-muted transition hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
            >
                Cancel
            </a>
        </div>
    </form>
@endsection
