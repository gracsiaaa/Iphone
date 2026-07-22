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
                        Gunakan username atau email yang sudah terdaftar.
                    </p>
                </div>

                <form
                    action="{{ route('login.store') }}"
                    method="POST"
                    class="mt-8 space-y-5"
                >
                    @csrf

                    <div class="field">
                        <label class="label" for="login">
                            Username atau Email
                        </label>
                        <input
                            id="login"
                            class="input"
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            placeholder="username atau nama@gmail.com"
                            autocomplete="username"
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
                    <a
                        class="font-bold text-zinc-950"
                        href="{{ route('register') }}"
                    >
                        Daftar reseller
                    </a>
                </p>
            </div>
        </div>
    </section>
@endsection
