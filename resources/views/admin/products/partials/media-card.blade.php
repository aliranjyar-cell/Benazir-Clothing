<div class="rounded-2xl border border-black/[0.06] bg-white shadow-card">
    <div class="border-b border-black/5 px-6 py-4">
        <h2 class="text-sm font-semibold tracking-tight text-ink">Product media</h2>
        <p class="mt-0.5 text-xs text-muted">JPG, PNG, WEBP or AVIF · up to 4 MB each · max 12 images</p>
    </div>

    <div class="space-y-4 px-6 py-5">
        @foreach ($errors->get('images') as $errorBag)
            @foreach ($errorBag as $message)
                <p class="rounded-xl border border-blush/40 bg-blush-soft px-4 py-2.5 text-xs font-medium text-blush-deep" role="alert">
                    {{ is_array($message) ? implode(' ', $message) : $message }}
                </p>
            @endforeach
        @endforeach

        {{-- Saved images --}}
        <div x-show="images.length > 0" x-cloak class="space-y-2.5">
            <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">Gallery</p>

            <template x-for="(image, imageIndex) in images" :key="image.id">
                <div class="flex items-center gap-3 rounded-xl border border-black/[0.06] bg-ivory/30 p-2.5">
                    <img :src="image.url" :alt="'Gallery image ' + (imageIndex + 1)" class="h-14 w-14 shrink-0 rounded-lg object-cover ring-1 ring-black/5" loading="lazy" />

                    <div class="min-w-0 flex-1 space-y-1.5">
                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="radio"
                                name="primary_image_id"
                                :value="String(image.id)"
                                x-model="primaryId"
                                class="border-black/20 text-lilac focus:ring-lilac"
                            />
                            <span class="truncate text-xs font-medium text-ink">Primary image</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="delete_images[]"
                                :value="image.id"
                                x-model.boolean="image.remove"
                                class="rounded border-black/20 text-blush focus:ring-blush"
                            />
                            <span class="text-xs text-muted">Remove on save</span>
                        </label>
                    </div>

                    <div class="flex shrink-0 flex-col gap-1">
                        <button
                            type="button"
                            @click="moveImage(imageIndex, -1)"
                            :disabled="imageIndex === 0"
                            class="rounded-md p-1 text-muted transition hover:bg-white hover:text-ink disabled:opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                            :aria-label="'Move image ' + (imageIndex + 1) + ' earlier'"
                        >
                            <x-admin.icon name="chevron-down" class="h-3.5 w-3.5 rotate-180" />
                        </button>
                        <button
                            type="button"
                            @click="moveImage(imageIndex, 1)"
                            :disabled="imageIndex === images.length - 1"
                            class="rounded-md p-1 text-muted transition hover:bg-white hover:text-ink disabled:opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                            :aria-label="'Move image ' + (imageIndex + 1) + ' later'"
                        >
                            <x-admin.icon name="chevron-down" class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            </template>

            <template x-for="image in images" :key="'order-' + image.id">
                <input type="hidden" name="image_order[]" :value="image.id" />
            </template>
        </div>

        {{-- Dropzone --}}
        <div
            class="flex flex-col items-center rounded-xl border-2 border-dashed border-black/10 bg-ivory/30 px-4 py-7 text-center transition-colors"
            :class="dragging ? 'border-lilac bg-lilac-soft/40' : ''"
            @dragenter.prevent="dragging = true"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false; handleDrop($event)"
        >
            <span class="grid h-10 w-10 place-items-center rounded-full bg-lilac-soft text-lilac-deep ring-1 ring-lilac/30">
                <x-admin.icon name="upload" class="h-5 w-5" />
            </span>

            <p class="mt-3 text-sm font-medium text-ink">Drag images here</p>
            <p class="mt-0.5 text-xs text-muted">or</p>

            <label class="mt-2 inline-flex cursor-pointer items-center gap-2 rounded-lg border border-lilac/50 bg-lilac-soft px-3.5 py-2 text-xs font-semibold text-aubergine transition hover:bg-lilac hover:text-white focus-within:ring-2 focus-within:ring-lilac">
                <x-admin.icon name="plus" class="h-3.5 w-3.5" />
                Choose files
                <input
                    x-ref="imageInput"
                    type="file"
                    name="images[]"
                    accept="image/jpeg,image/png,image/webp,image/avif"
                    multiple
                    class="sr-only"
                    @change="addFiles($event.target.files); $event.target.value = ''"
                />
            </label>
        </div>

        {{-- Selected image previews (before submission) --}}
        <div x-show="previews.length > 0" x-cloak class="space-y-2.5">
            <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">Ready to upload</p>

            <div class="grid grid-cols-3 gap-2.5 sm:grid-cols-4">
                <template x-for="(preview, previewIndex) in previews" :key="preview.url">
                    <div class="group relative">
                        <img :src="preview.url" :alt="preview.file.name" class="aspect-square w-full rounded-lg object-cover ring-1 ring-black/5" />

                        <label class="absolute bottom-1.5 left-1.5 flex cursor-pointer items-center gap-1.5 rounded-md bg-white/95 px-1.5 py-1 text-[10px] font-semibold text-ink shadow-sm ring-1 ring-black/5">
                            <input
                                type="radio"
                                name="primary_image_id"
                                :value="'new-' + previewIndex"
                                x-model="primaryId"
                                class="border-black/20 text-lilac focus:ring-lilac"
                            />
                            Primary
                        </label>

                        <button
                            type="button"
                            @click="removePreview(previewIndex)"
                            class="absolute right-1.5 top-1.5 rounded-md bg-white/95 p-1 text-blush-deep shadow-sm ring-1 ring-black/5 transition hover:bg-blush-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-lilac"
                            :aria-label="'Remove ' + preview.file.name"
                        >
                            <x-admin.icon name="x-mark" class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <p x-show="images.length === 0 && previews.length === 0" x-cloak class="text-xs leading-relaxed text-muted">
            No images yet. Products with photos sell better — add at least a front view.
        </p>
    </div>
</div>
