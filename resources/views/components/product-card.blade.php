@props(['product'])

<article class="product-card">
    <a
        href="{{ route('products.show', $product) }}"
        class="product-image-wrap block"
        style="position: relative; height: 18rem; width: 100%;"
    >
        <img
            src="{{ $product->primary_image_url }}" 
            alt="{{ $product->name }}"
            style="width: 100%; height: 100%; object-fit: contain; background-color: white; padding: 1rem;"
        >

        <span
            @class([
                'badge absolute left-3 top-3',
                'badge-success' => $product->stock > 3,
                'badge-warning' => $product->stock > 0 && $product->stock <= 3,
                'badge-danger' => $product->stock < 1,
            ])
        >
            {{ $product->stock > 0 ? 'Stok '.$product->stock : 'Stok habis' }}
        </span>
    </a>

    <div class="product-content">
        <p class="product-meta">
            {{ $product->capacity }} · {{ $product->color }}
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

            @auth
                <form action="{{ route('cart.store', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button
                        class="btn-ghost !px-3"
                        {{ $product->stock < 1 ? 'disabled' : '' }}
                    >
                        + Keranjang
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-link">
                    Login
                </a>
            @endauth
        </div>
    </div>
</article>