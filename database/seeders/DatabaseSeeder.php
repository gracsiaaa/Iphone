<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::updateOrCreate(['email' => 'superadmin@example.com'], [
            'name' => 'Super Administrator', 'phone' => '081234567890', 'store_name' => 'iPhone Reseller Store', 'address' => 'Alamat toko utama', 'password' => Hash::make('Superadmin123!'), 'role' => UserRole::SUPERADMIN, 'is_active' => true,
        ]);
        $admin = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Administrator Toko', 'phone' => '081234567891', 'store_name' => 'iPhone Reseller Store', 'address' => 'Alamat toko utama', 'password' => Hash::make('Admin123!'), 'role' => UserRole::ADMIN, 'is_active' => true,
        ]);
        User::updateOrCreate(['email' => 'reseller@example.com'], [
            'name' => 'Demo Reseller', 'phone' => '081234567892', 'store_name' => 'Demo Phone Shop', 'address' => 'Alamat demo reseller', 'password' => Hash::make('Reseller123!'), 'role' => UserRole::USER, 'is_active' => true,
        ]);

        $products = [
            [
                'name' => 'iPhone 11',
                'type' => 'iPhone 11',
                'color' => 'Black',
                'variants' => [
                    ['ram' => '4 GB', 'storage' => '64 GB', 'price' => 4_700_000, 'stock' => 8],
                    ['ram' => '4 GB', 'storage' => '128 GB', 'price' => 5_200_000, 'stock' => 12],
                ],
            ],
            [
                'name' => 'iPhone 12',
                'type' => 'iPhone 12',
                'color' => 'Blue',
                'variants' => [
                    ['ram' => '4 GB', 'storage' => '64 GB', 'price' => 6_100_000, 'stock' => 7],
                    ['ram' => '4 GB', 'storage' => '128 GB', 'price' => 6_800_000, 'stock' => 10],
                    ['ram' => '4 GB', 'storage' => '256 GB', 'price' => 7_600_000, 'stock' => 5],
                ],
            ],
            [
                'name' => 'iPhone 12 Pro',
                'type' => 'iPhone 12 Pro',
                'color' => 'Pacific Blue',
                'variants' => [
                    ['ram' => '6 GB', 'storage' => '128 GB', 'price' => 7_900_000, 'stock' => 5],
                    ['ram' => '6 GB', 'storage' => '256 GB', 'price' => 8_500_000, 'stock' => 7],
                ],
            ],
            [
                'name' => 'iPhone 13',
                'type' => 'iPhone 13',
                'color' => 'Midnight',
                'variants' => [
                    ['ram' => '4 GB', 'storage' => '128 GB', 'price' => 8_200_000, 'stock' => 15],
                    ['ram' => '4 GB', 'storage' => '256 GB', 'price' => 9_300_000, 'stock' => 8],
                ],
            ],
            [
                'name' => 'iPhone 13 Pro',
                'type' => 'iPhone 13 Pro',
                'color' => 'Sierra Blue',
                'variants' => [
                    ['ram' => '6 GB', 'storage' => '128 GB', 'price' => 10_400_000, 'stock' => 6],
                    ['ram' => '6 GB', 'storage' => '256 GB', 'price' => 11_200_000, 'stock' => 5],
                ],
            ],
            [
                'name' => 'iPhone 14',
                'type' => 'iPhone 14',
                'color' => 'Purple',
                'variants' => [
                    ['ram' => '6 GB', 'storage' => '128 GB', 'price' => 10_750_000, 'stock' => 11],
                    ['ram' => '6 GB', 'storage' => '256 GB', 'price' => 11_900_000, 'stock' => 7],
                ],
            ],
            [
                'name' => 'iPhone 14 Pro',
                'type' => 'iPhone 14 Pro',
                'color' => 'Deep Purple',
                'variants' => [
                    ['ram' => '6 GB', 'storage' => '128 GB', 'price' => 13_100_000, 'stock' => 5],
                    ['ram' => '6 GB', 'storage' => '256 GB', 'price' => 14_200_000, 'stock' => 4],
                ],
            ],
            [
                'name' => 'iPhone 15',
                'type' => 'iPhone 15',
                'color' => 'Pink',
                'variants' => [
                    ['ram' => '6 GB', 'storage' => '128 GB', 'price' => 13_250_000, 'stock' => 9],
                    ['ram' => '6 GB', 'storage' => '256 GB', 'price' => 14_600_000, 'stock' => 6],
                ],
            ],
            [
                'name' => 'iPhone 15 Pro',
                'type' => 'iPhone 15 Pro',
                'color' => 'Natural Titanium',
                'variants' => [
                    ['ram' => '8 GB', 'storage' => '128 GB', 'price' => 16_500_000, 'stock' => 4],
                    ['ram' => '8 GB', 'storage' => '256 GB', 'price' => 17_750_000, 'stock' => 6],
                    ['ram' => '8 GB', 'storage' => '512 GB', 'price' => 20_500_000, 'stock' => 3],
                ],
            ],
            [
                'name' => 'iPhone 16 Pro',
                'type' => 'iPhone 16 Pro',
                'color' => 'Desert Titanium',
                'variants' => [
                    ['ram' => '8 GB', 'storage' => '128 GB', 'price' => 19_600_000, 'stock' => 5],
                    ['ram' => '8 GB', 'storage' => '256 GB', 'price' => 20_900_000, 'stock' => 3],
                    ['ram' => '8 GB', 'storage' => '512 GB', 'price' => 23_500_000, 'stock' => 2],
                ],
            ],
        ];

        foreach ($products as $index => $productData) {
            $variants = collect($productData['variants']);
            $firstVariant = $variants->first();
            $slug = Str::slug($productData['name'].'-'.$productData['color']);

            $product = Product::updateOrCreate([
                'name' => $productData['name'],
                'color' => $productData['color'],
            ], [
                'slug' => $slug,
                'type' => $productData['type'],
                'capacity' => $firstVariant['storage'],
                'price' => $variants->min('price'),
                'stock' => $variants->sum('stock'),
                'description' => "Stok {$productData['name']} dengan pilihan RAM dan storage untuk kebutuhan reseller. Harga dan stok dicatat per kombinasi varian.",
                'is_active' => true,
                'is_featured' => $index < 4,
            ]);

            foreach ($variants as $variantData) {
                $product->allVariants()->updateOrCreate([
                    'ram' => $variantData['ram'],
                    'storage' => $variantData['storage'],
                ], [
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'is_active' => true,
                ]);
            }

            if (! $product->images()->exists()) {
                $product->images()->create([
                    'path' => 'products/demo-product.svg',
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        $articleData = [
            ['Cara Membaca Stok iPhone untuk Kebutuhan Reseller','Pelajari cara memilih kombinasi tipe, kapasitas, warna, dan jumlah stok secara lebih terencana.'],
            ['Panduan Pembayaran QRIS dan Verifikasi Invoice','Ikuti alur pembayaran QRIS manual agar Admin dapat memeriksa transaksi dengan cepat dan akurat.'],
            ['Tips Menentukan Produk iPhone yang Cepat Berputar','Gunakan riwayat penjualan dan preferensi pelanggan untuk menentukan stok yang lebih relevan.'],
        ];
        foreach ($articleData as $index => [$title,$excerpt]) {
            Article::updateOrCreate(['slug'=>Str::slug($title)], ['user_id'=>$admin->id,'title'=>$title,'excerpt'=>$excerpt,'content'=>$excerpt."\n\nWebsite ini menyediakan katalog publik, checkout khusus pengguna yang login, pembayaran QRIS manual, dan riwayat invoice yang dapat dipantau melalui dashboard. Admin perlu memperbarui stok dan status pembayaran secara disiplin agar informasi tetap akurat.",'thumbnail'=>'articles/demo-article.svg','status'=>'published','published_at'=>now()->subDays($index),'meta_title'=>$title,'meta_description'=>$excerpt]);
        }

        $faqs = [
            ['Apakah saya harus login untuk melihat produk?','Tidak. Pengunjung dapat melihat katalog dan artikel tanpa login. Login hanya diwajibkan ketika ingin membeli.','Akun'],
            ['Bagaimana cara melakukan pembayaran?','Setelah checkout, sistem menampilkan invoice dan QRIS toko. Bayar sesuai total invoice, kemudian kirim konfirmasi pembayaran.','Pembayaran'],
            ['Siapa yang memverifikasi pembayaran?','Admin atau Superadmin memeriksa pembayaran dan memperbarui status invoice.','Pembayaran'],
            ['Apakah stok langsung berkurang saat checkout?','Ya. Starter ini mencadangkan stok ketika invoice dibuat untuk mengurangi risiko kelebihan penjualan.','Stok'],
            ['Di mana saya melihat invoice?','Login dan buka menu Pesanan. Setiap transaksi memiliki halaman detail dan invoice yang dapat dicetak atau disimpan sebagai PDF.','Invoice'],
        ];
        foreach ($faqs as $i => [$question,$answer,$category]) Faq::updateOrCreate(['question'=>$question], ['answer'=>$answer,'category'=>$category,'sort_order'=>$i+1,'is_active'=>true]);

        $settings = [
            'site_name'=>'iPhone Reseller Store','site_tagline'=>'Stok iPhone terpercaya untuk pertumbuhan bisnis reseller Anda.','store_email'=>'hello@example.com','store_phone'=>'0812-3456-7890','whatsapp'=>'0812-3456-7890','store_address'=>'Ganti dengan alamat toko Anda','instagram'=>'@iphone.reseller.store','payment_instruction'=>'Scan QRIS sesuai total invoice. Setelah pembayaran berhasil, unggah bukti jika tersedia lalu klik tombol Saya Sudah Bayar.',
        ];
        foreach ($settings as $key=>$value) Setting::updateOrCreate(['key'=>$key], ['value'=>$value,'type'=>'text','group'=>str_contains($key,'payment')?'payment':'general']);
    }
}
