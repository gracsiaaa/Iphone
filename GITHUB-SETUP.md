# Panduan GitHub untuk Kerja Tim

## 1. File yang tidak boleh masuk repository

Jangan commit file dan folder berikut:

```text
.env
vendor/
node_modules/
public/build/
public/storage
storage/logs/*.log
```

File `.gitignore` dalam patch ini sudah mengabaikannya.

Yang perlu masuk repository:

```text
.env.example
composer.json
composer.lock
package.json
package-lock.json
app/
config/
database/
resources/
routes/
tests/
```

## 2. Membuat repository pertama kali

Pemilik project menjalankan:

```bash
git init
git add .
git commit -m "chore: initial Laravel project"
git branch -M main
git remote add origin URL_REPOSITORY_GITHUB
git push -u origin main
```

Jangan menyalin teks `URL_REPOSITORY_GITHUB` secara literal. Ganti dengan URL repository Anda.

## 3. Menambahkan anggota tim

Pada GitHub buka:

```text
Repository → Settings → Collaborators → Add people
```

Berikan akses hanya kepada orang yang memang bekerja pada project.

## 4. Aturan utama kerja tim

Jangan mengerjakan fitur langsung pada branch `main`.

Sebelum mulai bekerja:

```bash
git switch main
git pull origin main
git switch -c feature/nama-fitur
```

Contoh branch:

```text
feature/username-login
feature/product-filter
fix/checkout-stock
ui/home-redesign
```

Setelah selesai:

```bash
git status
git add .
git commit -m "feat: add username or email login"
git push -u origin feature/username-login
```

Kemudian buka GitHub dan buat Pull Request menuju `main`.

## 5. Sebelum membuat Pull Request

Jalankan:

```bash
php artisan optimize:clear
php artisan test
npm run build
```

Periksa juga migration:

```bash
php artisan migrate:status
```

## 6. Mengambil perubahan terbaru saat sedang bekerja

```bash
git switch main
git pull origin main
git switch feature/nama-fitur
git rebase main
```

Jika belum nyaman menggunakan rebase, gunakan:

```bash
git merge main
```

Selesaikan conflict secara manual, lalu jalankan kembali test.

## 7. Konvensi commit sederhana

```text
feat: fitur baru
fix: perbaikan bug
ui: perubahan tampilan
refactor: merapikan kode tanpa mengubah perilaku
chore: konfigurasi atau dependency
docs: dokumentasi
 test: penambahan pengujian
```

Contoh:

```text
feat: allow login with username or email
fix: restore stock after rejected payment
ui: improve registration form spacing
```

## 8. Migration dalam kerja tim

Jangan mengedit migration lama yang sudah pernah dijalankan anggota lain atau server.
Buat migration baru untuk setiap perubahan database.

Setelah menarik perubahan:

```bash
composer install
npm install
php artisan migrate
npm run build
```

Gunakan `npm ci` jika `package-lock.json` sudah stabil dan tidak sedang mengubah dependency.

## 9. Menangani file `.env`

Setiap anggota tim memiliki `.env` sendiri. Jangan mengirim `.env` melalui GitHub.
Jika ada variabel baru, tambahkan nama variabel tersebut ke `.env.example` tanpa memasukkan password atau API key asli.

## 10. Aturan Pull Request yang disarankan

- Minimal satu orang lain memeriksa Pull Request.
- Jangan merge ketika test gagal.
- Gunakan Squash and Merge agar riwayat `main` tetap bersih.
- Hapus feature branch setelah Pull Request berhasil di-merge.
- Hindari force push ke `main`.

## 11. Branch protection

Pada GitHub buka:

```text
Repository → Settings → Branches / Rules → Add rule
```

Target branch:

```text
main
```

Aktifkan bila tersedia:

- Require a pull request before merging.
- Require at least one approval.
- Require status checks to pass.
- Block force pushes.
- Block branch deletion.

Jika paket GitHub Anda tidak menyediakan seluruh opsi tersebut untuk repository private,
tetapkan aturan tim bahwa tidak ada yang melakukan push langsung ke `main`.

## 12. Alur harian paling aman

```bash
git switch main
git pull origin main
git switch -c feature/nama-fitur

# kerjakan perubahan

php artisan test
npm run build
git add .
git commit -m "feat: deskripsi perubahan"
git push -u origin feature/nama-fitur
```

Terakhir, buat Pull Request, minta review, kemudian merge setelah disetujui.
