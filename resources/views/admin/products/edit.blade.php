@extends('layouts.admin.app')

@section('title', 'Edit Product')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Products', 'url' => route('products.index')],
        ['label' => $product->name, 'url' => route('products.show', $product)],
        ['label' => 'Edit'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading
        title="Edit product"
        description="Update the catalogue details for “{{ $product->name }}”. Variant sizes, colours and pricing are managed separately."
    >
        <x-slot:actions>
            <a
                href="{{ route('products.show', $product) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
            >
                View product
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <form action="{{ route('products.update', $product) }}" method="POST" class="max-w-3xl">
        @csrf
        @method('PATCH')

        <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card sm:p-8">
            @include('admin.products._form', ['product' => $product])
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center">
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-lilac px-5 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                <x-admin.icon name="check" class="h-4 w-4" />
                Save changes
            </button>
        </div>
    </form>
@endsection