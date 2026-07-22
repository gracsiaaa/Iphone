# UI Refactor Notes

Perubahan utama:

1. Semua file Blade dipecah menjadi baris pendek dan blok semantik.
2. Panjang maksimum baris Blade dibatasi sekitar 120 karakter.
3. Style berulang dipindahkan ke `resources/css/app.css`.
4. Lebar konten publik dibatasi 1180 px agar halaman tidak terlalu melebar.
5. Desain menggunakan panel putih, background stone, graphite, dan aksen biru.
6. Header publik berubah menjadi floating panel dengan navigasi berbentuk pill.
7. Tabel, form, badge, alert, card, dan tombol memakai class global.
8. Dashboard Admin memakai layout yang lebih tenang dan konsisten.
9. Invoice tidak lagi memiliki CSS inline panjang; invoice memakai global CSS.
10. Konfigurasi MySQL diperbarui agar tidak menampilkan deprecation pada PHP 8.5.

Pemeriksaan yang sudah dilakukan:

- Semua file PHP lulus `php -l`.
- Semua Blade tidak memiliki baris lebih dari 120 karakter.
- Pasangan directive Blade utama telah diperiksa.
- Global CSS berhasil melewati proses kompilasi Tailwind CSS 4.1.10.
