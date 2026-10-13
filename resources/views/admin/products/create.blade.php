@extends('layouts.admin.app')

@section('title', 'Add Product')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Products', 'url' => route('products.index')],
        ['label' => 'Add Product'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading
        title="Add a product"
        description="Create the catalogue listing, then add sizes, colours, pricing and photos — all on this page."
    />

    @include('admin.products.partials.editor', [
        'action' => route('products.store'),
        'method' => null,
        'product' => null,
        'submitLabel' => 'Create product',
    ])
@endsection