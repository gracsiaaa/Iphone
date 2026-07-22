@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Akun reseller</p>
                    <h1 class="page-title mt-2">Pesanan dan invoice</h1>
                </div>

                <a href="{{ route('products.index') }}" class="btn-primary">
                    Belanja Lagi
                </a>
            </div>

            <div class="table-shell mt-8">
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="font-bold">{{ $order->invoice_number }}</td>
                                    <td class="text-zinc-500">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td>{{ $order->formatted_total }}</td>
                                    <td><x-order-badge :status="$order->status" /></td>
                                    <td class="text-right">
                                        <a
                                            class="text-link"
                                            href="{{ route('orders.show', $order) }}"
                                        >
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="table-empty">
                                        Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">{{ $orders->links() }}</div>
        </div>
    </section>
@endsection
