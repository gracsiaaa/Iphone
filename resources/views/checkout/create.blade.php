@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="mb-8">
                <p class="eyebrow">Checkout</p>
                <h1 class="page-title mt-2">Konfirmasi data dan buat invoice</h1>
            </div>

            <form
                action="{{ route('checkout.store') }}"
                method="POST"
                class="split-layout"
            >
                @csrf

                <section class="surface form-grid p-6 sm:p-8">
                    <div class="field-full">
                        <h2 class="panel-title">Data reseller</h2>
                        <p class="text-muted mt-1">
                            Data ini akan dicetak pada invoice transaksi.
                        </p>
                    </div>

                    <div class="field">
                        <label class="label" for="customer-name">Nama lengkap</label>
                        <input
                            id="customer-name"
                            class="input"
                            name="customer_name"
                            value="{{ old('customer_name', auth()->user()->name) }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="customer-store-name">Nama toko</label>
                        <input
                            id="customer-store-name"
                            class="input"
                            name="customer_store_name"
                            value="{{ old(
                                'customer_store_name',
                                auth()->user()->store_name
                            ) }}"
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="customer-email">Email</label>
                        <input
                            id="customer-email"
                            class="input"
                            type="email"
                            name="customer_email"
                            value="{{ old('customer_email', auth()->user()->email) }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label class="label" for="customer-phone">Nomor WhatsApp</label>
                        <input
                            id="customer-phone"
                            class="input"
                            name="customer_phone"
                            value="{{ old('customer_phone', auth()->user()->phone) }}"
                            required
                        >
                    </div>

                    <div class="field field-full">
                        <label class="label" for="billing-address">Alamat</label>
                        <textarea
                            id="billing-address"
                            class="input min-h-28"
                            name="billing_address"
                            required
                        >{{ old('billing_address', auth()->user()->address) }}</textarea>
                    </div>

                    <div class="field field-full">
                        <label class="label" for="notes">
                            Catatan pesanan
                            <span class="font-normal text-zinc-400">(opsional)</span>
                        </label>
                        <textarea
                            id="notes"
                            class="input min-h-24"
                            name="notes"
                        >{{ old('notes') }}</textarea>
                    </div>
                </section>

                <aside class="surface h-fit panel-padding">
                    <h2 class="panel-title">Rincian pesanan</h2>

                    <div class="mt-5 space-y-4">
                        @foreach($items as $item)
                            <div class="flex justify-between gap-4 text-sm">
                                <div>
                                    <strong>{{ $item['product']->name }}</strong>
                                    <p class="text-zinc-500">
                                        {{ $item['product']->capacity }} ·
                                        {{ $item['product']->color }} ×
                                        {{ $item['quantity'] }}
                                    </p>
                                </div>
                                <span>
                                    {{ 'Rp'.number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-between border-t border-zinc-200 pt-5">
                        <span class="font-bold">Total</span>
                        <strong class="text-xl">
                            {{ 'Rp'.number_format($total, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="alert alert-info mt-5 text-xs">
                        Stok akan dicadangkan ketika invoice dibuat. Setelah itu, lakukan
                        pembayaran QRIS dan kirim konfirmasi.
                    </div>

                    <button class="btn-primary mt-5 w-full">Buat Invoice</button>
                </aside>
            </form>
        </div>
    </section>
@endsection
