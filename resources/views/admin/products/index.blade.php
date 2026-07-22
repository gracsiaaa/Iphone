@extends('layouts.admin')

@section('title', 'Produk')
@section('heading', 'Produk & Stok')
@section('eyebrow', 'Katalog')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" class="flex w-full max-w-lg gap-2">
            <input
                class="input"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari produk..."
            >
            <button class="btn-secondary !py-2.5">Cari</button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            + Tambah Produk
        </a>
    </div>

    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Varian</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img
                                        class="h-12 w-12 rounded-xl bg-zinc-100 object-cover"
                                        src="{{ $product->primary_image_url }}"
                                        alt="{{ $product->name }}"
                                    >
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <div class="meta-text">{{ $product->sku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->capacity }} · {{ $product->color }}</td>
                            <td>{{ $product->formatted_price }}</td>
                            <td>
                                <strong @class(['text-red-600' => $product->stock <= 3])>
                                    {{ $product->stock }}
                                </strong>
                            </td>
                            <td>
                                <span
                                    @class([
                                        'badge',
                                        'badge-success' => $product->is_active,
                                        'badge-neutral' => ! $product->is_active,
                                    ])
                                >
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a
                                        class="text-link"
                                        href="{{ route('admin.products.edit', $product) }}"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        onsubmit="return confirm('Arsipkan produk ini?')"
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
                            <td colspan="6" class="table-empty">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection
