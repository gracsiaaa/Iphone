@extends('layouts.admin')

@section('title', 'Edit Artikel')
@section('heading', 'Edit Artikel')
@section('eyebrow', $article->title)

@section('content')
    <form
        action="{{ route('admin.articles.update', $article) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')
        @include('admin.articles._form')
    </form>
@endsection
