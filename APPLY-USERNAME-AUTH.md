# Memasang Patch Username Login

Patch ini berisi versi lengkap dari setiap file yang perlu ditambahkan atau diganti.
Ekstrak ZIP langsung ke folder utama project Laravel dan izinkan overwrite.

## Sebelum memasang

Buat branch Git terlebih dahulu:

```bash
git switch main
git pull origin main
git switch -c feature/username-login
```

Pastikan perubahan lokal sudah di-commit atau disimpan agar tidak hilang.

## Setelah file ditimpa

Jalankan:

```bash
composer install
php artisan optimize:clear
php artisan migrate
npm install
npm run build
php artisan test
```

## Perubahan database

Migration baru menambahkan kolom `username` pada tabel `users`.
Akun lama otomatis menerima username dari bagian awal email.

Contoh:

```text
superadmin@example.com → superadmin
admin@example.com      → admin
```

Jika username sudah digunakan, sistem menambahkan angka di belakangnya.

## Perilaku baru

- Registrasi meminta username dan email.
- Registrasi tidak meminta nomor telepon.
- Login menerima username atau email.
- Username selalu disimpan dalam huruf kecil.
- Nomor telepon tetap wajib pada checkout.
- Profile menyediakan nomor telepon sebagai data opsional.

## Setelah pengujian berhasil

```bash
git add .
git commit -m "feat: allow login with username or email"
git push -u origin feature/username-login
```

Buat Pull Request dari `feature/username-login` menuju `main`.
