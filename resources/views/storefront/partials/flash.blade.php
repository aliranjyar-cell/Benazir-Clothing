@if (session('success'))
    <div class="w-full max-w-7xl mx-auto px-margin lg:px-margin-md mt-space-xl">
        <div role="alert" class="bg-secondary-container/80 text-on-secondary-container border border-secondary/30 rounded-lg px-space-lg py-space-md font-body-sm text-body-sm text-body">
            <div class="flex items-center gap-space-sm">
                <span class="material-symbols-outlined text-lg shrink-0">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif
@if (session('error'))
    <div class="w-full max-w-7xl mx-auto px-margin lg:px-margin-md mt-space-xl">
        <div role="alert" class="bg-error-container/80 text-on-error-container border border-error/30 rounded-lg px-space-lg py-space-md font-body-sm text-body-sm text-body">
            <div class="flex items-center gap-space-sm">
                <span class="material-symbols-outlined text-lg shrink-0 text-error">error</span>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif
