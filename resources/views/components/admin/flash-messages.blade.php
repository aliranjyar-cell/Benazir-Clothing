@php
    /**
     * Renders session flash messages ("success", "error" and Breeze's "status")
     * accessibly: success/status use role="status", errors use role="alert".
     */
    $flashes = collect([
        ['message' => session('success'), 'tone' => 'mint'],
        ['message' => session('error'), 'tone' => 'blush'],
        ['message' => session('status'), 'tone' => 'lilac'],
    ])->filter(fn ($flash) => filled($flash['message']));
@endphp

@if ($flashes->isNotEmpty())
    <div class="mb-6 space-y-3">
        @foreach ($flashes as $flash)
            @php
                $toneClasses = match ($flash['tone']) {
                    'mint' => 'border-mint/50 bg-mint-soft text-mint-deep',
                    'blush' => 'border-blush/50 bg-blush-soft text-blush-deep',
                    default => 'border-lilac/50 bg-lilac-soft text-aubergine',
                };

                $icon = match ($flash['tone']) {
                    'mint' => 'check',
                    'blush' => 'alert',
                    default => 'sparkle',
                };
            @endphp

            <div
                x-data="{ visible: true }"
                x-show="visible"
                x-transition.opacity.duration.150ms
                role="{{ $flash['tone'] === 'blush' ? 'alert' : 'status' }}"
                tabindex="-1"
                class="flex items-start gap-3 rounded-2xl border px-4 py-3.5 shadow-sm focus:outline-none {{ $toneClasses }}"
            >
                <x-admin.icon name="{{ $icon }}" class="mt-0.5 h-5 w-5 shrink-0" />

                <p class="min-w-0 flex-1 text-sm font-medium leading-relaxed">
                    {{ $flash['message'] }}
                </p>

                <button
                    type="button"
                    @click="visible = false"
                    class="-me-1 shrink-0 rounded-lg p-1 transition-opacity hover:opacity-60 focus:outline-none focus-visible:ring-2 focus-visible:ring-current"
                    aria-label="Dismiss notification"
                >
                    <x-admin.icon name="x-mark" class="h-4 w-4" />
                </button>
            </div>
        @endforeach
    </div>
@endif