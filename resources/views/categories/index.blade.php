@extends('layouts.admin.app')

@section('title', 'Categories')

@section('breadcrumb')
    <x-admin.breadcrumb :items="[['label' => 'Admin', 'url' => route('dashboard')], ['label' => 'Categories']]" />
@endsection

@section('content')
    <x-admin.page-heading title="Categories"
        description="Manage catalogue groups, their parent relationships and storefront visibility.">
        <x-slot:actions>
            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-aubergine px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                <x-admin.icon name="plus" class="h-4 w-4" />
                Add Category
            </a>
        </x-slot:actions>
    </x-admin.page-heading>

    <form action="{{ route('categories.index') }}" method="GET"
        class="mt-5 rounded-xl border border-black/[0.07] bg-white p-4 shadow-card sm:p-5">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(14rem,1fr)_auto_auto_auto_auto]">
            <div>
                <label for="search" class="mb-1.5 block text-xs font-medium text-muted">Search</label>
                <div class="relative">
                    <x-admin.icon name="magnifying-glass"
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" />
                    <input id="search" name="search" type="search" value="{{ request('search') }}"
                        placeholder="Name or slug"
                        class="block w-full rounded-lg border-black/10 pl-9 text-sm text-ink shadow-sm transition placeholder:text-muted/60 focus:border-lilac focus:ring-lilac" />
                </div>
            </div>

            <div>
                <label for="filter-active" class="mb-1.5 block text-xs font-medium text-muted">Status</label>
                <select id="filter-active" name="active_only"
                    class="block w-full rounded-lg border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-40">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('active_only') === '1')>Active only</option>
                    <option value="0" @selected(request('active_only') === '0')>Inactive only</option>
                </select>
            </div>

            <div>
                <label for="sort" class="mb-1.5 block text-xs font-medium text-muted">Sort by</label>
                <select id="sort" name="sort"
                    class="block w-full rounded-lg border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-40">
                    <option value="name" @selected($sort === 'name')>Name</option>
                    <option value="created_at" @selected($sort === 'created_at')>Date added</option>
                </select>
            </div>

            <div>
                <label for="direction" class="mb-1.5 block text-xs font-medium text-muted">Order</label>
                <select id="direction" name="direction"
                    class="block w-full rounded-lg border-black/10 text-sm text-ink shadow-sm transition focus:border-lilac focus:ring-lilac lg:w-32">
                    <option value="asc" @selected($direction === 'asc')>Asc</option>
                    <option value="desc" @selected($direction === 'desc')>Desc</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-aubergine px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-aubergine-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                    <x-admin.icon name="arrows-sorted" class="h-4 w-4" />
                    Filter
                </button>

                @if (request()->filled('search') ||
                        request()->filled('active_only') ||
                        request()->filled('sort') ||
                        request()->filled('direction'))
                    <a href="{{ route('categories.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-black/10 bg-white px-4 py-2.5 text-sm font-medium text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac">
                        <x-admin.icon name="x-mark" class="h-4 w-4" />
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($categories->total() > 0)
        <section class="mt-4 overflow-hidden rounded-xl border border-black/[0.07] bg-white shadow-card"
            aria-label="Category list">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-black/[0.06] px-4 py-3.5 sm:px-5">
                <p class="text-sm font-semibold text-ink">All categories</p>
                <p class="text-xs tabular-nums text-muted">Showing
                    {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ $categories->total() }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/5 text-sm">
                    <thead class="bg-ivory/40">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.12em] text-muted sm:px-5">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                                    class="inline-flex items-center gap-1 rounded transition-colors hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac">
                                    Category
                                    <x-admin.icon name="chevron-down"
                                        class="h-3 w-3 {{ $sort === 'name' && $direction === 'desc' ? 'rotate-180' : '' }} {{ $sort === 'name' ? '' : 'opacity-0' }}" />
                                </a>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.12em] text-muted sm:px-5">
                                Status
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.12em] text-muted sm:px-5">
                                Products
                            </th>
                            <th scope="col"
                                class="hidden px-4 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.12em] text-muted sm:table-cell sm:px-5">
                                Parent
                            </th>
                            <th scope="col"
                                class="hidden px-4 py-3 text-start text-[10px] font-semibold uppercase tracking-[0.12em] text-muted lg:table-cell sm:px-5">
                                Created
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-end text-[10px] font-semibold uppercase tracking-[0.12em] text-muted sm:px-5">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">

                        @foreach ($categories as $category)
                            <tr class="transition-colors hover:bg-ivory/30">
                                <td class="px-4 py-4 sm:px-5">
                                    <div class="flex items-center gap-3">
                                        @if ($category->image_path)
                                            <img src="{{ asset('storage/' . $category->image_path) }}" alt=""
                                                class="h-11 w-11 shrink-0 rounded-lg object-cover ring-1 ring-black/5"
                                                loading="lazy" />
                                        @else
                                            <span
                                                class="grid h-11 w-11 shrink-0 place-items-center rounded-lg bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                                                <x-admin.icon name="tag" class="h-5 w-5" />
                                            </span>
                                        @endif

                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-ink">{{ $category->name }}</p>
                                            <p class="mt-0.5 truncate font-mono text-[11px] text-muted">
                                                {{ $category->slug }}</p>
                                            @if ($parent = $category->parent)
                                                <p class="truncate text-xs text-muted">→ {{ $parent->name }}</p>
                                            @endif
                                            <p class="mt-1 md:hidden">
                                                @if ($category->is_active)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-[11px] font-medium text-mint-deep"><span
                                                            class="h-1.5 w-1.5 rounded-full bg-mint"
                                                            aria-hidden="true"></span>Active</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-[11px] font-medium text-muted"><span
                                                            class="h-1.5 w-1.5 rounded-full bg-muted"
                                                            aria-hidden="true"></span>Inactive</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="hidden px-4 py-4 md:table-cell sm:px-5">
                                    @if ($category->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-mint-soft px-2.5 py-0.5 text-[11px] font-semibold text-mint-deep ring-1 ring-mint/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-mint" aria-hidden="true"></span>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-ivory px-2.5 py-0.5 text-[11px] font-semibold text-muted ring-1 ring-black/10">
                                            <span class="h-1.5 w-1.5 rounded-full bg-muted" aria-hidden="true"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 sm:px-5">
                                    <span
                                        class="text-sm font-medium tabular-nums text-ink">{{ $category->products_count ?? $category->products->count() }}</span>
                                </td>

                                <td class="hidden px-4 py-4 text-sm text-muted sm:table-cell sm:px-5">
                                    @if ($category->parent)
                                        {{ $category->parent->name }}
                                    @else
                                        <span class="text-xs text-muted">—</span>
                                    @endif
                                </td>

                                <td class="hidden px-4 py-4 text-sm text-muted lg:table-cell sm:px-5">
                                    {{ $category->created_at->format('M j, Y') }}
                                </td>

                                <td class="px-4 py-4 sm:px-5">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- View Category --}}
                                        <a href="{{ route('categories.show', $category) }}"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                            aria-label="View {{ $category->name }}" title="View category">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>

                                        {{-- Edit Category --}}
                                        <a href="{{ route('categories.edit', $category) }}"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                            aria-label="Edit {{ $category->name }}" title="Edit category">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>

                                        {{-- Toggle Category Status --}}
                                        <form method="POST" action="{{ route('categories.toggle-status', $category) }}"
                                            class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg p-2 text-muted transition hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                                aria-label="{{ $category->is_active ? 'Deactivate ' . $category->name : 'Activate ' . $category->name }}"
                                                title="{{ $category->is_active ? 'Deactivate category' : 'Activate category' }}">
                                                @if ($category->is_active)
                                                    <i class="fa-solid fa-eye-slash text-sm"></i>
                                                @else
                                                    <i class="fa-solid fa-eye text-sm"></i>
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Delete Category --}}
                                        <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                            class="inline-flex items-center"
                                            onsubmit="return confirm('Are you sure you want to delete this category?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-lg p-2 text-muted transition hover:bg-blush-soft hover:text-blush-deep focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                                                aria-label="Delete {{ $category->name }}" title="Delete category">
                                                <i class="fa-solid fa-trash text-sm"></i>
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
        </section>
    @else
        <div class="mt-5 rounded-2xl border border-black/[0.06] bg-white px-6 py-16 text-center shadow-card">
            <span
                class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
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

            @if (!request()->filled('search') && !request('active_only'))
                <a href="{{ route('categories.create') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl bg-lilac px-5 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2">
                    <x-admin.icon name="plus" class="h-5 w-5" />
                    Add your first category
                </a>
            @endif
        </div>
    @endif
@endsection
