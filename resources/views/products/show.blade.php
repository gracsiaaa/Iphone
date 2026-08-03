@extends('layouts.app')

@section('title', $product->name.' '.$product->color)

@section('content')
    @php
        $initialVariant = $product->variants->first(fn ($variant) => $variant->stock > 0)
            ?? $product->variants->first();
        $ramOptions = $product->variants->pluck('ram')->unique()->values();
    @endphp

    <section class="page-section">
        <div class="site-shell">
            <nav class="mb-6 text-sm text-zinc-500" aria-label="Breadcrumb">
                <a href="{{ route('products.index') }}" class="hover:text-zinc-950">
                    Product
                </a>
                <span class="mx-2">/</span>
                <span>{{ $product->name }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-2 lg:gap-14">
                <div class="surface overflow-hidden bg-zinc-100">
                    <img
                        src="{{ $product->primary_image_url }}"
                        alt="{{ $product->name }}"
                        class="aspect-square w-full bg-white object-contain p-5 sm:p-8 lg:min-h-[450px]"
                    >
                </div>

                <div class="flex flex-col justify-center">
                    <p class="eyebrow">{{ $product->type }}</p>
                    <h1 class="page-title mt-3">{{ $product->name }}</h1>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="badge badge-neutral">{{ $product->color }}</span>
                        <span id="variant-stock-badge" class="badge badge-neutral">
                            Pilih variasi
                        </span>
                    </div>

                    <div id="variant-price" class="mt-8 text-3xl font-bold tracking-tight">
                        {{ $initialVariant->formatted_price }}
                    </div>

                    <div class="mt-8 space-y-6">
                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <label class="font-semibold text-zinc-950">1. Pilih RAM</label>
                                <span id="selected-ram-label" class="text-sm text-zinc-500"></span>
                            </div>

                            <div id="ram-options" class="mt-3 flex flex-wrap gap-2">
                                @foreach ($ramOptions as $ram)
                                    <button
                                        type="button"
                                        data-ram="{{ $ram }}"
                                        class="ram-option rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:border-zinc-950"
                                    >
                                        {{ $ram }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-4">
                                <label class="font-semibold text-zinc-950">2. Pilih Storage</label>
                                <span id="selected-storage-label" class="text-sm text-zinc-500"></span>
                            </div>

                            <div id="storage-options" class="mt-3 flex flex-wrap gap-2"></div>
                        </div>

                        <div class="rounded-xl bg-zinc-50 p-4 text-sm text-zinc-600">
                            Varian terpilih:
                            <strong id="selected-variant-label" class="text-zinc-950"></strong>
                        </div>
                    </div>

                    <p class="body-copy mt-6">
                        {{ $product->description
                            ?: 'Pilih RAM dan storage untuk melihat harga serta stok varian yang tersedia.' }}
                    </p>

                    @auth
                        <form
                            action="{{ route('cart.store', $product) }}"
                            method="POST"
                            class="mt-8 flex max-w-md flex-col gap-3 min-[420px]:flex-row"
                        >
                            @csrf
                            <input
                                id="selected-variant-id"
                                type="hidden"
                                name="variant_id"
                                value="{{ $initialVariant->id }}"
                            >
                            <input
                                id="variant-quantity"
                                class="input w-full min-[420px]:w-28"
                                type="number"
                                name="quantity"
                                min="1"
                                max="{{ max(1, $initialVariant->stock) }}"
                                value="1"
                            >
                            <button
                                id="add-to-cart-button"
                                class="btn-primary flex-1"
                                {{ $initialVariant->stock < 1 ? 'disabled' : '' }}
                            >
                                Tambahkan ke Keranjang
                            </button>
                        </form>
                    @else
                        <div class="mt-8">
                            <a href="{{ route('login') }}" class="btn-primary">
                                Login untuk Membeli
                            </a>
                            <p class="field-note">
                                Kamu tetap dapat melihat harga semua variasi tanpa login.
                            </p>
                        </div>
                    @endauth

                    <div class="mt-8 grid gap-3 border-t border-zinc-200 pt-6 text-sm text-zinc-600">
                        <div>✓ Harga berubah sesuai RAM dan storage</div>
                        <div>✓ Stok dihitung per kombinasi varian</div>
                        <div>✓ Invoice mencatat varian yang dipilih</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="page-section pt-0">
            <div class="site-shell">
                <h2 class="section-title">Produk terkait</h2>
                <div class="product-grid mt-7">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const variants = @js($variantData);
            const initialVariantId = Number(@js($initialVariant->id));
            const ramButtons = Array.from(document.querySelectorAll('.ram-option'));
            const storageContainer = document.getElementById('storage-options');
            const priceElement = document.getElementById('variant-price');
            const stockBadge = document.getElementById('variant-stock-badge');
            const ramLabel = document.getElementById('selected-ram-label');
            const storageLabel = document.getElementById('selected-storage-label');
            const variantLabel = document.getElementById('selected-variant-label');
            const variantInput = document.getElementById('selected-variant-id');
            const quantityInput = document.getElementById('variant-quantity');
            const cartButton = document.getElementById('add-to-cart-button');
            const initialVariant = variants.find((variant) => variant.id === initialVariantId) ?? variants[0];
            let selectedRam = initialVariant.ram;
            let selectedStorage = initialVariant.storage;

            const optionClasses = {
                normal: ['border-zinc-300', 'text-zinc-700'],
                active: ['border-zinc-950', 'bg-zinc-950', 'text-white'],
            };

            const setActiveState = (button, active) => {
                button.classList.remove(...optionClasses.normal, ...optionClasses.active);
                button.classList.add(...(active ? optionClasses.active : optionClasses.normal));
            };

            const updateVariantDetails = () => {
                const selectedVariant = variants.find(
                    (variant) => variant.ram === selectedRam && variant.storage === selectedStorage
                );

                if (!selectedVariant) {
                    return;
                }

                priceElement.textContent = selectedVariant.formatted_price;
                ramLabel.textContent = selectedVariant.ram;
                storageLabel.textContent = selectedVariant.storage;
                variantLabel.textContent = `RAM ${selectedVariant.ram} / ${selectedVariant.storage}`;

                stockBadge.textContent = selectedVariant.stock > 0
                    ? `Stok ${selectedVariant.stock} unit`
                    : 'Stok habis';
                stockBadge.className = selectedVariant.stock > 0
                    ? 'badge badge-success'
                    : 'badge badge-danger';

                if (variantInput) {
                    variantInput.value = selectedVariant.id;
                }

                if (quantityInput) {
                    quantityInput.max = Math.max(1, selectedVariant.stock);
                    quantityInput.value = Math.min(
                        Math.max(1, Number(quantityInput.value) || 1),
                        Math.max(1, selectedVariant.stock)
                    );
                    quantityInput.disabled = selectedVariant.stock < 1;
                }

                if (cartButton) {
                    cartButton.disabled = selectedVariant.stock < 1;
                    cartButton.textContent = selectedVariant.stock > 0
                        ? 'Tambahkan ke Keranjang'
                        : 'Stok Habis';
                }
            };

            const renderStorageOptions = () => {
                const availableStorages = variants.filter((variant) => variant.ram === selectedRam);
                const selectedVariant = availableStorages.find(
                    (variant) => variant.storage === selectedStorage
                );

                if (!selectedVariant || (selectedVariant.stock < 1 && availableStorages.some((variant) => variant.stock > 0))) {
                    selectedStorage = (
                        availableStorages.find((variant) => variant.stock > 0)
                        ?? availableStorages[0]
                    ).storage;
                }

                storageContainer.replaceChildren();

                availableStorages.forEach((variant) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = variant.storage;
                    button.className = 'rounded-xl border px-4 py-2.5 text-sm font-semibold transition';
                    button.disabled = variant.stock < 1;

                    if (variant.stock < 1) {
                        button.classList.add('cursor-not-allowed', 'border-zinc-200', 'text-zinc-400', 'line-through');
                    } else {
                        setActiveState(button, variant.storage === selectedStorage);
                    }

                    button.addEventListener('click', () => {
                        selectedStorage = variant.storage;
                        renderStorageOptions();
                        updateVariantDetails();
                    });

                    storageContainer.appendChild(button);
                });
            };

            ramButtons.forEach((button) => {
                setActiveState(button, button.dataset.ram === selectedRam);

                button.addEventListener('click', () => {
                    selectedRam = button.dataset.ram;
                    ramButtons.forEach((ramButton) => {
                        setActiveState(ramButton, ramButton.dataset.ram === selectedRam);
                    });
                    renderStorageOptions();
                    updateVariantDetails();
                });
            });

            renderStorageOptions();
            updateVariantDetails();
        });
    </script>
@endsection
