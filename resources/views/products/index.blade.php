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
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
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
                        <label class="label" for="capacity">Kapasitas</label>
                        <select id="capacity" class="input" name="capacity">
                            <option value="">Semua kapasitas</option>
                            @foreach($capacities as $capacity)
                                <option @selected(request('capacity') == $capacity)>
                                    {{ $capacity }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label" for="color">Warna</label>
                        <select id="color" class="input" name="color">
                            <option value="">Semua warna</option>
                            @foreach($colors as $color)
                                <option @selected(request('color') == $color)>
                                    {{ $color }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="label" for="sort">Urutkan</label>
                        <div class="flex flex-col gap-2 min-[420px]:flex-row">
                            <select id="sort" class="input" name="sort">
                                <option value="">Terbaru</option>
                                <option
                                    value="price_low"
                                    @selected(request('sort') === 'price_low')
                                >
                                    Harga terendah
                                </option>
                                <option
                                    value="price_high"
                                    @selected(request('sort') === 'price_high')
                                >
                                    Harga tertinggi
                                </option>
                            </select>
                            <button class="btn-primary w-full !px-4 min-[420px]:w-auto">Cari</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="product-grid mt-8">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="empty-state sm:col-span-2 lg:col-span-4">
                        <h2 class="font-bold">Produk tidak ditemukan</h2>
                        <p class="text-muted mt-2">Ubah kata kunci atau filter pencarian.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection
