<div class="grid gap-6 xl:grid-cols-[1fr_360px]">
    <div class="card space-y-6 p-6 lg:p-8">
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

        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label for="type" class="label">Tipe</label>
                <input
                    id="type"
                    name="type"
                    class="input"
                    value="{{ old('type', $product->type ?? '') }}"
                    placeholder="iPhone 15 Pro"
                    required
                >
            </div>

            <div>
                <label for="capacity" class="label">Kapasitas</label>
                <input
                    id="capacity"
                    name="capacity"
                    class="input"
                    value="{{ old('capacity', $product->capacity ?? '') }}"
                    placeholder="256 GB"
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

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="price" class="label">Harga per unit</label>
                <input
                    id="price"
                    type="text"
                    name="price"
                    inputmode="numeric"
                    autocomplete="off"
                    class="input"
                    value="{{ old('price', isset($product) ? \App\Support\Money::rupiah($product->price) : '') }}"
                    placeholder="Rp 13.500.000"
                    required
                >
                <p class="mt-2 text-xs text-zinc-500">
                    Ketik angka saja. Sistem otomatis menambahkan Rp dan tanda titik.
                </p>
            </div>

            <div>
                <label for="stock" class="label">Stok</label>
                <input
                    id="stock"
                    type="number"
                    name="stock"
                    min="0"
                    class="input"
                    value="{{ old('stock', $product->stock ?? 0) }}"
                    required
                >
            </div>
        </div>

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
                const priceInput = document.getElementById('price');

                if (!priceInput) {
                    return;
                }

                const rupiahFormatter = new Intl.NumberFormat('id-ID');

                const formatRupiah = (value) => {
                    const digits = String(value ?? '').replace(/\D/g, '');

                    if (digits.length === 0) {
                        return '';
                    }

                    return `Rp ${rupiahFormatter.format(Number(digits))}`;
                };

                const applyFormat = () => {
                    priceInput.value = formatRupiah(priceInput.value);

                    // Posisi kursor dipindahkan ke akhir agar input stabil.
                    const endPosition = priceInput.value.length;
                    priceInput.setSelectionRange(endPosition, endPosition);
                };

                applyFormat();
                priceInput.addEventListener('input', applyFormat);
                priceInput.addEventListener('blur', applyFormat);
            });
    </script>
@endonce
