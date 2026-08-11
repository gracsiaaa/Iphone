# Activity Log Modern Update

Pembaruan ini mengubah Activity Log menjadi audit trail yang lebih jelas dan menambahkan event bisnis penting tanpa mengubah struktur database `activity_logs` yang sudah ada.

## Event yang dicatat

- `auth.login` — login berhasil
- `auth.logout` — logout
- `auth.register` — pembuatan akun reseller
- `order.created` — reseller membuat pesanan
- `payment.submitted` — reseller mengirim konfirmasi/bukti pembayaran
- `order.approved` — admin menyetujui pembayaran
- `order.rejected` — admin menolak pembayaran
- `order.completed` — admin menyelesaikan pesanan

## Tampilan baru

Halaman Superadmin > Activity Log kini memiliki statistik harian, pencarian, filter kategori, filter peran, filter tanggal, identitas pelaku, IP, browser/perangkat, label aktivitas, serta tautan ke invoice terkait.

Detail pesanan Admin juga memiliki bagian "Riwayat pesanan" sehingga alur sebuah invoice dapat dilihat langsung secara kronologis.

## Deploy

Tidak ada migration baru yang diperlukan. Setelah mengganti source code, jalankan perintah deployment proyek Laravel seperti biasa, misalnya:

```bash
composer install
npm install
npm run build
php artisan optimize:clear
```

Data log lama tetap dipertahankan. Event baru mulai tercatat setelah versi ini dipasang.
