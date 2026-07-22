<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside id="admin-sidebar" class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img
                    src="{{ asset('images/logo-mark.svg') }}"
                    class="h-10 w-10 rounded-xl"
                    alt="Logo"
                >
                <div class="min-w-0">
                    <div class="truncate font-bold text-white">
                        {{ $siteSettings->get('site_name', 'iPhone Reseller') }}
                    </div>
                    <div class="text-xs text-zinc-400">
                        {{ auth()->user()->role->label() }} Panel
                    </div>
                </div>
            </a>

            <nav class="space-y-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    @class([
                        'admin-link',
                        'admin-link-active' => request()->routeIs('admin.dashboard'),
                    ])
                >
                    Dashboard
                </a>
                <a
                    href="{{ route('admin.products.index') }}"
                    @class([
                        'admin-link',
                        'admin-link-active' => request()->routeIs('admin.products.*'),
                    ])
                >
                    Produk & Stok
                </a>
                <a
                    href="{{ route('admin.orders.index') }}"
                    @class([
                        'admin-link',
                        'admin-link-active' => request()->routeIs('admin.orders.*'),
                    ])
                >
                    Pesanan & Pembayaran
                </a>
                <a
                    href="{{ route('admin.articles.index') }}"
                    @class([
                        'admin-link',
                        'admin-link-active' => request()->routeIs('admin.articles.*'),
                    ])
                >
                    Artikel
                </a>

                @if(auth()->user()->isSuperadmin())
                    <div class="admin-nav-label">Superadmin</div>
                    <a
                        href="{{ route('admin.users.index') }}"
                        @class([
                            'admin-link',
                            'admin-link-active' => request()->routeIs('admin.users.*'),
                        ])
                    >
                        Pengguna
                    </a>
                    <a
                        href="{{ route('admin.faqs.index') }}"
                        @class([
                            'admin-link',
                            'admin-link-active' => request()->routeIs('admin.faqs.*'),
                        ])
                    >
                        FAQ
                    </a>
                    <a
                        href="{{ route('admin.contacts.index') }}"
                        @class([
                            'admin-link',
                            'admin-link-active' => request()->routeIs('admin.contacts.*'),
                        ])
                    >
                        Pesan Kontak
                    </a>
                    <a
                        href="{{ route('admin.settings.edit') }}"
                        @class([
                            'admin-link',
                            'admin-link-active' => request()->routeIs('admin.settings.*'),
                        ])
                    >
                        Pengaturan Website
                    </a>
                    <a
                        href="{{ route('admin.activity-logs.index') }}"
                        @class([
                            'admin-link',
                            'admin-link-active' => request()->routeIs('admin.activity-logs.*'),
                        ])
                    >
                        Activity Log
                    </a>
                @endif
            </nav>

            <div class="mt-10 border-t border-white/10 pt-5">
                <a href="{{ route('home') }}" class="admin-link">Lihat Website</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="admin-link w-full text-left">Keluar</button>
                </form>
            </div>
        </aside>

        <section class="admin-content">
            <header class="admin-header">
                <div class="flex min-w-0 items-center">
                    <button
                        type="button"
                        data-toggle="admin-sidebar"
                        class="admin-mobile-button"
                    >
                        Menu
                    </button>
                    <div class="min-w-0">
                        <p class="truncate text-xs font-semibold uppercase tracking-wider text-zinc-500">
                            @yield('eyebrow', 'Management')
                        </p>
                        <h1 class="truncate text-xl font-bold tracking-tight">
                            @yield('heading', 'Dashboard')
                        </h1>
                    </div>
                </div>

                <div class="hidden text-right sm:block">
                    <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-zinc-500">{{ auth()->user()->email }}</div>
                </div>
            </header>

            <main class="admin-main">
                @if(session('success'))
                    <div data-flash class="alert alert-success mb-5">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div data-flash class="alert alert-danger mb-5">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-5">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </section>
    </div>
</body>
</html>
