<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_with_ram_and_storage_variants(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'iPhone Test Pro',
            'type' => 'iPhone Test Pro',
            'color' => 'Black',
            'description' => 'Produk pengujian variasi.',
            'is_active' => '1',
            'variants' => [
                [
                    'ram' => '8 GB',
                    'storage' => '128 GB',
                    'price' => 'Rp 12.500.000',
                    'stock' => 5,
                ],
                [
                    'ram' => '12 GB',
                    'storage' => '256 GB',
                    'price' => 'Rp 15.000.000',
                    'stock' => 3,
                ],
            ],
        ]);

        $response->assertRedirect('/admin/products');

        $product = Product::where('name', 'iPhone Test Pro')->firstOrFail();

        $this->assertSame('128 GB', $product->capacity);
        $this->assertSame('12500000.00', $product->price);
        $this->assertSame(8, $product->stock);
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'ram' => '8 GB',
            'storage' => '128 GB',
            'price' => 12_500_000,
            'stock' => 5,
        ]);
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'ram' => '12 GB',
            'storage' => '256 GB',
            'price' => 15_000_000,
            'stock' => 3,
        ]);
    }

    public function test_product_page_contains_variant_data_for_dynamic_price_selection(): void
    {
        $product = $this->createProduct();
        $product->allVariants()->createMany([
            [
                'ram' => '8 GB',
                'storage' => '128 GB',
                'price' => 12_500_000,
                'stock' => 5,
                'is_active' => true,
            ],
            [
                'ram' => '12 GB',
                'storage' => '256 GB',
                'price' => 15_000_000,
                'stock' => 3,
                'is_active' => true,
            ],
        ]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Pilih RAM')
            ->assertSee('Pilih Storage')
            ->assertSee('8 GB')
            ->assertSee('12 GB')
            ->assertSee('128 GB')
            ->assertSee('256 GB')
            ->assertSee('formatted_price', false);
    }

    public function test_cart_uses_selected_variant_as_the_cart_key(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $variant = $product->allVariants()->create([
            'ram' => '8 GB',
            'storage' => '256 GB',
            'price' => 15_000_000,
            'stock' => 4,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('cart.store', $product), [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('cart_version', 2);
        $response->assertSessionHas('cart', [
            $variant->id => 2,
        ]);
    }

    private function createProduct(): Product
    {
        return Product::create([
            'name' => 'iPhone Variant Test',
            'slug' => 'iphone-variant-test-black',
            'type' => 'iPhone Variant Test',
            'color' => 'Black',
            'capacity' => '128 GB',
            'price' => 12_500_000,
            'stock' => 8,
            'description' => 'Produk pengujian.',
            'is_active' => true,
            'is_featured' => false,
        ]);
    }
}
