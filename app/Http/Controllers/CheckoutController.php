<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\InvoiceNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        [$items, $total] = CartController::resolveCart($request);
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        return view('checkout.create', compact('items', 'total'));
    }

    public function store(Request $request, InvoiceNumberService $invoiceService): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:190'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_store_name' => ['nullable', 'string', 'max:190'],
            'billing_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = $request->session()->get('cart', []);
        if (!$cart) return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');

        try {
            $order = DB::transaction(function () use ($cart, $data, $request, $invoiceService) {
                $products = Product::query()->whereIn('id', array_keys($cart))->lockForUpdate()->get()->keyBy('id');
                $prepared = collect();
                $total = 0;

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get((int) $productId);
                    if (!$product || !$product->is_active || $quantity < 1 || $product->stock < $quantity) {
                        throw new \RuntimeException('Stok salah satu produk sudah berubah. Periksa kembali keranjang Anda.');
                    }
                    $subtotal = (float) $product->price * (int) $quantity;
                    $total += $subtotal;
                    $prepared->push(compact('product', 'quantity', 'subtotal'));
                }

                $order = Order::create(array_merge($data, [
                    'user_id' => $request->user()->id,
                    'invoice_number' => $invoiceService->generate(),
                    'status' => OrderStatus::PENDING_PAYMENT,
                    'subtotal' => $total,
                    'total' => $total,
                ]));

                foreach ($prepared as $row) {
                    $product = $row['product'];
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_type' => $product->type,
                        'product_color' => $product->color,
                        'product_capacity' => $product->capacity,
                        'price' => $product->price,
                        'quantity' => $row['quantity'],
                        'subtotal' => $row['subtotal'],
                    ]);
                    $product->decrement('stock', $row['quantity']);
                }

                Payment::create(['order_id' => $order->id, 'method' => 'qris', 'amount' => $total, 'status' => 'pending']);
                return $order;
            });
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('cart.index')->with('error', $e instanceof \RuntimeException ? $e->getMessage() : 'Checkout gagal diproses.');
        }

        $request->session()->forget('cart');
        return redirect()->route('orders.show', $order)->with('success', 'Invoice berhasil dibuat. Silakan lakukan pembayaran QRIS.');
    }

    public function confirmPayment(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->status === OrderStatus::PENDING_PAYMENT, 422, 'Pembayaran tidak dapat dikonfirmasi pada status ini.');

        $data = $request->validate([
            'proof' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'payment_note' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->hasFile('proof')) {
            $data['proof_path'] = $request->file('proof')->store('payments', 'public');
        }

        $order->payment()->update([
            'status' => 'waiting_verification',
            'proof_path' => $data['proof_path'] ?? null,
            'confirmed_at' => now(),
            'admin_note' => $data['payment_note'] ?? null,
        ]);
        $order->update(['status' => OrderStatus::WAITING_VERIFICATION]);

        return back()->with('success', 'Konfirmasi pembayaran dikirim dan sedang diperiksa Admin.');
    }
}
