@extends('layouts.admin.app')

@section('title', 'Orders & Notifications')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">Orders &amp; Notifications</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    Orders &amp; Notifications
                </h1>
                <p class="mt-1 text-sm text-muted">
                    New-order alerts for the admin panel. Mark a notification as read once you've
                    actioned the order.
                </p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                All sections
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav active="orders-notifications" />

        {{-- Unread count summary --}}
        @php
            $unreadCount = $notifications->filter(fn ($n) => is_null($n->read_at))->count();
        @endphp
        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 rounded-2xl border border-primary/15 bg-primary/[0.04] px-5 py-3.5 text-sm text-ink">
            <span class="inline-flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-mint-deep" aria-hidden="true"></span>
                <strong>{{ $notifications->total() }}</strong>
                total notifications
            </span>
            <span class="inline-flex items-center gap-2
                @if ($unreadCount > 0) text-mint-deep @else text-muted @endif">
                <span class="h-2 w-2 rounded-full
                    @if ($unreadCount > 0) bg-mint-deep @else bg-secondary-fixed-dim @endif" aria-hidden="true"></span>
                <strong>{{ $unreadCount }}</strong>
                unread{{ $unreadCount !== 1 ? 's' : '' }}
            </span>
        </div>

        {{-- Notifications list --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @if ($notifications->isEmpty())
                <div class="border-b border-gray-100 bg-[#FCFAF8]/60 px-6 py-5">
                    <h2 class="text-base font-semibold text-ink">Notifications</h2>
                    <p class="mt-1 text-sm text-muted">New customer orders appear here as they are placed.</p>
                </div>
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-muted">No notifications yet.</p>
                    <p class="mt-1 text-xs text-muted">Place a test order from the storefront to see a new-order alert appear here.</p>
                </div>
            @else
                <div class="border-b border-gray-100 bg-[#FCFAF8]/60 px-6 py-5">
                    <h2 class="text-base font-semibold text-ink">Notifications</h2>
                    <p class="mt-1 text-sm text-muted">Most recent first. Unread notifications have a mint dot.</p>
                </div>

                <ul class="divide-y divide-gray-100">
                    @foreach ($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                        @endphp
                        <li class="px-6 py-4 @if ($isUnread) bg-lilac/5 @else bg-white @endif transition-colors duration-150 hover:bg-gray-50/60">
                            <div class="flex items-start gap-3">
                                @if ($isUnread)
                                    <span class="mt-1 h-2 w-2 rounded-full bg-mint-deep" aria-hidden="true"></span>
                                @else
                                    <span class="mt-1 h-2 w-2 rounded-full bg-gray-200" aria-hidden="true"></span>
                                @endif

                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-ink leading-tight">
                                        New order {{ $data['order_number'] ?? '—' }}
                                    </p>
                                    <p class="mt-0.5 text-sm text-muted leading-tight">
                                        {{ $data['customer_name'] ?? '—' }}
                                        <span class="mx-1.5 text-gray-400">·</span>
                                        {{ $data['customer_email'] ?? '—' }}
                                        <span class="mx-1.5 text-gray-400">·</span>
                                        {{ $data['currency'] ?? '' }} {{ number_format((float) ($data['grand_total'] ?? 0), 2) }}
                                    </p>
                                    <p class="mt-1 text-xs text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if ($isUnread)
                                            <span class="mx-1">·</span>
                                            <span class="font-semibold text-mint-deep">Unread</span>
                                        @endif
                                    </p>
                                </div>

                                @if ($isUnread)
                                    <form
                                        action="{{ route('admin.settings.orders-notifications.read', $notification) }}"
                                        method="POST"
                                        class="mt-1 sm:mt-0"
                                        onsubmit="return confirm('Mark this notification as read?')">
                                        @csrf
                                        @method('POST')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-ink transition hover:bg-ivory">
                                            Mark read
                                            <x-admin.icon name="check" class="h-3.5 w-3.5" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Pagination --}}
                <div class="border-t border-gray-100 bg-[#FCFAF8]/60 px-6 py-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
