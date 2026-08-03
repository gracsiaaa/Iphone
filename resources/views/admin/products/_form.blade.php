@php
    $variantRows = old('variants');

    if ($variantRows === null) {
        $variantRows = isset($product)
            ? $product->variants->map(fn ($variant) => [
                'id' => $variant->id,
                'ram' => $variant->ram,
                'storage' => $variant->storage,
                'price' => \App\Support\Money::rupiah($variant->price),
                'stock' => $variant->stock,
            ])->values()->all()
            : [[
                'id' => null,
                'ram' => '',
                'storage' => '',
                'price' => '',
                'stock' => 0,
            ]];
    }
@endphp

<div class="grid gap-6 xl:grid-cols-[1fr_360px]">
    <div class="card space-y-7 p-6 lg:p-8">
        <div>
            <label for="name" class="label">Nama produk</label>
            <input
                id="name"
                name="name"
                class="input"
                value="{{ old('name', $product->name ?? '') }}"
                placeholder="Contoh: iPhone 15 Pro"
                required
            >
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="type" class="label">Tipe</label>
                <input
                    id="type"
                    name="type"
                    class="input"
                    value="{{ old('type', $product->type ?? '') }}"
                    placeholder="iPhone / Macbook"
                    required
                >
            </div>

            <div>
                <label for="color" class="label">Warna</label>
                <input
                    id="color"
                    name="color"
                    class="input"
                    value="{{ old('color', $product->color ?? '') }}"
                    placeholder="Natural Titanium"
                    required
                >
            </div>
        </div>

        <section class="rounded-2xl border border-zinc-200 p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="font-bold text-zinc-950">Variasi RAM dan Storage</h2>
                    <p class="mt-1 text-sm leading-6 text-zinc-500">
                        Tambahkan setiap kombinasi RAM, storage, harga, dan stok yang tersedia.
                    </p>
                </div>

                <button
                    id="add-variant"
                    type="button"
                    class="btn-secondary shrink-0 !py-2.5"
                >
                    + Tambah Variasi
                </button>
            </div>

            @if (isset($product) && $product->variants->contains('ram', 'Belum diatur'))
                <div class="mt-4 rounded-xl bg-amber-50 p-4 text-sm leading-6 text-amber-700">
                    Produk lama ini masih memakai RAM “Belum diatur”. Ganti dengan kapasitas RAM yang benar sebelum menyimpan.
                </div>
            @endif

            @error('variants')
                <p class="mt-3 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror

            <div id="variant-list" class="mt-5 space-y-4">
                @foreach ($variantRows as $index => $variant)
                    <div class="variant-row rounded-xl bg-zinc-50 p-4" data-index="{{ $index }}">
                        <input
                            type="hidden"
                            name="variants[{{ $index }}][id]"
                            value="{{ $variant['id'] ?? '' }}"
                        >

                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-[1fr_1fr_1.2fr_120px_auto] xl:items-end">
                            <div>
                                <label class="label">RAM</label>
                                <input
                                    name="variants[{{ $index }}][ram]"
                                    class="input"
                                    value="{{ $variant['ram'] ?? '' }}"
                                    placeholder="8 GB"
                                    required
                                >
                                @error("variants.$index.ram")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Storage</label>
                                <input
                                    name="variants[{{ $index }}][storage]"
                                    class="input"
                                    value="{{ $variant['storage'] ?? '' }}"
                                    placeholder="256 GB"
                                    required
                                >
                                @error("variants.$index.storage")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Harga per unit</label>
                                <input
                                    type="text"
                                    name="variants[{{ $index }}][price]"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    class="input variant-price"
                                    value="{{ $variant['price'] ?? '' }}"
                                    placeholder="Rp 13.500.000"
                                    required
                                >
                                @error("variants.$index.price")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="label">Stok</label>
                                <input
                                    type="number"
                                    name="variants[{{ $index }}][stock]"
                                    min="0"
                                    class="input"
                                    value="{{ $variant['stock'] ?? 0 }}"
                                    required
                                >
                                @error("variants.$index.stock")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="button"
                                class="remove-variant rounded-xl px-3 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <template id="variant-template">
                <div class="variant-row rounded-xl bg-zinc-50 p-4" data-index="__INDEX__">
                    <input type="hidden" name="variants[__INDEX__][id]" value="">

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-[1fr_1fr_1.2fr_120px_auto] xl:items-end">
                        <div>
                            <label class="label">RAM</label>
                            <input
                                name="variants[__INDEX__][ram]"
                                class="input"
                                placeholder="8 GB"
                                required
                            >
                        </div>

                        <div>
                            <label class="label">Storage</label>
                            <input
                                name="variants[__INDEX__][storage]"
                                class="input"
                                placeholder="256 GB"
                                required
                            >
                        </div>

                        <div>
                            <label class="label">Harga per unit</label>
                            <input
                                type="text"
                                name="variants[__INDEX__][price]"
                                inputmode="numeric"
                                autocomplete="off"
                                class="input variant-price"
                                placeholder="Rp 13.500.000"
                                required
                            >
                        </div>

                        <div>
                            <label class="label">Stok</label>
                            <input
                                type="number"
                                name="variants[__INDEX__][stock]"
                                min="0"
                                class="input"
                                value="0"
                                required
                            >
                        </div>

                        <button
                            type="button"
                            class="remove-variant rounded-xl px-3 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </template>
        </section>

        <div>
            <label for="description" class="label">Deskripsi</label>
            <textarea
                id="description"
                name="description"
                class="input min-h-40"
                placeholder="Informasi singkat mengenai produk"
            >{{ old('description', $product->description ?? '') }}</textarea>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="card p-6">
            <h2 class="font-bold text-zinc-950">Status Produk</h2>

            <div class="mt-5 space-y-4">
                <label class="flex items-center gap-3 text-sm text-zinc-700">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $product->is_active ?? true))
                    >
                    Produk aktif dan tampil di katalog
                </label>

                <label class="flex items-center gap-3 text-sm text-zinc-700">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(old('is_featured', $product->is_featured ?? false))
                    >
                    Tampilkan sebagai produk unggulan
                </label>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-bold text-zinc-950">Foto Produk</h2>
            <p class="mt-2 text-xs leading-5 text-zinc-500">
                Maksimal enam gambar. Format JPG, JPEG, PNG, atau WebP. Maksimal 4 MB per gambar.
            </p>

            @isset($product)
                @if ($product->images->isNotEmpty())
                    <div class="mt-5 grid grid-cols-2 gap-3">
                        @foreach ($product->images as $image)
                            <div class="rounded-xl border border-zinc-200 p-2">
                                <img
                                    src="{{ $image->url }}"
                                    alt="{{ $product->name }}"
                                    class="aspect-square w-full rounded-lg object-contain"
                                >
                            </div>
                        @endforeach
                    </div>
                @endif
            @endisset

            <input
                type="file"
                name="images[]"
                accept="image/jpeg,image/png,image/webp"
                multiple
                class="input mt-5"
            >
        </div>

        <button type="submit" class="btn-primary w-full">
            {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Produk' }}
        </button>
    </aside>
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const list = document.getElementById('variant-list');
            const template = document.getElementById('variant-template');
            const addButton = document.getElementById('add-variant');
            const rupiahFormatter = new Intl.NumberFormat('id-ID');
            let nextIndex = Math.max(
                0,
                ...Array.from(list.querySelectorAll('.variant-row')).map((row) => Number(row.dataset.index) + 1)
            );

            const formatRupiah = (value) => {
                const digits = String(value ?? '').replace(/\D/g, '');
                return digits.length === 0 ? '' : `Rp ${rupiahFormatter.format(Number(digits))}`;
            };

            const bindPriceInput = (input) => {
                const applyFormat = () => {
                    input.value = formatRupiah(input.value);
                    const endPosition = input.value.length;
                    input.setSelectionRange(endPosition, endPosition);
                };

                applyFormat();
                input.addEventListener('input', applyFormat);
                input.addEventListener('blur', applyFormat);
            };

            const refreshRemoveButtons = () => {
                const rows = list.querySelectorAll('.variant-row');
                rows.forEach((row) => {
                    const button = row.querySelector('.remove-variant');
                    button.disabled = rows.length === 1;
                    button.classList.toggle('cursor-not-allowed', rows.length === 1);
                    button.classList.toggle('opacity-40', rows.length === 1);
                });
            };

            list.querySelectorAll('.variant-price').forEach(bindPriceInput);
            refreshRemoveButtons();

            addButton.addEventListener('click', () => {
                const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex++));
                list.insertAdjacentHTML('beforeend', html);
                const newRow = list.lastElementChild;
                bindPriceInput(newRow.querySelector('.variant-price'));
                refreshRemoveButtons();
                newRow.querySelector('input[name$="[ram]"]').focus();
            });

            list.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-variant');

                if (!button || button.disabled) {
                    return;
                }

                button.closest('.variant-row').remove();
                refreshRemoveButtons();
            });
        });
    </script>
@endonce
