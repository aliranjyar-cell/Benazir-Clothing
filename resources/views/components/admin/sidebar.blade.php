@php
    /*
     | Admin navigation.
     | - Items with a "route" key are live links (only when the named route exists).
     | - Items without one render as non-clickable "planned" entries so no broken
     |   links are generated before those modules are implemented.
     | - An optional "permission" key hides an item unless the authenticated user
     |   can perform that ability. spatie/laravel-permission is installed; checks
     |   activate as soon as roles/permissions are assigned to admins.
     */
    $navGroups = [
        [
            'label' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'home', 'route' => 'dashboard', 'matches' => 'dashboard'],
            ],
        ],
        [
            'label' => 'Catalogue',
            'items' => [
                ['label' => 'Products', 'icon' => 'cube', 'route' => 'products.index', 'matches' => 'products.*'],
                ['label' => 'Categories', 'icon' => 'tag', 'route' => 'categories.index', 'matches' => 'categories.*'],
                ['label' => 'Inventory', 'icon' => 'archive'],
            ],
        ],
        [
            'label' => 'Sales',
            'items' => [
                ['label' => 'Orders', 'icon' => 'clipboard'],
                ['label' => 'Customers', 'icon' => 'users'],
            ],
        ],
        [
            'label' => 'Administration',
            'items' => [
                ['label' => 'Staff &amp; Permissions', 'icon' => 'shield'],
                ['label' => 'Settings', 'icon' => 'cog'],
            ],
        ],
    ];
@endphp

{{-- Mobile backdrop --}}
<div
    x-show="sidebarOpen"
    x-transition.opacity.duration.150ms
    class="fixed inset-0 z-40 bg-aubergine/60 lg:hidden"
    aria-hidden="true"
    @click="sidebarOpen = false"
    style="display: none;"
></div>

<aside
    id="admin-sidebar"
    class="fixed inset-y-0 start-0 z-50 flex w-64 -translate-x-full flex-col bg-aubergine transition-transform duration-200 ease-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : ''"
>
    {{-- Brand --}}
    <div class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-white/10 px-5">
        <a
            href="{{ route('dashboard') }}"
            class="group flex items-center gap-3 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
        >
            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-lilac text-sm font-bold text-aubergine transition-colors group-hover:bg-white">
                A
            </span>
            <span class="min-w-0">
                <span class="block text-[15px] font-semibold leading-tight tracking-[0.24em] text-white">
                    AURELLE
                </span>
                <span class="mt-0.5 block text-[9px] font-medium uppercase leading-tight tracking-[0.22em] text-lilac/80">
                    Fashion Operations
                </span>
            </span>
        </a>

        <button
            type="button"
            class="rounded-lg p-1.5 text-white/50 transition-colors hover:bg-white/10 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac lg:hidden"
            @click="sidebarOpen = false"
            aria-label="Close navigation menu"
        >
            <x-admin.icon name="x-mark" class="h-5 w-5" />
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-2" aria-label="Admin navigation">
        @foreach ($navGroups as $group)
            <div class="px-3 py-3">
                <p class="px-3 pb-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-white/35">
                    {{ $group['label'] }}
                </p>

                <ul class="space-y-1">
                    @foreach ($group['items'] as $item)
                        @if (blank($item['permission'] ?? null) || auth()->user()?->can($item['permission']))
                            <li>
                                @if (! empty($item['route']) && Route::has($item['route']))
                                    @php
                                        $active = request()->routeIs($item['matches'] ?? $item['route']);
                                    @endphp

                                    <a
                                        href="{{ route($item['route']) }}"
                                        @click="sidebarOpen = false"
                                        @if ($active) aria-current="page" @endif
                                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2 focus-visible:ring-offset-aubergine {{ $active ? 'bg-lilac text-aubergine shadow-sm' : 'text-white/70 hover:bg-white/[0.07] hover:text-white' }}"
                                    >
                                        <x-admin.icon name="{{ $item['icon'] }}" class="h-5 w-5 shrink-0 {{ $active ? 'text-aubergine' : 'text-white/50 transition-colors group-hover:text-lilac' }}" />
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @else
                                    <span
                                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white/35"
                                        aria-disabled="true"
                                        title="Planned — this module has not been built yet"
                                    >
                                        <x-admin.icon name="{{ $item['icon'] }}" class="h-5 w-5 shrink-0 text-white/25" />
                                        <span>{{ $item['label'] }}</span>
                                        <span class="ms-auto rounded-full bg-white/[0.06] px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-white/40">
                                            Soon
                                        </span>
                                    </span>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    {{-- Sidebar footer --}}
    <div class="shrink-0 border-t border-white/10 px-5 py-4">
        <p class="flex items-center gap-2 text-[11px] text-white/40">
            <span class="h-1.5 w-1.5 rounded-full bg-mint" aria-hidden="true"></span>
            Admin portal · layout preview
        </p>
    </div>
</aside>

