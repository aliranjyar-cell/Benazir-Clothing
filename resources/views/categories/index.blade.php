@extends('layouts.admin.app')

@section('title', 'Categories')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('dashboard')],
        ['label' => 'Categories'],
    ]" />
@endsection

@section('content')
    <x-admin.page-heading
        title="Categories"
        description="Organise your Punjabi fashion catalogue into hierarchies of collections, with images, descriptions and active states."
    >
        <x-slot:actions>
            <a
                href="{{ route('categories.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-lilac px-4 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
            >
                <x-admin.icon name="plus" class="h-4 w-4" />
                Add Category
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    {{-- Search, filters, sort and pagination controls --}}
    <form
        action="{{ route('categories.index') }}"
        method="GET"
        class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-card sm:p-5"
    >
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_auto_auto_auto_auto] xl:grid-cols-[1fr_auto_auto_auto_auto_auto]">
            <div>
                <label for="search" class="sr-only">Search categories</label>
                <div class="relative">
                    <x-admin.icon name="magnifying-glass" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" />
                    <input
                        id="search"
                        name="search"
                        type="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, slug…"
                        class="block w-full rounded-xl border-black/10 pl-9 text-sm text-ink shadow-sm transition placeholder:text-muted/60 focus:border-lilac focus:ring-lilac"
                    />
                </div>
            </div>

            <div>
                <label for="filter-active" class="sr-only">Filter by status</label>
                <select
                    id="filter-active"
                    name="active_only"
                    class="block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-40"
                >
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('active_only') === '1')>Active only</option>
                    <option value="0" @selected(request('active_only') === '0')>Inactive only</option>
                </select>
            </div>

            <div>
                <label for="sort" class="sr-only">Sort by</label>
                <select
                    id="sort"
                    name="sort"
                    class="block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-40"
                >
                    <option value="name" @selected($sort === 'name')>Name</option>
                    <option value="created_at" @selected($sort === 'created_at')>Date added</option>
                </select>
            </div>

            <div>
                <label for="direction" class="sr-only">Direction</label>
                <select

    @if ($categories->total() > 0)
        <div class="mt-5 overflow-hidden rounded-2xl border border-black/[0.06] bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/5 text-sm">
                    <thead class="bg-ivory/40">
                        <tr>
                            <th scope="col" class="px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">
                                <a
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center gap-1 rounded transition-colors hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                >
                                    Category
                                    <x-admin.icon name="chevron-down" class="h-3 w-3 {{ $sort === 'name' && $direction === 'desc' ? 'rotate-180' : '' }} {{ $sort === 'name' ? '' : 'opacity-0' }}" />
                                </a>
                            </th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted md:table-cell">
                                Status
                            </th>
                            <th scope="col" class="px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">

                        @foreach ($categories as $category)
                            <tr class="transition-colors hover:bg-ivory/30">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($category->image_path)
                                            <img
                                                src="{{ asset('storage/'.$category->image_path) }}"
                                                alt=""
                                                class="h-11 w-11 shrink-0 rounded-xl object-cover ring-1 ring-black/5"
                                                loading="lazy"
                                            />
                                        @else
                                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                                                <x-admin.icon name="tag" class="h-5 w-5" />
                                            </span>
                                        @endif

                                        <div class="min-w-0">
                                            <p class="truncate rounded font-medium text-ink">{{ $category->name }}</p>
                                            @if ($parent = $category->parent)
                                                <p class="truncate text-xs text-muted">→ {{ $parent->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">

                                        <form
                                            method="POST"
                                            action="{{ route('categories.toggle-status', $category) }}"
                                            class="flex items-center gap-1"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-lg p-2 text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                                aria-label="{{ $category->is_active ? 'Deactivate ' . $category->name : 'Activate ' . $category->name }}"
                                            >
                                                <x-admin.icon name="{{ $category->is_active ? 'eye-off' : 'eye' }}" class="h-4 w-4" />
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('categories.destroy', $category) }}"
                                            class="flex items-center gap-1"
                                            onsubmit="return confirm('Are you sure you want to delete “{{ $category->name }}”?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg p-2 text-muted transition hover:bg-blush-soft hover:text-blush-deep focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                                aria-label="Delete {{ $category->name }}"
                                            >
                                                <x-admin.icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-black/5 px-5 py-4">
                {{ $categories->links() }}
            </div>
        </div>
    @else
        <div class="mt-5 rounded-2xl border border-black/[0.06] bg-white px-6 py-16 text-center shadow-card">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                <x-admin.icon name="tag" class="h-7 w-7" />
            </span>

            <h2 class="mt-5 text-base font-semibold tracking-tight text-ink">
                @if (request()->filled('search') || request('active_only'))
                    No categories match your filters
                @else
                    No categories yet
                @endif
            </h2>

            <p class="mx-auto mt-1.5 max-w-sm text-sm leading-relaxed text-muted">
                @if (request()->filled('search') || request('active_only'))
                    Try a different search term or clear the filters to see the full catalogue.
                @else
                    Start organising your catalogue by adding your first category.
                @endif
            </p>

            @if (! request()->filled('search') && ! request('active_only'))
                <a
                    href="{{ route('categories.create') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-lilac px-5 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                >
                    <x-admin.icon name="plus" class="h-4 w-4" />
                    Add your first category
                </a>
            @endif
        </div>
    @endif
@endsection

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
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm font-medium tabular-nums text-ink">{{ $category->products_count ?? $category->products->count() }}</span>
                                </td>

                                <td class="px-5 py-4 text-sm text-muted">
                                    @if ($category->parent)
                                        {{ $category->parent->name }}
                                    @else
                                        <span class="text-xs text-muted">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 hidden text-sm text-muted sm:table-cell">
                                    {{ $category->created_at->format('M j, Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('categories.show', $category) }}"
                                            class="rounded-lg p-2 text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                            aria-label="View {{ $category->name }}"
                                        >
                                            <x-admin.icon name="eye" class="h-4 w-4" />
                                        </a>

                                        <a
                                            href="{{ route('categories.edit', $category) }}"
                                            class="rounded-lg p-2 text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                            aria-label="Edit {{ $category->name }}"
                                        >
                                            <x-admin.icon name="pencil" class="h-4 w-4" />
                                        </a>

                                Products
                            </th>
                            <th scope="col" class="px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">
                                Parent
                            </th>
                            <th scope="col" class="hidden px-5 py-3.5 text-start text-[10px] font-semibold uppercase tracking-[0.14em] text-muted sm:table-cell">
                                Created
                            </th>
                            <th scope="col" class="px-5 py-3.5 text-end text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">

                    id="direction"
                    name="direction"
                    class="block w-full rounded-xl border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-32"
                >
                    <option value="asc" @selected($direction === 'asc')>Asc</option>
                    <option value="desc" @selected($direction === 'desc')>Desc</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-aubergine px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                >
                    <x-admin.icon name="arrows-sorted" class="h-4 w-4" />
                    Filter
                </button>

                @if (request()->filled('search') || request('active_only'))
                    <a
                        href="{{ route('categories.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                    >
                        <x-admin.icon name="x-mark" class="h-4 w-4" />
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
