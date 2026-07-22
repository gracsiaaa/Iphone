@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('heading', 'Tambah Pengguna')
@section('eyebrow', 'Superadmin')

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        @include('admin.users._form')
    </form>
@endsection
