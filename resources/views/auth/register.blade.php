@extends('storefront.layouts.store')

@section('title', 'Create your account')

@section('content')
    <div class="bg-background">
        <div class="mx-auto max-w-2xl px-margin py-space-xl lg:px-margin-md">
            <a href="{{ route('store.shop') }}"
                class="font-label-uppercase text-label-uppercase tracking-widest text-secondary hover:text-primary">
                Continue shopping
            </a>

            <section
                class="mt-space-lg border border-outline-variant/70 bg-surface-container-lowest p-space-lg sm:p-space-xl">
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-secondary">{{ strtoupper(config('storefront.store_name', 'BENAZIR')) }} customer account
                </p>
                <h1 class="mt-space-sm font-display-hero text-3xl text-primary">Create your account</h1>
                <p class="mt-space-sm font-body-md text-body-md text-on-surface-variant">Save your details and keep your
                    order history close.</p>

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

                <form method="POST" action="{{ route('register') }}" class="mt-space-lg grid gap-space-md">
                    @csrf
                    <div>
                        <label for="name" class="font-body-sm text-body-sm text-on-surface-variant">Full name</label>
                        <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required
                            autofocus
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                        @error('name')
                            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="font-body-sm text-body-sm text-on-surface-variant">Email
                            address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                            required
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                        @error('email')
                            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="font-body-sm text-body-sm text-on-surface-variant">Phone <span
                                class="text-on-surface-variant/70">(optional)</span></label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                            class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                        @error('phone')
                            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-space-md sm:grid-cols-2">
                        <div>
                            <label for="password" class="font-body-sm text-body-sm text-on-surface-variant">Password</label>
                            <input id="password" type="password" name="password" autocomplete="new-password" required
                                class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                            @error('password')
                                <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="font-body-sm text-body-sm text-on-surface-variant">Confirm password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                autocomplete="new-password" required
                                class="mt-1 w-full rounded-lg border border-outline-variant bg-surface px-3 py-2.5 font-body-sm text-on-surface focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/15">
                        </div>
                    </div>
                    <button type="submit"
                        class="mt-space-sm inline-flex w-full items-center justify-center rounded-lg bg-primary px-space-lg py-space-md font-label-uppercase text-label-uppercase tracking-widest text-on-primary transition-colors hover:bg-primary/90">
                        Create account
                    </button>
                </form>

                <p
                    class="mt-space-lg border-t border-outline-variant/50 pt-space-md font-body-sm text-body-sm text-on-surface-variant">
                    Already have an account?
                    <a href="{{ route('store.login') }}"
                        class="font-semibold text-primary underline underline-offset-4">Sign in</a>
                </p>
            </section>
        </div>
    </div>
@endsection
