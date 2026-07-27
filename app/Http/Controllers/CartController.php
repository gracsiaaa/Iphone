<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        [$items, $total, $totalQuantity] = self::resolveCart($request);

        $minimumQuantity = (int) config('shop.minimum_order_quantity', 10);

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
            'totalQuantity' => $totalQuantity,
            'minimumQuantity' => $minimumQuantity,
            'remainingQuantity' => max(0, $minimumQuantity - $totalQuantity),
            'canCheckout' => $totalQuantity >= $minimumQuantity,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless(
            $product->is_active && $product->stock > 0,
            422,
            'Produk tidak tersedia.'
        );

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = $request->session()->get('cart', []);

        $currentQuantity = (int) ($cart[$product->id] ?? 0);
        $newQuantity = min($currentQuantity + $quantity, $product->stock);

        $cart[$product->id] = $newQuantity;
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . max(1, $product->stock),
            ],
        ], [
            'quantity.max' => 'Jumlah melebihi stok yang tersedia.',
        ]);

        $cart = $request->session()->get('cart', []);

        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = (int) $validated['quantity'];
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Mengambil isi keranjang terbaru berdasarkan data produk di database.
     *
     * @return array{0: \Illuminate\Support\Collection, 1: float, 2: int}
     */
    public static function resolveCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return [collect(), 0.0, 0];
        }

        $products = Product::active()
            ->with('images')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (mixed $quantity, mixed $productId) use ($products): ?array {
                $product = $products->get((int) $productId);

                if (! $product || $product->stock < 1) {
                    return null;
                }

                $quantity = min((int) $quantity, (int) $product->stock);

                if ($quantity < 1) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => (float) $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();

        return [
            $items,
            (float) $items->sum('subtotal'),
            (int) $items->sum('quantity'),
        ];
    }
}
