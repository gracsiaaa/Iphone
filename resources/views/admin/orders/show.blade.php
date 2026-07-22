@extends('layouts.admin')

@section('title', $order->invoice_number)
@section('heading', $order->invoice_number)
@section('eyebrow', 'Detail pesanan')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <x-order-badge :status="$order->status" />
        <a
            href="{{ route('orders.invoice', $order) }}"
            target="_blank"
            class="btn-secondary !py-2.5"
        >
            Cetak Invoice
        </a>
    </div>

    <div class="editor-layout">
        <div class="space-y-6">
            <section class="surface overflow-hidden">
                <div class="panel-header">
                    <h2 class="panel-title">Item pesanan</h2>
                </div>

                <div class="divide-y divide-zinc-100">
                    @foreach($order->items as $item)
                        <div class="flex justify-between gap-5 p-5 sm:p-6">
                            <div>
                                <strong>{{ $item->product_name }}</strong>
                                <p class="text-muted mt-1">
                                    {{ $item->product_capacity }} ·
                                    {{ $item->product_color }} ·
                                    {{ $item->quantity }} unit
                                </p>
                            </div>
                            <strong class="text-right">
                                {{ 'Rp'.number_format((float) $item->subtotal, 0, ',', '.') }}
                            </strong>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between border-t border-zinc-200 bg-zinc-50 p-6">
                    <strong>Total</strong>
                    <strong class="text-xl">{{ $order->formatted_total }}</strong>
                </div>
            </section>

            <section class="surface panel-padding">
                <h2 class="panel-title">Informasi reseller</h2>

                <div class="info-grid mt-5">
                    <div>
                        <span class="info-label">Nama</span>
                        <div class="info-value">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <span class="info-label">Nama toko</span>
                        <div class="info-value">
                            {{ $order->customer_store_name ?: '-' }}
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Email</span>
                        <div class="info-value">{{ $order->customer_email }}</div>
                    </div>
                    <div>
                        <span class="info-label">WhatsApp</span>
                        <div class="info-value">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="info-label">Alamat</span>
                        <div class="info-value">{{ $order->billing_address }}</div>
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="surface panel-padding">
                <h2 class="panel-title">Pembayaran QRIS</h2>

                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Status payment</dt>
                        <dd class="font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $order->payment?->status ?? '-')) }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Konfirmasi user</dt>
                        <dd>
                            {{ optional($order->payment?->confirmed_at)->format('d M H:i') ?: '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Diverifikasi</dt>
                        <dd>
                            {{ optional($order->verified_at)->format('d M H:i') ?: '-' }}
                        </dd>
                    </div>
                </dl>

                @if($order->payment?->proof_url)
                    <a href="{{ $order->payment->proof_url }}" target="_blank">
                        <img
                            src="{{ $order->payment->proof_url }}"
                            class="mt-5 max-h-72 w-full rounded-xl border border-zinc-200 object-contain"
                            alt="Bukti pembayaran"
                        >
                    </a>
                @else
                    <div class="surface-soft mt-5 p-4 text-center text-xs text-zinc-500">
                        User tidak mengunggah bukti pembayaran.
                    </div>
                @endif
            </section>

            @if($order->status === \App\Enums\OrderStatus::WAITING_VERIFICATION)
                <section class="surface space-y-5 panel-padding">
                    <h2 class="panel-title">Tindakan Admin</h2>

                    <form
                        action="{{ route('admin.orders.approve', $order) }}"
                        method="POST"
                        class="space-y-3"
                    >
                        @csrf
                        <textarea
                            class="input min-h-20"
                            name="admin_note"
                            placeholder="Catatan verifikasi (opsional)"
                        ></textarea>
                        <button class="btn-primary w-full">Setujui Pembayaran</button>
                    </form>

                    <form
                        action="{{ route('admin.orders.reject', $order) }}"
                        method="POST"
                        class="space-y-3 border-t border-zinc-200 pt-5"
                        onsubmit="return confirm('Tolak pesanan dan kembalikan stok?')"
                    >
                        @csrf
                        <textarea
                            class="input min-h-20"
                            name="admin_note"
                            required
                            placeholder="Alasan penolakan"
                        ></textarea>
                        <button class="btn-danger w-full">Tolak Pembayaran</button>
                    </form>
                </section>
            @elseif($order->status === \App\Enums\OrderStatus::PENDING_PAYMENT)
                <section class="surface panel-padding">
                    <p class="text-muted">User belum mengirim konfirmasi pembayaran.</p>
                    <form
                        action="{{ route('admin.orders.reject', $order) }}"
                        method="POST"
                        class="mt-4 space-y-3"
                        onsubmit="return confirm('Batalkan invoice dan kembalikan stok?')"
                    >
                        @csrf
                        <textarea
                            class="input min-h-20"
                            name="admin_note"
                            required
                            placeholder="Alasan pembatalan"
                        ></textarea>
                        <button class="btn-danger w-full">Batalkan Invoice</button>
                    </form>
                </section>
            @elseif($order->status === \App\Enums\OrderStatus::PAID)
                <form
                    action="{{ route('admin.orders.complete', $order) }}"
                    method="POST"
                    class="surface panel-padding"
                >
                    @csrf
                    <p class="text-muted">
                        Pembayaran sudah terverifikasi. Tandai selesai setelah transaksi
                        dituntaskan.
                    </p>
                    <button class="btn-primary mt-4 w-full">Tandai Selesai</button>
                </form>
            @endif
        </aside>
    </div>
@endsection
