@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <section class="page-section">
        <div class="site-shell flex min-h-[560px] items-center justify-center">
            <div class="surface w-full max-w-md p-7 sm:p-9">
                <div class="text-center">
                    <img
                        src="{{ asset('images/logo-mark.svg') }}"
                        class="mx-auto h-12 w-12"
                        alt="Logo"
                    >
                    <h1 class="mt-5 text-2xl font-bold">Masuk ke akun</h1>
                    <p class="text-muted mt-2">
                        Checkout dan pantau invoice reseller Anda.
                    </p>
                </div>

                <!-- Error -->

                @if ($errors->any())
                    <div class="mt-6 p-4 bg-red-50 rounded-md border border-red-200">
                        <p class="font-bold text-sm text-red-600">Gagal masuk:</p>
                        <ul class="text-sm text-red-500 list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-5">
                    @csrf

                    <div class="field">
                        <label class="label" for="email">Email</label>
                        <input
                            id="email"
                            class="input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="password">Password</label>
                        <input
                            id="password"
                            class="input"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <label class="check-row">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>

                    <button class="btn-primary w-full">Login</button>
                </form>

                <p class="mt-6 text-center text-sm text-zinc-500">
                    Belum memiliki akun?
                    <a class="font-bold text-zinc-950" href="{{ route('register') }}">
                        Daftar reseller
                    </a>
                </p>
            </div>
        </div>
    </section>
@endsection
