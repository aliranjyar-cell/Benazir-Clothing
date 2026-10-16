@extends('storefront.layouts.store')

@section('title', 'Customer sign in')

@section('content')
    <div class="bg-background">
        <div class="mx-auto max-w-xl px-margin py-space-xl lg:px-margin-md">
            <a href="{{ route('store.shop') }}"
                class="font-label-uppercase text-label-uppercase tracking-widest text-secondary hover:text-primary">
                Continue shopping
            </a>

            <section
                class="mt-space-lg border border-outline-variant/70 bg-surface-container-lowest p-space-lg sm:p-space-xl">
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">BENAZIR customer account
                </p>
                <h1 class="mt-space-sm font-display-hero text-3xl text-primary">Welcome back</h1>
                <p class="mt-space-sm font-body-md text-body-md text-on-surface-variant">Sign in to view your details and
                    order history.</p>

                @if (session('status'))
                    <div class="mt-space-md border border-secondary/40 bg-secondary-container/30 px-space-md py-space-sm font-body-sm text-body-sm text-on-surface"
                        role="status">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mt-space-md border border-error/30 bg-error-container/20 px-space-md py-space-sm font-body-sm text-body-sm text-error"
                        role="alert">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('store.login') }}" class="mt-space-lg grid gap-space-md">
                    @csrf
                    <div>
                        <label for="email" class="font-body-sm text-body-sm text-on-surface-variant">Email
                            address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                            required autofocus
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    </div>
                    <div>
                        <div class="flex items-center justify-between gap-space-sm">
                            <label for="password" class="font-body-sm text-body-sm text-on-surface-variant">Password</label>
                            <a href="{{ route('password.request') }}"
                                class="font-body-sm text-body-sm text-primary underline underline-offset-4">Forgot
                                password?</a>
                        </div>
                        <input id="password" type="password" name="password" autocomplete="current-password" required
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                    </div>
                    <label for="remember"
                        class="flex items-center gap-space-sm font-body-sm text-body-sm text-on-surface-variant">
                        <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))
                            class="rounded border-outline-variant text-primary focus:ring-primary/30">
                        Remember me
                    </label>
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-primary px-space-lg py-space-md font-label-uppercase text-label-uppercase tracking-widest text-on-primary transition-colors hover:bg-primary/90">
                        Sign in
                    </button>
                </form>

                <p
                    class="mt-space-lg border-t border-outline-variant/50 pt-space-md font-body-sm text-body-sm text-on-surface-variant">
                    New to BENAZIR?
                    <a href="{{ route('register') }}" class="font-semibold text-primary underline underline-offset-4">Create
                        an account</a>
                </p>
            </section>
        </div>
    </div>
@endsection
