@extends('layouts.app')

@section('title', 'Product')

@section('content')
    <section class="section-rule">
        <div class="site-shell py-12 sm:py-14">
            <p class="eyebrow">Katalog</p>
            <h1 class="page-title mt-3">Available Products</h1>
        </div>
    </section>

    <section class="page-section">
        <div class="site-shell">
            <form method="GET" class="surface panel-padding">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
                    <div class="field sm:col-span-2 xl:col-span-2">
                        <label class="label" for="search">Cari produk</label>
                        <input
                            id="search"
                            class="input"
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Contoh: iPhone 15"
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="ram">RAM</label>
                        <select id="ram" class="input" name="ram">
                            <option value="">Semua RAM</option>
                            @foreach($rams as $ram)
                                <option value="{{ $ram }}" @selected(request('ram') == $ram)>
                                    {{ $ram }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label" for="storage">Storage</label>
                        <select id="storage" class="input" name="storage">
                            <option value="">Semua storage</option>
                            @foreach($storages as $storage)
                                <option value="{{ $storage }}" @selected(request('storage') == $storage)>
                                    {{ $storage }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label" for="color">Warna</label>
                        <select id="color" class="input" name="color">
                            <option value="">Semua warna</option>
                            @foreach($colors as $color)
                                <option value="{{ $color }}" @selected(request('color') == $color)>
                                    {{ $color }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label" for="sort">Urutkan</label>
                        <select id="sort" class="input" name="sort">
                            <option value="">Produk terbaru</option>
                            <option value="price_low" @selected(request('sort') === 'price_low')>
                                Harga terendah
                            </option>
                            <option value="price_high" @selected(request('sort') === 'price_high')>
                                Harga tertinggi
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <button class="btn-primary">Cari</button>
                    <a href="{{ route('products.index') }}" class="btn-secondary">Reset</a>
                </div>
            </form>

            <div class="mt-8 flex items-center justify-between gap-4">
                <p class="text-sm text-zinc-500">
                    Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                </p>
            </div>

            @if($products->isNotEmpty())
                <div class="product-grid mt-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-8">{{ $products->links() }}</div>
            @else
                <div class="surface mt-6 p-10 text-center">
                    <h2 class="text-xl font-bold text-zinc-950">Produk tidak ditemukan</h2>
                    <p class="mt-2 text-zinc-500">Ubah kombinasi filter RAM, storage, atau warna.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
