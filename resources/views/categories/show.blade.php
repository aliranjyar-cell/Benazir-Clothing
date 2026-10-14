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
    <x-admin.page-heading
        title="{{ $category->name }}"
        description="View the details of “{{ $category->name }}”."
    >
        <x-slot:actions>
            <a
                href="{{ route('categories.edit', $category) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-lilac px-4 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                <x-admin.icon name="pencil" class="h-4 w-4" />
                Edit
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <div class="mt-5 grid gap-5 rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card sm:p-7 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold tracking-tight text-ink">Overview</h2>

            <dl class="mt-4 space-y-4 text-sm">
                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Name</dt>
                    <dd class="flex-1 truncate rounded-lg bg-ivory/60 px-3 py-2 font-medium text-ink">{{ $category->name }}</dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Slug</dt>
                    <dd class="flex-1 truncate rounded-lg bg-ivory/60 px-3 py-2 font-mono text-xs text-ink">{{ $category->slug }}</dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Parent</dt>
                    <dd class="flex-1 truncate rounded-lg bg-ivory/60 px-3 py-2 text-ink">
                        @if ($category->parent)
                            {{ $category->parent->name }}
                        @else
                            <span class="text-muted">None</span>
                        @endif
                    </dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Description</dt>
                    <dd class="flex-1 max-w-xl rounded-lg bg-ivory/60 px-3 py-2 leading-relaxed text-muted">
                        {{ $category->description ?? '—' }}
                    </dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Status</dt>
                    <dd class="flex-1">
                        @if ($category->is_active)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-mint-soft px-2.5 py-0.5 text-[11px] font-semibold text-mint-deep ring-1 ring-mint/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-mint" aria-hidden="true"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-ivory px-2.5 py-0.5 text-[11px] font-semibold text-muted ring-1 ring-black/10">
                                <span class="h-1.5 w-1.5 rounded-full bg-muted" aria-hidden="true"></span>
                                Inactive
                            </span>
                        @endif
                    </dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Products</dt>
                    <dd class="flex-1 text-sm font-medium text-ink">{{ $category->products_count ?? $category->products->count() }}</dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Created</dt>
                    <dd class="flex-1 text-sm text-ink">{{ $category->created_at->format('F j, Y \a\t g:i a') }}</dd>
                </div>

                <div class="flex items-start gap-4">
                    <dt class="min-w-0 flex-1 text-muted">Updated</dt>
                    <dd class="flex-1 text-sm text-ink">{{ $category->updated_at->format('F j, Y \a\t g:i a') }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <h2 class="text-sm font-semibold tracking-tight text-ink">Image</h2>

            <div class="mt-4 flex flex-col items-center rounded-xl bg-ivory/60 p-6">
                @if ($category->image_path)
                    <img
                        src="{{ asset('storage/'.$category->image_path) }}"
                        alt="{{ $category->name }}"
                        class="h-40 w-full max-w-sm rounded-xl object-cover ring-1 ring-black/5"
                    />
                    <p class="mt-2 text-xs text-muted">{{ basename($category->image_path) }}</p>
                @else
                    <span class="grid h-40 w-full max-w-sm place-items-center rounded-xl border border-black/5 bg-white text-muted">
                        <x-admin.icon name="image" class="h-8 w-8" />
                    </span>
                    <p class="mt-2 text-sm text-muted">No image uploaded yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
