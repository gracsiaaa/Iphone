<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ActivityLogger;
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

        $cart = CartController::cartFromSession($request);

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
                $variants = ProductVariant::query()
                    ->with('product')
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

                foreach ($cart as $variantId => $quantity) {
                    $variant = $variants->get((int) $variantId);
                    $product = $variant?->product;
                    $quantity = (int) $quantity;

                    if (
                        ! $variant ||
                        ! $variant->is_active ||
                        ! $product ||
                        ! $product->is_active ||
                        $quantity < 1 ||
                        $variant->stock < $quantity
                    ) {
                        throw new RuntimeException(
                            'Stok salah satu varian telah berubah. Periksa kembali keranjang Anda.'
                        );
                    }

                    $subtotal = (float) $variant->price * $quantity;

                    $total += $subtotal;
                    $totalQuantity += $quantity;

                    $preparedItems->push([
                        'product' => $product,
                        'variant' => $variant,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                if ($totalQuantity < $minimumQuantity) {
                    throw new RuntimeException(
                        "Minimal pembelian adalah {$minimumQuantity} unit. ".
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

                $affectedProductIds = [];

                foreach ($preparedItems as $item) {
                    $product = $item['product'];
                    $variant = $item['variant'];

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'product_name' => $product->name,
                        'product_type' => $product->type,
                        'product_ram' => $variant->ram,
                        'product_color' => $product->color,
                        'product_capacity' => $variant->storage,
                        'price' => $variant->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $variant->decrement('stock', $item['quantity']);
                    $affectedProductIds[] = $product->id;
                }

                foreach (array_unique($affectedProductIds) as $productId) {
                    Product::whereKey($productId)->update([
                        'stock' => ProductVariant::active()
                            ->where('product_id', $productId)
                            ->sum('stock'),
                    ]);
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

        ActivityLogger::log(
            $request,
            'order.created',
            "Membuat pesanan {$order->invoice_number}",
            $order
        );

        return redirect()
            ->route('orders.show', $order)
            ->with(
                'success',
                'Invoice berhasil dibuat. Silakan lakukan pembayaran melalui QRIS.'
            );
    }

    public function confirmPayment(Request $request, Order $order): RedirectResponse
    {
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

        ActivityLogger::log(
            $request,
            'payment.submitted',
            "Mengirim bukti pembayaran untuk {$order->invoice_number}",
            $order
        );

        return back()->with(
            'success',
            'Konfirmasi pembayaran telah dikirim dan sedang diperiksa Admin.'
        );
    }
}
