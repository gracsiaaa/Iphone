@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="grid gap-8 lg:grid-cols-[260px_minmax(0,1fr)]">
                <aside class="account-sidebar">
                    <div class="rounded-xl bg-zinc-950 p-5 text-white">
                        <p class="text-xs font-medium text-zinc-400">Akun Reseller</p>
                        <p class="mt-1 font-bold">{{ $user->name }}</p>
                        <p class="mt-1 break-all text-xs text-zinc-400">{{ $user->email }}</p>
                    </div>

                    <nav class="account-nav">
                        <a href="{{ route('profile') }}" class="account-link account-link-active">
                            Profile
                        </a>
                        <a href="{{ route('orders.index') }}" class="account-link">
                            Pesanan & Invoice
                        </a>
                        <a href="{{ route('cart.index') }}" class="account-link">
                            Keranjang
                        </a>
                    </nav>
                </aside>

                <form
                    action="{{ route('profile.update') }}"
                    method="POST"
                    class="surface form-grid p-6 sm:p-8"
                >
                    @csrf
                    @method('PUT')

                    <div class="field-full">
                        <p class="eyebrow">Pengaturan akun</p>
                        <h1 class="section-title mt-2">Profile Reseller</h1>
                        <p class="text-muted mt-2">
                            Perbarui informasi yang digunakan saat checkout.
                        </p>
                    </div>

                    <div class="field">
                        <label class="label" for="name">Nama</label>
                        <input
                            id="name"
                            class="input"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="store-name">Nama toko</label>
                        <input
                            id="store-name"
                            class="input"
                            name="store_name"
                            value="{{ old('store_name', $user->store_name) }}"
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input
                            id="email"
                            class="input"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="phone">Nomor WhatsApp</label>
                        <input
                            id="phone"
                            class="input"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                        >
                    </div>

                    <div class="field field-full">
                        <label class="label" for="address">Alamat</label>
                        <textarea
                            id="address"
                            class="input min-h-28"
                            name="address"
                            required
                        >{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="field">
                        <label class="label" for="password">
                            Password baru
                            <span class="font-normal text-zinc-400">(opsional)</span>
                        </label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            name="password"
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="password-confirmation">
                            Konfirmasi password
                        </label>
                        <input
                            id="password-confirmation"
                            class="input"
                            type="password"
                            name="password_confirmation"
                        >
                    </div>

                    <div class="field-full form-actions">
                        <button class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
