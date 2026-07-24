@extends('layouts.app')

@section('title', 'Daftar Reseller')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="content-shell max-w-2xl">
                <div class="mb-8 text-center">
                    <p class="eyebrow">Akun reseller</p>
                    <h1 class="page-title mt-3">Buat akun reseller</h1>
                    <p class="body-copy mt-3">
                        Anda dapat langsung checkout setelah pendaftaran selesai.
                    </p>
                </div>
            
                @if ($errors->any())
                    <div class="mb-8 p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="font-bold text-sm text-red-600">Periksa kembali data berikut:</p>
                        <ul class="text-sm text-red-500 list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ route('register.store') }}"
                    method="POST"
                    class="surface form-grid p-7 sm:p-9"
                >
                    @csrf

                    <div class="field">
                        <label class="label" for="name">Nama lengkap</label>
                        <input
                            id="name"
                            class="input"
                            name="name"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="store-name">
                            Nama toko <span class="font-normal text-zinc-400">(opsional)</span>
                        </label>
                        <input
                            id="store-name"
                            class="input"
                            name="store_name"
                            value="{{ old('store_name') }}"
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input
                            id="email"
                            class="input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="phone">Nomor WhatsApp</label>
                        <input
                            id="phone"
                            class="input"
                            name="phone"
                            value="{{ old('phone') }}"
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
                        >{{ old('address') }}</textarea>
                    </div>

                    <div class="field">
                        <label class="label" for="password">Password</label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            name="password"
                            required
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
                            required
                        >
                    </div>

                    <div class="field-full">
                        <button class="btn-primary w-full">Buat Akun</button>
                        <p class="mt-4 text-center text-sm text-zinc-500">
                            Sudah memiliki akun?
                            <a class="font-bold text-zinc-950" href="{{ route('login') }}">
                                Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
