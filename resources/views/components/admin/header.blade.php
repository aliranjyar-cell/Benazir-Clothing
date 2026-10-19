@php
    $user = auth()->user();

    $initials = null;
    $roleLabel = null;

    if ($user) {
        $initials = strtoupper(
            mb_substr($user->first_name ?? '', 0, 1).mb_substr($user->last_name ?? '', 0, 1)
        );

        $roleLabel = ucwords(str_replace('_', ' ', $user->role ?? 'Staff'));
    }

    $admin = auth()->guard('admin')->user();
    $unreadCount = $admin ? (int) $admin->notifications()->unread()->count() : 0;
    $recentNotifications = $admin ? $admin->notifications()->unread()->take(5)->get() : collect();
@endphp

<header class="sticky top-0 z-30 border-b border-black/5 bg-white">
    <div class="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
        {{-- Mobile sidebar toggle --}}
        <button
            type="button"
            class="-ms-2 rounded-xl p-2 text-muted transition-colors hover:bg-ivory hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac lg:hidden"
            @click="sidebarOpen = true"
            aria-controls="admin-sidebar"
            :aria-expanded="sidebarOpen.toString()"
            aria-expanded="false"
        >
            <span class="sr-only">Open navigation menu</span>
            <x-admin.icon name="bars-3" class="h-5 w-5" />
        </button>

        {{-- Breadcrumb --}}
        <div class="min-w-0 flex-1 overflow-hidden">
            @hasSection('breadcrumb')
                @yield('breadcrumb')
            @else
                <p class="truncate text-sm font-medium text-ink">@yield('title', 'Dashboard')</p>
            @endif
        </div>

        {{-- Notifications --}}
        @if ($admin)
            <x-dropdown align="right" width="w-80">
                <x-slot name="trigger">
                    <button
                        type="button"
                        class="relative flex items-center gap-3 rounded-xl p-1.5 transition-colors hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                        aria-haspopup="true"
                        aria-label="Notifications"
                    >
                        <span class="relative">
                            <x-admin.icon name="bell" class="h-5 w-5 text-muted" />
                            @if ($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 h-2 w-2 rounded-full bg-blush-deep ring-2 ring-white" aria-hidden="true"></span>
                            @endif
                        </span>
                        @if ($unreadCount > 0)
                            <span class="hidden text-xs font-medium text-ink md:inline">{{ $unreadCount }}</span>
                        @endif
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="border-b border-black/5 px-4 py-3">
                        <p class="truncate text-sm font-medium text-ink">Notifications</p>
                        <p class="truncate text-xs text-muted">
                            {{ $unreadCount }} unread{{ $unreadCount !== 1 ? 's' : '' }}
                        </p>
                    </div>

                    <div class="py-1">
                        @if ($recentNotifications->isNotEmpty())
                            @foreach ($recentNotifications as $notification)
                                <a
                                    href="{{ route('admin.settings.orders-notifications') }}"
                                    class="block px-4 py-2.5 text-start text-sm leading-5 text-ink transition duration-150 ease-in-out hover:bg-ivory focus:outline-none focus:bg-ivory"
                                >
                                    <span class="block text-sm font-medium">{{ __('New order') }}: {{ $notification->data['order_number'] ?? '—' }}</span>
                                    <span class="block text-xs text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @endforeach
                        @else
                            <div class="px-4 py-2 text-xs text-muted text-center">
                                No unread notifications.
                            </div>
                        @endif

                        <div class="border-t border-black/5 px-4 py-2.5">
                            <a
                                href="{{ route('admin.settings.orders-notifications') }}"
                                class="block px-4 py-2 text-start text-sm font-semibold text-primary transition duration-150 ease-in-out hover:bg-ivory focus:outline-none focus:bg-ivory"
                            >
                                View all notifications
                            </a>
                        </div>
                    </div>
                </x-slot>
            </x-dropdown>
        @endif

        {{-- User menu --}}
        @if ($user)
            <div class="flex items-center">
                <x-dropdown align="right" width="w-60">
                    <x-slot name="trigger">
                        <button
                            type="button"
                            class="flex items-center gap-3 rounded-xl p-1.5 pe-3 transition-colors hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                            aria-haspopup="true"
                        >
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-lilac-soft text-xs font-bold text-aubergine ring-1 ring-lilac/40">
                                {{ $initials !== '' ? $initials : '?' }}
                            </span>

                            <span class="hidden text-start leading-tight md:block">
                                <span class="block max-w-[10rem] truncate text-sm font-medium text-ink">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </span>
                                <span class="block text-xs text-muted">{{ $roleLabel }}</span>
                            </span>

                            <x-admin.icon name="chevron-down" class="h-4 w-4 shrink-0 text-muted" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-black/5 px-4 py-3">
                            <p class="truncate text-sm font-medium text-ink">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </p>
                            <p class="truncate text-xs text-muted">{{ $user->email }}</p>
                        </div>

                        <div class="py-1">
                            {{-- @if (Route::has('profile.edit'))
                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-ivory focus:bg-ivory focus:outline-none"
                                >
                                    Profile settings
                                </a>
                            @endif --}}

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button
                                    type="submit"
                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-blush-deep transition duration-150 ease-in-out hover:bg-blush-soft focus:bg-blush-soft focus:outline-none"
                                >
                                    Log out
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        @endif
    </div>
</header>
