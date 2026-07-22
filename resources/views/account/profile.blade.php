@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="content-shell max-w-3xl">
                <div class="mb-8">
                    <p class="eyebrow">Akun reseller</p>
                    <h1 class="page-title mt-3">Profile</h1>
                    <p class="body-copy mt-3">
                        Kelola identitas akun dan informasi toko Anda.
                    </p>
                </div>

                <form
                    action="{{ route('profile.update') }}"
                    method="POST"
                    class="surface form-grid p-7 sm:p-9"
                >
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label class="label" for="name">Nama lengkap</label>
                        <input
                            id="name"
                            class="input"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="username">Username</label>
                        <input
                            id="username"
                            class="input"
                            name="username"
                            value="{{ old('username', $user->username) }}"
                            minlength="4"
                            maxlength="30"
                            pattern="[a-z0-9._]+"
                            required
                        >
                        <p class="field-note">
                            Username dapat digunakan untuk login.
                        </p>
                    </div>

                    <div class="field">
                        <label class="label" for="email">Email / Gmail</label>
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
                        <label class="label" for="phone">
                            Nomor telepon
                            <span class="font-normal text-zinc-400">
                                (opsional)
                            </span>
                        </label>
                        <input
                            id="phone"
                            class="input"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="Dapat diisi saat checkout"
                        >
                    </div>

                    <div class="field field-full">
                        <label class="label" for="store-name">Nama toko</label>
                        <input
                            id="store-name"
                            class="input"
                            name="store_name"
                            value="{{ old('store_name', $user->store_name) }}"
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
                        </label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                        >
                        <p class="field-note">
                            Kosongkan jika password tidak diubah.
                        </p>
                    </div>

                    <div class="field">
                        <label class="label" for="password-confirmation">
                            Konfirmasi password baru
                        </label>
                        <input
                            id="password-confirmation"
                            class="input"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="field-full form-actions">
                        <button class="btn-primary">Simpan Profile</button>
                        <a href="{{ route('orders.index') }}" class="btn-secondary">
                            Lihat Pesanan
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
