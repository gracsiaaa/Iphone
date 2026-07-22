@extends('layouts.admin')

@section('title', 'Artikel')
@section('heading', 'Artikel')
@section('eyebrow', 'Content management')

@section('content')
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.articles.create') }}" class="btn-primary">
            + Tulis Artikel
        </a>
    </div>

    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Publikasi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <strong>{{ $article->title }}</strong>
                                <div class="meta-text mt-1 max-w-lg truncate">
                                    {{ $article->excerpt }}
                                </div>
                            </td>
                            <td>{{ $article->author?->name ?: '-' }}</td>
                            <td>
                                <span
                                    @class([
                                        'badge',
                                        'badge-success' => $article->status === 'published',
                                        'badge-warning' => $article->status !== 'published',
                                    ])
                                >
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="text-zinc-500">
                                {{ optional($article->published_at)->format('d M Y H:i') ?: '-' }}
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a
                                        class="text-link"
                                        href="{{ route('admin.articles.edit', $article) }}"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        action="{{ route('admin.articles.destroy', $article) }}"
                                        method="POST"
                                        onsubmit="return confirm('Arsipkan artikel ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-link">Arsip</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">Belum ada artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $articles->links() }}</div>
@endsection
