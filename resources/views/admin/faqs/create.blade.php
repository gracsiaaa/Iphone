@extends('layouts.admin')

@section('title', 'Tambah FAQ')
@section('heading', 'Tambah FAQ')
@section('eyebrow', 'Superadmin')

@section('content')
    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        @include('admin.faqs._form')
    </form>
@endsection
