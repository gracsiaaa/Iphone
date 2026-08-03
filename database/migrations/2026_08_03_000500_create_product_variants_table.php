<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('ram', 50);
            $table->string('storage', 50);
            $table->decimal('price', 15, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['product_id', 'ram', 'storage']);
            $table->index(['ram', 'storage', 'price']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')
                ->nullable()
                ->after('product_id')
                ->constrained('product_variants')
                ->nullOnDelete();
            $table->string('product_ram', 50)
                ->nullable()
                ->after('product_type');
        });

        // Memindahkan produk lama menjadi satu varian awal agar data tetap dapat dipakai.
        DB::table('products')
            ->select(['id', 'capacity', 'price', 'stock', 'created_at', 'updated_at'])
            ->orderBy('id')
            ->chunkById(100, function ($products): void {
                $rows = [];

                foreach ($products as $product) {
                    $rows[] = [
                        'product_id' => $product->id,
                        'ram' => 'Belum diatur',
                        'storage' => $product->capacity,
                        'price' => $product->price,
                        'stock' => $product->stock,
                        'is_active' => true,
                        'created_at' => $product->created_at,
                        'updated_at' => $product->updated_at,
                    ];
                }

                if ($rows !== []) {
                    DB::table('product_variants')->insert($rows);
                }
            });

        // Menautkan item pesanan lama ke varian hasil migrasi.
        DB::table('product_variants')
            ->select(['id', 'product_id'])
            ->orderBy('id')
            ->chunkById(100, function ($variants): void {
                foreach ($variants as $variant) {
                    DB::table('order_items')
                        ->where('product_id', $variant->product_id)
                        ->whereNull('product_variant_id')
                        ->update([
                            'product_variant_id' => $variant->id,
                            'product_ram' => 'Belum diatur',
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_variant_id');
            $table->dropColumn('product_ram');
        });

        Schema::dropIfExists('product_variants');
    }
};
