<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::active()
            ->whereHas('variants')
            ->with(['images', 'variants'])
            ->withMin('variants', 'price')
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(function ($nested) use ($request): void {
                    $search = '%'.$request->string('search').'%';
                    $nested->where('name', 'like', $search)
                        ->orWhere('type', 'like', $search);
                })
            )
            ->when(
                $request->filled('ram') || $request->filled('storage'),
                fn ($query) => $query->whereHas('variants', function ($variantQuery) use ($request): void {
                    $variantQuery
                        ->when(
                            $request->filled('ram'),
                            fn ($q) => $q->where('ram', $request->string('ram'))
                        )
                        ->when(
                            $request->filled('storage'),
                            fn ($q) => $q->where('storage', $request->string('storage'))
                        );
                })
            )
            ->when(
                $request->filled('color'),
                fn ($query) => $query->where('color', $request->string('color'))
            )
            ->when(
                (string) $request->string('sort') === 'price_low',
                fn ($query) => $query->orderBy('variants_min_price')
            )
            ->when(
                (string) $request->string('sort') === 'price_high',
                fn ($query) => $query->orderByDesc('variants_min_price')
            )
            ->when(
                ! $request->filled('sort'),
                fn ($query) => $query->latest()
            )
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'rams' => ProductVariant::active()
                ->whereHas('product', fn ($query) => $query->active())
                ->select('ram')
                ->distinct()
                ->orderBy('ram')
                ->pluck('ram'),
            'storages' => ProductVariant::active()
                ->whereHas('product', fn ($query) => $query->active())
                ->select('storage')
                ->distinct()
                ->orderBy('storage')
                ->pluck('storage'),
            'colors' => Product::active()
                ->select('color')
                ->distinct()
                ->orderBy('color')
                ->pluck('color'),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['images', 'variants']);
        abort_if($product->variants->isEmpty(), 404);

        $variantData = $product->variants
            ->map(fn ($variant) => [
                'id' => $variant->id,
                'ram' => $variant->ram,
                'storage' => $variant->storage,
                'price' => (float) $variant->price,
                'formatted_price' => $variant->formatted_price,
                'stock' => $variant->stock,
            ])
            ->values();

        return view('products.show', [
            'product' => $product,
            'variantData' => $variantData,
            'relatedProducts' => Product::active()
                ->whereHas('variants')
                ->with(['images', 'variants'])
                ->where('type', $product->type)
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get(),
        ]);
    }
}
