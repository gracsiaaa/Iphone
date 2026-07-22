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
        [$items, $total] = $this->resolveCart($request);
        return view('cart.index', compact('items', 'total'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->stock > 0, 422, 'Produk tidak tersedia.');
        $quantity = (int) $request->validate(['quantity' => ['nullable', 'integer', 'min:1']])['quantity'] ?? 1;
        $cart = $request->session()->get('cart', []);
        $newQuantity = min(($cart[$product->id] ?? 0) + $quantity, $product->stock);
        $cart[$product->id] = $newQuantity;
        $request->session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $quantity = (int) $request->validate(['quantity' => ['required', 'integer', 'min:1']])['quantity'];
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id] = min($quantity, $product->stock);
            $request->session()->put('cart', $cart);
        }
        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public static function resolveCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::active()->with('images')->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get((int) $productId);
            if (!$product) return null;
            $quantity = min((int) $quantity, $product->stock);
            return ['product' => $product, 'quantity' => $quantity, 'subtotal' => (float) $product->price * $quantity];
        })->filter()->values();
        return [$items, $items->sum('subtotal')];
    }
}
