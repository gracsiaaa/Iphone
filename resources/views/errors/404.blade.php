@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <section class="page-section">
        <div class="site-shell flex min-h-[520px] items-center justify-center">
            <div class="empty-state max-w-lg">
                <p class="eyebrow">404</p>
                <h1 class="page-title mt-3">Halaman tidak ditemukan</h1>
                <p class="body-copy mt-4">
                    Alamat yang Anda buka tidak tersedia atau sudah dipindahkan.
                </p>
                <a href="{{ route('home') }}" class="btn-primary mt-6">Kembali ke Home</a>
            </div>
        </div>
    </section>
@endsection
