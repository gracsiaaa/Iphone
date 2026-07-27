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
use RuntimeException;
use Throwable;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        [$items, $total, $totalQuantity] = CartController::resolveCart($request);

        if ($items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $minimumQuantity = (int) config('shop.minimum_order_quantity', 10);

        if ($totalQuantity < $minimumQuantity) {
            $remainingQuantity = $minimumQuantity - $totalQuantity;

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    "Minimal pembelian adalah {$minimumQuantity} unit. Tambahkan {$remainingQuantity} unit lagi."
                );
        }

        return view('checkout.create', [
            'items' => $items,
            'total' => $total,
            'totalQuantity' => $totalQuantity,
            'minimumQuantity' => $minimumQuantity,
        ]);
    }

    public function store(
        Request $request,
        InvoiceNumberService $invoiceNumberService
    ): RedirectResponse {
        $customerData = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:190'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_store_name' => ['nullable', 'string', 'max:190'],
            'billing_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        try {
            $order = DB::transaction(function () use (
                $cart,
                $customerData,
                $request,
                $invoiceNumberService
            ): Order {
                $products = Product::query()
                    ->whereIn('id', array_keys($cart))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $preparedItems = collect();
                $total = 0.0;
                $totalQuantity = 0;
                $minimumQuantity = (int) config(
                    'shop.minimum_order_quantity',
                    10
                );

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get((int) $productId);
                    $quantity = (int) $quantity;

                    if (
                        ! $product ||
                        ! $product->is_active ||
                        $quantity < 1 ||
                        $product->stock < $quantity
                    ) {
                        throw new RuntimeException(
                            'Stok salah satu produk telah berubah. Periksa kembali keranjang Anda.'
                        );
                    }

                    $subtotal = (float) $product->price * $quantity;

                    $total += $subtotal;
                    $totalQuantity += $quantity;

                    $preparedItems->push([
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Validasi utama di backend agar tidak bisa dilewati dari browser.
                if ($totalQuantity < $minimumQuantity) {
                    throw new RuntimeException(
                        "Minimal pembelian adalah {$minimumQuantity} unit. " .
                        "Jumlah keranjang Anda hanya {$totalQuantity} unit."
                    );
                }

                $order = Order::create(array_merge($customerData, [
                    'user_id' => $request->user()->id,
                    'invoice_number' => $invoiceNumberService->generate(),
                    'status' => OrderStatus::PENDING_PAYMENT,
                    'subtotal' => $total,
                    'total' => $total,
                ]));

                foreach ($preparedItems as $item) {
                    $product = $item['product'];

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_type' => $product->type,
                        'product_color' => $product->color,
                        'product_capacity' => $product->capacity,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Stok dicadangkan ketika invoice dibuat.
                    $product->decrement('stock', $item['quantity']);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'method' => 'qris',
                    'amount' => $total,
                    'status' => 'pending',
                ]);

                return $order;
            });
        } catch (Throwable $exception) {
            report($exception);

            $message = $exception instanceof RuntimeException
                ? $exception->getMessage()
                : 'Checkout gagal diproses. Silakan coba kembali.';

            return redirect()
                ->route('cart.index')
                ->with('error', $message);
        }

        $request->session()->forget('cart');

        return redirect()
            ->route('orders.show', $order)
            ->with(
                'success',
                'Invoice berhasil dibuat. Silakan lakukan pembayaran melalui QRIS.'
            );
    }

    public function confirmPayment(
        Request $request,
        Order $order
    ): RedirectResponse {
        abort_unless($order->user_id === $request->user()->id, 403);

        abort_unless(
            $order->status === OrderStatus::PENDING_PAYMENT,
            422,
            'Pembayaran tidak dapat dikonfirmasi pada status ini.'
        );

        $validated = $request->validate([
            'proof' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'payment_note' => ['nullable', 'string', 'max:500'],
        ]);

        $proofPath = null;

        if ($request->hasFile('proof')) {
            $proofPath = $request
                ->file('proof')
                ->store('payments', 'public');
        }

        $order->payment()->update([
            'status' => 'waiting_verification',
            'proof_path' => $proofPath,
            'confirmed_at' => now(),
            'admin_note' => $validated['payment_note'] ?? null,
        ]);

        $order->update([
            'status' => OrderStatus::WAITING_VERIFICATION,
        ]);

        return back()->with(
            'success',
            'Konfirmasi pembayaran telah dikirim dan sedang diperiksa Admin.'
        );
    }
}
