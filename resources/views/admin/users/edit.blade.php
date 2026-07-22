@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('heading', 'Edit Pengguna')
@section('eyebrow', $user->email)

@section('content')
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.users._form')
    </form>
@endsection
