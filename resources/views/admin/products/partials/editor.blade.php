@php
    $seedVariants = old('variants', $product?->variants?->toArray() ?? []);
    $seedImages = $product?->images
        ?->map(fn ($image) => ['id' => $image->id, 'url' => asset('storage/'.$image->image_path), 'remove' => false])
        ?->values()
        ?? collect();
    $seedPrimary = (string) old('primary_image_id', $product?->images->firstWhere('is_primary', '1')?->id ?? $product?->images->firstWhere('is_primary', true)?->id ?? '');
    $savedStock = (int) ($product?->variants()->where('is_active', true)->sum('stock_quantity') ?? 0);
    $savedCount = (int) ($product?->variants()->count() ?? 0);

    // Per-row variant errors, keyed as "index.field" for Alpine lookups.
    $variantErrors = [];
    foreach ($errors->get('variants') as $index => $rowErrors) {
        foreach ($rowErrors as $field => $messages) {
            $variantErrors[$index.'.'.Illuminate\Support\Str::afterLast($field, '.')] = Illuminate\Support\Arr::first($messages);
        }
    }
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="grid items-start gap-5 lg:grid-cols-12" x-data="productEditor()">
    @csrf
    @if (! empty($method))
        @method($method)
    @endif

    {{-- Marks the form as variant-aware so an emptied editor removes all rows. --}}
    <input type="hidden" name="has_variant_editor" value="1" />

    {{-- LEFT COLUMN — main editor --}}
    <div class="space-y-5 lg:col-span-8">
        <div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
            <div class="border-b border-black/5 px-6 py-4">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Basic information</h2>
            </div>
            <div class="px-6 py-5">
                @include('admin.products.partials.basic-info')
            </div>
        </div>

        @include('admin.products.partials.variants-editor')
    </div>

    {{-- RIGHT COLUMN — media, publishing, inventory, actions --}}
    <div class="space-y-5 lg:col-span-4 lg:sticky lg:top-20">
        @include('admin.products.partials.media-card')

        {{-- Publishing --}}
        <div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
            <div class="border-b border-black/5 px-6 py-4">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Publishing</h2>
            </div>

            <div class="space-y-4 px-6 py-5">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="is_active" value="0" />
                    <input
                        id="is_active"
                        name="is_active"
                        type="checkbox"
                        value="1"
                        @checked(old('is_active', $product->is_active ?? 1))
                        class="mt-0.5 rounded border-black/20 text-lilac focus:ring-lilac"
                    />
                    <span>
                        <span class="block text-sm font-medium text-ink">Active</span>
                        <span class="mt-0.5 block text-xs text-muted">Visible on the storefront once the shop goes live.</span>
                    </span>
                </label>
                @error('is_active')
                    <p class="text-xs font-medium text-blush-deep" role="alert">{{ $message }}</p>
                @enderror

                <label class="flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="is_featured" value="0" />
                    <input
                        id="is_featured"
                        name="is_featured"
                        type="checkbox"
                        value="1"
                        @checked(old('is_featured', $product->is_featured ?? 0))
                        class="mt-0.5 rounded border-black/20 text-lilac focus:ring-lilac"
                    />
                    <span>
                        <span class="block text-sm font-medium text-ink">Featured</span>
                        <span class="mt-0.5 block text-xs text-muted">Highlight this piece in featured collections.</span>
                    </span>
                </label>
                @error('is_featured')
                    <p class="text-xs font-medium text-blush-deep" role="alert">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Inventory summary (live from the variant rows above) --}}
        <div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
            <div class="border-b border-black/5 px-6 py-4">
                <h2 class="text-sm font-semibold tracking-tight text-ink">Inventory summary</h2>
            </div>

            <div class="px-6 py-5">
                <dl class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Variants in form</dt>
                        <dd class="font-semibold tabular-nums text-ink" x-text="variants.length"></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Total stock</dt>
                        <dd class="font-semibold tabular-nums text-ink" x-text="totalStock"></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Out-of-stock rows</dt>
                        <dd
                            class="font-semibold tabular-nums"
                            :class="outOfStockCount > 0 ? 'text-blush-deep' : 'text-mint-deep'"
                            x-text="outOfStockCount"
                        ></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-muted">Low stock (≤ 5)</dt>
                        <dd
                            class="font-semibold tabular-nums"
                            :class="lowStockCount > 0 ? 'text-blush-deep' : 'text-mint-deep'"
                            x-text="lowStockCount"
                        ></dd>
                    </div>
                </dl>

                </dl>

                @if ($product->exists ?? false)
                    <p class="mt-4 rounded-xl bg-ivory/60 px-4 py-3 text-xs leading-relaxed text-muted">
                        Currently saved: {{ $savedCount }} variant{{ $savedCount === 1 ? '' : 's' }} · {{ $savedStock }} unit{{ $savedStock === 1 ? '' : 's' }} in stock.
                    </p>
                @endif
            </div>
        </div>

        {{-- Save actions --}}
        <div class="rounded-2xl border border-black/[0.06] bg-white p-6 shadow-card">
            <div class="flex flex-col gap-3">
                <button
                    type="submit"
                    name="intent"
                    value="listing"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-lilac px-5 py-2.5 text-sm font-semibold text-aubergine shadow-sm transition hover:bg-lilac-deep hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac focus-visible:ring-offset-2"
                >
                    <x-admin.icon name="check" class="h-4 w-4" />
                    {{ $submitLabel }}
                </button>

                <button
                    type="submit"
                    name="intent"
                    value="stay"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-black/10 bg-white px-5 py-2.5 text-sm font-medium text-ink transition hover:bg-ivory focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                >
                    Save &amp; keep editing
                </button>

                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-2.5 text-sm font-medium text-muted transition hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                >
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('productEditor', () => ({
                variants: @json($seedVariants),
                images: @json($seedImages),
                primaryId: @json($seedPrimary),
                variantErrors: @json($variantErrors),
                previews: [],
                dragging: false,

                addVariant() {
                    this.variants.push({
                        id: null,
                        sku: '',
                        size: '',
                        color: '',
                        price: '',
                        compare_at_price: '',
                        stock_quantity: 0,
                        is_active: true,
                    });
                },

                removeVariant(index) {
                    this.variants.splice(index, 1);
                },

                get totalStock() {
                    return this.variants.reduce((sum, variant) => sum + (parseInt(variant.stock_quantity, 10) || 0), 0);
                },

                get outOfStockCount() {
                    return this.variants.filter((variant) => (parseInt(variant.stock_quantity, 10) || 0) === 0).length;
                },

                get lowStockCount() {
                    return this.variants.filter((variant) => {
                        const stock = parseInt(variant.stock_quantity, 10) || 0;
                        return stock > 0 && stock <= {{ (int) $lowStockThreshold }};
                    }).length;
                },

                handleDrop(event) {
                    this.addFiles(event.dataTransfer.files);
                },

                addFiles(fileList) {
                    const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];

                    for (const file of fileList) {
                        if (!allowed.includes(file.type) || file.size > 4 * 1024 * 1024) {
                            continue;
                        }

                        if (this.images.filter((image) => !image.remove).length + this.previews.length >= 12) {
                            break;
                        }

                        this.previews.push({ file, url: URL.createObjectURL(file) });
                    }

                    this.syncFiles();
                },

                removePreview(index) {
                    URL.revokeObjectURL(this.previews[index].url);
                    this.previews.splice(index, 1);

                    if (this.primaryId.startsWith('new-')) {
                        this.primaryId = this.images.length > 0 ? String(this.images[0].id) : '';
                    }

                    this.syncFiles();
                },

                syncFiles() {
                    const transfer = new DataTransfer();
                    this.previews.forEach((preview) => transfer.items.add(preview.file));
                    this.$refs.imageInput.files = transfer.files;
                    // #region agent log
                    fetch('http://127.0.0.1:7724/ingest/be648393-b15d-4fdd-85bb-1b3aabd31d5c',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'d42924'},body:JSON.stringify({sessionId:'d42924',hypothesisId:'D',location:'editor.blade.php:syncFiles',message:'after syncFiles',data:{previewCount:this.previews.length,inputFileCount:this.$refs.imageInput?.files?.length ?? -1},timestamp:Date.now(),runId:'post-fix'})}).catch(()=>{});
                    // #endregion
                },

                init() {
                    this.$el.addEventListener('submit', () => {
                        // #region agent log
                        const payload = JSON.stringify({sessionId:'d42924',hypothesisId:'A',location:'editor.blade.php:submit',message:'form submit file state',data:{previewCount:this.previews.length,inputFileCount:this.$refs.imageInput?.files?.length ?? -1,inputNames:Array.from(this.$refs.imageInput?.files ?? []).map(f => f.name)},timestamp:Date.now(),runId:'post-fix'});
                        try { navigator.sendBeacon('http://127.0.0.1:7724/ingest/be648393-b15d-4fdd-85bb-1b3aabd31d5c', new Blob([payload], {type:'application/json'})); } catch (e) {}
                        // #endregion
                    });
                },

                moveImage(index, direction) {
                    const target = index + direction;

                    if (target < 0 || target >= this.images.length) {
                        return;
                    }

                    const [image] = this.images.splice(index, 1);
                    this.images.splice(target, 0, image);
                },
            }));
        });
    </script>
@endpush

