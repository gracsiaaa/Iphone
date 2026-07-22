# Panduan Mengedit Tampilan Website

Dokumen ini menjelaskan lokasi teks, foto, layout, dan style setelah folder `resources/views` dirapikan.

## 1. Prinsip Struktur Baru

Semua file Blade sudah dibuat satu elemen per blok agar mudah dibaca. Tidak ada lagi halaman Blade yang ditulis dalam satu baris panjang.

Style berulang dipindahkan ke:

```text
resources/css/app.css
```

Contoh class global yang dapat digunakan:

```text
site-shell       Batas lebar konten website
page-section     Jarak atas dan bawah setiap bagian
surface          Panel putih dengan border dan bayangan
page-title       Judul utama halaman
section-title    Judul bagian
body-copy        Paragraf utama
text-muted       Teks penjelas
btn-primary      Tombol hitam
btn-secondary    Tombol putih
btn-accent       Tombol biru
input            Input, select, dan textarea
form-grid        Form dua kolom
field-full       Field selebar form
table-shell      Wadah tabel
data-table       Style tabel global
badge-*          Status berwarna
```

Saat ingin mengubah kalimat, cukup cari teksnya dengan `Ctrl + Shift + F` di Visual Studio Code. Hindari mengubah `{{ ... }}`, `@if`, `@foreach`, `@csrf`, `route(...)`, dan atribut `name` pada form.

## 2. File Tampilan Publik

### Home

```text
resources/views/home.blade.php
```

Bagian yang dapat diubah:

- Judul hero
- Paragraf hero
- Label tombol
- Statistik singkat
- Judul Produk Unggulan
- Langkah pembelian
- Judul stok terbaru
- CTA pendaftaran

Foto hero saat ini:

```text
public/images/products/iphone-placeholder.svg
```

Anda dapat mengganti file tersebut dengan gambar baru menggunakan nama file yang sama. Alternatifnya, ubah bagian berikut di `home.blade.php`:

```blade
src="{{ asset('images/products/iphone-placeholder.svg') }}"
```

Contoh gambar baru:

```blade
src="{{ asset('images/banner/hero-iphone.webp') }}"
```

Kemudian simpan gambar di:

```text
public/images/banner/hero-iphone.webp
```

### Header, Menu, dan Footer

```text
resources/views/layouts/app.blade.php
```

File ini mengatur:

- Logo
- Nama toko
- Menu Home, Product, Article, FAQ, dan Contact
- Tombol Login dan Daftar
- Menu pengguna
- Footer
- Kontak toko

Nama toko, alamat, email, telepon, dan QRIS sebaiknya diubah dari dashboard Superadmin, bukan melalui file Blade.

### Katalog Produk

```text
resources/views/products/index.blade.php
```

Mengatur judul katalog, filter, pencarian, dan susunan grid produk.

### Detail Produk

```text
resources/views/products/show.blade.php
```

Mengatur foto besar, harga, varian, stok, deskripsi, dan tombol keranjang.

### Card Produk

```text
resources/views/components/product-card.blade.php
```

Perubahan di file ini otomatis berlaku pada card produk di Home dan katalog.

### Artikel

```text
resources/views/articles/index.blade.php
resources/views/articles/show.blade.php
```

Data judul, thumbnail, ringkasan, dan isi artikel berasal dari dashboard Admin.

### FAQ dan Contact

```text
resources/views/pages/faq.blade.php
resources/views/pages/contact.blade.php
```

Pertanyaan FAQ berasal dari dashboard Superadmin. File Blade hanya mengatur layout.

### Login dan Register

```text
resources/views/auth/login.blade.php
resources/views/auth/register.blade.php
```

## 3. Halaman Reseller

```text
resources/views/account/profile.blade.php
resources/views/account/orders/index.blade.php
resources/views/account/orders/show.blade.php
resources/views/account/orders/invoice.blade.php
resources/views/cart/index.blade.php
resources/views/checkout/create.blade.php
```

File tersebut mengatur profile, keranjang, checkout, detail order, pembayaran QRIS, dan invoice.

## 4. Dashboard Admin

Kerangka sidebar dan header:

```text
resources/views/layouts/admin.blade.php
```

Dashboard:

```text
resources/views/admin/dashboard.blade.php
```

Produk:

```text
resources/views/admin/products/
```

Artikel:

```text
resources/views/admin/articles/
```

Pesanan:

```text
resources/views/admin/orders/
```

Superadmin:

```text
resources/views/admin/users/
resources/views/admin/faqs/
resources/views/admin/settings/
resources/views/admin/contacts/
resources/views/admin/activity-logs/
```

## 5. Mengganti Foto Produk

Foto produk tidak perlu diganti melalui coding. Gunakan:

```text
Dashboard Admin → Produk & Stok → Edit Produk → Foto Produk
```

File akan disimpan di:

```text
storage/app/public/products
```

Pastikan Anda pernah menjalankan:

```bash
php artisan storage:link
```

## 6. Mengganti QRIS

Gunakan:

```text
Dashboard Superadmin → Pengaturan Website → QRIS Toko
```

Jangan mengganti gambar QRIS langsung melalui file Blade karena path gambar disimpan di database.

## 7. Mengubah Warna

Buka:

```text
resources/css/app.css
```

Warna utama tersedia pada bagian `@theme`:

```css
--color-brand-500: #3b82f6;
--color-brand-600: #2563eb;
--color-brand-700: #1d4ed8;
```

Desain saat ini menggunakan warna:

- Zinc/graphite untuk elemen utama
- Putih untuk panel
- Stone untuk background
- Biru untuk aksen dan link
- Hijau, kuning, dan merah untuk status

## 8. Setelah Mengedit

Untuk menjalankan mode pengembangan:

```bash
npm run dev
```

Untuk membuat aset final:

```bash
npm run build
```

Jika teks Blade tidak berubah di browser:

```bash
php artisan view:clear
php artisan optimize:clear
```

## 9. Kesalahan yang Harus Dihindari

Jangan menghapus:

```blade
@csrf
@method('PUT')
@method('DELETE')
```

Jangan mengganti atribut form berikut tanpa menyesuaikan controller:

```html
name="email"
name="password"
name="price"
name="stock"
```

Jangan mengubah nama route seperti:

```blade
route('products.index')
route('cart.store', $product)
```

Teks biasa di antara tag HTML aman untuk diganti.
