@extends('layouts.admin.app')

@section('title', $category->name)

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Categories', 'url' => route('categories.index')],
        ['label' => $category->name],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading title="Category details" description="Review catalogue information and storefront status.">
        <x-slot:actions>
            <a href="{{ route('categories.edit', $category) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-aubergine px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                <x-admin.icon name="pencil" class="h-4 w-4" />
                Edit
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <div class="mt-5 overflow-hidden rounded-xl border border-black/[0.07] bg-white shadow-card">
        <div class="grid lg:grid-cols-[minmax(0,1.15fr)_minmax(18rem,0.85fr)]">
            <section class="p-5 sm:p-7">
                <div class="flex flex-wrap items-start justify-between gap-4 border-b border-black/[0.06] pb-5">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-muted">Category</p>
                        <h2 class="mt-2 break-words text-2xl font-semibold text-ink">{{ $category->name }}</h2>
                        <p class="mt-1 break-all font-mono text-xs text-muted">/{{ $category->slug }}</p>
                    </div>
                    @if ($category->is_active)
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-mint-soft px-3 py-1.5 text-xs font-semibold text-mint-deep ring-1 ring-mint/20">
                            <span class="h-2 w-2 rounded-full bg-mint" aria-hidden="true"></span>
                            Active
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-ivory px-3 py-1.5 text-xs font-semibold text-muted ring-1 ring-black/10">
                            <span class="h-2 w-2 rounded-full bg-muted" aria-hidden="true"></span>
                            Inactive
                        </span>
                    @endif
                </div>

                <dl class="grid gap-x-8 sm:grid-cols-2">
                    <div class="border-b border-black/[0.05] py-4">
                        <dt class="text-xs font-medium text-muted">Parent category</dt>
                        <dd class="mt-1 text-sm font-medium text-ink">{{ $category->parent?->name ?? 'Top-level category' }}
                        </dd>
                    </div>
                    <div class="border-b border-black/[0.05] py-4">
                        <dt class="text-xs font-medium text-muted">Products</dt>
                        <dd class="mt-1 text-sm font-medium tabular-nums text-ink">
                            {{ $category->products_count ?? $category->products->count() }}</dd>
                    </div>
                    <div class="border-b border-black/[0.05] py-4">
                        <dt class="text-xs font-medium text-muted">Created</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $category->created_at->format('M j, Y') }}</dd>
                    </div>
                    <div class="border-b border-black/[0.05] py-4">
                        <dt class="text-xs font-medium text-muted">Last updated</dt>
                        <dd class="mt-1 text-sm text-ink">{{ $category->updated_at->format('M j, Y') }}</dd>
                    </div>
                    <div class="py-4 sm:col-span-2">
                        <dt class="text-xs font-medium text-muted">Description</dt>
                        <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-ink">
                            {{ filled($category->description) ? $category->description : 'No description provided.' }}</dd>
                    </div>
                </dl>
            </section>

            <aside class="border-t border-black/[0.06] bg-ivory/30 p-5 sm:p-7 lg:border-l lg:border-t-0">
                <h2 class="text-sm font-semibold text-ink">Category image</h2>
                <div class="mt-4 overflow-hidden rounded-lg border border-black/[0.06] bg-white">
                    @if ($category->image_path)
                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}"
                            class="aspect-[4/3] w-full object-cover" />
                    @else
                        <div class="grid aspect-[4/3] place-items-center text-muted">
                            <x-admin.icon name="image" class="h-9 w-9" />
                        </div>
                    @endif
                </div>
                @if ($category->image_path)
                    <p class="mt-2 truncate text-xs text-muted">{{ basename($category->image_path) }}</p>
                @else
                    <p class="mt-2 text-xs text-muted">No image uploaded.</p>
                @endif
            </aside>
        </div>
    </div>
@endsection
