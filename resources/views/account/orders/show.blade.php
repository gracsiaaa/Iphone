@extends('layouts.app')

@section('title', $order->invoice_number)

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="section-heading mb-7">
                <div>
                    <a href="{{ route('orders.index') }}" class="text-link text-zinc-500">
                        ← Pesanan Saya
                    </a>
                    <h1 class="page-title mt-2">{{ $order->invoice_number }}</h1>
                    <p class="text-muted mt-1">
                        Dibuat {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <x-order-badge :status="$order->status" />
                    <a
                        href="{{ route('orders.invoice', $order) }}"
                        target="_blank"
                        class="btn-secondary !py-2.5"
                    >
                        Cetak Invoice
                    </a>
                </div>
            </div>

            <div class="split-layout">
                <div class="stack-lg">
                    <section class="surface overflow-hidden">
                        <div class="panel-header">
                            <h2 class="panel-title">Produk</h2>
                        </div>

                        <div class="divide-y divide-zinc-100">
                            @foreach($order->items as $item)
                                <div class="flex flex-col justify-between gap-4 p-5 sm:flex-row sm:p-6">
                                    <div>
                                        <strong>{{ $item->product_name }}</strong>
                                        <p class="text-muted mt-1">
                                            {{ $item->product_capacity }} ·
                                            {{ $item->product_color }} ·
                                            {{ $item->quantity }} unit
                                        </p>
                                    </div>

                                    <div class="sm:text-right">
                                        <p>
                                            {{ 'Rp'.number_format((float) $item->price, 0, ',', '.') }}
                                        </p>
                                        <strong class="mt-1 block">
                                            {{ 'Rp'.number_format((float) $item->subtotal, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="surface panel-padding">
                        <h2 class="panel-title">Data pemesan</h2>

                        <dl class="info-grid mt-5">
                            <div>
                                <dt class="info-label">Nama</dt>
                                <dd class="info-value">{{ $order->customer_name }}</dd>
                            </div>
                            <div>
                                <dt class="info-label">Toko</dt>
                                <dd class="info-value">
                                    {{ $order->customer_store_name ?: '-' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="info-label">WhatsApp</dt>
                                <dd class="info-value">{{ $order->customer_phone }}</dd>
                            </div>
                            <div>
                                <dt class="info-label">Email</dt>
                                <dd class="info-value">{{ $order->customer_email }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="info-label">Alamat</dt>
                                <dd class="info-value">{{ $order->billing_address }}</dd>
                            </div>
                        </dl>
                    </section>
                </div>

                <aside class="stack-lg">
                    <section class="surface panel-padding">
                        <h2 class="panel-title">Ringkasan pembayaran</h2>

                        <div class="mt-5 flex justify-between text-sm">
                            <span class="text-zinc-500">Subtotal</span>
                            <span>{{ $order->formatted_total }}</span>
                        </div>

                        <div class="mt-5 flex justify-between border-t border-zinc-200 pt-5">
                            <strong>Total</strong>
                            <strong class="text-xl">{{ $order->formatted_total }}</strong>
                        </div>
                    </section>

                    @if($order->status === \App\Enums\OrderStatus::PENDING_PAYMENT)
                        <section class="surface panel-padding">
                            <h2 class="panel-title">Bayar dengan QRIS</h2>
                            <p class="text-muted mt-2">
                                Scan QRIS dan pastikan nominal sesuai total invoice.
                            </p>

                            <img
                                src="{{ $siteSettings->get('qris_path')
                                    ? \Illuminate\Support\Facades\Storage::url(
                                        $siteSettings->get('qris_path')
                                    )
                                    : asset('images/qris-placeholder.svg') }}"
                                class="mx-auto mt-5 w-full max-w-xs rounded-xl border border-zinc-200"
                                alt="QRIS"
                            >

                            <div class="alert alert-warning mt-4 text-xs">
                                {{ $siteSettings->get(
                                    'payment_instruction',
                                    'Setelah membayar, unggah bukti pembayaran dan klik konfirmasi.'
                                ) }}
                            </div>

                            <form
                                action="{{ route('orders.confirm-payment', $order) }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="mt-5 space-y-4"
                            >
                                @csrf

                                <div class="field">
                                    <label class="label" for="proof">
                                        Bukti pembayaran
                                        <span class="font-normal text-zinc-400">(opsional)</span>
                                    </label>
                                    <input
                                        id="proof"
                                        class="input"
                                        type="file"
                                        name="proof"
                                        accept="image/*"
                                    >
                                </div>

                                <div class="field">
                                    <label class="label" for="payment-note">Catatan</label>
                                    <textarea
                                        id="payment-note"
                                        class="input min-h-20"
                                        name="payment_note"
                                    ></textarea>
                                </div>

                                <button class="btn-primary w-full">Saya Sudah Bayar</button>
                            </form>
                        </section>
                    @elseif($order->status === \App\Enums\OrderStatus::WAITING_VERIFICATION)
                        <section class="alert alert-info">
                            <strong>Konfirmasi diterima</strong>
                            <p class="mt-2">
                                Admin sedang memeriksa pembayaran Anda. Status invoice akan
                                diperbarui setelah verifikasi.
                            </p>
                            @if($order->payment?->proof_url)
                                <a
                                    href="{{ $order->payment->proof_url }}"
                                    target="_blank"
                                    class="mt-3 inline-block font-bold"
                                >
                                    Lihat bukti pembayaran
                                </a>
                            @endif
                        </section>
                    @elseif(in_array($order->status, [
                        \App\Enums\OrderStatus::PAID,
                        \App\Enums\OrderStatus::COMPLETED,
                    ]))
                        <section class="alert alert-success">
                            <strong>Pembayaran terverifikasi</strong>
                            <p class="mt-2">
                                Diverifikasi pada
                                {{ optional($order->verified_at)->format('d M Y H:i') }}.
                            </p>
                        </section>
                    @else
                        <section class="alert alert-danger">
                            <strong>Pesanan tidak diproses</strong>
                            <p class="mt-2">
                                {{ $order->payment?->admin_note
                                    ?: 'Hubungi toko untuk informasi lebih lanjut.' }}
                            </p>
                        </section>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection
