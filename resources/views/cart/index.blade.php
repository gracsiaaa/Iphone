@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<section class="container-site py-10 lg:py-14">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">
            Keranjang reseller
        </p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-950 lg:text-4xl">
            Keranjang Belanja
        </h1>
        <p class="mt-3 text-zinc-500">
            Minimal pembelian {{ $minimumQuantity }} unit dalam satu transaksi.
            Produk boleh terdiri dari beberapa tipe, kapasitas, dan warna.
        </p>
    </div>

    @if ($items->isEmpty())
        <div class="card px-6 py-16 text-center">
            <h2 class="text-xl font-bold text-zinc-900">Keranjang masih kosong</h2>
            <p class="mt-2 text-zinc-500">
                Tambahkan produk hingga jumlahnya mencapai minimal
                {{ $minimumQuantity }} unit.
            </p>
            <a href="{{ route('products.index') }}" class="btn-primary mt-6">
                Lihat Produk
            </a>
        </div>
    @else
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
            <div class="space-y-4">
                @foreach ($items as $item)
                    @php
                        $product = $item['product'];
                    @endphp

                    <article class="card p-5 sm:p-6">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                            <img
                                src="{{ $product->primary_image_url }}"
                                alt="{{ $product->name }}"
                                class="h-28 w-full rounded-2xl bg-zinc-100 object-contain p-3 sm:w-28"
                            >

                            <div class="min-w-0 flex-1">
                                <a
                                    href="{{ route('products.show', $product) }}"
                                    class="text-lg font-bold text-zinc-950 hover:text-blue-600"
                                >
                                    {{ $product->name }}
                                </a>

                                <p class="mt-1 text-sm text-zinc-500">
                                    {{ $product->capacity }} · {{ $product->color }}
                                </p>

                                <p class="mt-3 font-semibold text-zinc-900">
                                    {{ \App\Support\Money::rupiah($product->price) }} / unit
                                </p>
                            </div>

                            <div class="sm:w-44">
                                <form
                                    action="{{ route('cart.update', $product) }}"
                                    method="POST"
                                    class="flex items-center gap-2"
                                >
                                    @csrf
                                    @method('PUT')

                                    <input
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        max="{{ $product->stock }}"
                                        value="{{ $item['quantity'] }}"
                                        class="input !py-2.5 text-center"
                                        aria-label="Jumlah {{ $product->name }}"
                                    >

                                    <button type="submit" class="btn-secondary !px-3 !py-2.5">
                                        Ubah
                                    </button>
                                </form>

                                <form
                                    action="{{ route('cart.destroy', $product) }}"
                                    method="POST"
                                    class="mt-2 text-right"
                                    onsubmit="return confirm('Hapus produk ini dari keranjang?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-sm font-semibold text-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-between border-t border-zinc-100 pt-4 text-sm">
                            <span class="text-zinc-500">
                                Subtotal {{ $item['quantity'] }} unit
                            </span>
                            <strong class="text-zinc-950">
                                {{ \App\Support\Money::rupiah($item['subtotal']) }}
                            </strong>
                        </div>
                    </article>
                @endforeach
            </div>

            <aside>
                <div class="card sticky top-24 p-6">
                    <h2 class="text-lg font-bold text-zinc-950">Ringkasan Belanja</h2>

                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-zinc-500">Total jumlah barang</span>
                            <strong>{{ $totalQuantity }} unit</strong>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-zinc-500">Minimal pembelian</span>
                            <strong>{{ $minimumQuantity }} unit</strong>
                        </div>

                        <div class="flex justify-between gap-4 border-t border-zinc-200 pt-4">
                            <span class="font-semibold text-zinc-900">Total pembayaran</span>
                            <strong class="text-lg text-zinc-950">
                                {{ \App\Support\Money::rupiah($total) }}
                            </strong>
                        </div>
                    </div>

                    @if ($canCheckout)
                        <div class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-700">
                            Jumlah minimum sudah terpenuhi. Anda dapat melanjutkan checkout.
                        </div>

                        <a
                            href="{{ route('checkout.create') }}"
                            class="btn-primary mt-5 w-full text-center"
                        >
                            Lanjut Checkout
                        </a>
                    @else
                        <div class="mt-5 rounded-xl bg-amber-50 p-4 text-sm leading-6 text-amber-700">
                            Tambahkan <strong>{{ $remainingQuantity }} unit lagi</strong>
                            untuk memenuhi minimal pembelian.
                        </div>

                        <button
                            type="button"
                            disabled
                            class="mt-5 w-full cursor-not-allowed rounded-xl bg-zinc-200 px-5 py-3 font-semibold text-zinc-500"
                        >
                            Minimal {{ $minimumQuantity }} Unit
                        </button>
                    @endif

                    <a
                        href="{{ route('products.index') }}"
                        class="mt-3 block text-center text-sm font-semibold text-blue-600"
                    >
                        Tambah produk lain
                    </a>
                </div>
            </aside>
        </div>
    @endif
</section>
@endsection
