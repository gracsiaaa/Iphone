@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('heading', 'Edit FAQ')
@section('eyebrow', 'Superadmin')

@section('content')
    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.faqs._form')
    </form>
@endsection
