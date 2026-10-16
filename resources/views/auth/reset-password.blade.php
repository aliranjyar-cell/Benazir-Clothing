@extends('storefront.layouts.store')

@section('title', 'Choose a new password')

@section('content')
    <div class="bg-background">
        <div class="mx-auto max-w-xl px-margin py-space-xl lg:px-margin-md">
            <a href="{{ route('store.shop') }}"
                class="font-label-uppercase text-label-uppercase tracking-widest text-secondary hover:text-primary">Continue
                shopping</a>
            <section
                class="mt-space-lg border border-outline-variant/70 bg-surface-container-lowest p-space-lg sm:p-space-xl">
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">Customer account</p>
                <h1 class="mt-space-sm font-display-hero text-3xl text-primary">Choose a new password</h1>

                @if ($errors->any())
                    <div class="mt-space-md border border-error/30 bg-error-container/20 px-space-md py-space-sm font-body-sm text-body-sm text-error"
                        role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}" class="mt-space-lg grid gap-space-md">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <div>
                        <label for="email" class="font-body-sm text-body-sm text-on-surface-variant">Email
                            address</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                            autocomplete="email" required autofocus
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    </div>
                    <div>
                        <label for="password" class="font-body-sm text-body-sm text-on-surface-variant">New password</label>
                        <input id="password" type="password" name="password" autocomplete="new-password" required
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    </div>
                    <div>
                        <label for="password_confirmation" class="font-body-sm text-body-sm text-on-surface-variant">Confirm
                            new password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            autocomplete="new-password" required
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    </div>
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-space-lg py-space-md font-label-uppercase text-label-uppercase tracking-widest text-on-primary transition-colors hover:bg-primary/90">Save
                        new password</button>
                </form>
            </section>
        </div>
    </div>
@endsection
