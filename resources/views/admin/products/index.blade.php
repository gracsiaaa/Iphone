@extends('layouts.admin')

@section('title', 'Produk')
@section('heading', 'Produk & Stok')
@section('eyebrow', 'Katalog')

@section('content')
    <div class="mb-6 space-y-3 lg:flex lg:items-center lg:justify-between lg:space-y-0">
        <form
            method="GET"
            action="{{ route('admin.products.index') }}"
            class="flex w-full items-center gap-2 lg:max-w-xl"
        >
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="input min-w-0 flex-1"
                placeholder="Cari produk..."
            >

            <button
                type="submit"
                class="btn-secondary shrink-0 !px-5 !py-2.5"
            >
                Cari
            </button>
        </form>

        <a
            href="{{ route('admin.products.create') }}"
            class="btn-primary flex w-full items-center justify-center lg:w-auto"
        >
            + Tambah Produk
        </a>
    </div>

    <div class="space-y-4 md:hidden">
        @forelse($products as $product)
            <article class="card overflow-hidden p-4">
                <div class="flex items-start gap-4">
                    <img
                        src="{{ $product->primary_image_url }}"
                        alt="{{ $product->name }}"
                        class="h-20 w-20 shrink-0 rounded-xl bg-zinc-100 object-contain"
                    >

                    <div class="min-w-0 flex-1">
                        <h2 class="break-words font-bold text-zinc-950">
                            {{ $product->name }}
                        </h2>

                        <p class="mt-1 text-sm text-zinc-500">
                            {{ $product->color }}
                        </p>

                        <span
                            @class([
                                'badge mt-3',
                                'badge-success' => $product->is_active,
                                'badge-neutral' => ! $product->is_active,
                            ])
                        >
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <div class="mt-5 border-t border-zinc-200 pt-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">
                        Variasi RAM / Storage
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse($product->variants as $variant)
                            <div class="rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-center">
                                <p class="text-sm font-semibold text-zinc-900">
                                    {{ $variant->ram }} RAM
                                </p>

                                <p class="mt-0.5 text-xs text-zinc-500">
                                    {{ $variant->storage }}
                                </p>

                                <p
                                    @class([
                                        'mt-1 text-xs font-semibold',
                                        'text-red-600' => $variant->stock <= 3,
                                        'text-zinc-500' => $variant->stock > 3,
                                    ])
                                >
                                    Stok {{ $variant->stock }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm font-semibold text-red-600">
                                Belum ada variasi aktif
                            </p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-zinc-50 p-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">
                            Rentang Harga
                        </p>

                        <p class="mt-2 break-words text-sm font-bold text-zinc-950">
                            {{ $product->formatted_price }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-zinc-50 p-3 text-center">
                        <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">
                            Total Stok
                        </p>

                        <p
                            @class([
                                'mt-2 text-lg font-bold',
                                'text-red-600' => $product->total_stock <= 3,
                                'text-zinc-950' => $product->total_stock > 3,
                            ])
                        >
                            {{ $product->total_stock }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <a
                        href="{{ route('admin.products.edit', $product) }}"
                        class="btn-secondary flex items-center justify-center !py-2.5"
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

                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-xl border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                        >
                            Arsip
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="card p-8 text-center">
                <p class="text-sm text-zinc-500">
                    Belum ada produk.
                </p>
            </div>
        @endforelse
    </div>

    <div class="table-shell hidden overflow-hidden md:block">
        <div class="table-scroll overflow-x-auto">
            <table class="data-table w-full min-w-[1000px]">
                <thead>
                    <tr>
                        <th class="text-left">
                            Produk
                        </th>

                        <th class="text-center">
                            Variasi RAM / Storage
                        </th>

                        <th class="text-center">
                            Rentang Harga
                        </th>

                        <th class="text-center">
                            Total Stok
                        </th>

                        <th class="text-center">
                            Status
                        </th>

                        <th class="text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ $product->primary_image_url }}"
                                        alt="{{ $product->name }}"
                                        class="h-14 w-14 shrink-0 rounded-xl bg-zinc-100 object-contain"
                                    >

                                    <div class="min-w-0">
                                        <strong class="block max-w-52 break-words text-zinc-950">
                                            {{ $product->name }}
                                        </strong>

                                        <div class="meta-text mt-1">
                                            {{ $product->color }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center align-middle">
                                <div class="flex max-w-sm flex-wrap justify-center gap-2">
                                    @forelse($product->variants as $variant)
                                        <div class="rounded-lg border border-zinc-200 bg-zinc-50 px-3 py-2 text-center">
                                            <p class="whitespace-nowrap text-sm font-semibold text-zinc-900">
                                                {{ $variant->ram }} RAM
                                            </p>

                                            <p class="mt-0.5 whitespace-nowrap text-xs text-zinc-500">
                                                {{ $variant->storage }}
                                            </p>

                                            <p
                                                @class([
                                                    'mt-1 text-xs font-semibold',
                                                    'text-red-600' => $variant->stock <= 3,
                                                    'text-zinc-400' => $variant->stock > 3,
                                                ])
                                            >
                                                Stok {{ $variant->stock }}
                                            </p>
                                        </div>
                                    @empty
                                        <span class="text-sm font-semibold text-red-600">
                                            Belum ada variasi aktif
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="text-center align-middle">
                                <span class="font-semibold text-zinc-950">
                                    {{ $product->formatted_price }}
                                </span>
                            </td>

                            <td class="text-center align-middle">
                                <strong
                                    @class([
                                        'text-base',
                                        'text-red-600' => $product->total_stock <= 3,
                                        'text-zinc-950' => $product->total_stock > 3,
                                    ])
                                >
                                    {{ $product->total_stock }}
                                </strong>
                            </td>

                            <td class="text-center align-middle">
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

                            <td class="text-center align-middle">
                                <div class="flex items-center justify-center gap-3">
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="text-link"
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

                                        <button
                                            type="submit"
                                            class="danger-link"
                                        >
                                            Arsip
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="table-empty py-10 text-center"
                            >
                                Belum ada produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
@endsection