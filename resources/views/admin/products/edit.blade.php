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
        description="Update “{{ $product->name }}” — details, variants and gallery images."
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

    @include('admin.products.partials.editor', [
        'action' => route('products.update', $product),
        'method' => 'PATCH',
        'product' => $product,
        'submitLabel' => 'Save changes',
    ])
@endsection