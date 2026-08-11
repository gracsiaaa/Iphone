<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with(['user', 'payment'])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status'))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load([
            'user',
            'items',
            'payment',
            'verifier',
            'activityLogs' => fn ($query) => $query->with('user')->oldest('created_at'),
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function approve(Request $request, Order $order): RedirectResponse
    {
        abort_unless(
            $order->status === OrderStatus::WAITING_VERIFICATION,
            422,
            'Pesanan tidak sedang menunggu verifikasi.'
        );

        DB::transaction(function () use ($order, $request): void {
            $order->update([
                'status' => OrderStatus::PAID,
                'paid_at' => now(),
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
            $order->payment()->update([
                'status' => 'verified',
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'admin_note' => $request->input('admin_note'),
            ]);
        });

        ActivityLogger::log(
            $request,
            'order.approved',
            "Menyetujui pembayaran {$order->invoice_number}",
            $order
        );

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Order $order): RedirectResponse
    {
        abort_unless(
            in_array($order->status, [
                OrderStatus::PENDING_PAYMENT,
                OrderStatus::WAITING_VERIFICATION,
            ], true),
            422,
            'Pesanan tidak dapat ditolak.'
        );

        $data = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($order, $request, $data): void {
            $order->load('items.variant');
            $affectedProductIds = [];

            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->increment('stock', $item->quantity);
                    $affectedProductIds[] = $item->variant->product_id;
                } elseif ($item->product) {
                    // Kompatibilitas pesanan yang dibuat sebelum sistem variasi.
                    $fallbackVariant = $item->product
                        ->allVariants()
                        ->where('storage', $item->product_capacity)
                        ->first();

                    if ($fallbackVariant) {
                        $fallbackVariant->increment('stock', $item->quantity);
                        $affectedProductIds[] = $item->product->id;
                    } else {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            foreach (array_unique($affectedProductIds) as $productId) {
                Product::whereKey($productId)->update([
                    'stock' => ProductVariant::active()
                        ->where('product_id', $productId)
                        ->sum('stock'),
                ]);
            }

            $order->update([
                'status' => OrderStatus::REJECTED,
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);
            $order->payment()->update([
                'status' => 'rejected',
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
                'admin_note' => $data['admin_note'],
            ]);
        });

        ActivityLogger::log(
            $request,
            'order.rejected',
            "Menolak pembayaran {$order->invoice_number}",
            $order
        );

        return back()->with('success', 'Pesanan ditolak dan stok varian dikembalikan.');
    }

    public function complete(Request $request, Order $order): RedirectResponse
    {
        abort_unless(
            $order->status === OrderStatus::PAID,
            422,
            'Hanya pesanan berstatus dibayar yang dapat diselesaikan.'
        );

        $order->update(['status' => OrderStatus::COMPLETED]);

        ActivityLogger::log(
            $request,
            'order.completed',
            "Menyelesaikan pesanan {$order->invoice_number}",
            $order
        );

        return back()->with('success', 'Pesanan ditandai selesai.');
    }
}
