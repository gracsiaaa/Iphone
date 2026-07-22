@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
    <section class="page-section">
        <div class="site-shell flex min-h-[520px] items-center justify-center">
            <div class="empty-state max-w-lg">
                <p class="eyebrow">403</p>
                <h1 class="page-title mt-3">Akses ditolak</h1>
                <p class="body-copy mt-4">
                    Akun Anda tidak memiliki izin untuk membuka halaman ini.
                </p>
                <a href="{{ route('home') }}" class="btn-primary mt-6">Kembali ke Home</a>
            </div>
        </div>
    </section>
@endsection
