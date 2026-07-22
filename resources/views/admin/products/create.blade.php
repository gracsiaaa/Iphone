@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('heading', 'Tambah Produk')
@section('eyebrow', 'Produk & Stok')

@section('content')
    <form
        action="{{ route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @include('admin.products._form')
    </form>
@endsection
