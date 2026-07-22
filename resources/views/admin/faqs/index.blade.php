@extends('layouts.admin')

@section('title', 'FAQ')
@section('heading', 'FAQ')
@section('eyebrow', 'Superadmin')

@section('content')
    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary">
            + Tambah FAQ
        </a>
    </div>

    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pertanyaan</th>
                        <th>Kategori</th>
                        <th>Urutan</th>
                        <th>Aktif</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <td>
                                <strong>{{ $faq->question }}</strong>
                                <div class="meta-text mt-1 max-w-2xl truncate">
                                    {{ $faq->answer }}
                                </div>
                            </td>
                            <td>{{ $faq->category }}</td>
                            <td>{{ $faq->sort_order }}</td>
                            <td>
                                <span
                                    @class([
                                        'badge',
                                        'badge-success' => $faq->is_active,
                                        'badge-neutral' => ! $faq->is_active,
                                    ])
                                >
                                    {{ $faq->is_active ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a
                                        class="text-link"
                                        href="{{ route('admin.faqs.edit', $faq) }}"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        action="{{ route('admin.faqs.destroy', $faq) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus FAQ ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button class="danger-link">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">Belum ada FAQ.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $faqs->links() }}</div>
@endsection
