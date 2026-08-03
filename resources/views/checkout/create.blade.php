@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="container-site py-10 lg:py-14">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">
            Checkout reseller
        </p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-950 lg:text-4xl">
            Selesaikan Pesanan
        </h1>
    </div>

    <form
        action="{{ route('checkout.store') }}"
        method="POST"
        class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_340px] xl:grid-cols-[minmax(0,1fr)_390px]"
    >
        @csrf

        <div class="card p-5 sm:p-6 lg:p-8">
            <h2 class="text-xl font-bold text-zinc-950">Informasi Reseller</h2>
            <p class="mt-2 text-sm text-zinc-500">
                Pastikan data di bawah benar untuk pencatatan invoice.
            </p>

            <div class="mt-7 grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="customer_name" class="label">Nama lengkap</label>
                    <input
                        id="customer_name"
                        name="customer_name"
                        class="input"
                        value="{{ old('customer_name', auth()->user()->name) }}"
                        required
                    >
                </div>

                <div>
                    <label for="customer_store_name" class="label">Nama toko</label>
                    <input
                        id="customer_store_name"
                        name="customer_store_name"
                        class="input"
                        value="{{ old('customer_store_name', auth()->user()->store_name) }}"
                    >
                </div>

                <div>
                    <label for="customer_email" class="label">Email</label>
                    <input
                        id="customer_email"
                        type="email"
                        name="customer_email"
                        class="input"
                        value="{{ old('customer_email', auth()->user()->email) }}"
                        required
                    >
                </div>

                <div>
                    <label for="customer_phone" class="label">Nomor WhatsApp</label>
                    <input
                        id="customer_phone"
                        name="customer_phone"
                        class="input"
                        value="{{ old('customer_phone', auth()->user()->phone) }}"
                        required
                    >
                </div>

                <div class="sm:col-span-2">
                    <label for="billing_address" class="label">Alamat</label>
                    <textarea
                        id="billing_address"
                        name="billing_address"
                        class="input min-h-32"
                        required
                    >{{ old('billing_address', auth()->user()->address) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="label">Catatan pesanan</label>
                    <textarea
                        id="notes"
                        name="notes"
                        class="input min-h-28"
                        placeholder="Catatan tambahan untuk Admin (opsional)"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <aside>
            <div class="card overflow-hidden lg:sticky lg:top-24">
                <div class="border-b border-zinc-200 px-6 py-5">
                    <h2 class="text-lg font-bold text-zinc-950">Ringkasan Pesanan</h2>
                </div>

                <div class="max-h-80 divide-y divide-zinc-100 overflow-y-auto">
                    @foreach ($items as $item)
                        <div class="flex gap-4 p-5">
                            <img
                                src="{{ $item['product']->primary_image_url }}"
                                alt="{{ $item['product']->name }}"
                                class="h-16 w-16 shrink-0 rounded-xl bg-zinc-100 object-contain p-2"
                            >

                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-zinc-950">
                                    {{ $item['product']->name }}
                                </p>
                                <p class="mt-1 text-xs text-zinc-500">
                                    {{ $item['product']->capacity }} ·
                                    {{ $item['product']->color }} ·
                                    {{ $item['quantity'] }} unit
                                </p>
                                <p class="mt-2 text-sm font-semibold">
                                    {{ \App\Support\Money::rupiah($item['subtotal']) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4 bg-zinc-50 p-6 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-zinc-500">Jumlah barang</span>
                        <strong>{{ $totalQuantity }} unit</strong>
                    </div>

                    <div class="flex justify-between gap-4 border-t border-zinc-200 pt-4">
                        <span class="font-semibold text-zinc-900">Total pembayaran</span>
                        <strong class="text-xl text-zinc-950">
                            {{ \App\Support\Money::rupiah($total) }}
                        </strong>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Buat Invoice
                    </button>

                    <a
                        href="{{ route('cart.index') }}"
                        class="block text-center font-semibold text-zinc-600"
                    >
                        Kembali ke keranjang
                    </a>
                </div>
            </div>
        </aside>
    </form>
</section>
@endsection
