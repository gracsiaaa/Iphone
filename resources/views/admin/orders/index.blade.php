@extends('layouts.admin')

@section('title', 'Pesanan')
@section('heading', 'Pesanan & Pembayaran')
@section('eyebrow', 'Invoice management')

@section('content')
    <form method="GET" class="mb-6 flex max-w-sm gap-2">
        <select class="input" name="status">
            <option value="">Semua status</option>
            @foreach(\App\Enums\OrderStatus::cases() as $status)
                <option
                    value="{{ $status->value }}"
                    @selected(request('status') === $status->value)
                >
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        <button class="btn-secondary !py-2.5">Filter</button>
    </form>

    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Reseller</th>
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
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                                <div class="meta-text">
                                    {{ $order->customer_store_name ?: $order->customer_email }}
                                </div>
                            </td>
                            <td class="text-zinc-500">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="font-semibold">{{ $order->formatted_total }}</td>
                            <td><x-order-badge :status="$order->status" /></td>
                            <td class="text-right">
                                <a
                                    class="text-link"
                                    href="{{ route('admin.orders.show', $order) }}"
                                >
                                    Periksa →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="table-empty">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
