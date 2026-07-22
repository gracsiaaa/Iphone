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
            ['iPhone 11','iPhone 11','128 GB','Black',5200000,12],
            ['iPhone 12','iPhone 12','128 GB','Blue',6800000,10],
            ['iPhone 12 Pro','iPhone 12 Pro','256 GB','Pacific Blue',8500000,7],
            ['iPhone 13','iPhone 13','128 GB','Midnight',8200000,15],
            ['iPhone 13','iPhone 13','256 GB','Starlight',9300000,8],
            ['iPhone 13 Pro','iPhone 13 Pro','256 GB','Sierra Blue',11200000,5],
            ['iPhone 14','iPhone 14','128 GB','Purple',10750000,11],
            ['iPhone 14 Pro','iPhone 14 Pro','256 GB','Deep Purple',14200000,4],
            ['iPhone 15','iPhone 15','128 GB','Pink',13250000,9],
            ['iPhone 15 Pro','iPhone 15 Pro','256 GB','Natural Titanium',17750000,6],
            ['iPhone 16','iPhone 16','256 GB','Ultramarine',16900000,10],
            ['iPhone 16 Pro','iPhone 16 Pro','256 GB','Desert Titanium',20900000,3],
        ];

        foreach ($products as $i => [$name,$type,$capacity,$color,$price,$stock]) {
            $slug = Str::slug("$name-$capacity-$color");
            $product = Product::updateOrCreate(['slug' => $slug], [
                'name'=>$name,'type'=>$type,'capacity'=>$capacity,'color'=>$color,'price'=>$price,'stock'=>$stock,'description'=>"Stok $name varian $capacity warna $color untuk kebutuhan reseller. Harga dan stok dapat diperbarui langsung melalui dashboard Admin.",'is_active'=>true,'is_featured'=>$i < 4,
            ]);
            if (!$product->images()->exists()) $product->images()->create(['path'=>'products/demo-product.svg','is_primary'=>true,'sort_order'=>0]);
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
