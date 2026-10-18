@props(['title', 'description' => null])

{{-- Titled block inside a white settings card (matches the original General Store form). --}}
<section {{ $attributes->merge(['class' => '']) }}>
    <div class="border-b border-gray-100 bg-[#FCFAF8]/60 px-6 py-5">
        <h2 class="text-base font-semibold text-ink">{{ $title }}</h2>

        @if (filled($description))
            <p class="mt-1 text-sm text-muted">{{ $description }}</p>
        @endif
    </div>

    <div class="space-y-6 p-6">
        {{ $slot }}
    </div>
</section>