<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public const CART_VERSION = 2;

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
        $validated = $request->validate([
            'variant_id' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $variant = ProductVariant::active()
            ->where('product_id', $product->id)
            ->findOrFail($validated['variant_id']);

        abort_unless(
            $product->is_active && $variant->stock > 0,
            422,
            'Varian produk tidak tersedia.'
        );

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = self::cartFromSession($request);

        $currentQuantity = (int) ($cart[$variant->id] ?? 0);
        $newQuantity = min($currentQuantity + $quantity, $variant->stock);

        $cart[$variant->id] = $newQuantity;
        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            "{$product->name} RAM {$variant->ram} / {$variant->storage} berhasil ditambahkan ke keranjang."
        );
    }

    public function update(Request $request, ProductVariant $variant): RedirectResponse
    {
        abort_unless(
            $variant->is_active && $variant->product?->is_active && $variant->stock > 0,
            422,
            'Varian produk tidak tersedia.'
        );

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:'.max(1, $variant->stock),
            ],
        ], [
            'quantity.max' => 'Jumlah melebihi stok yang tersedia.',
        ]);

        $cart = self::cartFromSession($request);

        if (array_key_exists($variant->id, $cart)) {
            $cart[$variant->id] = (int) $validated['quantity'];
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function destroy(Request $request, ProductVariant $variant): RedirectResponse
    {
        $cart = self::cartFromSession($request);

        unset($cart[$variant->id]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Varian produk berhasil dihapus dari keranjang.');
    }

    /**
     * Mengambil isi keranjang terbaru berdasarkan varian produk di database.
     *
     * @return array{0: \Illuminate\Support\Collection, 1: float, 2: int}
     */
    public static function resolveCart(Request $request): array
    {
        $cart = self::cartFromSession($request);

        if (empty($cart)) {
            return [collect(), 0.0, 0];
        }

        $variants = ProductVariant::active()
            ->whereHas('product', fn ($query) => $query->active())
            ->with(['product.images'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)
            ->map(function (mixed $quantity, mixed $variantId) use ($variants): ?array {
                $variant = $variants->get((int) $variantId);

                if (! $variant || $variant->stock < 1 || ! $variant->product) {
                    return null;
                }

                $quantity = min((int) $quantity, (int) $variant->stock);

                if ($quantity < 1) {
                    return null;
                }

                return [
                    'product' => $variant->product,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'subtotal' => (float) $variant->price * $quantity,
                ];
            })
            ->filter()
            ->values();

        $normalizedCart = $items
            ->mapWithKeys(fn (array $item) => [
                $item['variant']->id => $item['quantity'],
            ])
            ->all();

        if ($normalizedCart !== $cart) {
            $request->session()->put('cart', $normalizedCart);
        }

        return [
            $items,
            (float) $items->sum('subtotal'),
            (int) $items->sum('quantity'),
        ];
    }

    public static function cartFromSession(Request $request): array
    {
        if ((int) $request->session()->get('cart_version') !== self::CART_VERSION) {
            $request->session()->forget('cart');
            $request->session()->put('cart_version', self::CART_VERSION);

            return [];
        }

        return $request->session()->get('cart', []);
    }
}
