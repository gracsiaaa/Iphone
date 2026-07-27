USERNAME / EMAIL LOGIN PATCH
============================

1. Jangan ekstrak langsung ke branch main.
2. Dari project Anda jalankan:

   git switch main
   git pull origin main
   git switch -c feature/username-login

3. Backup database lokal jika berisi data penting.
4. Ekstrak seluruh isi ZIP ini ke root project Laravel.
5. Izinkan overwrite untuk file yang sama.
6. Jalankan:

   php artisan optimize:clear
   php artisan migrate
   npm run build
   php artisan test

7. Uji registrasi tanpa nomor telepon.
8. Uji login menggunakan username dan email.
9. Baca APPLY-USERNAME-AUTH.md dan GITHUB-SETUP.md.

Jangan memasukkan file .env, vendor, node_modules, atau database lokal ke GitHub.
