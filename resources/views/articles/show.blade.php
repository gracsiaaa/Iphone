@extends('layouts.app')

@section('title', $article->meta_title ?: $article->title)
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')
    <article class="page-section pt-5">
        <div class="site-shell">
            <header class="surface-dark px-6 py-12 sm:px-10 sm:py-16">
                <a href="{{ route('articles.index') }}" class="text-sm font-bold text-blue-300">
                    ← Kembali ke Article
                </a>

                <h1 class="mt-6 max-w-4xl text-4xl font-bold leading-tight tracking-[-0.04em] sm:text-5xl">
                    {{ $article->title }}
                </h1>

                <p class="mt-5 text-sm text-zinc-400">
                    {{ optional($article->published_at)->format('d F Y') }}
                    · {{ $article->author?->name }}
                </p>
            </header>

            <div class="content-shell py-10 sm:py-12">
                <img
                    src="{{ $article->thumbnail_url }}"
                    class="aspect-video w-full rounded-2xl object-cover shadow-sm"
                    alt="{{ $article->title }}"
                >

                <div class="article-content mt-10">
                    {{ $article->content }}
                </div>
            </div>
        </div>
    </article>
@endsection
