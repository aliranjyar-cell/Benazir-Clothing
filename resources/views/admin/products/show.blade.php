@extends('layouts.admin.app')

@section('title', $product->name)

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Products', 'url' => route('products.index')],
        ['label' => $product->name],
    ]" />
@endsection

@section('content')
    @php
        $stock = (int) ($product->total_stock ?? 0);
        $hasVariants = $product->variants_count > 0;
    @endphp

    <x-admin.page-heading
        title="{{ $product->name }}"
        description="Slug: /{{ $product->slug }}{{ $product->category ? ' · '.$product->category->name : '' }}"
    >
        <x-slot:actions>
            <form
                method="POST"
                action="{{ route('products.toggle-status', $product) }}"
                onsubmit="return confirm('{{ $product->is_active ? 'Deactivate' : 'Reactivate' }} &quot;{{ $product->name }}&quot;?')"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac {{ $product->is_active ? 'border-blush/40 bg-blush-soft text-blush-deep hover:bg-blush hover:text-white' : 'border-mint/40 bg-mint-soft text-mint-deep hover:bg-mint hover:text-aubergine' }}"
                >
                    {{ $product->is_active ? 'Deactivate' : 'Reactivate' }}
                </button>
            </form>

            <a
                href="{{ route('products.edit', $product) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-lilac px-4 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                Edit product
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <div class="grid gap-5 lg:grid-cols-3">
        {{-- Main details --}}
        <div class="space-y-5 lg:col-span-2">
            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Description</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-muted">
                    {{ $product->description ?: 'No description has been added yet.' }}
                </p>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Details</h2>

                <dl class="mt-4 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Category</dt>
                        <dd class="mt-1 text-sm text-ink">
                            {{ $product->category?->name ?: 'Uncategorised' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Fabric</dt>
                        <dd class="mt-1 text-sm text-ink">
                            {{ $product->fabric ?: '—' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Care instructions</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm leading-relaxed text-ink">
                            {{ $product->care_instructions ?: '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Created</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $product->created_at->format('d M Y, H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Last updated</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $product->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Side column --}}
        <div class="space-y-5">
            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Status</h2>

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @if ($product->is_active)
                        <x-admin.badge tone="mint">Active</x-admin.badge>
                    @else
                        <x-admin.badge tone="blush">Inactive</x-admin.badge>
                    @endif

                    @if ($product->is_featured)
                        <x-admin.badge tone="lilac">Featured</x-admin.badge>
                    @else
                        <x-admin.badge>Not featured</x-admin.badge>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Product image</h2>

                @if ($product->primaryImage?->image_path)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($product->primaryImage->image_path) }}"
                        alt="{{ $product->name }}"
                        class="mt-4 aspect-square w-full rounded-xl object-cover ring-1 ring-black/5"
                    />
                @else
                    <div class="mt-4 flex flex-col items-center rounded-xl border border-dashed border-black/10 bg-ivory/40 px-4 py-8 text-center">
                        <span class="grid h-11 w-11 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                            <x-admin.icon name="cube" class="h-5 w-5" />
                        </span>
                        <p class="mt-3 text-xs leading-relaxed text-muted">
                            No image yet. Image uploads arrive with the media module.
                        </p>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Variants &amp; stock</h2>

                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Variants</dt>
                        <dd class="font-medium tabular-nums text-ink">{{ $product->variants_count }}</dd>
                    </div>

                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Stock (active variants)</dt>
                        <dd class="font-medium tabular-nums {{ $stock > 0 ? 'text-ink' : 'text-blush-deep' }}">
                            {{ $stock }} units
                        </dd>
                    </div>

                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Price range</dt>
                        <dd class="font-medium tabular-nums text-ink">
                            @if (! $hasVariants)
                                —
                            @elseif ((float) $product->price_min === (float) $product->price_max)
                                ₹{{ number_format((float) $product->price_min, 2) }}
                            @else
                                ₹{{ number_format((float) $product->price_min, 2) }} – ₹{{ number_format((float) $product->price_max, 2) }}
                            @endif
                        </dd>
                    </div>
                </dl>

                <p class="mt-4 rounded-xl bg-ivory/60 px-4 py-3 text-xs leading-relaxed text-muted">
                    Variant sizes, colours and pricing are managed in the next phase.
                </p>
            </div>
        </div>
    </div>
@endsection

