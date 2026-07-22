@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('eyebrow', 'Ringkasan bisnis')

@section('content')
    @php
        $stats = [
            [
                'label' => 'Total Penjualan',
                'value' => 'Rp'.number_format((float) $totalSales, 0, ',', '.'),
                'caption' => 'Transaksi terverifikasi',
            ],
            [
                'label' => 'Pesanan Hari Ini',
                'value' => $todayOrders,
                'caption' => 'Invoice baru hari ini',
            ],
            [
                'label' => 'Menunggu Verifikasi',
                'value' => $waitingPayments,
                'caption' => 'Perlu diperiksa admin',
            ],
            [
                'label' => 'Produk Aktif',
                'value' => $activeProducts,
                'caption' => 'Stok yang tampil publik',
            ],
        ];
    @endphp

    <div class="stat-grid">
        @foreach($stats as $stat)
            <article class="stat-card">
                <p class="text-sm font-medium text-zinc-500">{{ $stat['label'] }}</p>
                <div class="stat-value">{{ $stat['value'] }}</div>
                <p class="meta-text mt-2">{{ $stat['caption'] }}</p>
            </article>
        @endforeach
    </div>

    <div class="mt-7 grid gap-7 xl:grid-cols-[minmax(0,1fr)_360px]">
        <section class="table-shell">
            <div class="panel-header">
                <div>
                    <h2 class="panel-title">Transaksi terbaru</h2>
                    <p class="meta-text mt-1">Aktivitas invoice terkini</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-link">
                    Lihat semua
                </a>
            </div>

            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Reseller</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a
                                        class="text-link"
                                        href="{{ route('admin.orders.show', $order) }}"
                                    >
                                        {{ $order->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->formatted_total }}</td>
                                <td><x-order-badge :status="$order->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="table-empty">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="space-y-7">
            <section class="surface panel-padding">
                <h2 class="panel-title">Ringkasan pengguna</h2>
                <div class="mt-5 flex items-end justify-between">
                    <div>
                        <div class="text-3xl font-bold">{{ $resellerCount }}</div>
                        <div class="text-sm text-zinc-500">Reseller terdaftar</div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ $articleCount }}</div>
                        <div class="text-xs text-zinc-500">Artikel</div>
                    </div>
                </div>
            </section>

            <section class="surface panel-padding">
                <div class="flex items-center justify-between">
                    <h2 class="panel-title">Stok menipis</h2>
                    <a href="{{ route('admin.products.index') }}" class="text-link text-xs">
                        Kelola
                    </a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($lowStockProducts as $product)
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="truncate">
                                {{ $product->name }} {{ $product->capacity }}
                            </span>
                            <strong class="text-red-600">{{ $product->stock }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada stok menipis.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
