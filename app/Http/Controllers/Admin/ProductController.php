<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ActivityLogger;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('images')
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(
                    'name',
                    'like',
                    '%' . $request->string('search') . '%'
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug(
            $data['name'] . '-' . $data['capacity'] . '-' . $data['color']
        );

        $product = Product::create($data);

        $this->storeImages($request, $product);

        ActivityLogger::log(
            $request,
            'product.created',
            "Membuat produk {$product->name}",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product): View
    {
        $product->load('images');

        return view('admin.products.edit', compact('product'));
    }

    public function update(
        Request $request,
        Product $product
    ): RedirectResponse {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug(
            $data['name'] . '-' . $data['capacity'] . '-' . $data['color'],
            $product->id
        );

        $product->update($data);

        $this->storeImages($request, $product);

        ActivityLogger::log(
            $request,
            'product.updated',
            "Memperbarui produk {$product->name}",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(
        Request $request,
        Product $product
    ): RedirectResponse {
        $product->delete();

        ActivityLogger::log(
            $request,
            'product.deleted',
            "Menghapus produk {$product->name}",
            $product
        );

        return back()->with('success', 'Produk dipindahkan ke arsip.');
    }

    public function deleteImage(
        Request $request,
        ProductImage $image
    ): RedirectResponse {
        $product = $image->product;

        Storage::disk('public')->delete($image->path);
        $image->delete();

        if (! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->first()?->update(['is_primary' => true]);
        }

        ActivityLogger::log(
            $request,
            'product.image_deleted',
            "Menghapus gambar produk {$product->name}",
            $product
        );

        return back()->with('success', 'Gambar produk berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        // Input seperti "Rp 13.500.000" diubah menjadi 13500000.
        $request->merge([
            'price' => Money::parseRupiah($request->input('price')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'type' => ['required', 'string', 'max:120'],
            'color' => ['required', 'string', 'max:80'],
            'capacity' => ['required', 'string', 'max:50'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ], [
            'price.min' => 'Harga produk harus lebih dari Rp 0.',
            'images.max' => 'Maksimal enam gambar untuk satu produk.',
            'images.*.max' => 'Ukuran setiap gambar maksimal 4 MB.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }

    private function storeImages(
        Request $request,
        Product $product
    ): void {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimaryImage = $product
            ->images()
            ->where('is_primary', true)
            ->exists();

        foreach ($request->file('images') as $index => $file) {
            $product->images()->create([
                'path' => $file->store('products', 'public'),
                'is_primary' => ! $hasPrimaryImage && $index === 0,
                'sort_order' => $product->images()->count(),
            ]);
        }
    }

    private function uniqueSlug(
        string $value,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Product::withTrashed()
                ->where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}
