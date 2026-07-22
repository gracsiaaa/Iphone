@extends('layouts.app')

@section('title', $product->name.' '.$product->capacity.' '.$product->color)

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <nav class="mb-6 text-sm text-zinc-500" aria-label="Breadcrumb">
                <a href="{{ route('products.index') }}" class="hover:text-zinc-950">
                    Product
                </a>
                <span class="mx-2">/</span>
                <span>{{ $product->name }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-2 lg:gap-14">
                <div class="surface overflow-hidden bg-zinc-100">
                    <img
                        src="{{ $product->primary_image_url }}"
                        class="aspect-square w-full object-cover"
                        alt="{{ $product->name }}"
                    >
                </div>

                <div class="flex flex-col justify-center">
                    <p class="eyebrow">{{ $product->type }}</p>
                    <h1 class="page-title mt-3">{{ $product->name }}</h1>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="badge badge-neutral">{{ $product->capacity }}</span>
                        <span class="badge badge-neutral">{{ $product->color }}</span>
                        <span
                            @class([
                                'badge',
                                'badge-success' => $product->stock > 0,
                                'badge-danger' => $product->stock < 1,
                            ])
                        >
                            {{ $product->stock > 0
                                ? 'Stok '.$product->stock.' unit'
                                : 'Stok habis' }}
                        </span>
                    </div>

                    <div class="mt-8 text-3xl font-bold tracking-tight">
                        {{ $product->formatted_price }}
                    </div>

                    <p class="body-copy mt-6">
                        {{ $product->description
                            ?: 'Produk dicatat berdasarkan kombinasi tipe, kapasitas, dan warna. '.
                                'Hubungi toko untuk informasi tambahan.' }}
                    </p>

                    @auth
                        <form
                            action="{{ route('cart.store', $product) }}"
                            method="POST"
                            class="mt-8 flex max-w-md gap-3"
                        >
                            @csrf
                            <input
                                class="input w-28"
                                type="number"
                                name="quantity"
                                min="1"
                                max="{{ $product->stock }}"
                                value="1"
                            >
                            <button
                                class="btn-primary flex-1"
                                {{ $product->stock < 1 ? 'disabled' : '' }}
                            >
                                Tambahkan ke Keranjang
                            </button>
                        </form>
                    @else
                        <div class="mt-8">
                            <a href="{{ route('login') }}" class="btn-primary">
                                Login untuk Membeli
                            </a>
                            <p class="field-note">
                                Anda tetap dapat melihat katalog tanpa login.
                            </p>
                        </div>
                    @endauth

                    <div class="mt-8 grid gap-3 border-t border-zinc-200 pt-6 text-sm text-zinc-600">
                        <div>✓ Pembayaran QRIS manual</div>
                        <div>✓ Invoice tercatat di akun</div>
                        <div>✓ Verifikasi pembayaran oleh Admin</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="page-section pt-0">
            <div class="site-shell">
                <h2 class="section-title">Produk terkait</h2>
                <div class="product-grid mt-7">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
