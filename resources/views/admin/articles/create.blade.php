@extends('layouts.admin')

@section('title', 'Tulis Artikel')
@section('heading', 'Tulis Artikel')
@section('eyebrow', 'Content management')

@section('content')
    <form
        action="{{ route('admin.articles.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @include('admin.articles._form')
    </form>
@endsection
