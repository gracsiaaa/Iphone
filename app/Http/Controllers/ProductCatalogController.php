<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::active()->with('images')
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($x) => $x->where('name', 'like', '%'.$request->string('search').'%')->orWhere('type', 'like', '%'.$request->string('search').'%')))
            ->when($request->filled('capacity'), fn ($q) => $q->where('capacity', $request->string('capacity')))
            ->when($request->filled('color'), fn ($q) => $q->where('color', $request->string('color')))
            ->when((string) $request->string('sort') === 'price_low', fn ($q) => $q->orderBy('price'))
            ->when((string) $request->string('sort') === 'price_high', fn ($q) => $q->orderByDesc('price'))
            ->when(!$request->filled('sort'), fn ($q) => $q->latest())
            ->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products,
            'capacities' => Product::active()->select('capacity')->distinct()->orderBy('capacity')->pluck('capacity'),
            'colors' => Product::active()->select('color')->distinct()->orderBy('color')->pluck('color'),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);
        $product->load('images');

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => Product::active()->with('images')->where('type', $product->type)->where('id', '!=', $product->id)->take(4)->get(),
        ]);
    }
}
