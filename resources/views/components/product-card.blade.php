@props(['product'])

@php
    $ramSummary = $product->variants->pluck('ram')->unique()->implode(', ');
    $storageSummary = $product->variants->pluck('storage')->unique()->implode(', ');
@endphp

<article class="product-card">
    <a
        href="{{ route('products.show', $product) }}"
        class="product-image-wrap block aspect-square w-full"
    >
        <img
            src="{{ $product->primary_image_url }}"
            alt="{{ $product->name }}"
            class="h-full w-full bg-white object-contain p-4 sm:p-5"
        >

        <span
            @class([
                'badge absolute left-3 top-3',
                'badge-success' => $product->total_stock > 3,
                'badge-warning' => $product->total_stock > 0 && $product->total_stock <= 3,
                'badge-danger' => $product->total_stock < 1,
            ])
        >
            {{ $product->total_stock > 0 ? 'Stok '.$product->total_stock : 'Stok habis' }}
        </span>
    </a>

    <div class="product-content">
        <p class="product-meta">
            RAM {{ $ramSummary }} · Storage {{ $storageSummary }}
        </p>

        <h3 class="product-name">
            <a href="{{ route('products.show', $product) }}">
                {{ $product->name }}
            </a>
        </h3>

        <div class="product-footer">
            <div>
                <p class="meta-text">Harga reseller</p>
                <p class="mt-1 font-bold text-zinc-950">
                    {{ $product->formatted_price }}
                </p>
            </div>

            <a href="{{ route('products.show', $product) }}" class="btn-ghost !px-3">
                Pilih Varian
            </a>
        </div>
    </div>
</article>
