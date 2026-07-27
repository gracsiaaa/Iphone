<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Minimal jumlah barang dalam satu transaksi
    |--------------------------------------------------------------------------
    |
    | Nilai ini sengaja ditulis langsung di file konfigurasi, bukan di .env.
    | Reseller boleh membeli beberapa tipe produk berbeda selama total jumlah
    | seluruh barang di keranjang mencapai nilai minimum berikut.
    |
    */
    'minimum_order_quantity' => 10,
];
