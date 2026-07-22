# iPhone Reseller Store

Starter project profesional untuk satu toko iPhone yang menjual stok kepada reseller. Dibangun menggunakan **Laravel Framework 12.56.0**, Blade, Tailwind CSS, Vite, dan MySQL.

## Fitur utama

- Katalog publik tanpa login.
- Registrasi dan login reseller tanpa proses verifikasi manual.
- Produk berdasarkan kombinasi tipe, kapasitas, dan warna.
- Keranjang berbasis session.
- Checkout dengan pencadangan stok.
- Pembayaran QRIS manual dan unggah bukti opsional.
- Verifikasi pembayaran oleh Admin/Superadmin.
- Riwayat pesanan dan invoice cetak.
- CRUD produk, stok, foto, dan artikel untuk Admin.
- Manajemen pengguna, FAQ, pesan kontak, QRIS, pengaturan website, dan audit log untuk Superadmin.
- Seed 12 produk, artikel, FAQ, dan tiga akun demonstrasi.

## Requirement

- PHP 8.2 atau lebih baru beserta ekstensi umum Laravel: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, dan PDO MySQL.
- Composer 2.x.
- Node.js 20+ dan npm.
- MySQL/MariaDB melalui Laragon.

## Instalasi cepat di Laragon

1. Ekstrak folder ke `C:\\laragon\\www\\iphone-reseller-store`.
2. Jalankan Laragon, kemudian aktifkan Apache/Nginx dan MySQL.
3. Buat database kosong bernama `iphone_reseller_store` melalui HeidiSQL atau phpMyAdmin.
4. Buka terminal pada folder proyek.
5. Jalankan:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm install
npm run build
```

6. Gunakan menu Laragon **Reload**. Buka `http://iphone-reseller-store.test` atau jalankan `php artisan serve`.

File `SETUP_WINDOWS.bat` dapat menjalankan sebagian besar perintah di atas secara otomatis setelah database dibuat.

## Akun awal

| Role | Email | Password |
|---|---|---|
| Superadmin | `superadmin@example.com` | `Superadmin123!` |
| Admin | `admin@example.com` | `Admin123!` |
| Reseller | `reseller@example.com` | `Reseller123!` |

**Ganti seluruh password akun demonstrasi sebelum website digunakan secara nyata.**

## Konfigurasi awal wajib

Login sebagai Superadmin, lalu buka:

- **Pengaturan Website** untuk mengganti nama, alamat, email, WhatsApp, dan Instagram.
- **QRIS toko** untuk mengunggah QRIS pembayaran yang resmi.
- **Pengguna** untuk membuat atau menonaktifkan Admin.
- **Produk & Stok** untuk mengganti produk contoh dengan data nyata.

## Alur transaksi

1. Reseller login dan memasukkan produk ke keranjang.
2. Checkout membuat order, payment, order item, dan nomor invoice.
3. Sistem mengurangi stok ketika invoice dibuat agar stok tercadangkan.
4. Reseller membayar QRIS dan mengirim konfirmasi.
5. Admin menyetujui atau menolak pembayaran.
6. Jika ditolak, sistem mengembalikan stok secara otomatis.
7. Jika disetujui, invoice berstatus dibayar dan dapat ditandai selesai.

## Struktur role

- `user`: melihat katalog, membeli, mengatur profil, dan melihat invoice miliknya.
- `admin`: mengelola produk, stok, artikel, pesanan, dan pembayaran.
- `superadmin`: memiliki seluruh akses Admin ditambah pengguna, FAQ, pengaturan, kontak, dan activity log.

## Catatan produksi

Starter ini sudah menyediakan fondasi, tetapi deployment nyata tetap memerlukan HTTPS, backup otomatis, konfigurasi mail, kebijakan privasi, pengujian keamanan, optimasi gambar, proses pembatalan invoice kedaluwarsa, serta integrasi notifikasi WhatsApp/email bila diperlukan.
