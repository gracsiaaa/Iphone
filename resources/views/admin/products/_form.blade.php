<div class="editor-layout">
    <section class="surface form-grid p-6 sm:p-8">
        <div class="field-full">
            <h2 class="panel-title">Informasi produk</h2>
            <p class="text-muted mt-1">
                Satu produk mewakili kombinasi tipe, kapasitas, dan warna.
            </p>
        </div>

        <div class="field">
            <label class="label" for="name">Nama produk</label>
            <input
                id="name"
                class="input"
                name="name"
                value="{{ old('name', $product->name ?? '') }}"
                placeholder="iPhone 15 Pro"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="type">Tipe</label>
            <input
                id="type"
                class="input"
                name="type"
                value="{{ old('type', $product->type ?? '') }}"
                placeholder="iPhone 15 Pro"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="capacity">Kapasitas</label>
            <input
                id="capacity"
                class="input"
                name="capacity"
                value="{{ old('capacity', $product->capacity ?? '') }}"
                placeholder="256 GB"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="color">Warna</label>
            <input
                id="color"
                class="input"
                name="color"
                value="{{ old('color', $product->color ?? '') }}"
                placeholder="Natural Titanium"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="price">Harga</label>
            <input
                id="price"
                class="input"
                type="number"
                name="price"
                min="0"
                value="{{ old('price', $product->price ?? '') }}"
                required
            >
        </div>

        <div class="field">
            <label class="label" for="stock">Stok</label>
            <input
                id="stock"
                class="input"
                type="number"
                name="stock"
                min="0"
                value="{{ old('stock', $product->stock ?? 0) }}"
                required
            >
        </div>

        <div class="field field-full">
            <label class="label" for="description">Deskripsi</label>
            <textarea
                id="description"
                class="input min-h-36"
                name="description"
            >{{ old('description', $product->description ?? '') }}</textarea>
        </div>
    </section>

    <aside class="space-y-6">
        <section class="surface panel-padding">
            <h2 class="panel-title">Publikasi</h2>

            <label class="check-row mt-5">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $product->is_active ?? true))
                >
                <span>Tampilkan produk</span>
            </label>

            <label class="check-row mt-4">
                <input
                    type="checkbox"
                    name="is_featured"
                    value="1"
                    @checked(old('is_featured', $product->is_featured ?? false))
                >
                <span>Produk unggulan</span>
            </label>
        </section>

        <section class="surface panel-padding">
            <h2 class="panel-title">Foto produk</h2>
            <p class="field-note">
                Maksimal 6 gambar, masing-masing 4 MB. Gambar pertama menjadi foto utama.
            </p>

            <input
                class="input mt-4"
                type="file"
                name="images[]"
                accept="image/*"
                multiple
            >

            @isset($product)
                @if($product->images->isNotEmpty())
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        @foreach($product->images as $image)
                            <div class="relative">
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}"
                                    class="aspect-square w-full rounded-lg object-cover"
                                    alt="Foto produk"
                                >
                                <button
                                    type="submit"
                                    form="delete-image-{{ $image->id }}"
                                    class="absolute right-1 top-1 rounded-lg bg-red-600 px-2 py-1 text-xs text-white"
                                    aria-label="Hapus gambar"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endisset
        </section>

        <button class="btn-primary w-full">
            {{ isset($product) ? 'Simpan Perubahan' : 'Buat Produk' }}
        </button>
    </aside>
</div>
