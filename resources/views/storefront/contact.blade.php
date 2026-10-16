@extends('storefront.layouts.store')

@section('title', 'Contact')

@section('content')
    <section class="relative overflow-hidden bg-background">
        <div class="max-w-7xl mx-auto px-margin lg:px-margin-md py-space-2xl lg:py-space-3xl">
            <div class="max-w-2xl">
                <p class="font-label-uppercase text-label-uppercase tracking-widest text-primary">ATELIER</p>
                <h1 class="font-display-hero font-headline-lg text-headline-lg text-primary mt-space-lg leading-[1.15] tracking-tight">
                    Contact
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-md leading-relaxed">
                    A thoughtfully woven atelier with ateliers in Lahore, London and New York.
                    Tell us what you need and we will be in touch within one business day.
                </p>
            </div>

            <div class="mt-space-2xl max-w-xl">
                <form action="#" method="POST">
                    <div class="space-y-space-md">
                        <div class="grid gap-space-sm sm:grid-cols-2">
                            <div class="space-y-space-xs">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">First name</label>
                                <input class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" name="name" placeholder="Name" />
                            </div>
                            <div class="space-y-space-xs">
                                <label class="font-body-sm text-body-sm text-on-surface-variant">Last name</label>
                                <input class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" name="name" placeholder="Surname" />
                            </div>
                        </div>

                        <div class="space-y-space-xs">
                            <label class="font-body-sm text-body-sm text-on-surface-variant">Email address</label>
                            <input type="email" class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" name="email" placeholder="you@example.com" />
                        </div>

                        <div class="space-y-space-xs">
                            <label class="font-body-sm text-body-sm text-on-surface-variant">Subject</label>
                            <input class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" name="subject" placeholder="How can we help?" />
                        </div>

                        <div class="space-y-space-xs">
                            <label class="font-body-sm text-body-sm text-on-surface-variant">Message</label>
                            <textarea rows="5" class="w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-3 py-2 text-on-surface font-body-sm outline-none ring-1 ring-secondary/30 focus:ring-primary-container transition-all" name="message" placeholder="Write your message..."></textarea>
                        </div>

                        <button class="inline-flex items-center gap-space-sm rounded-lg bg-primary px-5 py-2.5 text-label-uppercase text-on-primary font-label-uppercase tracking-widest hover:bg-primary-container transition-colors" type="submit">
                            Send message
                            <span class="material-symbols-outlined text-sm">send</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-space-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-space-md border-t border-outline-variant/60 pt-space-lg">
                <div class="flex items-center gap-space-md">
                    <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                    <div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Head Studio</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">12-B, The Mall, Lahore, Pakistan</p>
                    </div>
                </div>
                <div class="flex items-center gap-space-md">
                    <span class="material-symbols-outlined text-primary text-xl">call</span>
                    <div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">+92 300 0000000</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">+44 20 7946 0958</p>
                    </div>
                </div>
                <div class="flex items-center gap-space-md">
                    <span class="material-symbols-outlined text-primary text-xl">mail</span>
                    <div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">atelier@benazir.example</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">care@benazir.example</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
