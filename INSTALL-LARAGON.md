# Panduan Instalasi Laragon

## 1. Persiapan

Pastikan Laragon menyediakan PHP 8.2+, MySQL/MariaDB, Composer, serta Node.js. Aktifkan Apache atau Nginx dan MySQL.

## 2. Penempatan proyek

Ekstrak proyek ke:

```text
C:\laragon\www\iphone-reseller-store
```

## 3. Database

Buka HeidiSQL melalui Laragon dan buat database:

```sql
CREATE DATABASE iphone_reseller_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Konfigurasi default `.env.example` menggunakan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iphone_reseller_store
DB_USERNAME=root
DB_PASSWORD=
```

Ubah username atau password jika konfigurasi MySQL Anda berbeda.

## 4. Instalasi dependency

Buka Terminal Laragon pada folder proyek:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm install
npm run build
```

## 5. Menjalankan website

Pilih **Menu > www > iphone-reseller-store** atau jalankan:

```bash
php artisan serve
```

## 6. Jika gambar tidak tampil

Hapus link lama, kemudian buat ulang:

```bash
php artisan storage:unlink
php artisan storage:link
```

## 7. Reset database demo

Perintah berikut menghapus seluruh data dan membuat ulang data contoh:

```bash
php artisan migrate:fresh --seed
```

Jangan menjalankan perintah tersebut pada database produksi.
