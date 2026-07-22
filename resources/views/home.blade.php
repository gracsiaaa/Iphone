@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="hero-section">
        <div class="site-shell">
            <div class="hero-panel">
                <div class="hero-glow"></div>

                <div class="hero-grid">
                    <div>
                        <p class="eyebrow-light">Partner stok reseller profesional</p>

                        <h1 class="display-title mt-5 max-w-3xl">
                            Stok iPhone yang siap membantu bisnis reseller Anda tumbuh.
                        </h1>

                        <p class="mt-6 max-w-xl text-base leading-7 text-zinc-300 sm:text-lg">
                            Temukan tipe, kapasitas, warna, dan harga secara transparan.
                            Checkout cepat, pembayaran QRIS, serta invoice yang tercatat rapi.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('products.index') }}" class="btn-secondary !border-white !bg-white">
                                Lihat Stok Produk
                            </a>

                            @guest
                                <a
                                    href="{{ route('register') }}"
                                    class="btn-secondary !border-white/20 !bg-white/5 !text-white hover:!bg-white/10"
                                >
                                    Daftar Reseller
                                </a>
                            @endguest
                        </div>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <strong class="text-xl">10+</strong>
                                <p class="mt-1 text-xs text-zinc-400">Pilihan stok</p>
                            </div>
                            <div class="hero-stat">
                                <strong class="text-xl">QRIS</strong>
                                <p class="mt-1 text-xs text-zinc-400">Pembayaran mudah</p>
                            </div>
                            <div class="hero-stat">
                                <strong class="text-xl">Invoice</strong>
                                <p class="mt-1 text-xs text-zinc-400">Riwayat terstruktur</p>
                            </div>
                        </div>
                    </div>

                    <div class="hero-art">
                        <div class="hero-image-frame">
                            <img
                                src="{{ asset('images/products/iphone-placeholder.svg') }}"
                                class="w-full rounded-2xl"
                                alt="Stok iPhone reseller"
                            >
                        </div>

                        <div class="surface absolute -bottom-5 -left-3 p-4 text-zinc-950 sm:-left-6">
                            <p class="meta-text">Stok diperbarui</p>
                            <p class="mt-1 text-sm font-bold">Langsung dari Admin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($featuredProducts->isNotEmpty())
        <section class="page-section">
            <div class="site-shell">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Pilihan utama</p>
                        <h2 class="section-title mt-2">Produk unggulan</h2>
                    </div>

                    <a href="{{ route('products.index') }}" class="text-link">
                        Lihat semua →
                    </a>
                </div>

                <div class="product-grid mt-8">
                    @foreach($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-rule page-section">
        <div class="site-shell">
            <div class="mx-auto max-w-2xl text-center">
                <p class="eyebrow">Alur pembelian</p>
                <h2 class="section-title mt-2">Sederhana dari stok sampai invoice</h2>
                <p class="body-copy mt-4">
                    Empat langkah yang jelas agar proses pemesanan mudah dipahami.
                </p>
            </div>

            @php
                $steps = [
                    ['01', 'Pilih produk', 'Temukan tipe, kapasitas, dan warna yang dibutuhkan.'],
                    ['02', 'Masuk keranjang', 'Atur jumlah unit berdasarkan stok yang tersedia.'],
                    ['03', 'Bayar QRIS', 'Checkout dan scan QRIS toko sesuai nominal invoice.'],
                    ['04', 'Verifikasi Admin', 'Admin memeriksa pembayaran dan memperbarui invoice.'],
                ];
            @endphp

            <div class="mt-10 grid gap-5 md:grid-cols-4">
                @foreach($steps as $step)
                    <div class="surface panel-padding">
                        <span class="text-sm font-black text-blue-600">{{ $step[0] }}</span>
                        <h3 class="mt-5 font-bold">{{ $step[1] }}</h3>
                        <p class="text-muted mt-2">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="site-shell">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Stok terbaru</p>
                    <h2 class="section-title mt-2">Siap masuk keranjang</h2>
                </div>

                <a href="{{ route('products.index') }}" class="text-link">
                    Jelajahi katalog →
                </a>
            </div>

            <div class="product-grid mt-8">
                @forelse($latestProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="empty-state sm:col-span-2 lg:col-span-4">
                        <h3 class="font-bold">Belum ada produk aktif</h3>
                        <p class="text-muted mt-2">Produk baru akan tampil di bagian ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @if($latestArticles->isNotEmpty())
        <section class="section-rule page-section">
            <div class="site-shell">
                <div>
                    <p class="eyebrow">Insight reseller</p>
                    <h2 class="section-title mt-2">Artikel terbaru</h2>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach($latestArticles as $article)
                        <article class="article-card">
                            <a href="{{ route('articles.show', $article) }}">
                                <img
                                    src="{{ $article->thumbnail_url }}"
                                    class="aspect-video w-full object-cover"
                                    alt="{{ $article->title }}"
                                >
                            </a>

                            <div class="p-6">
                                <p class="meta-text">
                                    {{ optional($article->published_at)->format('d M Y') }}
                                </p>
                                <h3 class="mt-2 text-lg font-bold leading-snug">
                                    <a href="{{ route('articles.show', $article) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                                <p class="text-muted mt-3 line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="page-section">
        <div class="site-shell">
            <div class="cta-panel">
                <p class="eyebrow-light">Mulai sekarang</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight">
                    Siap menemukan stok untuk toko Anda?
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-blue-100">
                    Buat akun reseller, simpan riwayat pembelian, dan kelola seluruh invoice
                    dalam satu tempat.
                </p>
                <a
                    href="{{ auth()->check() ? route('products.index') : route('register') }}"
                    class="btn-secondary mt-7 !border-white !bg-white !text-blue-700"
                >
                    {{ auth()->check() ? 'Mulai Belanja' : 'Daftar Sekarang' }}
                </a>
            </div>
        </div>
    </section>
@endsection
