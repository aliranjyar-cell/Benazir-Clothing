@props(['active' => null])

@php
    /**
     * Horizontal tab bar for the eight Settings sections. Every tab is a
     * real link; "soon" tabs carry a small gold dot instead of a badge.
     */
    $sections = \App\Http\Controllers\Admin\SettingController::SECTIONS;
@endphp

<nav class="mb-6 overflow-x-auto" aria-label="Settings sections">
    <ul class="flex min-w-max items-center gap-1 rounded-2xl border border-gray-200 bg-white p-1.5 shadow-sm">
        @foreach ($sections as $section)
            @php($isActive = $section['key'] === $active)
            <li>
                <a href="{{ $section['status'] === 'ready'
                    ? route($section['route'])
                    : route('admin.settings.section', $section['key']) }}"
                    @if ($isActive) aria-current="page" @endif
                    class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-medium transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-1 {{ $isActive
                        ? 'bg-primary text-white shadow-sm'
                        : 'text-muted hover:bg-ivory hover:text-ink' }}">
                    <x-admin.icon name="{{ $section['icon'] }}" class="h-4 w-4 shrink-0" />
                    <span>{{ $section['title'] }}</span>
                    @if ($section['status'] !== 'ready')
                        <span class="h-1.5 w-1.5 rounded-full bg-secondary-fixed-dim" aria-hidden="true"
                            title="Coming soon"></span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</nav>