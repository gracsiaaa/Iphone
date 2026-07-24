<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', $siteSettings->get('site_name', config('app.name')))
        — {{ $siteSettings->get('site_tagline', 'Stok iPhone untuk reseller') }}
    </title>

    <meta
        name="description"
        content="@yield(
            'meta_description',
            $siteSettings->get(
                'site_tagline',
                'Platform stok iPhone profesional untuk reseller.'
            )
        )"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="site-shell">
            <div class="site-header-bar">
                <a href="{{ route('home') }}" class="brand-link">
                    <img
                        src="{{ asset('images/logo-mark.svg') }}"
                        alt="Logo {{ $siteSettings->get('site_name', 'iPhone Reseller') }}"
                        class="brand-logo"
                    >

                    <div class="min-w-0">
                        <div class="brand-name">
                            {{ $siteSettings->get('site_name', 'iPhone Reseller') }}
                        </div>
                        <div class="brand-caption">Premium Stock Partner</div>
                    </div>
                </a>

                <nav class="site-nav" aria-label="Navigasi utama">
                    <a
                        href="{{ route('home') }}"
                        @class([
                            'nav-link',
                            'nav-link-active' => request()->routeIs('home'),
                        ])
                    >
                        Home
                    </a>
                    <a
                        href="{{ route('products.index') }}"
                        @class([
                            'nav-link',
                            'nav-link-active' => request()->routeIs('products.*'),
                        ])
                    >
                        Product
                    </a>
                    <a
                        href="{{ route('articles.index') }}"
                        @class([
                            'nav-link',
                            'nav-link-active' => request()->routeIs('articles.*'),
                        ])
                    >
                        Article
                    </a>
                    <a
                        href="{{ route('faq') }}"
                        @class([
                            'nav-link',
                            'nav-link-active' => request()->routeIs('faq'),
                        ])
                    >
                        FAQ
                    </a>
                    <a
                        href="{{ route('contact') }}"
                        @class([
                            'nav-link',
                            'nav-link-active' => request()->routeIs('contact*'),
                        ])
                    >
                        Contact
                    </a>
                </nav>

                <div class="header-actions">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-secondary !px-4 !py-2.5">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('orders.index') }}" class="btn-ghost">
                                Pesanan
                            </a>
                        @endif

                        <a href="{{ route('cart.index') }}" class="btn-primary !px-4 !py-2.5">
                            Keranjang
                            <span class="ml-2 rounded-full bg-white/15 px-2 py-0.5 text-xs">
                                {{ array_sum(session('cart', [])) }}
                            </span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn-ghost">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary !px-4 !py-2.5">
                            Daftar Reseller
                        </a>
                    @endauth
                </div>

                <button
                    type="button"
                    data-toggle="mobile-menu"
                    class="mobile-toggle"
                    aria-label="Buka menu"
                >
                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>
            </div>

            <div id="mobile-menu" class="mobile-menu hidden">
                <a href="{{ route('home') }}" class="mobile-link">Home</a>
                <a href="{{ route('products.index') }}" class="mobile-link">Product</a>
                <a href="{{ route('articles.index') }}" class="mobile-link">Article</a>
                <a href="{{ route('faq') }}" class="mobile-link">FAQ</a>
                <a href="{{ route('contact') }}" class="mobile-link">Contact</a>

                @auth
                    <a
                        href="{{ auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : route('orders.index') }}"
                        class="mobile-link"
                    >
                        Dashboard
                    </a>
                    <a href="{{ route('cart.index') }}" class="mobile-link">
                        Keranjang ({{ array_sum(session('cart', [])) }})
                    </a>
                @else
                    <a href="{{ route('login') }}" class="mobile-link">Login</a>
                    <a href="{{ route('register') }}" class="mobile-link !bg-zinc-950 !text-white">
                        Daftar Reseller
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if(session('success'))
        <div data-flash class="site-shell pt-3">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div data-flash class="site-shell pt-3">
            <div class="alert alert-danger">{{ session('error') }}</div>
        </div>
    @endif

    <!-- Tambahkan pengecualian rute menggunakan !request()->routeIs() -->
    @if($errors->any() && !request()->routeIs('register', 'login'))
        <div data-flash class="site-shell pt-3">
            <div class="alert alert-danger">
                <strong>Periksa kembali data berikut:</strong>
                <ul class="mt-2 list-disc pl-8">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="mt-12 border-t border-zinc-200 bg-white/70">
        <div class="site-shell grid gap-10 py-12 md:grid-cols-4">
            <div class="md:col-span-2">
                <div class="brand-link">
                    <img
                        src="{{ asset('images/logo-mark.svg') }}"
                        class="brand-logo"
                        alt="Logo"
                    >
                    <strong>
                        {{ $siteSettings->get('site_name', 'iPhone Reseller') }}
                    </strong>
                </div>

                <p class="mt-4 max-w-md text-muted">
                    {{ $siteSettings->get(
                        'site_tagline',
                        'Stok iPhone terpercaya untuk mendukung pertumbuhan bisnis reseller Anda.'
                    ) }}
                </p>
            </div>

            <div>
                <h3 class="panel-title">Navigasi</h3>
                <div class="mt-4 grid gap-3 text-sm text-zinc-500">
                    <a href="{{ route('products.index') }}">Produk</a>
                    <a href="{{ route('articles.index') }}">Artikel</a>
                    <a href="{{ route('faq') }}">FAQ</a>
                </div>
            </div>

            <div>
                <h3 class="panel-title">Kontak</h3>
                <div class="mt-4 grid gap-3 text-sm text-zinc-500">
                    <span>{{ $siteSettings->get('store_phone', '08xx-xxxx-xxxx') }}</span>
                    <span>{{ $siteSettings->get('store_email', 'hello@example.com') }}</span>
                    <span>{{ $siteSettings->get('store_address', 'Alamat toko Anda') }}</span>
                </div>
            </div>
        </div>

        <div class="border-t border-zinc-200">
            <div class="site-shell py-5 text-xs text-zinc-500">
                © {{ date('Y') }}
                {{ $siteSettings->get('site_name', 'iPhone Reseller') }}.
                Seluruh hak dilindungi.
            </div>
        </div>
    </footer>
</body>
</html>
