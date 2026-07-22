<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $order->invoice_number }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="invoice-body">
    <button
        type="button"
        onclick="window.print()"
        class="btn-primary print-hidden mx-auto mb-5 flex"
    >
        Cetak / Simpan PDF
    </button>

    <main class="invoice-page">
        <header class="flex flex-col justify-between gap-8 border-b-2 border-zinc-950 pb-7 sm:flex-row">
            <div class="flex items-center gap-4">
                <img
                    src="{{ asset('images/logo-mark.svg') }}"
                    class="h-14 w-14"
                    alt="Logo"
                >
                <div>
                    <h1 class="text-xl font-bold">
                        {{ $siteSettings->get('site_name', 'iPhone Reseller') }}
                    </h1>
                    <p class="text-muted mt-1 max-w-sm">
                        {{ $siteSettings->get('store_address', 'Alamat toko Anda') }}<br>
                        {{ $siteSettings->get('store_phone', '') }}
                        @if($siteSettings->get('store_email', ''))
                            · {{ $siteSettings->get('store_email') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="sm:text-right">
                <p class="eyebrow">Dokumen transaksi</p>
                <h2 class="mt-2 text-3xl font-bold">INVOICE</h2>
                <p class="text-muted mt-2">
                    {{ $order->invoice_number }}<br>
                    {{ $order->created_at->format('d F Y, H:i') }}
                </p>
            </div>
        </header>

        <section class="mt-8 grid gap-5 sm:grid-cols-2">
            <div class="surface-soft p-5">
                <h3 class="panel-title">Ditagihkan kepada</h3>
                <div class="text-muted mt-3">
                    <strong class="text-zinc-950">{{ $order->customer_name }}</strong><br>
                    {{ $order->customer_store_name }}<br>
                    {{ $order->customer_email }}<br>
                    {{ $order->customer_phone }}<br>
                    {{ $order->billing_address }}
                </div>
            </div>

            <div class="surface-soft p-5">
                <h3 class="panel-title">Status pembayaran</h3>
                <div class="mt-3">
                    <x-order-badge :status="$order->status" />
                </div>
                <p class="text-muted mt-3">
                    Metode: QRIS<br>
                    Diverifikasi:
                    {{ optional($order->verified_at)->format('d M Y H:i') ?: '-' }}
                </p>
            </div>
        </section>

        <section class="table-shell mt-8 shadow-none">
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td><strong>{{ $item->product_name }}</strong></td>
                                <td>
                                    {{ $item->product_capacity }} · {{ $item->product_color }}
                                </td>
                                <td class="text-right">
                                    {{ 'Rp'.number_format((float) $item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right">
                                    {{ 'Rp'.number_format((float) $item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="ml-auto mt-7 w-full max-w-sm">
            <div class="flex justify-between py-2 text-sm">
                <span class="text-zinc-500">Subtotal</span>
                <span>{{ $order->formatted_total }}</span>
            </div>
            <div class="flex justify-between border-t-2 border-zinc-950 py-3 text-lg font-bold">
                <span>Total</span>
                <span>{{ $order->formatted_total }}</span>
            </div>
        </section>

        @if($order->notes)
            <section class="surface-soft mt-8 p-5">
                <h3 class="panel-title">Catatan</h3>
                <p class="text-muted mt-2">{{ $order->notes }}</p>
            </section>
        @endif

        <footer class="mt-12 border-t border-zinc-200 pt-5 text-center text-xs text-zinc-500">
            Invoice ini dibuat otomatis oleh sistem
            {{ $siteSettings->get('site_name', 'iPhone Reseller') }}.
        </footer>
    </main>
</body>
</html>
