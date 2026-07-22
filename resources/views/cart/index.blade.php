@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="mb-8">
                <p class="eyebrow">Keranjang</p>
                <h1 class="page-title mt-2">Produk yang akan dibeli</h1>
            </div>

            @if($items->isEmpty())
                <div class="empty-state">
                    <h2 class="text-xl font-bold">Keranjang masih kosong</h2>
                    <p class="body-copy mt-2">
                        Pilih stok iPhone yang dibutuhkan dari katalog.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn-primary mt-6">
                        Lihat Produk
                    </a>
                </div>
            @else
                <div class="split-layout">
                    <div class="space-y-4">
                        @foreach($items as $item)
                            <article class="surface flex flex-col gap-5 p-5 sm:flex-row sm:items-center">
                                <img
                                    src="{{ $item['product']->primary_image_url }}"
                                    class="h-28 w-28 rounded-xl bg-zinc-100 object-cover"
                                    alt="{{ $item['product']->name }}"
                                >

                                <div class="min-w-0 flex-1">
                                    <p class="product-meta">
                                        {{ $item['product']->capacity }} ·
                                        {{ $item['product']->color }}
                                    </p>
                                    <h2 class="mt-1 font-bold">{{ $item['product']->name }}</h2>
                                    <p class="text-muted mt-2">
                                        {{ $item['product']->formatted_price }} / unit ·
                                        Stok {{ $item['product']->stock }}
                                    </p>
                                </div>

                                <form
                                    action="{{ route('cart.update', $item['product']) }}"
                                    method="POST"
                                    class="flex items-center gap-2"
                                >
                                    @csrf
                                    @method('PUT')
                                    <input
                                        class="input w-24"
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        max="{{ $item['product']->stock }}"
                                        value="{{ $item['quantity'] }}"
                                    >
                                    <button class="btn-secondary !px-3 !py-2.5">Update</button>
                                </form>

                                <div class="min-w-32 sm:text-right">
                                    <strong>
                                        {{ 'Rp'.number_format($item['subtotal'], 0, ',', '.') }}
                                    </strong>
                                    <form
                                        action="{{ route('cart.destroy', $item['product']) }}"
                                        method="POST"
                                        class="mt-2"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-link text-xs">Hapus</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="surface h-fit panel-padding">
                        <h2 class="panel-title">Ringkasan</h2>

                        <div class="mt-5 flex justify-between border-b border-zinc-200 pb-5 text-sm">
                            <span class="text-zinc-500">Subtotal</span>
                            <strong>{{ 'Rp'.number_format($total, 0, ',', '.') }}</strong>
                        </div>

                        <div class="mt-5 flex justify-between">
                            <span class="font-semibold">Total</span>
                            <strong class="text-xl">
                                {{ 'Rp'.number_format($total, 0, ',', '.') }}
                            </strong>
                        </div>

                        <a href="{{ route('checkout.create') }}" class="btn-primary mt-6 w-full">
                            Lanjut Checkout
                        </a>
                        <a
                            href="{{ route('products.index') }}"
                            class="btn-ghost mt-2 w-full"
                        >
                            Tambah produk lain
                        </a>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
