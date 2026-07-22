@extends('layouts.app')

@section('title', 'Article')

@section('content')
    <section class="hero-section">
        <div class="site-shell">
            <div class="surface-dark px-6 py-12 sm:px-10 sm:py-14">
                <p class="eyebrow-light">Insight & panduan</p>
                <h1 class="page-title mt-3 text-white">Article</h1>
                <p class="mt-4 max-w-2xl text-zinc-400">
                    Informasi produk, tips reseller, dan panduan transaksi dari toko kami.
                </p>
            </div>
        </div>
    </section>

    <section class="page-section pt-4">
        <div class="site-shell">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($articles as $article)
                    <article class="article-card">
                        <a href="{{ route('articles.show', $article) }}">
                            <img
                                src="{{ $article->thumbnail_url }}"
                                class="aspect-video w-full object-cover"
                                alt="{{ $article->title }}"
                            >
                        </a>

                        <div class="p-6">
                            <p class="meta-text">
                                {{ optional($article->published_at)->format('d M Y') }}
                                · {{ $article->author?->name }}
                            </p>

                            <h2 class="mt-2 text-xl font-bold leading-snug">
                                <a href="{{ route('articles.show', $article) }}">
                                    {{ $article->title }}
                                </a>
                            </h2>

                            <p class="text-muted mt-3 line-clamp-3">
                                {{ $article->excerpt }}
                            </p>

                            <a
                                href="{{ route('articles.show', $article) }}"
                                class="text-link mt-5 inline-block"
                            >
                                Baca artikel →
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="empty-state md:col-span-2 lg:col-span-3">
                        <h2 class="font-bold">Belum ada artikel</h2>
                        <p class="text-muted mt-2">Artikel yang dipublikasikan akan tampil di sini.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $articles->links() }}</div>
        </div>
    </section>
@endsection
