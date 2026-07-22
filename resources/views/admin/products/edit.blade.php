@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('heading', 'Edit Produk')
@section('eyebrow', $product->name)

@section('content')
    <form
        action="{{ route('admin.products.update', $product) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')
        @include('admin.products._form')
    </form>

    @foreach($product->images as $image)
        <form
            id="delete-image-{{ $image->id }}"
            action="{{ route('admin.product-images.destroy', $image) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
