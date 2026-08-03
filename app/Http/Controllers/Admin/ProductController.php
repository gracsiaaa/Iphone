<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ActivityLogger;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['images', 'variants'])
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(
                    'name',
                    'like',
                    '%'.$request->string('search').'%'
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
        $variants = $data['variants'];
        $productData = $this->prepareProductData($data, $variants);
        $productData['slug'] = $this->uniqueSlug(
            $productData['name'].'-'.$productData['color']
        );

        $product = DB::transaction(function () use ($productData, $variants): Product {
            $product = Product::create($productData);

            foreach ($variants as $variant) {
                $product->allVariants()->create([
                    'ram' => trim($variant['ram']),
                    'storage' => trim($variant['storage']),
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'is_active' => true,
                ]);
            }

            return $product;
        });

        $this->storeImages($request, $product);

        ActivityLogger::log(
            $request,
            'product.created',
            "Membuat produk {$product->name}",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk dan variasinya berhasil dibuat.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'variants']);

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateData($request, $product);
        $variants = $data['variants'];
        $productData = $this->prepareProductData($data, $variants);
        $productData['slug'] = $this->uniqueSlug(
            $productData['name'].'-'.$productData['color'],
            $product->id
        );

        DB::transaction(function () use ($product, $productData, $variants): void {
            $product->update($productData);

            $keptVariantIds = [];

            foreach ($variants as $variantData) {
                $variant = null;

                if (! empty($variantData['id'])) {
                    $variant = $product->allVariants()
                        ->whereKey($variantData['id'])
                        ->firstOrFail();
                }

                if (! $variant) {
                    $variant = $product->allVariants()
                        ->where('ram', trim($variantData['ram']))
                        ->where('storage', trim($variantData['storage']))
                        ->first();
                }

                $variant ??= $product->allVariants()->make();

                $variant->fill([
                    'ram' => trim($variantData['ram']),
                    'storage' => trim($variantData['storage']),
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'is_active' => true,
                ]);
                $variant->save();

                $keptVariantIds[] = $variant->id;
            }

            $removedVariants = $product->allVariants()
                ->whereNotIn('id', $keptVariantIds)
                ->get();

            foreach ($removedVariants as $removedVariant) {
                if ($removedVariant->orderItems()->exists()) {
                    $removedVariant->update(['is_active' => false]);
                } else {
                    $removedVariant->delete();
                }
            }
        });

        $this->storeImages($request, $product);

        ActivityLogger::log(
            $request,
            'product.updated',
            "Memperbarui produk {$product->name}",
            $product
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk dan variasinya berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $product->delete();

        ActivityLogger::log(
            $request,
            'product.deleted',
            "Menghapus produk {$product->name}",
            $product
        );

        return back()->with('success', 'Produk dipindahkan ke arsip.');
    }

    public function deleteImage(Request $request, ProductImage $image): RedirectResponse
    {
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

    private function validateData(Request $request, ?Product $product = null): array
    {
        $variants = collect($request->input('variants', []))
            ->map(function ($variant): array {
                $variant = is_array($variant) ? $variant : [];
                $variant['price'] = Money::parseRupiah($variant['price'] ?? null);

                return $variant;
            })
            ->values()
            ->all();

        $request->merge(['variants' => $variants]);

        $variantIdRule = Rule::exists('product_variants', 'id');

        if ($product) {
            $variantIdRule->where('product_id', $product->id);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'type' => ['required', 'string', 'max:120'],
            'color' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'variants' => ['required', 'array', 'min:1', 'max:30'],
            'variants.*.id' => ['nullable', 'integer', $variantIdRule],
            'variants.*.ram' => ['required', 'string', 'max:50'],
            'variants.*.storage' => ['required', 'string', 'max:50'],
            'variants.*.price' => ['required', 'integer', 'min:1'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ], [
            'variants.required' => 'Tambahkan minimal satu variasi RAM dan storage.',
            'variants.min' => 'Tambahkan minimal satu variasi RAM dan storage.',
            'variants.*.ram.required' => 'RAM pada setiap variasi wajib diisi.',
            'variants.*.storage.required' => 'Storage pada setiap variasi wajib diisi.',
            'variants.*.price.min' => 'Harga variasi harus lebih dari Rp 0.',
            'images.max' => 'Maksimal enam gambar untuk satu produk.',
            'images.*.max' => 'Ukuran setiap gambar maksimal 4 MB.',
        ]);

        $combinations = collect($validated['variants'])
            ->map(fn ($variant) => Str::lower(trim($variant['ram'])).'|'.Str::lower(trim($variant['storage'])));

        if ($combinations->duplicates()->isNotEmpty()) {
            throw ValidationException::withMessages([
                'variants' => 'Kombinasi RAM dan storage tidak boleh duplikat.',
            ]);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }

    private function prepareProductData(array $data, array $variants): array
    {
        $firstVariant = $variants[0];

        return array_merge(
            Arr::except($data, ['variants', 'images']),
            [
                // Kolom lama tetap disinkronkan untuk kompatibilitas data lama.
                'capacity' => $firstVariant['storage'],
                'price' => collect($variants)->min('price'),
                'stock' => collect($variants)->sum('stock'),
            ]
        );
    }

    private function storeImages(Request $request, Product $product): void
    {
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

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
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
            $slug = $baseSlug.'-'.$counter++;
        }

        return $slug;
    }
}
