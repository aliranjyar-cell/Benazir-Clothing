@props(['title', 'description' => null])

<div {{ $attributes->class(['mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-start sm:justify-between']) }}>
    <div class="min-w-0">
        <h1 class="text-xl font-semibold tracking-tight text-ink sm:text-2xl">
            {{ $title }}
        </h1>

        @if (filled($description))
            <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-muted">
                {{ $description }}
            </p>
        @endif
    </div>

    @isset($actions)
        <div class="flex shrink-0 items-center gap-3">
            {{ $actions }}
        </div>
    @endisset
</div>