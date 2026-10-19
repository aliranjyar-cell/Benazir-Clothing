@extends('layouts.admin.app')

@section('title', 'Account & Security')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm">
                    <a href="{{ route('admin.settings.index') }}"
                        class="font-medium text-secondary transition hover:underline">Settings</a>
                    <span class="mx-1.5 text-muted">/</span>
                    <span class="font-medium text-muted">Account &amp; Security</span>
                </p>
                <h1 class="mt-1 text-2xl font-semibold text-ink">
                    Account &amp; Security
                </h1>
                <p class="mt-1 text-sm text-muted">
                    Change your admin password and review your signed-in account.
                </p>
            </div>

            <a href="{{ route('admin.settings.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory">
                <i class="fa-solid fa-arrow-left"></i>
                All sections
            </a>
        </div>

        {{-- Section tabs --}}
        <x-admin.settings.section-nav active="account-security" />

        {{-- Your account (read-only) --}}
        <x-admin.settings.panel title="Your account"
            description="Details of the administrator currently signed in.">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-[#FCFAF8]/60 p-4">
                    <p class="text-xs text-muted uppercase tracking-wider">Name</p>
                    <p class="text-sm font-semibold text-ink">
                        {{ $admin->first_name }} {{ $admin->last_name }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-[#FCFAF8]/60 p-4">
                    <p class="text-xs text-muted uppercase tracking-wider">Username</p>
                    <p class="text-sm font-semibold text-ink">{{ $admin->username }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-[#FCFAF8]/60 p-4">
                    <p class="text-xs text-muted uppercase tracking-wider">Email</p>
                    <p class="text-sm font-semibold text-ink">{{ $admin->email }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-[#FCFAF8]/60 p-4">
                    <p class="text-xs text-muted uppercase tracking-wider">Role</p>
                    <p class="text-sm font-semibold text-ink">{{ ucfirst($admin->role) }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-[#FCFAF8]/60 p-4">
                    <p class="text-xs text-muted uppercase tracking-wider">Status</p>
                    <p class="text-sm font-semibold text-ink">{{ ucfirst($admin->status) }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-[#FCFAF8]/60 p-4">
                    <p class="text-xs text-muted uppercase tracking-wider">Last login</p>
                    <p class="text-sm font-semibold text-ink">
                        {{ $admin->last_login_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>
            </div>
        </x-admin.settings.panel>

        {{-- Change password --}}
        <form action="{{ route('admin.settings.account-security.password') }}" method="POST"
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            @csrf

            <x-admin.settings.panel title="Change password"
                description="The new password must be at least 8 characters and differ from your current one.">
                <x-admin.settings.input label="Current password" name="current_password" type="password"
                    :required="true" autocomplete="current-password"
                    hint="Enter your current password to confirm it is you making the change." />

                <x-admin.settings.input label="New password" name="password" type="password"
                    :required="true" autocomplete="new-password"
                    hint="At least 8 characters." />

                <x-admin.settings.input label="Confirm new password" name="password_confirmation" type="password"
                    :required="true" autocomplete="new-password"
                    hint="Type the new password again to confirm it." />
            </x-admin.settings.panel>

            {{-- Footer actions --}}
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-[#FCFAF8]/60 px-6 py-4">
                <a href="{{ route('admin.settings.account-security') }}"
                    class="rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-medium text-ink transition hover:bg-ivory">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-xl bg-aubergine px-6 py-3 text-sm font-semibold text-white transition hover:bg-aubergine/90">
                    Change password
                </button>
            </div>
        </form>
    </div>
@endsection