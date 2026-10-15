@extends('layouts.admin.app')

@section('title', 'Edit Category')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Categories', 'url' => route('categories.index')],
        ['label' => 'Edit Category'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading title="Edit category" description="Update the category details, description and settings.">
        <x-slot:actions>
            <a href="{{ route('categories.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac">
                <x-admin.icon name="chevron-left" class="h-4 w-4" />
                Back to categories
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data"
        class="mt-5 overflow-hidden rounded-xl border border-black/[0.07] bg-white shadow-card">
        @csrf
        @method('PATCH')

        <div class="border-b border-black/[0.06] px-5 py-4 sm:px-7">
            <h2 class="text-sm font-semibold text-ink">Category details</h2>
            <p class="mt-1 text-sm text-muted">Update the catalogue information and storefront settings.</p>
        </div>

        <div class="grid gap-6 p-5 sm:p-7">
            @include('categories.partials.form', ['category' => $category, 'parents' => $parents])
        </div>

        <div
            class="flex flex-col-reverse gap-3 border-t border-black/[0.06] bg-ivory/30 px-5 py-4 sm:flex-row sm:items-center sm:justify-end sm:px-7">
            <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-aubergine px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                <x-admin.icon name="check" class="h-4 w-4" />
                Save changes
            </button>

            <a href="{{ route('categories.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-muted transition hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac">
                Cancel
            </a>
        </div>
    </form>
@endsection
